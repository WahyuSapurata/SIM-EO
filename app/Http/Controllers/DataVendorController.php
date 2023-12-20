<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataVendorRequest;
use App\Http\Requests\UpdateDataVendorRequest;
use App\Models\DataVendor;

class DataVendorController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Vendor';
        return view('admin.datavendor.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = DataVendor::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreDataVendorRequest $storeDataVendorRequest)
    {
        $data = array();
        try {
            $data = new DataVendor();
            $data->nama_owner = $storeDataVendorRequest->nama_owner;
            $data->nama_perusahaan = $storeDataVendorRequest->nama_perusahaan;
            $data->alamat_perusahaan = $storeDataVendorRequest->alamat_perusahaan;
            $data->no_telp = $storeDataVendorRequest->no_telp;
            $data->nama_bank = $storeDataVendorRequest->nama_bank;
            $data->no_rek = $storeDataVendorRequest->no_rek;
            $data->npwp = $storeDataVendorRequest->npwp;
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
            $data = DataVendor::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreDataVendorRequest $storeDataVendorRequest, $params)
    {
        try {
            $data = DataVendor::where('uuid', $params)->first();
            $data->nama_owner = $storeDataVendorRequest->nama_owner;
            $data->nama_perusahaan = $storeDataVendorRequest->nama_perusahaan;
            $data->alamat_perusahaan = $storeDataVendorRequest->alamat_perusahaan;
            $data->no_telp = $storeDataVendorRequest->no_telp;
            $data->nama_bank = $storeDataVendorRequest->nama_bank;
            $data->no_rek = $storeDataVendorRequest->no_rek;
            $data->npwp = $storeDataVendorRequest->npwp;
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
            $data = DataVendor::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
