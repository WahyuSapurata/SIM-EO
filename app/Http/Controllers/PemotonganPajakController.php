<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemotonganPajakRequest;
use App\Http\Requests\UpdatePemotonganPajakRequest;
use App\Imports\PemotonganPajak as ImportsPemotonganPajak;
use App\Models\PemotonganPajak;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PemotonganPajakController extends BaseController
{
    public function index()
    {
        $module = 'Pemotongan Pajak';
        return view('pajak.pemotonganpajak.index', compact('module'));
    }

    public function get()
    {
        $data = PemotonganPajak::all();
        return $this->sendResponse($data, 'Get data success');
    }

    public function import_pemotongan_pajak(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'nullable|mimes:xls,xlsx,csv|max:20048', // Batas ukuran file diatur menjadi 2MB (sesuaikan sesuai kebutuhan)
            ]);

            $file = $request->file('file_excel');
            Excel::import(new ImportsPemotonganPajak, $file);

            return $this->sendResponse('success', 'Excel data uploaded and saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Peringatan: Perbaiki Format Excel', $e->getMessage(), 200);
        }
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = PemotonganPajak::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(Request $request, $params)
    {
        $data = array();
        $dpp = (int) str_replace(['Rp', ',', ' '], '', $request->dpp);
        $ppn = (int) str_replace(['Rp', ',', ' '], '', $request->ppn);
        $pph = (int) str_replace(['Rp', ',', ' '], '', $request->pph);
        try {
            $data = PemotonganPajak::where('uuid', $params)->first();
            $data->npwp = $request->npwp;
            $data->nama_vendor = $request->nama_vendor;
            $data->no_faktur = $request->no_faktur;
            $data->tanggal_faktur = $request->tanggal_faktur;
            $data->masa = $request->masa;
            $data->tahun = $request->tahun;
            $data->dpp = $dpp;
            $data->ppn = $ppn;
            $data->pph = $pph;
            $data->no_bupot = $request->no_bupot;
            $data->tgl_bupot = $request->tgl_bupot;
            $data->area = $request->area;
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
            $data = PemotonganPajak::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
