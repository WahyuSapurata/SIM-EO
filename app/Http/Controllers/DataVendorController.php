<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataVendorRequest;
use App\Http\Requests\UpdateDataVendorRequest;
use App\Imports\DataVendor as ImportsDataVendor;
use App\Models\DataVendor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
            $data->email = $storeDataVendorRequest->email;
            $data->no_telp = $storeDataVendorRequest->no_telp;
            $data->nama_bank = $storeDataVendorRequest->nama_bank;
            $data->nama_pemegan_rek = $storeDataVendorRequest->nama_pemegan_rek;
            $data->no_rek = $storeDataVendorRequest->no_rek;
            $data->nama_npwp = $storeDataVendorRequest->nama_npwp;
            $data->npwp = $storeDataVendorRequest->npwp;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function import_data_vendor(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'nullable|mimes:xls,xlsx,csv|max:20048', // Batas ukuran file diatur menjadi 2MB (sesuaikan sesuai kebutuhan)
            ]);

            $file = $request->file('file_excel');
            Excel::import(new ImportsDataVendor, $file);

            return $this->sendResponse('success', 'Excel data uploaded and saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Peringatan: Perbaiki Format Excel', $e->getMessage(), 200);
        }
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
            $data->email = $storeDataVendorRequest->email;
            $data->no_telp = $storeDataVendorRequest->no_telp;
            $data->nama_bank = $storeDataVendorRequest->nama_bank;
            $data->nama_pemegan_rek = $storeDataVendorRequest->nama_pemegan_rek;
            $data->no_rek = $storeDataVendorRequest->no_rek;
            $data->nama_npwp = $storeDataVendorRequest->nama_npwp;
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
