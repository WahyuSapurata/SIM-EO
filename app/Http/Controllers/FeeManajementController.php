<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeManajementRequest;
use App\Http\Requests\UpdateFeeManajementRequest;
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
}
