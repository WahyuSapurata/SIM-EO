<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtangRequest;
use App\Http\Requests\UpdateUtangRequest;
use App\Models\PersetujuanPo;
use App\Models\Utang;

class UtangController extends BaseController
{
    public function index()
    {
        $module = 'Utang';
        return view('admin.utang.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Utang::all();
        $dataPersetujuanPo = PersetujuanPo::all();

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanPo) {
            $persetujuanPo = $dataPersetujuanPo->where('uuid', $item->uuid_persetujuanPo)->first();
            $item->no_po = $persetujuanPo->no_po;
            $item->client = $persetujuanPo->client;
            $item->event = $persetujuanPo->event;
            $item->file = $persetujuanPo->file;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function update(UpdateUtangRequest $updateUtangRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updateUtangRequest->tagihan);
        try {
            $data = Utang::where('uuid', $params)->first();
            $data->tagihan = $numericValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
