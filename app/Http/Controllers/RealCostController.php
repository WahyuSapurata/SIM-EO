<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRealCostRequest;
use App\Http\Requests\UpdateRealCostRequest;
use App\Models\DataClient;
use App\Models\RealCost;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RealCostController extends BaseController
{
    public function store(StoreRealCostRequest $storeRealCostRequest)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeRealCostRequest->satuan_real_cost);
        $numericValueDisc = (int) str_replace(['Rp', ',', ' '], '', $storeRealCostRequest->disc_item);
        if ($storeRealCostRequest->pajak_po === "null") {
            $pajak = 0;
        } else {
            $pajak = $storeRealCostRequest->pajak_po;
        }
        $data = array();
        try {
            $data = new RealCost();
            $data->satuan_real_cost = $numericValue;
            $data->pajak_po = $pajak;
            $data->pajak_pph = $storeRealCostRequest->pajak_pph === "null" ? 0 : $storeRealCostRequest->pajak_pph;
            $data->disc_item = $numericValueDisc;

            $data->uuid_user = auth()->user()->uuid;
            $data->uuid_client = $storeRealCostRequest->uuid_client;
            $data->kegiatan = $storeRealCostRequest->kegiatan;
            $data->qty = $storeRealCostRequest->qty;
            $data->satuan_kegiatan = $storeRealCostRequest->satuan_kegiatan;
            $data->freq = $storeRealCostRequest->freq;
            $data->satuan = $storeRealCostRequest->satuan;
            $data->marker = $storeRealCostRequest->markerValue;

            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = RealCost::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function get($params)
    {
        // Mengembalikan response berdasarkan data yang sudah disaring
        if (auth()->user()->role === 'direktur') {
            $dataFull = RealCost::where('uuid_client', $params)->get();
        } else {
            $lokasiUser = auth()->user()->lokasi;

            // Menampilkan RealCost berdasarkan lokasi user dengan melakukan join
            $dataFull = RealCost::join('users', 'real_costs.uuid_user', '=', 'users.uuid')
                ->where('real_costs.uuid_client', $params)
                ->where('users.lokasi', $lokasiUser)
                ->select('real_costs.*') // Sesuaikan dengan nama kolom pada real_costs
                ->get();
        }
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update(StoreRealCostRequest $storeRealCostRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeRealCostRequest->satuan_real_cost);
        $numericValueDisc = (int) str_replace(['Rp', ',', ' '], '', $storeRealCostRequest->disc_item);
        if ($storeRealCostRequest->pajak_po === "null") {
            $pajak = 0;
        } else {
            $pajak = $storeRealCostRequest->pajak_po;
        }

        try {
            $data = RealCost::where('uuid', $params)->first();
            $data->satuan_real_cost = $numericValue;
            $data->pajak_po = $pajak;
            $data->pajak_pph = $storeRealCostRequest->pajak_pph === "null" ? 0 : $storeRealCostRequest->pajak_pph;
            $data->disc_item = $numericValueDisc;

            $data->uuid_client = $storeRealCostRequest->uuid_client;
            $data->kegiatan = $storeRealCostRequest->kegiatan;
            $data->qty = $storeRealCostRequest->qty;
            $data->satuan_kegiatan = $storeRealCostRequest->satuan_kegiatan;
            $data->freq = $storeRealCostRequest->freq;
            $data->satuan = $storeRealCostRequest->satuan;
            $data->marker = $storeRealCostRequest->markerValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = RealCost::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }

    public function exportToExcel($params)
    {
        // Mengembalikan response berdasarkan data yang sudah disaring
        if (auth()->user()->role === 'direktur') {
            $dataFull = RealCost::where('uuid_client', $params)->get();
        } else {
            $lokasiUser = auth()->user()->lokasi;

            // Menampilkan RealCost berdasarkan lokasi user dengan melakukan join
            $dataFull = RealCost::join('users', 'real_costs.uuid_user', '=', 'users.uuid')
                ->where('real_costs.uuid_client', $params)
                ->where('users.lokasi', $lokasiUser)
                ->select('real_costs.*') // Sesuaikan dengan nama kolom pada real_costs
                ->get();
        }

        $dataClient = DataClient::where('uuid', $params)->first();

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
        $sheet->setCellValue('A1', 'LAPORAN ' . '"' . $dataClient->event . '"')->mergeCells('A1:M1');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'KEGITAN');
        $sheet->setCellValue('C3', 'QTY');
        $sheet->setCellValue('D3', 'SATUAN');
        $sheet->setCellValue('E3', 'FREQ');
        $sheet->setCellValue('F3', 'SATUAN');
        $sheet->setCellValue('G3', 'HARGA SATUAN');
        $sheet->setCellValue('H3', 'SUB TOTAL');
        $sheet->setCellValue('I3', 'SATUAN REAL COST');
        $sheet->setCellValue('J3', 'TOTAL REAL COST');
        $sheet->setCellValue('K3', 'PAJAK');
        $sheet->setCellValue('L3', 'DISC');
        $sheet->setCellValue('M3', 'KET');

        // Memberikan warna pada sel-sel baris ke-10
        $sheet->getStyle('A3:M3')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'acb9ca', // Warna Peach
                ],
            ],
        ]);

        $row = 4;
        $subtotalTotal = 0;
        $subSatuanRealCost = 0;

        foreach ($dataFull as $index => $lap) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $lap->kegiatan);
            $sheet->setCellValue('C' . $row, $lap->qty);
            $sheet->setCellValue('D' . $row, $lap->satuan_kegiatan);
            $sheet->setCellValue('E' . $row, $lap->freq);
            $sheet->setCellValue('F' . $row, $lap->satuan);
            $sheet->setCellValue('G' . $row, $lap->harga_satuan === 0 ? '-' : "Rp " . number_format($lap->harga_satuan, 0, ',', '.'));

            $jumlah_satuan = $lap->qty * $lap->freq * $lap->harga_satuan;
            $sheet->setCellValue('H' . $row, "Rp " . number_format($jumlah_satuan, 0, ',', '.'));
            $sheet->setCellValue('I' . $row, $lap->satuan_real_cost === 0 ? '-' : "Rp " . number_format($lap->satuan_real_cost, 0, ',', '.'));

            $jumlah_satuan_real_cost = $lap->qty * $lap->freq * $lap->satuan_real_cost;
            $sheet->setCellValue('J' . $row, "Rp " . number_format($jumlah_satuan_real_cost, 0, ',', '.'));

            $valuePo = $lap->pajak_po === '0' || $lap->pajak_po === null ? '' : $lap->pajak_po;
            $valuePPH = $lap->pajak_pph === '0' || $lap->pajak_pph === null ? '' : $lap->pajak_pph;
            $sheet->setCellValue('K' . $row, $valuePo . ' ' . $valuePPH);
            $sheet->setCellValue('L' . $row, $lap->disc_item === 0 ? '-' : "Rp " . number_format($lap->disc_item, 0, ',', '.'));
            $sheet->setCellValue('M' . $row, $lap->ket);

            // Format rupiah pada kolom H
            $subtotalTotal += $jumlah_satuan;
            $subSatuanRealCost += $jumlah_satuan_real_cost;

            $row++;
        }

        // Baris Total
        $sheet->setCellValue('A' . $row, 'Total'); // Gantilah 'Total' dengan label yang sesuai
        $sheet->mergeCells('A' . $row . ':G' . $row); // Gabungkan sel dari A hingga E
        $sheet->setCellValue('H' . $row, "Rp " . number_format($subtotalTotal, 0, ',', '.')); // Menghitung total
        $sheet->setCellValue('J' . $row, "Rp " . number_format($subSatuanRealCost, 0, ',', '.')); // Menghitung total
        // Menerapkan gaya untuk sel total
        $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
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
        for ($col = 'A'; $col <= 'M'; $col++) {
            $sheet->getStyle($col . '3:' . $col . ($row - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        $excelFileName = 'laporan_' . $dataClient->event . '.xlsx';
        $excelFilePath = public_path($excelFileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        // Kembalikan response dengan file PDF yang diunduh
        return response()->download(public_path($excelFileName));
    }
}
