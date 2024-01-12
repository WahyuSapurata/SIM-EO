<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\Kategori;

class KategoriController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Kategori';
        return view('admin.kategori.index', compact('module'));
    }

    public function get()
    {
        $dataFull = Kategori::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreKategoriRequest $storeKategoriRequest)
    {
        $data = array();
        try {
            $data = new Kategori();
            $data->nama_kategori = $storeKategoriRequest->nama_kategori;
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
            $data = Kategori::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreKategoriRequest $storeKategoriRequest, $params)
    {
        try {
            $data = Kategori::where('uuid', $params)->first();
            $data->nama_kategori = $storeKategoriRequest->nama_kategori;
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
            $data = Kategori::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
