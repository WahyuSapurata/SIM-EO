<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFakturKeluarRequest;
use App\Http\Requests\UpdateFakturKeluarRequest;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\FakturKeluar;
use App\Models\FakturMasuk;
use App\Models\Invoice;
use App\Models\NonVendor;
use App\Models\PersetujuanPo;
use App\Models\RealCost;
use App\Models\User;
use Illuminate\Http\Request;

class FakturKeluarController extends BaseController
{
    public function index()
    {
        $module = 'Faktur Keluar';
        return view('pajak.fakturkeluar.index', compact('module'));
    }

    public function get_faktur_keluar()
    {
        // Menggabungkan data dari PersetujuanPo
        $persetujuanInvoice = Invoice::whereNotNull('uuid_pajak')
            ->whereNotNull('tagihan')
            ->get();

        $combinedData = $persetujuanInvoice->map(function ($item) {
            // Tambahkan logika modifikasi data di sini
            $faktur_keluar = FakturKeluar::where('uuid_persetujuan', $item->uuid)->first();
            $dataClient = DataClient::where('uuid', $item->uuid_vendor)->first();
            $dataUser = User::where('uuid', $item->uuid_user)->first();
            $dataPajak = DataPajak::where('uuid', $item->uuid_pajak)->first();
            $deskripsiPajak = $dataPajak->deskripsi_pajak ?? null;

            $ppn = 0;
            $pph = 0;

            if (stripos($deskripsiPajak, 'ppn') !== false) {
                $pajak = $dataPajak->pajak / 100;
                $ppn = $item->total * $pajak; // Jika jenis pajak adalah PPN
            } else {
                $pajak = $dataPajak->pajak / 100;
                $pph = $item->total * $pajak; // Jika jenis pajak adalah PPH
            }

            $item->npwp = $faktur_keluar->npwp ?? null;
            $item->client = $dataClient->nama_client ?? null;
            $item->no_faktur = $faktur_keluar->no_faktur ?? null;
            $item->tanggal_faktur = $faktur_keluar->tanggal_faktur ?? null;
            $item->masa = $faktur_keluar->masa ?? null;
            $item->tahun = $faktur_keluar->tahun ?? null;
            $item->status_faktur = $faktur_keluar->status_faktur ?? null;
            $item->dpp = $faktur_keluar->dpp ?? null;
            $item->ppn = $ppn;
            $item->event = $dataClient->event;
            $item->area = $dataUser->lokasi;
            $item->pph = $pph;
            $item->total_tagihan = $item->total;
            $item->realisasi_dana_masuk = $faktur_keluar->realisasi_dana_masuk ?? null;
            $item->deskripsi = $faktur_keluar->deskripsi ?? null;
            $item->selisih = $faktur_keluar->selisih ?? null;
            $item->no_bupot = $faktur_keluar->no_bupot ?? null;
            $item->tgl_bupot = $faktur_keluar->tgl_bupot ?? null;

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
            $data = FakturKeluar::where('uuid_persetujuan', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function storeUpdate(Request $request, $params)
    {
        $data = array();
        $dpp = (int) str_replace(['Rp', ',', ' '], '', $request->dpp);
        try {
            $data = FakturKeluar::where('uuid_persetujuan', $params)->first();

            $realisasi = (int) str_replace(['Rp', ',', ' '], '', $request->realisasi_dana_masuk);
            $selisih = (int) str_replace(['Rp', ',', ' '], '', $request->selisih);
            if ($data) {
                $data->npwp = $request->npwp;
                $data->no_faktur = $request->no_faktur;
                $data->tanggal_faktur = $request->tanggal_faktur;
                $data->masa = $request->masa;
                $data->tahun = $request->tahun;
                $data->status_faktur = $request->status_faktur;
                $data->dpp = $dpp;
                $data->realisasi_dana_masuk = $realisasi;
                $data->deskripsi = $request->deskripsi;
                $data->selisih = $selisih;
                $data->no_bupot = $request->no_bupot;
                $data->tgl_bupot = $request->tgl_bupot;
                $data->save();
            } else {
                $faktur_keluar = new FakturKeluar();
                $faktur_keluar->uuid_persetujuan = $params;
                $faktur_keluar->npwp = $request->npwp;
                $faktur_keluar->no_faktur = $request->no_faktur;
                $faktur_keluar->tanggal_faktur = $request->tanggal_faktur;
                $faktur_keluar->masa = $request->masa;
                $faktur_keluar->tahun = $request->tahun;
                $faktur_keluar->status_faktur = $request->status_faktur;
                $faktur_keluar->dpp = $dpp;
                $faktur_keluar->realisasi_dana_masuk = $realisasi;
                $faktur_keluar->deskripsi = $request->deskripsi;
                $faktur_keluar->selisih = $selisih;
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
