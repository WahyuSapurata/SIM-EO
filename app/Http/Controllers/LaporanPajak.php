<?php

namespace App\Http\Controllers;

use App\Models\DataClient;
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
        // dd($combinedPersetujuanPo);

        $mergedData = collect([]);
        $mergedData = $mergedData->merge($combinedPersetujuanPo);
        // Menggabungkan data dari Invoice
        $persetujuanInvoice = Invoice::whereNotNull('uuid_pajak')->get();
        $mergedData = $mergedData->merge($persetujuanInvoice);

        // Modifikasi data jika diperlukan
        $combinedData = $mergedData->map(function ($item) {
            // Tambahkan logika modifikasi data di sini
            $item->tanggal = optional($item->created_at)->format('d-m-Y');

            if ($item instanceof PersetujuanPo || $item instanceof NonVendor) {
                $item->client = $item->client;
                $item->event = $item->event;
                $item->no = $item->no_po;
                $item->nominal = $item->total_po;
                $item->file_po = $item->file;
            } elseif ($item instanceof Invoice) {
                $clientInvoice = DataClient::where('uuid', $item->uuid_vendor)->first();
                $item->client = $clientInvoice->nama_client;
                $item->event = $clientInvoice->event;
                $item->no = $item->no_invoice;
                $item->nominal = $item->total;
                $item->file_invoice = $item->file;
            }

            return $item;
        });

        // Mengurutkan data berdasarkan tanggal create yang terbaru
        $sortedData = $combinedData->sortByDesc('created_at')->values()->all();

        // Mengembalikan respon
        return $this->sendResponse($sortedData, 'Get data success');
    }
}
