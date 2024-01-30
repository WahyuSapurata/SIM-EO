<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePersetujuanPo;
use App\Models\DataClient;
use App\Models\Penjualan;
use App\Models\PersetujuanPo as ModelsPersetujuanPo;
use App\Models\Po;
use App\Models\RealCost;
use App\Models\User;
use App\Models\Utang;
use Illuminate\Http\Request;

class PersetujuanPo extends BaseController
{
    public function index()
    {
        $module = 'Persetujun PO';
        return view('admin.pesetujuanpo.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = ModelsPersetujuanPo::all();
        $dataClient = DataClient::all();

        $combinedData = $dataFull->map(function ($item) use ($dataClient) {
            $data = $dataClient->where('nama_client', $item->client)->first();
            $item->uuid_user = $data->uuid_user;
            return $item;
        });

        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'finance') {
            $dataCombined = $combinedData;
        } else {
            $lokasiUser = auth()->user()->lokasi;
            $dataUser = User::all();
            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataCombined = $combinedData->filter(function ($item) use ($lokasiUser, $dataUser) {
                $user = $dataUser->where('uuid', $item->uuid_user)->first();
                return $user->lokasi === $lokasiUser;
            });
        }

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataCombined, 'Get data success');
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

            if ($numericValue != 0 && $numericValue != $data->total_po) {
                $utang = new Utang();
                $utang->uuid_persetujuanPo = $data->uuid;
                $utang->utang = $data->total_po - $numericValue;
                $utang->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function reload(Request $request)
    {
        $uuidArray = explode(',', $request->uuid_penjualan);
        try {
            $dataRealCost = ModelsPersetujuanPo::where('uuid', $request->uuid)->first();
            if ($dataRealCost->file && file_exists(public_path('pdf/' . $dataRealCost->file))) {
                unlink(public_path('pdf/' . $dataRealCost->file));
            }
            $dataRealCost->delete();

            RealCost::whereIn('uuid', $uuidArray)->update(['ket' => $request->ket]);
            Po::whereIn('uuid_penjualan', $uuidArray)->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Delete data success');
    }
}
