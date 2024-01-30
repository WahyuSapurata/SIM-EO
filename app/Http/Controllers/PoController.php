<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePoRequest;
use App\Http\Requests\UpdatePoRequest;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\DataVendor;
use App\Models\Penjualan;
use App\Models\PersetujuanPo;
use App\Models\Po;
use App\Models\RealCost;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PoController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Client';
        return view('procurement.po.index', compact('module'));
    }

    public function po($params)
    {
        $module = 'Daftar Real Cost';
        return view('procurement.po.po', compact('module'));
    }

    // public function penjualan($params)
    // {
    //     $module = 'Daftar Penjualan';
    //     $this->get($params);
    //     return view('procurement.penjualan.penjualan', compact('module'));
    // }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Po::all();
        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(Request $request)
    {
        try {
            // Memecah string UUID menjadi dua UUID terpisah
            $uuids = explode(',', $request->uuid_penjualan);

            // Membuat objek Po untuk setiap UUID
            foreach ($uuids as $uuid) {
                $data = new Po();
                $data->uuid_penjualan = $uuid;
                $data->status = 'progres';
                $data->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Added data success');
    }

    public function exportToPDF(StorePoRequest $storePoRequest)
    {
        $vendor = DataVendor::where('uuid', $storePoRequest->vendor)->first();

        $uuidArray = explode(',', $storePoRequest->uuid_penjualan);
        $realCost = RealCost::whereIn('uuid', $uuidArray)->get();


        // Buat koleksi baru untuk menyimpan data pajak sesuai dengan urutan pada $pajakPoValues
        $orderedPajak = $realCost->map(function ($value) {
            $deskripsiPajak = [$value->pajak_po, $value->pajak_pph];

            // Ambil data pajak berdasarkan deskripsi
            $pajak = DataPajak::whereIn('deskripsi_pajak', $deskripsiPajak)->get();

            // Tambahkan data pajak ke dalam nilai aktual
            $value->pajak_data = $pajak->toArray();

            return $value;
        });

        $client = DataClient::where('uuid', $realCost[0]->uuid_client)->first();

        $disc = $storePoRequest->disc;
        $tempo = $storePoRequest->tempo;
        $no_invoice = $storePoRequest->no_invoice;

        // Tanggal sekarang
        $tanggalSekarang = Carbon::now();

        // Tanggal 31 pada bulan ini
        $tanggal31 = Carbon::parse($tempo)->addDay();

        // Hitung jumlah hari
        $jumlahHari = $tanggalSekarang->diffInDays($tanggal31);

        return view('procurement.po.invoice', compact('vendor', 'realCost', 'client', 'disc', 'tempo', 'jumlahHari', 'orderedPajak', 'no_invoice'))->render();

        // $html = view('procurement.po.invoice', compact('vendor', 'realCost', 'client', 'disc', 'tempo', 'jumlahHari', 'orderedPajak', 'no_invoice'))->render();

        // // Buat nama file PDF dengan nomor urut
        // $tahun = date('Y'); // Mendapatkan tahun saat ini
        // $duaAngkaTerakhir = substr($tahun, -2);

        // if (auth()->user()->lokasi === 'makassar') {
        //     $no_po = 'PO/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        // } else {
        //     $no_po = 'PO/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        // }

        // // Pastikan $client dan $vendor tidak null sebelum mengakses propertinya
        // $clientEvent = $client ? $client->event : '';
        // $vendorAlamatPerusahaan = $vendor ? $vendor->alamat_perusahaan : '';

        // $pdfFileName = 'Purchase Invoice-' . $clientEvent . ' - ' . $vendorAlamatPerusahaan . time() . '.pdf';

        // $pdfFilePath = 'pdf/' . $pdfFileName; // Direktori dalam direktori public

        // SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

        // // Simpan informasi PDF ke dalam database menggunakan model Po
        // $pdfInfoCollection = Po::whereIn('uuid_penjualan', $uuidArray)->get();

        // foreach ($pdfInfoCollection as $pdfInfo) {
        //     $pdfInfo->file = $no_invoice;
        //     $pdfInfo->save();
        // }

        // $subtotalTotal = 0;
        // $subTotalPajak = 0;
        // foreach ($realCost as $row) {
        //     $jumlah = $row->satuan_real_cost * $row->freq * $row->qty - $row->disc_item;
        //     $subtotalTotal += $jumlah;
        // }
        // foreach ($orderedPajak as $row_pajak) {
        //     if ($row_pajak->pajak_data) {
        //         $jumlahPajak = ($row_pajak->satuan_real_cost * $row_pajak->qty * $row_pajak->freq - $row_pajak->disc_item) * ($row_pajak->pajak_data->pajak / 100);
        //         $subTotalPajak += $jumlahPajak;
        //     }
        // }
        // try {
        //     $data = new PersetujuanPo();
        //     $data->uuid_penjualan = $storePoRequest->uuid_penjualan;
        //     $data->no_po = $no_po;
        //     $data->jatuh_tempo = $tempo;
        //     $data->client = $client->nama_client;
        //     $data->event = $client->event;
        //     $data->total_po = $subtotalTotal + $subTotalPajak - (int) str_replace(['Rp', ',', ' '], '', $disc);
        //     $data->file = $pdfFileName;
        //     $data->save();
        // } catch (\Exception $e) {
        //     return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        // }

        // // Kembalikan link untuk diakses oleh pengguna
        // return response()->json([
        //     'success' => true,
        //     'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
        //     'message' => 'PDF Po has been generated and saved successfully.',
        // ]);
    }
}
