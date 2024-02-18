<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersetujuanOperasionalKantorRequest;
use App\Http\Requests\UpdatePersetujuanOperasionalKantorRequest;
use App\Models\OperasionalKantor;
use App\Models\PersetujuanOperasionalKantor;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PersetujuanOperasionalKantorController extends BaseController
{
    public function index()
    {
        $module = 'Persetujuan Operasional Kantor';
        return view('admin.operasional.persetujuanoperasional', compact('module'));
    }

    public function update(UpdatePersetujuanOperasionalKantorRequest $updatePersetujuanOperasionalKantorRequest, $params)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updatePersetujuanOperasionalKantorRequest->sisa_tagihan);
        try {
            $data = OperasionalKantor::where('uuid', $params)->first();
            $data->sisa_tagihan = $numericValue ? $numericValue : $data->qty * $data->freq * $data->harga_satuan;
            $data->save();

            $persetujuan = new PersetujuanOperasionalKantor();
            $persetujuan->uuid_operasional = $params;
            $persetujuan->status = $updatePersetujuanOperasionalKantorRequest->status;
            $persetujuan->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function exportToExcel()
    {
        // Mengambil semua data pengguna
        $dataFull = OperasionalKantor::all();

        $lokasiUser = auth()->user()->lokasi;
        $dataUser = User::all();

        // Menampilkan OperasionalKantor berdasarkan lokasi user dengan melakukan join
        $data = $dataFull->map(function ($item) use ($dataUser) {
            $user = $dataUser->where('uuid', $item->uuid_user)->first();
            $item->lokasi_user = $user->lokasi;
            return $item;
        });

        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataCombined = $dataFull;
        } else {
            // Menggunakan where untuk menyaring item yang sesuai dengan lokasi user
            $dataCombined = $data->where('lokasi_user', $lokasiUser)->values();
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
        $sheet->setCellValue('A1', 'LAPORAN PERSETUJUAN OPERASIONAL KANTOR')->mergeCells('A1:K1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'KATEGORI');
        $sheet->setCellValue('C3', 'TANGGAL INPUT');
        $sheet->setCellValue('D3', 'DESKRIPSI');
        $sheet->setCellValue('E3', 'SPESIFIKASI');
        $sheet->setCellValue('F3', 'HARGA SATUAN');
        $sheet->setCellValue('G3', 'QTY');
        $sheet->setCellValue('H3', 'SATUAN');
        $sheet->setCellValue('I3', 'FREQ');
        $sheet->setCellValue('J3', 'SATUAN');
        $sheet->setCellValue('K3', 'TOTAL KALKULASI');

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
        $subtotalTagihan = 0;

        foreach ($dataCombined as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->kategori);
            $sheet->setCellValue('C' . $row, $lap->tanggal);
            $sheet->setCellValue('D' . $row, $lap->deskripsi);
            $sheet->setCellValue('E' . $row, $lap->spsifikasi);
            $sheet->setCellValue('F' . $row, $lap->harga_satuan === 0 ? '-' : "Rp " . number_format($lap->harga_satuan, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, $lap->qty);
            $sheet->setCellValue('H' . $row, $lap->qty_satuan);
            $sheet->setCellValue('I' . $row, $lap->freq);
            $sheet->setCellValue('J' . $row, $lap->freq_satuan);
            $sheet->setCellValue('K' . $row, $lap->sisa_tagihan === 0 ? '-' : "Rp " . number_format($lap->sisa_tagihan, 0, ',', '.'));

            // Format rupiah pada kolom H
            $subtotalTagihan += $lap->sisa_tagihan;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':J' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('K' . $row, "Rp " . number_format($subtotalTagihan, 0, ',', '.')); // Menghitung total

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

        $excelFileName = 'laporan_persetujuan_operasional_kantor' . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
