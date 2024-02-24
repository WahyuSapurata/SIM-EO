<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\DataBank;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\DataVendor;
use App\Models\Invoice;
use Barryvdh\Snappy\Facades\SnappyPdf;
use NumberFormatter;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoiceController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Invoice';
        return view('admin.invoice.index', compact('module'));
    }

    public function get()
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
                $item->nilai_pajak = $pajak->pajak ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->pajak = null;
                $item->nilai_pajak = null;
            }

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = Invoice::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(UpdateInvoiceRequest $updateInvoiceRequest)
    {
        $data = Invoice::where('uuid', $updateInvoiceRequest->uuid)->first();

        $kop = $updateInvoiceRequest->kop;
        $uuid_vendor = $updateInvoiceRequest->uuid_vendor;
        $alamat_perusahaan = $updateInvoiceRequest->alamat_perusahaan;
        $no_perusahaan = $updateInvoiceRequest->no_perusahaan;
        $no_invoice = $updateInvoiceRequest->no_invoice;
        $tanggal_invoice = $updateInvoiceRequest->tanggal_invoice;
        $deskripsi = $updateInvoiceRequest->deskripsi;
        $penanggung_jawab = $updateInvoiceRequest->penanggung_jawab;
        $jabatan = $updateInvoiceRequest->jabatan;
        $uuid_bank = $updateInvoiceRequest->uuid_bank;
        $total = (int) str_replace(['Rp', ',', ' '], '', $updateInvoiceRequest->total);
        $uuid_pajak = $updateInvoiceRequest->uuid_pajak;


        $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
        $huruf = $formatter->format($total);

        // Buat nama file PDF dengan nomor urut
        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);
        if (auth()->user()->lokasi === 'makassar') {
            $no_inv = 'INV/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        } else {
            $no_inv = 'INV/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        }

        $dataClient = DataClient::where('uuid', $uuid_vendor)->first();

        $dataBank = DataBank::where('uuid', $uuid_bank)->first();

        $dataPajak = DataPajak::where('uuid', $uuid_pajak)->first();

        if ($data->file === null) {
            $this->validate($updateInvoiceRequest, [
                'kop' => 'required',
            ], [
                'required' => 'Kolom :attribute harus di isi.',
            ], [
                'kop' => 'Kop',
            ]);

            // return view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak'))->render();
            if ($kop === 'CV. INIEVENT LANCAR JAYA') {
                $html = view('admin.invoice.pdf_invoice', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
            } elseif ($kop === 'DoubleHelix Indonesia') {
                $html = view('admin.invoice.pdf_invoice_2', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
            } elseif ($kop === 'PT. LINGKARAN GANDA BERKARYA') {
                $html = view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
            } elseif ($kop === 'Kop Kosong') {
                $html = view('admin.invoice.pdf_invoice_kopkosong', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
            } elseif ($kop === 'PT. MAHAKARYA KREASI SOLUSI') {
                $html = view('admin.invoice.pdf_invoice_4', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
            }

            $pdfFileName = 'Purchase Invoice ' . $deskripsi . ' ' . time() . '.pdf';

            $pdfFilePath = 'pdf-invoice/' . $pdfFileName; // Direktori dalam direktori public

            SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

            try {
                $data->uuid_vendor = $uuid_vendor;
                $data->alamat_perusahaan = $alamat_perusahaan;
                $data->no_perusahaan = $no_perusahaan;
                $data->uuid_user = auth()->user()->uuid;
                $data->no_invoice = $no_inv;
                $data->tanggal = $updateInvoiceRequest->tanggal;
                $data->tanggal_invoice = $tanggal_invoice;
                $data->deskripsi = $deskripsi;
                $data->penanggung_jawab = $penanggung_jawab;
                $data->jabatan = $jabatan;
                $data->uuid_bank = $uuid_bank;
                $data->total = $total;
                $data->uuid_pajak = $uuid_pajak;
                $data->file = $pdfFileName;
                $data->save();
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), $e->getMessage(), 400);
            }

            // Kembalikan link untuk diakses oleh pengguna
            return response()->json([
                'success' => true,
                'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
                'message' => 'PDF Invoice has been generated and saved successfully.',
            ]);
        } else {
            try {
                $data->uuid_vendor = $updateInvoiceRequest->uuid_vendor;
                $data->alamat_perusahaan = $alamat_perusahaan;
                $data->no_perusahaan = $no_perusahaan;
                $data->uuid_user = auth()->user()->uuid;
                $data->no_invoice = $no_inv;
                $data->tanggal = $updateInvoiceRequest->tanggal;
                $data->tanggal_invoice = $updateInvoiceRequest->tanggal_invoice;
                $data->deskripsi = $updateInvoiceRequest->deskripsi;
                $data->penanggung_jawab = $updateInvoiceRequest->penanggung_jawab;
                $data->jabatan = $updateInvoiceRequest->jabatan;
                $data->uuid_bank = $updateInvoiceRequest->uuid_bank;
                $data->total = $total;
                $data->uuid_pajak = $updateInvoiceRequest->uuid_pajak;
                $data->save();
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), $e->getMessage(), 400);
            }

            return $this->sendResponse($data, 'Update data success');
        }
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = Invoice::where('uuid', $params)->first();
            if ($data->file && file_exists(public_path('pdf-invoice/' . $data->file))) {
                unlink(public_path('pdf-invoice/' . $data->file));
            }
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }

    public function exportToPDF(StoreInvoiceRequest $storeInvoiceRequest)
    {
        $kop = $storeInvoiceRequest->kop;
        $uuid_vendor = $storeInvoiceRequest->uuid_vendor;
        $alamat_perusahaan = $storeInvoiceRequest->alamat_perusahaan;
        $no_perusahaan = $storeInvoiceRequest->no_perusahaan;
        $no_invoice = $storeInvoiceRequest->no_invoice;
        $tanggal_invoice = $storeInvoiceRequest->tanggal_invoice;
        $deskripsi = $storeInvoiceRequest->deskripsi;
        $penanggung_jawab = $storeInvoiceRequest->penanggung_jawab;
        $jabatan = $storeInvoiceRequest->jabatan;
        $uuid_bank = $storeInvoiceRequest->uuid_bank;
        $total = (int) str_replace(['Rp', ',', ' '], '', $storeInvoiceRequest->total);
        $uuid_pajak = $storeInvoiceRequest->uuid_pajak;

        $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
        $huruf = $formatter->format($total);

        // Buat nama file PDF dengan nomor urut
        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);
        if (auth()->user()->lokasi === 'makassar') {
            $no_inv = 'INV/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        } else {
            $no_inv = 'INV/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        }

        $dataClient = DataClient::where('uuid', $uuid_vendor)->first();

        $dataBank = DataBank::where('uuid', $uuid_bank)->first();

        $dataPajak = DataPajak::where('uuid', $uuid_pajak)->first();

        // return view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak'))->render();
        if ($kop === 'CV. INIEVENT LANCAR JAYA') {
            $html = view('admin.invoice.pdf_invoice', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
        } elseif ($kop === 'DoubleHelix Indonesia') {
            $html = view('admin.invoice.pdf_invoice_2', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
        } elseif ($kop === 'PT. LINGKARAN GANDA BERKARYA') {
            $html = view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
        } elseif ($kop === 'Kop Kosong') {
            $html = view('admin.invoice.pdf_invoice_kopkosong', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
        } elseif ($kop === 'PT. MAHAKARYA KREASI SOLUSI') {
            $html = view('admin.invoice.pdf_invoice_4', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak', 'alamat_perusahaan', 'no_perusahaan'))->render();
        }

        $pdfFileName = 'Purchase Invoice ' . $deskripsi . ' ' . time() . '.pdf';

        $pdfFilePath = 'pdf-invoice/' . $pdfFileName; // Direktori dalam direktori public

        SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

        try {
            $data = new Invoice();
            $data->uuid_vendor = $uuid_vendor;
            $data->alamat_perusahaan = $alamat_perusahaan;
            $data->no_perusahaan = $no_perusahaan;
            $data->uuid_user = auth()->user()->uuid;
            $data->no_invoice = $no_inv;
            $data->tanggal = $storeInvoiceRequest->tanggal;
            $data->tanggal_invoice = $tanggal_invoice;
            $data->deskripsi = $deskripsi;
            $data->penanggung_jawab = $penanggung_jawab;
            $data->jabatan = $jabatan;
            $data->uuid_bank = $uuid_bank;
            $data->total = $total;
            $data->uuid_pajak = $uuid_pajak;
            $data->file = $pdfFileName;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        // Kembalikan link untuk diakses oleh pengguna
        return response()->json([
            'success' => true,
            'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
            'message' => 'PDF Invoice has been generated and saved successfully.',
        ]);
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
        $sheet->setCellValue('A1', 'LAPORAN INVOICE')->mergeCells('A1:K1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NO INVOICE');
        $sheet->setCellValue('C3', 'TANGGAL');
        $sheet->setCellValue('D3', 'JATUH TEMPO');
        $sheet->setCellValue('E3', 'CLIENT');
        $sheet->setCellValue('F3', 'ALAMAT PERUSAHAAN');
        $sheet->setCellValue('G3', 'NOMOR PERUSAHAAN');
        $sheet->setCellValue('H3', 'DESKRIPSI');
        $sheet->setCellValue('I3', 'TOTAL');
        $sheet->setCellValue('J3', 'PAJAK');
        $sheet->setCellValue('K3', 'KET');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A3:K3')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
        ]);

        $row = 4;
        $subtotal = 0;

        foreach ($combinedData as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->no_invoice);
            $sheet->setCellValue('C' . $row, $lap->tanggal);
            $sheet->setCellValue('D' . $row, $lap->tanggal_invoice);
            $sheet->setCellValue('E' . $row, $lap->vendor);
            $sheet->setCellValue('F' . $row, $lap->alamat_perusahaan);
            $sheet->setCellValue('G' . $row, $lap->no_perusahaan);
            $sheet->setCellValue('H' . $row, $lap->deskripsi);
            $sheet->setCellValue('I' . $row, $lap->total === 0 ? '-' : "Rp " . number_format($lap->total, 0, ',', '.'));
            $sheet->setCellValue('J' . $row, $lap->pajak);
            $sheet->setCellValue('K' . $row, $lap->ket);

            // Format rupiah pada kolom H
            $subtotal += $lap->total;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':H' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('I' . $row, "Rp " . number_format($subtotal, 0, ',', '.')); // Menghitung total
        // Menerapkan gaya untuk sel total
        $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
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
        for ($col = 'A'; $col <= 'K'; $col++) {
            $sheet->getStyle($col . '3:' . $col . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        $excelFileName = 'laporan_invoice' . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
