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
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PoController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Client';
        return view('procurement.po.index', compact('module'));
    }

    public function po($params)
    {
        $module = 'Daftar PO';
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

    public function exportToPDF(Request $request)
    {
        $vendor = DataVendor::where('uuid', $request->vendor)->first();
        $uuidArray = explode(',', $request->uuid_penjualan);
        $penjualan = Penjualan::whereIn('uuid', $uuidArray)->get();
        $realCost = RealCost::all();

        $combinedData = $penjualan->map(function ($item) use ($realCost) {
            $data = $realCost->where('uuid_po', $item->uuid)->first();

            // Periksa apakah $data tidak kosong sebelum mengakses propertinya
            if ($data) {
                // Menambahkan data user ke dalam setiap item absen
                $item->satuan_real_cost = $data->satuan_real_cost ?? null;
                $item->pajak_po = $data->pajak_po ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->satuan_real_cost = null;
                $item->pajak_po = null;
            }

            return $item;
        });

        $pajakPoValues = $combinedData->pluck('pajak_po')->filter()->toArray();

        // Ambil data pajak berdasarkan deskripsi_pajak yang sesuai dengan nilai-nilai pada $pajakPoValues
        $pajak = DataPajak::whereIn('deskripsi_pajak', $pajakPoValues)->get();

        // Buat koleksi baru untuk menyimpan data pajak sesuai dengan urutan pada $pajakPoValues
        $orderedPajak = collect($pajakPoValues)->map(function ($value) use ($pajak) {
            return $pajak->firstWhere('deskripsi_pajak', $value);
        });

        $client = DataClient::where('uuid', $penjualan[0]->uuid_client)->first();

        $disc = $request->disc;
        $tempo = $request->tempo;

        // Tanggal sekarang
        $tanggalSekarang = Carbon::now();

        // Tanggal 31 pada bulan ini
        $tanggal31 = Carbon::parse($tempo)->addDay();

        // Hitung jumlah hari
        $jumlahHari = $tanggalSekarang->diffInDays($tanggal31);

        $lastPdfNumber = Po::max('file') ?? 0;
        // Menggunakan ekspresi reguler untuk mengambil angka dari nama file
        preg_match('/\d+/', $lastPdfNumber, $matches);

        // Hasilnya akan ada di dalam $matches[0]
        $angkaDariNamaFile = $matches[0];
        $newPdfNumber = $angkaDariNamaFile + 1;

        $html = view('procurement.po.invoice', compact('vendor', 'combinedData', 'client', 'disc', 'tempo', 'jumlahHari', 'orderedPajak', 'newPdfNumber'))->render();

        // Buat nama file PDF dengan nomor urut
        $pdfFileName = $newPdfNumber . '.pdf';
        $pdfFilePath = 'pdf/' . $pdfFileName; // Direktori dalam direktori public

        SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

        // Simpan informasi PDF ke dalam database menggunakan model Po
        $pdfInfoCollection = Po::whereIn('uuid_penjualan', $uuidArray)->get();

        foreach ($pdfInfoCollection as $pdfInfo) {
            $pdfInfo->file = $pdfFileName;
            $pdfInfo->save();
        }

        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);
        if (auth()->user()->lokasi === 'makassar') {
            $no_po = 'PO/MKS-' . $duaAngkaTerakhir . date('m') . $newPdfNumber;
        } else {
            $no_po = 'PO/JKT-' . $duaAngkaTerakhir . date('m') . $newPdfNumber;
        }

        $subtotalTotal = 0;
        $subTotalPajak = 0;
        foreach ($combinedData as $row) {
            $jumlah = $row->harga_satuan * $row->freq * $row->qty;
            $subtotalTotal += $jumlah;
        }
        foreach ($orderedPajak as $row_pajak) {
            $subTotalPajak += $subtotalTotal * ($row_pajak->pajak / 100);
        }

        try {
            $data = new PersetujuanPo();
            $data->uuid_penjualan = $request->uuid_penjualan;
            $data->no_po = $no_po;
            $data->client = $client->nama_client;
            $data->event = $client->event;
            $data->total_po = $subtotalTotal + $subTotalPajak - (int) str_replace(['Rp', ',', ' '], '', $disc);
            $data->file = $pdfFileName;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        // $pdf = FacadePdf::loadHTML($html);
        return SnappyPdf::loadHTML($html)
            ->download($pdfFileName);
    }
}
