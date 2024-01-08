<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRealCostRequest;
use App\Http\Requests\UpdateRealCostRequest;
use App\Models\RealCost;
use Illuminate\Http\Request;

class RealCostController extends BaseController
{
    public function store(Request $request)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $request->satuan_real_cost);
        $numericValuePPH = (int) str_replace(['Rp', ',', ' '], '', $request->disc_item);
        if ($request->pajak_po === "null") {
            $pajak = 0;
        } else {
            $pajak = $request->pajak_po;
        }
        $data = array();
        try {
            $data = new RealCost();
            $data->uuid_po = $request->uuid_po;
            $data->satuan_real_cost = $numericValue;
            $data->pajak_po = $pajak;
            $data->pajak_pph = $request->pajak_pph === "null" ? 0 : $request->pajak_pph;
            $data->disc_item = $numericValuePPH;
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

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = RealCost::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update(Request $request, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $request->satuan_real_cost);
        $numericValuePPH = (int) str_replace(['Rp', ',', ' '], '', $request->disc_item);
        if ($request->pajak_po === "null") {
            $pajak = 0;
        } else {
            $pajak = $request->pajak_po;
        }
        try {
            $data = RealCost::where('uuid', $params)->first();
            $data->uuid_po = $request->uuid_po;
            $data->satuan_real_cost = $numericValue;
            $data->pajak_po = $pajak;
            $data->pajak_pph = $request->pajak_pph === "null" ? 0 : $request->pajak_pph;
            $data->disc_item = $numericValuePPH;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
