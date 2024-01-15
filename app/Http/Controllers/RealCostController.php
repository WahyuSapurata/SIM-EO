<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRealCostRequest;
use App\Http\Requests\UpdateRealCostRequest;
use App\Models\RealCost;
use Illuminate\Http\Request;

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

            $data->uuid_client = $storeRealCostRequest->uuid_client;
            $data->kegiatan = $storeRealCostRequest->kegiatan;
            $data->qty = $storeRealCostRequest->qty;
            $data->satuan_kegiatan = $storeRealCostRequest->satuan_kegiatan;
            $data->freq = $storeRealCostRequest->freq;
            $data->satuan = $storeRealCostRequest->satuan;

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
        // Mengambil semua data pengguna
        $dataFull = RealCost::where('uuid_client', $params)->get();
        // Mengembalikan response berdasarkan data yang sudah disaring
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
}
