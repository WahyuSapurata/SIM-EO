<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemVendorRequest;
use App\Http\Requests\UpdateItemVendorRequest;
use App\Imports\ItemVendor as ImportsItemVendor;
use App\Models\ItemVendor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemVendorController extends BaseController
{
    public function index($params)
    {
        $module = 'Daftar Item Vendor';
        return view('admin.dataitemvendor.index', compact('module'));
    }

    public function get($params)
    {
        // Mengambil semua data pengguna
        $dataFull = ItemVendor::where('uuid_vendor', $params)->get();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreItemVendorRequest $storeItemVendorRequest)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeItemVendorRequest->harga_satuan);
        $data = array();
        try {
            $data = new ItemVendor();
            $data->uuid_vendor = $storeItemVendorRequest->uuid_vendor;
            $data->kegiatan = $storeItemVendorRequest->kegiatan;
            $data->qty = $storeItemVendorRequest->qty;
            $data->satuan_kegiatan = $storeItemVendorRequest->satuan_kegiatan;
            $data->freq = $storeItemVendorRequest->freq;
            $data->satuan = $storeItemVendorRequest->satuan;
            $data->harga_satuan = $numericValue;
            $data->ket = $storeItemVendorRequest->ket;
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
            $data = ItemVendor::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreItemVendorRequest $storeItemVendorRequest, $params)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeItemVendorRequest->harga_satuan);
        try {
            $data = ItemVendor::where('uuid', $params)->first();
            $data->uuid_vendor = $storeItemVendorRequest->uuid_vendor;
            $data->kegiatan = $storeItemVendorRequest->kegiatan;
            $data->qty = $storeItemVendorRequest->qty;
            $data->satuan_kegiatan = $storeItemVendorRequest->satuan_kegiatan;
            $data->freq = $storeItemVendorRequest->freq;
            $data->satuan = $storeItemVendorRequest->satuan;
            $data->harga_satuan = $numericValue;
            $data->ket = $storeItemVendorRequest->ket;
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
            $data = ItemVendor::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }

    public function import_vendor(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'nullable|mimes:xls,xlsx,csv|max:20048', // Batas ukuran file diatur menjadi 2MB (sesuaikan sesuai kebutuhan)
            ]);

            $uuid_vendor = $request->input('uuid_vendor');
            $file = $request->file('file_excel');
            Excel::import(new ImportsItemVendor($uuid_vendor), $file);

            return $this->sendResponse('success', 'Excel data uploaded and saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error uploading and saving Excel: ' . $e->getMessage(), $e->getMessage(), 200);
        }
    }
}
