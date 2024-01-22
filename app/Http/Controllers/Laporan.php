<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaldoAwalRequest;
use App\Models\Invoice;
use App\Models\NonVendor;
use App\Models\OperasionalKantor;
use App\Models\PersetujuanPo;
use App\Models\Piutang;
use App\Models\SaldoAwal;
use App\Models\Utang;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends BaseController
{
    public function index()
    {
        $module = 'Laporan';
        return view('admin.laporan.index', compact('module'));
    }

    public function store(StoreSaldoAwalRequest $storeSaldoAwalRequest)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeSaldoAwalRequest->saldo);
        $data = array();
        try {
            $data = new SaldoAwal();
            $data->saldo = $numericValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = SaldoAwal::first();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function getLaporan($params)
    {
        // Memisahkan tanggal berdasarkan kata kunci "to"
        $dateParts = explode(' to ', $params);

        // $dateParts[0] akan berisi tanggal awal dan $dateParts[1] akan berisi tanggal akhir
        $startDateStr = trim($dateParts[0]);
        $endDateStr = trim($dateParts[1]);

        $mergedData = collect([]); // Membuat koleksi kosong

        // Menggabungkan data dari PersetujuanPo
        $persetujuanPo = PersetujuanPo::whereNotNull('sisa_tagihan')->get();
        $mergedData = $mergedData->merge($persetujuanPo);

        // Menggabungkan data dari NonVendor
        $persetujuanNonVendor = NonVendor::whereNotNull('sisa_tagihan')->get();
        $mergedData = $mergedData->merge($persetujuanNonVendor);

        // Menggabungkan data dari Invoice
        $persetujuanInvoice = Invoice::whereNotNull('tagihan')->get();
        $mergedData = $mergedData->merge($persetujuanInvoice);

        // Menggabungkan data dari Utang
        $utang = Utang::whereNotNull('tagihan')->get();
        $mergedData = $mergedData->merge($utang);

        // Menggabungkan data dari Piutang
        $piutang = Piutang::whereNotNull('tagihan')->get();
        $mergedData = $mergedData->merge($piutang);

        $operasional = OperasionalKantor::whereNotNull('sisa_tagihan')->get();
        $mergedData = $mergedData->merge($operasional);

        // Modifikasi data jika diperlukan
        $combinedData = $mergedData->map(function ($item) {
            // Tambahkan logika modifikasi data di sini
            $item->tanggal = optional($item->created_at)->format('d-m-Y');
            $item->deskripsi = $item->event ?? $item->deskripsi ?? '';

            if ($item instanceof Utang) {
                $item->deskripsi .= 'Pembayaran utang sebesar ' . "Rp " . number_format($item->utang, 0, ',', '.');
            } elseif ($item instanceof Piutang) {
                $item->deskripsi .= 'Pembayaran piutang sebesar ' . "Rp " . number_format($item->utang, 0, ',', '.');
            }

            $item->keluar = ($item instanceof PersetujuanPo || $item instanceof NonVendor || $item instanceof Utang || $item instanceof OperasionalKantor) ? ($item->sisa_tagihan ?? 0) + ($item->tagihan ?? 0) : 0;
            $item->masuk = ($item instanceof Invoice || $item instanceof Piutang) ? ($item->tagihan ?? 0) : 0;
            return $item;
        });

        $filteredData = $combinedData->whereBetween('tanggal', [$startDateStr, $endDateStr]);
        // Mengurutkan data berdasarkan tanggal create yang terbaru
        $sortedData = $filteredData->sortBy('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($sortedData, 'Get data success');
    }

    public function exportToExcel($params)
    {
        // Memisahkan tanggal berdasarkan kata kunci "to"
        $dateParts = explode(' to ', $params);

        // $dateParts[0] akan berisi tanggal awal dan $dateParts[1] akan berisi tanggal akhir
        $startDateStr = trim($dateParts[0]);
        $endDateStr = trim($dateParts[1]);

        $mergedData = collect([]); // Membuat koleksi kosong

        // Menggabungkan data dari PersetujuanPo
        $persetujuanPo = PersetujuanPo::whereNotNull('sisa_tagihan')->get();
        $mergedData = $mergedData->merge($persetujuanPo);

        // Menggabungkan data dari NonVendor
        $persetujuanNonVendor = NonVendor::whereNotNull('sisa_tagihan')->get();
        $mergedData = $mergedData->merge($persetujuanNonVendor);

        // Menggabungkan data dari Invoice
        $persetujuanInvoice = Invoice::whereNotNull('tagihan')->get();
        $mergedData = $mergedData->merge($persetujuanInvoice);

        // Menggabungkan data dari Utang
        $utang = Utang::whereNotNull('tagihan')->get();
        $mergedData = $mergedData->merge($utang);

        // Menggabungkan data dari Piutang
        $piutang = Piutang::whereNotNull('tagihan')->get();
        $mergedData = $mergedData->merge($piutang);

        $operasional = OperasionalKantor::whereNotNull('sisa_tagihan')->get();
        $mergedData = $mergedData->merge($operasional);

        // Modifikasi data jika diperlukan
        $combinedData = $mergedData->map(function ($item) {
            // Tambahkan logika modifikasi data di sini
            $item->tanggal = optional($item->created_at)->format('d-m-Y');
            $item->deskripsi = $item->event ?? $item->deskripsi ?? '';

            if ($item instanceof Utang) {
                $item->deskripsi .= 'Pembayaran utang sebesar ' . "Rp " . number_format($item->utang, 0, ',', '.');
            } elseif ($item instanceof Piutang) {
                $item->deskripsi .= 'Pembayaran piutang sebesar ' . "Rp " . number_format($item->utang, 0, ',', '.');
            }

            $item->keluar = ($item instanceof PersetujuanPo || $item instanceof NonVendor || $item instanceof Utang || $item instanceof OperasionalKantor) ? ($item->sisa_tagihan ?? 0) + ($item->tagihan ?? 0) : 0;
            $item->masuk = ($item instanceof Invoice || $item instanceof Piutang) ? ($item->tagihan ?? 0) : 0;
            return $item;
        });

        $filteredData = $combinedData->whereBetween('tanggal', [$startDateStr, $endDateStr]);
        // Mengurutkan data berdasarkan tanggal create yang terbaru
        $sortedData = $filteredData->sortBy('created_at')->values()->all();

        $saldoAwal = SaldoAwal::first();

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
        $sheet->setCellValue('A1', 'LAPORAN')->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'MULAI DARI TANGGAL ' . $startDateStr . ' SAMPAI ' . $endDateStr)->mergeCells('A2:F2');

        $sheet->setCellValue('A7',  'PT LINGKARAN GANDA BERKARYA')->mergeCells('A7:F7');
        $sheet->setCellValue('A8',  'JL. Pandang Raya No. 8 Makassar / Phone :  0411-425194')->mergeCells('A8:F8');

        // Memberikan gaya bold pada sel-sel baris ke-10
        $sheet->getStyle('A7:F7')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('/logo.png')); // Gantilah dengan path gambar Anda
        $drawing->setCoordinates('C2'); // Gantilah dengan koordinat sel tempat gambar akan ditampilkan
        $drawing->setOffsetX(160); // Sesuaikan nilai offsetX agar gambar terletak di tengah
        $drawing->setOffsetY(10); // Sesuaikan nilai offsetY agar gambar terletak di tengah
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $sheet->mergeCells('A3:F6');

        $sheet->setCellValue('A10', 'NO');
        $sheet->setCellValue('B10', 'TANGGAL');
        $sheet->setCellValue('C10', 'KETERANGAN');
        $sheet->setCellValue('D10', 'KELUAR');
        $sheet->setCellValue('E10', 'MASUK');
        $sheet->setCellValue('F10', 'SALDO');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A10:F10')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
        ]);

        $row = 11;
        $subtotalTotal = $saldoAwal ? floatval($saldoAwal->saldo) : 0;

        foreach ($sortedData as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->tanggal);
            $sheet->setCellValue('C' . $row, $lap->deskripsi);
            $sheet->setCellValue('D' . $row, $lap->keluar === 0 ? '-' : "Rp " . number_format($lap->keluar, 0, ',', '.'));
            $sheet->setCellValue('E' . $row, $lap->masuk === 0 ? '-' : "Rp " . number_format($lap->masuk, 0, ',', '.'));

            // Format rupiah pada kolom H
            $sisa_saldo = $lap->masuk - $lap->keluar;
            $subtotalTotal += $sisa_saldo;
            $sheet->setCellValue('F' . $row, "Rp " . number_format($subtotalTotal, 0, ',', '.'));

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total Saldo'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':E' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('F' . $row, "Rp " . number_format($subtotalTotal, 0, ',', '.')); // Menghitung total
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

        // Menghapus border pada baris A9 hingga I9
        for ($col = 'A'; $col <= 'F'; $col++) {
            $sheet->getStyle($col . '9')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                    ],
                ],
            ]);
        }


        $excelFileName = 'laporan_' . $params . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
