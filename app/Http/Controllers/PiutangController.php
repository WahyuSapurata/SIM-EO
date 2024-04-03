<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePiutangRequest;
use App\Http\Requests\UpdatePiutangRequest;
use App\Models\DataClient;
use App\Models\DataVendor;
use App\Models\Invoice;
use App\Models\PersetujuanInvoice;
use App\Models\Piutang;
use App\Models\User;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PiutangController extends BaseController
{
    public function index()
    {
        $module = 'Piutang';
        return view('admin.piutang.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Piutang::all();
        $dataPersetujuanInvoice = PersetujuanInvoice::all();
        $dataInvoice = Invoice::all();
        $dataClient = DataClient::all();

        // $persetujuanInvoice = $dataPersetujuanInvoice->whereIn('uuid', $dataFull->pluck('uuid_persetujuanInvoice'));
        // $invoice = $dataInvoice->whereIn('uuid', $persetujuanInvoice->pluck('uuid_invoice'))->all();
        // dd($invoice);

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanInvoice, $dataInvoice, $dataClient) {
            $dataUser = User::where('uuid', $item->uuid_user)->first();
            $persetujuanInvoice = $dataPersetujuanInvoice->where('uuid', $item->uuid_persetujuanInvoice)->first();
            $invoiceUUIDs = $persetujuanInvoice ? $persetujuanInvoice->pluck('uuid_invoice') : [];
            $invoice = $dataInvoice->whereIn('uuid', $invoiceUUIDs)->first();
            $client = $dataClient->where('uuid', optional($invoice)->uuid_vendor)->first();

            $item->no_invoice = optional($invoice)->no_invoice;
            $item->tanggal_invoice = optional($invoice)->tanggal_invoice;
            $item->client = optional($client)->nama_client;
            $item->deskripsi = optional($invoice)->deskripsi;
            $item->file = optional($invoice)->file;
            $item->lokasi_user = optional($dataUser)->lokasi;

            return $item;
        });


        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataCombined = $combinedData;
        } else {
            $lokasiUser = auth()->user()->lokasi;
            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataCombined = $combinedData->where('lokasi_user', $lokasiUser)->values();
        }
        //

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataCombined, 'Get data success');
    }

    public function update(UpdatePiutangRequest $updatePiutangRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updatePiutangRequest->tagihan);
        try {
            $data = Piutang::where('uuid', $params)->first();
            $data->tagihan = $numericValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function lunas($params)
    {
        try {
            $dataRealCost = Piutang::where('uuid', $params)->first();
            $dataRealCost->ket = "Lunas";
            $dataRealCost->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Data di lunaskan');
    }

    public function exportToExcel()
    {
        // Mengambil semua data pengguna
        $dataFull = Piutang::all();
        $dataPersetujuanInvoice = PersetujuanInvoice::all();
        $dataInvoice = Invoice::all();
        $dataClient = DataClient::all();

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanInvoice, $dataInvoice, $dataClient) {
            $dataUser = User::where('uuid', $item->uuid_user)->first();
            $persetujuanInvoice = $dataPersetujuanInvoice->where('uuid', $item->uuid_persetujuanInvoice)->first();
            $invoice = $dataInvoice->where('uuid', $persetujuanInvoice->uuid_invoice)->first();
            $client = $dataClient->where('uuid', $invoice->uuid_vendor)->first();

            $item->no_invoice = $invoice->no_invoice;
            $item->tanggal_invoice = $invoice->tanggal_invoice;
            $item->client = $client->nama_client;
            $item->deskripsi = $invoice->deskripsi;
            $item->file = $invoice->file;
            $item->lokasi_user = $dataUser->lokasi;
            return $item;
        });

        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataCombined = $combinedData;
        } else {
            $lokasiUser = auth()->user()->lokasi;
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
        $sheet->setCellValue('A1', 'LAPORAN UTANG')->mergeCells('A1:H1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NO INVOICE');
        $sheet->setCellValue('C3', 'TANGGAL PIUTANG');
        $sheet->setCellValue('D3', 'CLIENT');
        $sheet->setCellValue('E3', 'DESKRIPSI');
        $sheet->setCellValue('F3', 'PIUTANG');
        $sheet->setCellValue('G3', 'JUMLAH TERBAYARKAN');
        $sheet->setCellValue('H3', 'KET');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A3:H3')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
        ]);

        $row = 4;
        $subtotal = 0;
        $subtotalTagihan = 0;

        foreach ($dataCombined as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->no_invoice);
            $sheet->setCellValue('C' . $row, $lap->tanggal_invoice);
            $sheet->setCellValue('D' . $row, $lap->client);
            $sheet->setCellValue('E' . $row, $lap->deskripsi);
            $sheet->setCellValue('F' . $row, $lap->utang === 0 ? '-' : "Rp " . number_format($lap->utang, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, $lap->tagihan === 0 ? '-' : "Rp " . number_format($lap->tagihan, 0, ',', '.'));
            $sheet->setCellValue('H' . $row, $lap->ket);

            // Format rupiah pada kolom H
            $subtotal += $lap->utang;
            $subtotalTagihan += $lap->tagihan;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':D' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('F' . $row, "Rp " . number_format($subtotal, 0, ',', '.')); // Menghitung total
        $sheet->setCellValue('G' . $row, "Rp " . number_format($subtotalTagihan, 0, ',', '.')); // Menghitung total

        // Menerapkan gaya untuk sel total
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
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
        for ($col = 'A'; $col <= 'H'; $col++) {
            $sheet->getStyle($col . '3:' . $col . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        $excelFileName = 'laporan_piutang' . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
