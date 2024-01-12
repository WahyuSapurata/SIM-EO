<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersetujuanOperasionalKantorRequest;
use App\Http\Requests\UpdatePersetujuanOperasionalKantorRequest;
use App\Models\OperasionalKantor;
use App\Models\PersetujuanOperasionalKantor;

class PersetujuanOperasionalKantorController extends BaseController
{
    public function index()
    {
        $module = 'Persetujuan Operasional Kantor';
        return view('admin.operasional.persetujuanoperasional', compact('module'));
    }

    public function update(UpdatePersetujuanOperasionalKantorRequest $updatePersetujuanOperasionalKantorRequest, $params)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updatePersetujuanOperasionalKantorRequest->sisa_tagihan);
        try {
            $data = OperasionalKantor::where('uuid', $params)->first();
            $data->sisa_tagihan = $numericValue;
            $data->save();

            $persetujuan = new PersetujuanOperasionalKantor();
            $persetujuan->uuid_operasional = $params;
            $persetujuan->status = $updatePersetujuanOperasionalKantorRequest->status;
            $persetujuan->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
