<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaldoAwalRequest;
use App\Models\Invoice;
use App\Models\OperasionalKantor;
use App\Models\PersetujuanPo;
use App\Models\Piutang;
use App\Models\SaldoAwal;
use App\Models\Utang;
use Illuminate\Http\Request;

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

        // Ambil data dari model PersetujuanPo
        $persetujuanPo = PersetujuanPo::all();

        // Ambil data dari model Invoice
        $persetujuanInvoice = Invoice::all();

        // Ambil data dari model Utang
        $utang = Utang::all();

        // Ambil data dari model Piutang
        $piutang = Piutang::all();

        // $operasional = OperasionalKantor::all()

        // Gabungkan semua data dalam satu variabel
        $mergedData = $persetujuanPo->merge($persetujuanInvoice)->merge($utang)->merge($piutang);

        // Modifikasi data jika diperlukan
        $combinedData = $mergedData->map(function ($item) {
            // Tambahkan logika modifikasi data di sini
            $item->tanggal = $item->created_at->format('d-m-Y');
            $item->deskripsi = $item->event ?? $item->deskripsi ?? ($item instanceof Utang ? 'Pembayaran utang sebesar ' . "Rp. " . number_format($item->utang, 0, ',', '.') : "Rp " . number_format($item->utang, 0, ',', '.')) ?? ($item instanceof Piutang ? 'Pembayaran piutang sebesar ' . "Rp. " . number_format($item->utang, 0, ',', '.') : "Rp. " . number_format($item->utang, 0, ',', '.'));
            $item->keluar = ($item instanceof PersetujuanPo || $item instanceof Utang) ? $item->sisa_tagihan + $item->tagihan : 0;
            $item->masuk = ($item instanceof Invoice || $item instanceof Piutang) ? $item->tagihan : 0;
            return $item;
        });

        $filteredData = $combinedData->whereBetween('tanggal', [$startDateStr, $endDateStr]);
        // Mengurutkan data berdasarkan tanggal create yang terbaru
        $sortedData = $filteredData->sortBy('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($sortedData, 'Get data success');
    }
}
