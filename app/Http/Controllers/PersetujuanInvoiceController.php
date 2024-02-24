<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersetujuanInvoiceRequest;
use App\Http\Requests\UpdatePersetujuanInvoiceRequest;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\Invoice;
use App\Models\PersetujuanInvoice;
use App\Models\Piutang;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PersetujuanInvoiceController extends BaseController
{
    public function index()
    {
        $module = 'Persetujuan Invoice';
        return view('admin.persetjuaninvoice.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = PersetujuanInvoice::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update(StorePersetujuanInvoiceRequest $storePersetujuanInvoiceRequest, $params)
    {
        try {
            $data = new PersetujuanInvoice();
            $data->uuid_invoice = $storePersetujuanInvoiceRequest->uuid_invoice;
            $data->status = $storePersetujuanInvoiceRequest->status;
            $data->save();

            // Gunakan fungsi str_replace dan intval untuk mengonversi tagihan ke nilai numerik
            $numericValue = intval(str_replace(['Rp', ',', ' '], '', $storePersetujuanInvoiceRequest->tagihan));

            // Pastikan $params adalah array
            $params = is_array($params) ? $params : [$params];

            // Perbarui tagihan pada Invoice berdasarkan $params
            $invoice = Invoice::where('uuid', $params)->first();
            $pajak = DataPajak::where('uuid', $invoice->uuid_pajak)->first();
            $nilaiPajak = $pajak->pajak / 100;
            $nilaiTotal = $invoice->total * $nilaiPajak;
            $invoice->update(['tagihan' => $numericValue === 0 ? $invoice->total : $numericValue]);

            if ($numericValue != 0 && $numericValue != $invoice->total) {
                $utang = new Piutang();
                $utang->uuid_user = auth()->user()->uuid;
                $utang->uuid_persetujuanInvoice = $data->uuid;
                $utang->utang = $invoice->total + $nilaiTotal - $numericValue;
                $utang->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function reload(Request $request)
    {
        try {
            $dataInvoice = Invoice::where('uuid', $request->uuid)->first();

            // Menghapus file PDF jika ada
            if ($dataInvoice->file && file_exists(public_path('pdf-invoice/' . $dataInvoice->file))) {
                unlink(public_path('pdf-invoice/' . $dataInvoice->file));
            }

            // Mengupdate data Invoice
            $dataInvoice->update(['ket' => $request->ket, 'file' => null]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Update data success');
    }

    public function exportToExcel()
    {
        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataFull = Invoice::all();
        } else {
            $lokasiUser = auth()->user()->lokasi;

            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataFull = Invoice::join('users', 'invoices.uuid_user', '=', 'users.uuid')
                ->where('users.lokasi', $lokasiUser)
                ->select('invoices.*') // Sesuaikan dengan nama kolom pada penjualans
                ->get();
        }

        $dataVendor = DataClient::all();
        $dataPajak = DataPajak::all();

        $combinedData = $dataFull->map(function ($item) use ($dataVendor, $dataPajak) {
            $vendor = $dataVendor->where('uuid', $item->uuid_vendor)->first();
            $pajak = $dataPajak->where('uuid', $item->uuid_pajak)->first();

            // Periksa apakah $data tidak kosong sebelum mengakses propertinya
            if ($vendor) {
                // Menambahkan data user ke dalam setiap item absen
                $item->vendor = $vendor->nama_client ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->vendor = null;
            }

            // Periksa apakah $data tidak kosong sebelum mengakses propertinya
            if ($pajak) {
                // Menambahkan data user ke dalam setiap item absen
                $item->pajak = $pajak->deskripsi_pajak ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->pajak = null;
            }

            return $item;
        });

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
        $sheet->setCellValue('A1', 'LAPORAN PERSETUJUAN INVOICE')->mergeCells('A1:I1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NO INVOICE');
        $sheet->setCellValue('C3', 'JATUH TEMPO');
        $sheet->setCellValue('D3', 'CLIENT');
        $sheet->setCellValue('E3', 'ALAMAT PERUSAHAAN');
        $sheet->setCellValue('F3', 'NOMOR PERUSAHAAN');
        $sheet->setCellValue('G3', 'DESKRIPSI');
        $sheet->setCellValue('H3', 'TOTAL');
        $sheet->setCellValue('I3', 'JUMLAH TERBAYAR');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A3:I3')->applyFromArray([
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

        foreach ($combinedData as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->no_invoice);
            $sheet->setCellValue('C' . $row, $lap->tanggal_invoice);
            $sheet->setCellValue('D' . $row, $lap->vendor);
            $sheet->setCellValue('E' . $row, $lap->alamat_perusahaan);
            $sheet->setCellValue('F' . $row, $lap->no_perusahaan);
            $sheet->setCellValue('G' . $row, $lap->deskripsi);
            $sheet->setCellValue('H' . $row, $lap->total === 0 ? '-' : "Rp " . number_format($lap->total, 0, ',', '.'));
            $sheet->setCellValue('I' . $row, $lap->tagihan === 0 ? '-' : "Rp " . number_format($lap->tagihan, 0, ',', '.'));

            // Format rupiah pada kolom H
            $subtotal += $lap->total;
            $subtotalTagihan += $lap->tagihan;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':G' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('H' . $row, "Rp " . number_format($subtotal, 0, ',', '.')); // Menghitung total
        $sheet->setCellValue('I' . $row, "Rp " . number_format($subtotalTagihan, 0, ',', '.')); // Menghitung total
        // Menerapkan gaya untuk sel total
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
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
        for ($col = 'A'; $col <= 'I'; $col++) {
            $sheet->getStyle($col . '3:' . $col . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        $excelFileName = 'laporan_persetujuan_invoice' . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
