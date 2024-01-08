<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataBankRequest;
use App\Http\Requests\UpdateDataBankRequest;
use App\Models\DataBank;

class DataBankController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Bank';
        return view('admin.bank.index', compact('module'));
    }

    public function get()
    {
        $dataFull = DataBank::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreDataBankRequest $storeDataBankRequest)
    {
        $data = array();
        try {
            $data = new DataBank();
            $data->nama_bank = $storeDataBankRequest->nama_bank;
            $data->no_rek = $storeDataBankRequest->no_rek;
            $data->cabang = $storeDataBankRequest->cabang;
            $data->atas_nama = $storeDataBankRequest->atas_nama;
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
            $data = DataBank::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreDataBankRequest $storeDataBankRequest, $params)
    {
        try {
            $data = DataBank::where('uuid', $params)->first();
            $data->nama_bank = $storeDataBankRequest->nama_bank;
            $data->no_rek = $storeDataBankRequest->no_rek;
            $data->cabang = $storeDataBankRequest->cabang;
            $data->atas_nama = $storeDataBankRequest->atas_nama;
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
            $data = DataBank::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
