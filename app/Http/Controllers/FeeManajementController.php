<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeManajementRequest;
use App\Http\Requests\UpdateFeeManajementRequest;
use App\Models\DataClient;
use App\Models\FeeManajement;

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
        $module = 'Laporan Fee Management';
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
        $data = FeeManajement::all();
        $combinedData = $data->map(function ($item) {
            $client = DataClient::where('uuid', $item->uuid_client)->first();

            $item->client = $client->nama_client;
            return $item;
        });

        // $filteredData = $combinedData->whereBetween('tanggal', [$startDateStr, $endDateStr]);
        // // Mengurutkan data berdasarkan tanggal create yang terbaru
        // $sortedData = $filteredData->sortBy('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($combinedData, 'Get data success');
    }
}
