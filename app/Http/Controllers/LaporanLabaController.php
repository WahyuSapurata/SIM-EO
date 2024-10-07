<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanLabaRequest;
use App\Http\Requests\UpdateLaporanLabaRequest;
use App\Models\LaporanLaba;
use App\Models\User;

class LaporanLabaController extends BaseController
{
    public function index()
    {
        $module = 'Laporan Laba Bersih';
        return view('finance.laba.index', compact('module'));
    }

    public function get()
    {
        $dataFull = LaporanLaba::where('area', auth()->user()->lokasi)->get();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreLaporanLabaRequest $storeLaporanLabaRequest)
    {
        $numericValueBudget = (int) str_replace(['Rp', ',', ' '], '', $storeLaporanLabaRequest->budget_client);
        $numericValueCost = (int) str_replace(['Rp', ',', ' '], '', $storeLaporanLabaRequest->real_cost);
        $numericValuePph = (int) str_replace(['Rp', ',', ' '], '', $storeLaporanLabaRequest->pph);
        $numericValueOps = (int) str_replace(['Rp', ',', ' '], '', $storeLaporanLabaRequest->operasional_kantor);
        $data = array();
        try {
            $data = new LaporanLaba();
            $data->uuid_user = auth()->user()->uuid;
            $data->nama_event = $storeLaporanLabaRequest->nama_event;
            $data->budget_client = $numericValueBudget;
            $data->real_cost = $numericValueCost;
            $data->pph = $numericValuePph;
            $data->operasional_kantor = $numericValueOps;
            $data->area = auth()->user()->lokasi;
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
            $data = LaporanLaba::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreLaporanLabaRequest $storeLaporanLabaRequest, $params)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeLaporanLabaRequest->harga_satuan);
        try {
            $data = LaporanLaba::where('uuid', $params)->first();
            $data->nama_event = $storeLaporanLabaRequest->nama_event;
            $data->budget_client = $storeLaporanLabaRequest->budget_client;
            $data->real_cost = $storeLaporanLabaRequest->real_cost;
            $data->pph = $storeLaporanLabaRequest->pph;
            $data->operasional_kantor = $storeLaporanLabaRequest->operasional_kantor;
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
            $data = LaporanLaba::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
