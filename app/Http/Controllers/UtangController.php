<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtangRequest;
use App\Http\Requests\UpdateUtangRequest;
use App\Models\NonVendor;
use App\Models\PersetujuanPo;
use App\Models\User;
use App\Models\Utang;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UtangController extends BaseController
{
    public function index()
    {
        $module = 'Utang';
        return view('admin.utang.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Utang::all();
        $dataPersetujuanPo = PersetujuanPo::all();
        $dataNonVendor = NonVendor::all();

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanPo, $dataNonVendor) {
            $dataUser = User::where('uuid', $item->uuid_user)->first();
            // Mencari data PersetujuanPo berdasarkan uuid_persetujuanPo
            $persetujuanPo = $dataPersetujuanPo->where('uuid', $item->uuid_persetujuanPo)->first();

            // Mencari data NonVendor berdasarkan uuid_persetujuanPo
            $persetujuanNonVendor = $dataNonVendor->where('uuid', $item->uuid_persetujuanPo)->first();

            // Mengisi nilai-nilai baru pada item
            $item->no_po = $persetujuanPo ? $persetujuanPo->no_po : ($persetujuanNonVendor ? $persetujuanNonVendor->no_po : null);
            $item->client = $persetujuanPo ? $persetujuanPo->client : ($persetujuanNonVendor ? $persetujuanNonVendor->client : null);
            $item->event = $persetujuanPo ? $persetujuanPo->event : ($persetujuanNonVendor ? $persetujuanNonVendor->event : null);
            $item->file = $persetujuanPo ? $persetujuanPo->file : ($persetujuanNonVendor ? $persetujuanNonVendor->file : null);
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
        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataCombined, 'Get data success');
    }

    public function update(UpdateUtangRequest $updateUtangRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updateUtangRequest->tagihan);
        try {
            $data = Utang::where('uuid', $params)->first();
            $data->tagihan = $numericValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function exportToExcel()
    {
        // Mengambil semua data pengguna
        $dataFull = Utang::all();
        $dataPersetujuanPo = PersetujuanPo::all();
        $dataNonVendor = NonVendor::all();

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanPo, $dataNonVendor) {
            $dataUser = User::where('uuid', $item->uuid_user)->first();
            // Mencari data PersetujuanPo berdasarkan uuid_persetujuanPo
            $persetujuanPo = $dataPersetujuanPo->where('uuid', $item->uuid_persetujuanPo)->first();

            // Mencari data NonVendor berdasarkan uuid_persetujuanPo
            $persetujuanNonVendor = $dataNonVendor->where('uuid', $item->uuid_persetujuanPo)->first();

            // Mengisi nilai-nilai baru pada item
            $item->no_po = $persetujuanPo ? $persetujuanPo->no_po : ($persetujuanNonVendor ? $persetujuanNonVendor->no_po : null);
            $item->client = $persetujuanPo ? $persetujuanPo->client : ($persetujuanNonVendor ? $persetujuanNonVendor->client : null);
            $item->event = $persetujuanPo ? $persetujuanPo->event : ($persetujuanNonVendor ? $persetujuanNonVendor->event : null);
            $item->file = $persetujuanPo ? $persetujuanPo->file : ($persetujuanNonVendor ? $persetujuanNonVendor->file : null);
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
        $sheet->setCellValue('A1', 'LAPORAN UTANG')->mergeCells('A1:F1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NO PO');
        $sheet->setCellValue('C3', 'CLIENT');
        $sheet->setCellValue('D3', 'PROJECT/EVENT');
        $sheet->setCellValue('E3', 'UTANG');
        $sheet->setCellValue('F3', 'JUMLAH TERBAYARKAN');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A3:F3')->applyFromArray([
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
            $sheet->setCellValue('B' . $row, $lap->no_po);
            $sheet->setCellValue('C' . $row, $lap->client);
            $sheet->setCellValue('D' . $row, $lap->event);
            $sheet->setCellValue('E' . $row, $lap->utang === 0 ? '-' : "Rp " . number_format($lap->utang, 0, ',', '.'));
            $sheet->setCellValue('F' . $row, $lap->tagihan === 0 ? '-' : "Rp " . number_format($lap->tagihan, 0, ',', '.'));

            // Format rupiah pada kolom H
            $subtotal += $lap->utang;
            $subtotalTagihan += $lap->tagihan;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':D' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('E' . $row, "Rp " . number_format($subtotal, 0, ',', '.')); // Menghitung total
        $sheet->setCellValue('F' . $row, "Rp " . number_format($subtotalTagihan, 0, ',', '.')); // Menghitung total

        // Menerapkan gaya untuk sel total
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
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
        for ($col = 'A'; $col <= 'F'; $col++) {
            $sheet->getStyle($col . '3:' . $col . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        $excelFileName = 'laporan_utang' . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
