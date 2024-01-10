<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaldoAwalRequest;
use App\Models\Invoice;
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

    public function getLaporan()
    {
        $po = PersetujuanPo::all()->toArray();
        $invoice = Invoice::all()->toArray();
        $utang = Utang::all()->toArray();
        $piutang = Piutang::all()->toArray();

        // Menggabungkan variabel $po dan $utang menjadi satu array
        $mergedData = array_merge($po, $utang);

        // Menambahkan variabel $invoice dan $piutang ke dalam array
        $mergedData = array_merge($mergedData, $invoice, $piutang);

        // Mengurutkan array berdasarkan tanggal created_at
        usort($mergedData, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        dd($mergedData);

        return $this->sendResponse($mergedData, 'Get data success');
    }
}
