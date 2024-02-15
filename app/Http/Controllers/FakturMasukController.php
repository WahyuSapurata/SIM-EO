<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFakturMasukRequest;
use App\Http\Requests\UpdateFakturMasukRequest;
use App\Models\DataPajak;
use App\Models\FakturKeluar;
use App\Models\FakturMasuk;
use App\Models\Invoice;
use App\Models\NonVendor;
use App\Models\PersetujuanPo;
use App\Models\RealCost;
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
            $total_ppn = 0;
            $total_pph = 0;
            if ($item instanceof PersetujuanPo || $item instanceof NonVendor) {
                $uuidValuesPenjualan = explode(',', $item->uuid_penjualan);
                $uuidValuesRealCost = explode(',', $item->uuid_realCost);

                // Gabungkan dua array untuk mencakup semua nilai
                $uuidValues = array_merge($uuidValuesPenjualan, $uuidValuesRealCost);
                $realCostPo = $dataRealcost->whereIn('uuid', $uuidValues);

                // Menggunakan metode first() untuk mendapatkan satu objek hasil
                $pajak_po = $realCostPo->first()->pajak_po ?? null;
                $pajak_pph = $realCostPo->first()->pajak_pph ?? null;

                // $pajak_ppn = DataPajak::where('deskripsi_pajak', $pajak_po)->first();
                // $total_ppn = ($realCostPo->satuan_real_cost * $realCostPo->qty * $realCostPo->freq - $realCostPo->disc_item) * ($pajak_ppn->pajak / 100);
                // dd($total_ppn);
            }

            $faktur_masuk = FakturMasuk::where('uuid_persetujuan', $item->uuid)->first();
            $dataUser = User::where('uuid', $item->uuid_user)->first();

            $item->npwp = $faktur_masuk->npwp ?? null;
            $item->nama_vendor = $faktur_masuk->nama_vendor ?? null;
            $item->no_faktur = $faktur_masuk->no_faktur ?? null;
            $item->tanggal_faktur = $faktur_masuk->tanggal_faktur ?? null;
            $item->masa = $faktur_masuk->masa ?? null;
            $item->tahun = $faktur_masuk->tahun ?? null;
            $item->dpp = $faktur_masuk->dpp ?? null;
            $item->ppn = $pajak_po;
            $item->pph = $pajak_pph;
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
                $faktur_masuk = new FakturMasuk();
                $faktur_masuk->uuid_persetujuan = $params;
                $faktur_masuk->npwp = $request->npwp;
                $faktur_masuk->nama_vendor = $request->nama_vendor;
                $faktur_masuk->no_faktur = $request->no_faktur;
                $faktur_masuk->tanggal_faktur = $request->tanggal_faktur;
                $faktur_masuk->masa = $request->masa;
                $faktur_masuk->tahun = $request->tahun;
                $faktur_masuk->dpp = $request->dpp;
                $faktur_masuk->no_bupot = $request->no_bupot;
                $faktur_masuk->tgl_bupot = $request->tgl_bupot;
                $faktur_masuk->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Update data success');
    }
}
