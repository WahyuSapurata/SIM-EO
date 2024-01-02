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
        $data = array();
        try {
            $data = new RealCost();
            $data->uuid_po = $request->uuid_po;
            $data->satuan_real_cost = $numericValue;
            $data->pajak_po = $request->pajak_po;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }
}
