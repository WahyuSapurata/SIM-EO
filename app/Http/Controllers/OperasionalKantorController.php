<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperasionalKantorRequest;
use App\Http\Requests\UpdateOperasionalKantorRequest;
use App\Models\OperasionalKantor;
use App\Models\User;

class OperasionalKantorController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Operasional Kantor';
        return view('admin.operasional.index', compact('module'));
    }

    public function get()
    {
        $dataFull = OperasionalKantor::all();

        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataCombined = $dataFull;
        } else {
            $lokasiUser = auth()->user()->lokasi;
            $dataUser = User::all();

            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $data = $dataFull->map(function ($item) use ($dataUser) {
                $user = $dataUser->where('uuid', $item->uuid_user)->first();
                $item->lokasi_user = $user->lokasi;
                return $item;
            });

            $dataCombined = $data->where('lokasi_user', $lokasiUser);
        }

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataCombined, 'Get data success');
    }

    public function store(StoreOperasionalKantorRequest $storeOperasionalKantorRequest)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeOperasionalKantorRequest->harga_satuan);
        $data = array();
        try {
            $data = new OperasionalKantor();
            $data->uuid_user = auth()->user()->uuid;
            $data->tanggal = $storeOperasionalKantorRequest->tanggal;
            $data->deskripsi = $storeOperasionalKantorRequest->deskripsi;
            $data->spsifikasi = $storeOperasionalKantorRequest->spsifikasi;
            $data->harga_satuan = $numericValue;
            $data->qty = $storeOperasionalKantorRequest->qty;
            $data->qty_satuan = $storeOperasionalKantorRequest->qty_satuan;
            $data->freq = $storeOperasionalKantorRequest->freq;
            $data->freq_satuan = $storeOperasionalKantorRequest->freq_satuan;
            $data->kategori = $storeOperasionalKantorRequest->kategori;
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
            $data = OperasionalKantor::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreOperasionalKantorRequest $storeOperasionalKantorRequest, $params)
    {
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storeOperasionalKantorRequest->harga_satuan);
        try {
            $data = OperasionalKantor::where('uuid', $params)->first();
            $data->tanggal = $storeOperasionalKantorRequest->tanggal;
            $data->deskripsi = $storeOperasionalKantorRequest->deskripsi;
            $data->spsifikasi = $storeOperasionalKantorRequest->spsifikasi;
            $data->harga_satuan = $numericValue;
            $data->qty = $storeOperasionalKantorRequest->qty;
            $data->qty_satuan = $storeOperasionalKantorRequest->qty_satuan;
            $data->freq = $storeOperasionalKantorRequest->freq;
            $data->freq_satuan = $storeOperasionalKantorRequest->freq_satuan;
            $data->kategori = $storeOperasionalKantorRequest->kategori;
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
            $data = OperasionalKantor::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
