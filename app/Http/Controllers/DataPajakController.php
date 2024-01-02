<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataPajakRequest;
use App\Http\Requests\UpdateDataPajakRequest;
use App\Models\DataPajak;

class DataPajakController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Pajak';
        return view('admin.datapajak.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = DataPajak::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreDataPajakRequest $storeDataPajakRequest)
    {
        $data = array();
        try {
            $data = new DataPajak();
            $data->deskripsi_pajak = $storeDataPajakRequest->deskripsi_pajak;
            $data->pajak = $storeDataPajakRequest->pajak;
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
            $data = DataPajak::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreDataPajakRequest $storeDataPajakRequest, $params)
    {
        try {
            $data = DataPajak::where('uuid', $params)->first();
            $data->deskripsi_pajak = $storeDataPajakRequest->deskripsi_pajak;
            $data->pajak = $storeDataPajakRequest->pajak;
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
            $data = DataPajak::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
