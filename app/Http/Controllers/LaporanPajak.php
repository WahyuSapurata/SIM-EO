<?php

namespace App\Http\Controllers;

use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\Invoice;
use App\Models\NonVendor;
use App\Models\PersetujuanPo;
use App\Models\RealCost;
use Illuminate\Http\Request;

class LaporanPajak extends BaseController
{
    public function index()
    {
        $module = 'Laporan Pajak';
        return view('pajak.laporan.index', compact('module'));
    }

    public function get_laporanPajak()
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

        $mergedData = collect([]);
        $mergedData = $mergedData->merge($combinedPersetujuanPo);
        // Menggabungkan data dari Invoice
        $persetujuanInvoice = Invoice::whereNotNull('uuid_pajak')
            ->whereNotNull('tagihan')
            ->get();
        $mergedData = $mergedData->merge($persetujuanInvoice);

        // Modifikasi data jika diperlukan
        $combinedData = $mergedData->map(function ($item) use ($dataRealcost, $persetujuanInvoice) {
            // Tambahkan logika modifikasi data di sini
            $item->tanggal = optional($item->created_at)->format('d-m-Y');
            $pajak = null; // Variabel $pajak didefinisikan di awal dengan nilai default null

            if ($item instanceof PersetujuanPo || $item instanceof NonVendor) {
                $uuidValuesPenjualan = explode(',', $item->uuid_penjualan);
                $uuidValuesRealCost = explode(',', $item->uuid_realCost);

                // Gabungkan dua array untuk mencakup semua nilai
                $uuidValues = array_merge($uuidValuesPenjualan, $uuidValuesRealCost);
                $realCostPo = $dataRealcost->whereIn('uuid', $uuidValues);

                // Menggunakan metode first() untuk mendapatkan satu objek hasil
                $pajak_po = $realCostPo->first()->pajak_po ?? null;
                $pajak_pph = $realCostPo->first()->pajak_pph ?? null;

                $item->client = $item->client;
                $item->event = $item->event;
                $item->no = $item->no_po;
                $item->nominal = $item->total_po;
                $item->file_po = $item->file;
            } elseif ($item instanceof Invoice) {
                $clientInvoice = DataClient::where('uuid', $item->uuid_vendor)->first();
                $dataPajak = DataPajak::where('uuid', $item->uuid_pajak)->first();
                $pajak = $dataPajak->deskripsi_pajak ?? null; // Menggunakan $pajak untuk invoice
                $item->client = $clientInvoice->nama_client;
                $item->event = $clientInvoice->event;
                $item->no = $item->no_invoice;
                $item->nominal = $item->total;
                $item->file_invoice = $item->file;
                $pajak_po = null; // Reset nilai pajak_po untuk invoice
                $pajak_pph = null; // Reset nilai pajak_pph untuk invoice
            }

            // Pajak dipindahkan ke luar dari kondisi if-else untuk memastikan setiap $item memiliki atribut 'pajakData'
            $item->pajakData = ['pajak_po' => $pajak_po, 'pajak_pph' => $pajak_pph, 'pajak' => $pajak];

            return $item;
        });

        // dd($combinedData);

        // Mengurutkan data berdasarkan tanggal create yang terbaru
        $sortedData = $combinedData->sortByDesc('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($sortedData, 'Get data success');
    }
}
