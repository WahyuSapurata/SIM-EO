<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNonVendorRequest;
use App\Http\Requests\UpdateNonVendorRequest;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\NonVendor;
use App\Models\Po;
use App\Models\RealCost;
use App\Models\User;
use App\Models\Utang;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NonVendorController extends BaseController
{
    public function index()
    {
        if (auth()->user()->role === 'finance' || auth()->user()->role === 'direktur') {
            $module = 'Persetujuan PO Non Vendor';
        } else {
            $module = 'Daftar PO Non Vendor';
        }
        return view('admin.persetujuannonvendor.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = NonVendor::all();
        $dataClient = DataClient::all();

        $lokasiUser = auth()->user()->lokasi;
        $dataUser = User::all();

        $combinedData = $dataFull->map(function ($item) use ($dataClient, $dataUser) {
            $uuidArray = explode(',', $item->uuid_realCost);
            $dataRealCost = RealCost::whereIn('uuid', $uuidArray)->first();
            $data = $dataClient->where('uuid', $dataRealCost->uuid_client)->first();
            $user = $dataUser->where('uuid', $data->uuid_user)->first();
            $item->lokasi_user = $user->lokasi;
            return $item;
        });

        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataCombined = $dataFull;
        } else {
            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataCombined = $combinedData->where('lokasi_user', $lokasiUser)->values();
        }

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataCombined, 'Get data success');
    }

    public function update(UpdateNonVendorRequest $updateNonVendorRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updateNonVendorRequest->sisa_tagihan);
        try {
            $data = NonVendor::where('uuid', $params)->first();
            $data->sisa_tagihan = $numericValue ? $numericValue : $data->total_po;
            $data->save();

            $uuidArray = explode(',', $data->uuid_realCost);
            Po::whereIn('uuid_penjualan', $uuidArray)->update(['status' => $updateNonVendorRequest->status]);

            if ($numericValue != 0 && $numericValue != $data->total_po) {
                $utang = new Utang();
                $utang->uuid_user = auth()->user()->uuid;
                $utang->uuid_persetujuanPo = $data->uuid;
                $utang->utang = $data->total_po - $numericValue;
                $utang->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function exportToPDF(Request $request)
    {
        $uuidArray = explode(',', $request->uuid_penjualan);
        $realCost = RealCost::whereIn('uuid', $uuidArray)->get();

        // Buat koleksi baru untuk menyimpan data pajak sesuai dengan urutan pada $pajakPoValues
        $orderedPajak = $realCost->map(function ($value) {
            $deskripsiPajak = [$value->pajak_po, $value->pajak_pph];

            // Ambil data pajak berdasarkan deskripsi
            $pajak = DataPajak::whereIn('deskripsi_pajak', $deskripsiPajak)->get();

            // Tambahkan data pajak ke dalam nilai aktual
            $value->pajak_data = $pajak->toArray();

            return $value;
        });

        $client = DataClient::where('uuid', $realCost[0]->uuid_client)->first();

        $tempo = $request->tempo;
        $no_invoice = $request->no_invoice;

        // Tanggal sekarang
        $tanggalSekarang = Carbon::now();

        // Tanggal 31 pada bulan ini
        $tanggal31 = Carbon::parse($tempo)->addDay();

        // Hitung jumlah hari
        $jumlahHari = $tanggalSekarang->diffInDays($tanggal31);

        // return view('procurement.po.invoicenonvendor', compact('realCost', 'client', 'tempo', 'jumlahHari', 'orderedPajak', 'no_invoice'))->render();

        $html = view('procurement.po.invoicenonvendor', compact('realCost', 'client', 'tempo', 'jumlahHari', 'orderedPajak', 'no_invoice'))->render();

        // Buat nama file PDF dengan nomor urut
        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);

        if (auth()->user()->lokasi === 'makassar') {
            $no_po = 'PO/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        } else {
            $no_po = 'PO/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        }

        // Pastikan $client dan $vendor tidak null sebelum mengakses propertinya
        $clientEvent = $client ? $client->event : '';

        $pdfFileName = 'Purchase Invoice-' . $clientEvent . time() . '.pdf';

        $pdfFilePath = 'pdf/' . $pdfFileName; // Direktori dalam direktori public

        SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

        // Simpan informasi PDF ke dalam database menggunakan model Po
        $pdfInfoCollection = Po::whereIn('uuid_penjualan', $uuidArray)->get();

        foreach ($pdfInfoCollection as $pdfInfo) {
            $pdfInfo->file = $no_invoice;
            $pdfInfo->save();
        }

        $subtotalTotal = 0;
        $subTotalPajak = 0;
        foreach ($realCost as $row) {
            $jumlah = $row->satuan_real_cost * $row->freq * $row->qty - $row->disc_item;
            $subtotalTotal += $jumlah;
        }
        foreach ($orderedPajak as $row_pajak) {
            if ($row_pajak->pajak_data) {
                // $jumlahPajak = ($row_pajak->satuan_real_cost * $row_pajak->qty * $row_pajak->freq - $row_pajak->disc_item) * ($row_pajak->pajak_data->pajak / 100);
                // $subTotalPajak += $jumlahPajak;

                foreach ($row_pajak->pajak_data as $pajakData) {
                    // Hitung jumlah pajak untuk setiap data pajak
                    $jumlahPajakPerData = ($row_pajak->satuan_real_cost * $row_pajak->qty * $row_pajak->freq - $row_pajak->disc_item) * ($pajakData['pajak'] / 100);

                    // Periksa apakah "PPH" muncul di awal deskripsi pajak (tanpa memperhatikan huruf besar atau kecil)
                    if (stripos($pajakData['deskripsi_pajak'], 'pph') === 0) {
                        $jumlahPajakPerData *= -1; // Jika jenis pajak adalah PPH, kurangi jumlah pajak
                    }

                    // Hitung total pajak
                    $subTotalPajak += $jumlahPajakPerData;
                }
            }
        }
        try {
            $data = new NonVendor();
            $data->uuid_user = auth()->user()->uuid;
            $data->uuid_realCost = $request->uuid_penjualan;
            $data->no_po = $no_po;
            $data->jatuh_tempo = $tempo;
            $data->client = $client->nama_client;
            $data->event = $client->event;
            $data->total_po = $subtotalTotal + $subTotalPajak;
            $data->file = $pdfFileName;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        // Kembalikan link untuk diakses oleh pengguna
        return response()->json([
            'success' => true,
            'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
            'message' => 'PDF Po has been generated and saved successfully.',
        ]);
    }

    public function reload(Request $request)
    {
        $uuidArray = explode(',', $request->uuid_realCost);
        try {
            $dataRealCost = NonVendor::where('uuid', $request->uuid)->first();
            if ($dataRealCost->file && file_exists(public_path('pdf/' . $dataRealCost->file))) {
                unlink(public_path('pdf/' . $dataRealCost->file));
            }
            $dataRealCost->delete();

            RealCost::whereIn('uuid', $uuidArray)->update(['ket' => $request->ket]);
            Po::whereIn('uuid_penjualan', $uuidArray)->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Delete data success');
    }

    public function exportToExcel()
    {
        // Mengambil semua data pengguna
        $dataFull = NonVendor::all();
        $dataClient = DataClient::all();

        $lokasiUser = auth()->user()->lokasi;
        $dataUser = User::all();

        $combinedData = $dataFull->map(function ($item) use ($dataClient, $dataUser) {
            $uuidArray = explode(',', $item->uuid_realCost);
            $dataRealCost = RealCost::whereIn('uuid', $uuidArray)->first();
            $data = $dataClient->where('uuid', $dataRealCost->uuid_client)->first();
            $user = $dataUser->where('uuid', $data->uuid_user)->first();
            $item->lokasi_user = $user->lokasi;
            return $item;
        });

        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataCombined = $dataFull;
        } else {
            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataCombined = $combinedData->where('lokasi_user', $lokasiUser)->values();
        }

        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Ambil objek aktif (sheet aktif)
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $fontStyle = [
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
        ];

        // Isi data ke dalam sheet

        $centerStyle = [
            'alignment' => [
                //'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->setCellValue('A1', 'LAPORAN PERSETUJUAN PO NON VENDOR')->mergeCells('A1:G1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NO PO');
        $sheet->setCellValue('C3', 'CLIENT');
        $sheet->setCellValue('D3', 'PROJECT/EVENT');
        $sheet->setCellValue('E3', 'JATUH TEMPO');
        $sheet->setCellValue('F3', 'TOTAL PO');
        $sheet->setCellValue('G3', 'JUMLAH TERBAYAR');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A3:G3')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
        ]);

        $row = 4;
        $subtotalPo = 0;
        $subTotalTagihan = 0;

        foreach ($dataCombined as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->no_po);
            $sheet->setCellValue('C' . $row, $lap->client);
            $sheet->setCellValue('D' . $row, $lap->event);
            $sheet->setCellValue('E' . $row, $lap->jatuh_tempo);
            $sheet->setCellValue('F' . $row, $lap->total_po === 0 ? '-' : "Rp " . number_format($lap->total_po, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, $lap->sisa_tagihan === 0 ? '-' : "Rp " . number_format($lap->sisa_tagihan, 0, ',', '.'));

            // Format rupiah pada kolom H
            $subtotalPo += $lap->total_po;
            $subTotalTagihan += $lap->sisa_tagihan;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':E' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('F' . $row, "Rp " . number_format($subtotalPo, 0, ',', '.')); // Menghitung total
        $sheet->setCellValue('G' . $row, "Rp " . number_format($subTotalTagihan, 0, ',', '.')); // Menghitung total
        // Menerapkan gaya untuk sel total
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        $row++; // Pindahkan ke baris berikutnya

        // Ambil objek kolom terakhir yang memiliki data (A, B, C, dst.)
        $lastColumn = $sheet->getHighestDataColumn();

        // Iterate melalui kolom-kolom yang memiliki data dan atur lebar kolomnya
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Menerapkan style alignment untuk seluruh sel dalam spreadsheet
        $sheet->getStyle('A1:' . $lastColumn . $row)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('C11:' . $lastColumn . $row)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A10:I10')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A11:A' . $row)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1:I1')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('E7:E8')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Memberikan border ke seluruh sel di kolom
        for ($col = 'A'; $col <= 'G'; $col++) {
            $sheet->getStyle($col . '3:' . $col . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        $excelFileName = 'laporan_persetujuan_po_non_vendor' . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
