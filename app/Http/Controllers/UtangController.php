<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtangRequest;
use App\Http\Requests\UpdateUtangRequest;
use App\Models\NonVendor;
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
        $dataNonVendor = NonVendor::all();

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanPo, $dataNonVendor) {
            // Mencari data PersetujuanPo berdasarkan uuid_persetujuanPo
            $persetujuanPo = $dataPersetujuanPo->where('uuid', $item->uuid_persetujuanPo)->first();

            // Mencari data NonVendor berdasarkan uuid_persetujuanPo
            $persetujuanNonVendor = $dataNonVendor->where('uuid', $item->uuid_persetujuanPo)->first();

            // Mengisi nilai-nilai baru pada item
            $item->no_po = $persetujuanPo ? $persetujuanPo->no_po : ($persetujuanNonVendor ? $persetujuanNonVendor->no_po : null);
            $item->client = $persetujuanPo ? $persetujuanPo->client : ($persetujuanNonVendor ? $persetujuanNonVendor->client : null);
            $item->event = $persetujuanPo ? $persetujuanPo->event : ($persetujuanNonVendor ? $persetujuanNonVendor->event : null);
            $item->file = $persetujuanPo ? $persetujuanPo->file : ($persetujuanNonVendor ? $persetujuanNonVendor->file : null);

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
