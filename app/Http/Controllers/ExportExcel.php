<?php

namespace App\Http\Controllers;

use App\Models\DataClient;
use App\Models\FeeManajement;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel extends Controller
{
    public function exportToExcel($params)
    {
        $budget_client = Penjualan::where('uuid_client', $params)->get();
        $client = DataClient::where('uuid', $params)->first();
        $feeManagement = FeeManajement::where('uuid_client', $client->uuid)->first();

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
        $sheet->setCellValue('A1', 'QUOATION')->mergeCells('A1:I1');

        $sheet->setCellValue('A3', 'Clien');
        $sheet->setCellValue('A4', 'Project');
        $sheet->setCellValue('A5', 'Venue');
        $sheet->setCellValue('A6', 'Project Date');
        $sheet->setCellValue('A7', 'Name PIC');
        $sheet->setCellValue('A8', 'Nomor PIC');

        $sheet->setCellValue('B3', ': ' . $client->nama_client)->mergeCells('B3:D3');
        $sheet->setCellValue('B4', ': ' . $client->event)->mergeCells('B4:D4');
        $sheet->setCellValue('B5', ': ' . $client->venue)->mergeCells('B5:D5');
        $sheet->setCellValue('B6', ': ' . $client->project_date)->mergeCells('B6:D6');
        $sheet->setCellValue('B7', ': ' . $client->nama_pic)->mergeCells('B7:D7');
        $sheet->setCellValue('B8', ': ' . $client->no_pic)->mergeCells('B8:D8');

        $sheet->setCellValue('E7',  'PT LINGKARAN GANDA BERKARYA')->mergeCells('E7:I7');
        $sheet->setCellValue('E8',  'JL. Pandang Raya No. 8 Makassar / Phone :  0411-425194')->mergeCells('E8:I8');

        // Memberikan gaya bold pada sel-sel baris ke-10
        $sheet->getStyle('E7:I7')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('/logo.png')); // Gantilah dengan path gambar Anda
        $drawing->setCoordinates('G2'); // Gantilah dengan koordinat sel tempat gambar akan ditampilkan
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $sheet->mergeCells('E3:I6');

        $sheet->setCellValue('A10', 'NO');
        $sheet->setCellValue('B10', 'KEGIATAN');
        $sheet->setCellValue('C10', 'QTY');
        $sheet->setCellValue('D10', 'SATUAN');
        $sheet->setCellValue('E10', 'FREQ');
        $sheet->setCellValue('F10', 'SATUAN');
        $sheet->setCellValue('G10', 'HARGA SATUAN');
        $sheet->setCellValue('H10', 'SUB TOTAL');
        $sheet->setCellValue('I10', 'KETERANGAN');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A10:I10')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
        ]);

        $row = 11;
        $subtotalTotal = 0;

        foreach ($budget_client as $index => $budget) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $budget->kegiatan);
            $sheet->setCellValue('C' . $row, $budget->qty);
            $sheet->setCellValue('D' . $row, $budget->satuan_kegiatan);
            $sheet->setCellValue('E' . $row, $budget->freq);
            $sheet->setCellValue('F' . $row, $budget->satuan);
            $sheet->setCellValue('G' . $row, "Rp. " . number_format($budget->harga_satuan, 0, ',', '.'));

            // Format rupiah pada kolom H
            $total_budget = $budget->freq * $budget->harga_satuan * $budget->qty;
            $subtotalTotal += $total_budget;
            $sheet->setCellValue('H' . $row, "Rp. " . number_format($total_budget, 0, ',', '.'));
            $sheet->setCellValue('I' . $row, $budget->ket);

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':G' . $row); // Gabungkan sel dari A hingga G
        $sheet->setCellValue('H' . $row, "Rp. " . number_format($subtotalTotal, 0, ',', '.')); // Menghitung total
        $sheet->setCellValue('I' . $row, ''); // Kolom keterangan (jika ada)
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

        // Baris Fee Management
        $row++; // Pindahkan ke baris berikutnya
        $sheet->setCellValue('A' . $row, 'Fee Management'); // Gantilah 'Fee Management' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':G' . $row); // Gabungkan sel dari A hingga G
        $sheet->setCellValue('H' . $row, "Rp. " . number_format($feeManagement->total_fee, 0, ',', '.')); // Menghitung total Fee Management
        $sheet->setCellValue('I' . $row, ''); // Kolom keterangan (jika ada)
        // Menerapkan gaya untuk sel Fee Management
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

        // Baris Fee Management
        $row++; // Pindahkan ke baris berikutnya
        $sheet->setCellValue('A' . $row, 'Grand Total'); // Gantilah 'Fee Management' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':G' . $row); // Gabungkan sel dari A hingga G
        $sheet->setCellValue('H' . $row, "Rp. " . number_format($subtotalTotal + $feeManagement->total_fee, 0, ',', '.')); // Menghitung total Fee Management
        $sheet->setCellValue('I' . $row, ''); // Kolom keterangan (jika ada)
        // Menerapkan gaya untuk sel Fee Management
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
                // 'horizontal' => Alignment::HORIZONTAL_CENTER,
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

        // Menghapus border pada baris A9 hingga I9
        for ($col = 'A'; $col <= 'I'; $col++) {
            $sheet->getStyle($col . '9')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                    ],
                ],
            ]);
        }


        $excelFileName = 'laporan_' . $client->event . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
