<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Imports\ImportPenjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Penjualan';
        return view('procurement.penjualan.index', compact('module'));
    }

    public function penjualan($params)
    {
        $module = 'Daftar Penjualan';
        $this->get($params);
        return view('procurement.penjualan.penjualan', compact('module'));
    }

    public function get($params)
    {
        // Mengambil semua data pengguna
        $dataFull = Penjualan::where('uuid_client', $params)->get();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(StorePenjualanRequest $storePenjualanRequest)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storePenjualanRequest->harga_satuan);
        $data = array();
        try {
            $data = new Penjualan();
            $data->uuid_client = $storePenjualanRequest->uuid_client;
            $data->kegiatan = $storePenjualanRequest->kegiatan;
            $data->qty = $storePenjualanRequest->qty;
            $data->satuan_kegiatan = $storePenjualanRequest->satuan_kegiatan;
            $data->freq = $storePenjualanRequest->freq;
            $data->satuan = $storePenjualanRequest->satuan;
            $data->harga_satuan = $numericValue;
            $data->ket = $storePenjualanRequest->ket;
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
            return $this->sendError('Error uploading and saving Excel: ' . $e->getMessage(), $e->getMessage(), 200);
        }
    }
}
