<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Imports\ImportPenjualan;
use App\Models\Penjualan;
use App\Models\RealCost;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Budget Client';
        return view('procurement.penjualan.index', compact('module'));
    }

    public function penjualan($params)
    {
        $module = 'Daftar Budget Client';
        $this->get($params);
        return view('procurement.penjualan.penjualan', compact('module'));
    }

    public function get($params)
    {
        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataFull = Penjualan::where('uuid_client', $params)->get();
        } else {
            $lokasiUser = auth()->user()->lokasi;

            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataFull = Penjualan::join('users', 'penjualans.uuid_user', '=', 'users.uuid')
                ->where('penjualans.uuid_client', $params)
                ->where('users.lokasi', $lokasiUser)
                ->select('penjualans.*') // Sesuaikan dengan nama kolom pada penjualans
                ->get();
        }
        $realCost = RealCost::all();

        $combinedData = $dataFull->map(function ($item) use ($realCost) {
            $data = $realCost->where('uuid_po', $item->uuid)->first();

            // Periksa apakah $data tidak kosong sebelum mengakses propertinya
            if ($data) {
                // Menambahkan data user ke dalam setiap item absen
                $item->satuan_real_cost = $data->satuan_real_cost ?? null;
                $item->pajak_po = $data->pajak_po ?? null;
                $item->pajak_pph = $data->pajak_pph ?? null;
                $item->disc_item = $data->disc_item ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->satuan_real_cost = null;
                $item->pajak_po = null;
                $item->pajak_pph = null;
                $item->disc_item = null;
            }

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function store(StorePenjualanRequest $storePenjualanRequest)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storePenjualanRequest->harga_satuan);
        $data = array();
        try {
            $data = new Penjualan();
            $data->uuid_user = auth()->user()->uuid;
            $data->uuid_client = $storePenjualanRequest->uuid_client;
            $data->kegiatan = $storePenjualanRequest->kegiatan;
            $data->qty = $storePenjualanRequest->qty;
            $data->satuan_kegiatan = $storePenjualanRequest->satuan_kegiatan;
            $data->freq = $storePenjualanRequest->freq;
            $data->satuan = $storePenjualanRequest->satuan;
            $data->harga_satuan = $numericValue;
            $data->ket = $storePenjualanRequest->ket;
            $data->save();

            $realCost = new RealCost();
            $realCost->uuid_user = auth()->user()->uuid;
            $realCost->uuid_client = $storePenjualanRequest->uuid_client;
            $realCost->uuid_penjualan = $data->uuid;
            $realCost->kegiatan = $storePenjualanRequest->kegiatan;
            $realCost->qty = $storePenjualanRequest->qty;
            $realCost->satuan_kegiatan = $storePenjualanRequest->satuan_kegiatan;
            $realCost->freq = $storePenjualanRequest->freq;
            $realCost->satuan = $storePenjualanRequest->satuan;
            $realCost->harga_satuan = $numericValue;
            $realCost->ket = $storePenjualanRequest->ket;
            $realCost->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = Penjualan::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(StorePenjualanRequest $storePenjualanRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storePenjualanRequest->harga_satuan);
        try {
            $data = Penjualan::where('uuid', $params)->first();
            $data->uuid_client = $storePenjualanRequest->uuid_client;
            $data->kegiatan = $storePenjualanRequest->kegiatan;
            $data->qty = $storePenjualanRequest->qty;
            $data->satuan_kegiatan = $storePenjualanRequest->satuan_kegiatan;
            $data->freq = $storePenjualanRequest->freq;
            $data->satuan = $storePenjualanRequest->satuan;
            $data->harga_satuan = $numericValue;
            $data->ket = $storePenjualanRequest->ket;
            $data->save();

            $dataRealCost = RealCost::where('uuid_penjualan', $params)->first();
            $dataRealCost->uuid_client = $storePenjualanRequest->uuid_client;
            $dataRealCost->kegiatan = $storePenjualanRequest->kegiatan;
            $dataRealCost->qty = $storePenjualanRequest->qty;
            $dataRealCost->satuan_kegiatan = $storePenjualanRequest->satuan_kegiatan;
            $dataRealCost->freq = $storePenjualanRequest->freq;
            $dataRealCost->satuan = $storePenjualanRequest->satuan;
            $dataRealCost->harga_satuan = $numericValue;
            $dataRealCost->ket = $storePenjualanRequest->ket;
            $dataRealCost->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = Penjualan::where('uuid', $params)->first();
            $data->delete();
            $dataRealCost = RealCost::where('uuid_penjualan', $params)->first();
            $dataRealCost->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }

    public function import_penjualan(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'nullable|mimes:xls,xlsx,csv|max:20048', // Batas ukuran file diatur menjadi 2MB (sesuaikan sesuai kebutuhan)
            ]);

            $uuid_client = $request->input('uuid_client');
            $file = $request->file('file_excel');
            Excel::import(new ImportPenjualan($uuid_client), $file);

            return $this->sendResponse('success', 'Excel data uploaded and saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Peringatan: Perbaiki Format Excel', $e->getMessage(), 200);
        }
    }
}
