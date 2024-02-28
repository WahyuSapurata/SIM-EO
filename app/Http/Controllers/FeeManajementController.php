<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeManajementRequest;
use App\Http\Requests\UpdateFeeManajementRequest;
use App\Models\DataClient;
use App\Models\FeeManajement;
use App\Models\Penjualan;
use App\Models\RealCost;

class FeeManajementController extends BaseController
{
    public function store(StoreFeeManajementRequest $storeFeeManajementRequest)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeFeeManajementRequest->total_fee);
        $data = array();
        try {
            $data = new FeeManajement();
            $data->uuid_client = $storeFeeManajementRequest->uuid_client;
            $data->total_fee = $numericValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function get($params)
    {
        // Mengambil semua data pengguna
        $dataFull = FeeManajement::where('uuid_client', $params)->first();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function index()
    {
        $module = 'Laporan Event';
        return view('admin.laporan.fee', compact('module'));
    }

    public function get_laporanFee()
    {
        // Memisahkan tanggal berdasarkan kata kunci "to"
        // $dateParts = explode(' to ', $params);

        // // $dateParts[0] akan berisi tanggal awal dan $dateParts[1] akan berisi tanggal akhir
        // $startDateStr = trim($dateParts[0]);
        // $endDateStr = trim($dateParts[1]);

        // Modifikasi data jika diperlukan
        if (auth()->user()->role === 'direktur') {
            $data = DataClient::all();
        } else {
            $lokasiUser = auth()->user()->lokasi;

            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $data = DataClient::join('users', 'data_clients.uuid_user', '=', 'users.uuid')
                ->where('users.lokasi', $lokasiUser)
                ->select('data_clients.*') // Sesuaikan dengan nama kolom pada penjualans
                ->get();
        }

        $combinedData = $data->map(function ($item) {
            $jumlah_budget = 0;
            $jumlah_realCost = 0;

            // Ambil semua data penjualan berdasarkan uuid_client
            $budget_client = Penjualan::where('uuid_client', $item->uuid)->get();

            // Ambil data fee manajemen berdasarkan uuid_client
            $fee = FeeManajement::where('uuid_client', $item->uuid)->first();

            // Ambil semua data real cost berdasarkan uuid_client dan memiliki nilai satuan_real_cost
            $data_realCost = RealCost::where('uuid_client', $item->uuid)
                ->whereNotNull('satuan_real_cost')
                ->get();

            foreach ($budget_client as $budget) {
                $jumlah_budget += $budget->qty * $budget->freq * $budget->harga_satuan;
            }

            foreach ($data_realCost as $realCost) {
                $jumlah_realCost += $realCost->qty * $realCost->freq * $realCost->satuan_real_cost;
            }

            $keuntungan = $jumlah_budget + optional($fee)->total_fee ?? 0 - $jumlah_realCost - ($jumlah_budget + optional($fee)->total_fee ?? 0 * 0.02);

            // Anda dapat menghapus baris berikut jika dd() di atas dihapus
            $item->budget = $jumlah_budget + optional($fee)->total_fee ?? 0;
            $item->real_cost = $jumlah_realCost;
            $item->keuntungan = $keuntungan;
            $item->persentase_keuntungan = ($jumlah_realCost != 0) ? ($keuntungan / $jumlah_budget * 100) : 0;

            return $item;
        });

        // $filteredData = $combinedData->whereBetween('tanggal', [$startDateStr, $endDateStr]);
        // // Mengurutkan data berdasarkan tanggal create yang terbaru
        // $sortedData = $filteredData->sortBy('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($combinedData, 'Get data success');
    }
}
