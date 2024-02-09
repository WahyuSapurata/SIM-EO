<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFakturKeluarRequest;
use App\Http\Requests\UpdateFakturKeluarRequest;
use App\Models\FakturKeluar;
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
        $dataRealcost = RealCost::whereNotNull('pajak_po')->orWhereNotNull('pajak_pph')->get();

        $mergePo = collect([]);

        $persetujuanNonVendor = NonVendor::all();
        $mergePo = $mergePo->merge($persetujuanNonVendor);
        $persetujuanPo = PersetujuanPo::all();
        $mergePo = $mergePo->merge($persetujuanPo);

        $combinedPersetujuanPo = $mergePo->filter(function ($item) use ($dataRealcost) {
            // Pecah nilai uuid_penjualan dan uuid_realCost menjadi array jika mengandung koma
            $uuidValuesPenjualan = explode(',', $item->uuid_penjualan);
            $uuidValuesRealCost = explode(',', $item->uuid_realCost);

            // Gabungkan dua array untuk mencakup semua nilai
            $uuidValues = array_merge($uuidValuesPenjualan, $uuidValuesRealCost);

            // Cek apakah setidaknya satu nilai uuid cocok dengan dataRealcost
            return $dataRealcost->whereIn('uuid', $uuidValues)->isNotEmpty();
        });

        $combinedData = $combinedPersetujuanPo->map(function ($item) use ($dataRealcost) {
            // Tambahkan logika modifikasi data di sini
            if ($item instanceof PersetujuanPo || $item instanceof NonVendor) {
                $uuidValuesPenjualan = explode(',', $item->uuid_penjualan);
                $uuidValuesRealCost = explode(',', $item->uuid_realCost);

                // Gabungkan dua array untuk mencakup semua nilai
                $uuidValues = array_merge($uuidValuesPenjualan, $uuidValuesRealCost);
                $realCostPo = $dataRealcost->whereIn('uuid', $uuidValues);

                // Menggunakan metode first() untuk mendapatkan satu objek hasil
                $pajak_po = $realCostPo->first()->pajak_po ?? null;
                $pajak_pph = $realCostPo->first()->pajak_pph ?? null;
            }

            $faktur_keluar = FakturKeluar::where('uuid_persetujuan', $item->uuid)->first();
            $dataUser = User::where('uuid', $item->uuid_user)->first();

            $item->npwp = $faktur_keluar->npwp ?? null;
            $item->no_faktur = $faktur_keluar->no_faktur ?? null;
            $item->tanggal_faktur = $faktur_keluar->tanggal_faktur ?? null;
            $item->masa = $faktur_keluar->masa ?? null;
            $item->tahun = $faktur_keluar->tahun ?? null;
            $item->status_faktur = $faktur_keluar->status_faktur ?? null;
            $item->dpp = $faktur_keluar->dpp ?? null;
            $item->ppn = $pajak_po;
            $item->area = $dataUser->lokasi;
            $item->pph = $pajak_pph;
            $item->total_tagihan = $item->total_po;
            $item->realisasi_dana_masuk = $faktur_keluar->realisasi_dana_masuk ?? null;
            $item->deskripsi = $faktur_keluar->deskripsi ?? null;
            $item->selisih = $faktur_keluar->selisih ?? null;
            $item->no_bupot = $faktur_keluar->no_bupot ?? null;
            $item->tgl_bupot = $faktur_keluar->tgl_bupot ?? null;

            // Pajak dipindahkan ke luar dari kondisi if-else untuk memastikan setiap $item memiliki atribut 'pajakData'
            // $item->pajakData = ['pajak_po' => $pajak_po, 'pajak_pph' => $pajak_pph];

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
                $data->dpp = $request->dpp;
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
                $faktur_keluar->dpp = $request->dpp;
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
