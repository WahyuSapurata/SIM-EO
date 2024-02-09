<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFakturMasukRequest;
use App\Http\Requests\UpdateFakturMasukRequest;
use App\Models\DataPajak;
use App\Models\FakturMasuk;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class FakturMasukController extends BaseController
{
    public function index()
    {
        $module = 'Faktur Masuk';
        return view('pajak.fakturmasuk.index', compact('module'));
    }

    public function get_faktur_masuk()
    {
        // Menggabungkan data dari PersetujuanPo
        $persetujuanInvoice = Invoice::whereNotNull('uuid_pajak')
            ->whereNotNull('tagihan')
            ->get();

        $combinedData = $persetujuanInvoice->map(function ($item) {
            // Tambahkan logika modifikasi data di sini
            $faktur_masuk = FakturMasuk::where('uuid_persetujuan', $item->uuid)->first();
            $dataUser = User::where('uuid', $item->uuid_user)->first();
            $dataPajak = DataPajak::where('uuid', $item->uuid_pajak)->first();
            $deskripsiPajak = $dataPajak->deskripsi_pajak ?? null;

            $ppn = null;
            $pph = null;

            if (stripos($deskripsiPajak, 'ppn') !== false) {
                $ppn = $deskripsiPajak; // Jika jenis pajak adalah PPN
            } else {
                $pph = $deskripsiPajak; // Jika jenis pajak adalah PPH
            }

            $item->npwp = $faktur_masuk->npwp ?? null;
            $item->nama_vendor = $faktur_masuk->nama_vendor ?? null;
            $item->no_faktur = $faktur_masuk->no_faktur ?? null;
            $item->tanggal_faktur = $faktur_masuk->tanggal_faktur ?? null;
            $item->masa = $faktur_masuk->masa ?? null;
            $item->tahun = $faktur_masuk->tahun ?? null;
            $item->dpp = $faktur_masuk->dpp ?? null;
            $item->ppn = $ppn;
            $item->pph = $pph;
            $item->no_bupot = $faktur_masuk->no_bupot ?? null;
            $item->tgl_bupot = $faktur_masuk->tgl_bupot ?? null;
            $item->area = $dataUser->lokasi;

            return $item;
        });

        $sortedData = $combinedData->sortByDesc('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($sortedData, 'Get data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = FakturMasuk::where('uuid_persetujuan', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function storeUpdate(Request $request, $params)
    {
        $data = array();
        try {
            $data = FakturMasuk::where('uuid_persetujuan', $params)->first();

            if ($data) {
                $data->npwp = $request->npwp;
                $data->nama_vendor = $request->nama_vendor;
                $data->no_faktur = $request->no_faktur;
                $data->tanggal_faktur = $request->tanggal_faktur;
                $data->masa = $request->masa;
                $data->tahun = $request->tahun;
                $data->dpp = $request->dpp;
                $data->no_bupot = $request->no_bupot;
                $data->tgl_bupot = $request->tgl_bupot;
                $data->save();
            } else {
                $faktur_keluar = new FakturMasuk();
                $faktur_keluar->uuid_persetujuan = $params;
                $faktur_keluar->npwp = $request->npwp;
                $faktur_keluar->nama_vendor = $request->nama_vendor;
                $faktur_keluar->no_faktur = $request->no_faktur;
                $faktur_keluar->tanggal_faktur = $request->tanggal_faktur;
                $faktur_keluar->masa = $request->masa;
                $faktur_keluar->tahun = $request->tahun;
                $faktur_keluar->dpp = $request->dpp;
                $faktur_keluar->no_bupot = $request->no_bupot;
                $faktur_keluar->tgl_bupot = $request->tgl_bupot;
                $faktur_keluar->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Update data success');
    }
}
