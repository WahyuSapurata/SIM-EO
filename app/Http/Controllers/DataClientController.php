<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataClientRequest;
use App\Http\Requests\UpdateDataClientRequest;
use App\Models\DataClient;

class DataClientController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Client';
        return view('procurement.dataclient.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        if (auth()->user()->role === 'admin') {
            $dataFull = DataClient::all();
        } else {
            $dataFull = DataClient::where('uuid_user', auth()->user()->uuid)->get();
        }

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StoreDataClientRequest $storeDataClientRequest)
    {
        $data = array();
        try {
            $data = new DataClient();
            $data->uuid_user = auth()->user()->uuid;
            $data->nama_client = $storeDataClientRequest->nama_client;
            $data->event = $storeDataClientRequest->event;
            $data->venue = $storeDataClientRequest->venue;
            $data->project_date = $storeDataClientRequest->project_date;
            $data->nama_pic = $storeDataClientRequest->nama_pic;
            $data->no_pic = $storeDataClientRequest->no_pic;
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
            $data = DataClient::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StoreDataClientRequest $storeDataClientRequest, $params)
    {
        try {
            $data = DataClient::where('uuid', $params)->first();
            $data->nama_client = $storeDataClientRequest->nama_client;
            $data->event = $storeDataClientRequest->event;
            $data->venue = $storeDataClientRequest->venue;
            $data->project_date = $storeDataClientRequest->project_date;
            $data->nama_pic = $storeDataClientRequest->nama_pic;
            $data->no_pic = $storeDataClientRequest->no_pic;
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
            $data = DataClient::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
