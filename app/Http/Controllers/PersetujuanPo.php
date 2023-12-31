<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePersetujuanPo;
use App\Models\PersetujuanPo as ModelsPersetujuanPo;
use App\Models\Po;
use Illuminate\Http\Request;

class PersetujuanPo extends BaseController
{
    public function index()
    {
        $module = 'Persetujun Po';
        return view('admin.pesetujuanpo.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = ModelsPersetujuanPo::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update(UpdatePersetujuanPo $updatePersetujuanPo, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updatePersetujuanPo->sisa_tagihan);
        try {
            $data = ModelsPersetujuanPo::where('uuid', $params)->first();
            $data->sisa_tagihan = $numericValue ? $numericValue : $data->total_po;
            $data->save();

            $uuidArray = explode(',', $data->uuid_penjualan);
            Po::whereIn('uuid_penjualan', $uuidArray)->update(['status' => $updatePersetujuanPo->status]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
