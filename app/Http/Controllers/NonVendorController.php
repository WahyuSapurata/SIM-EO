<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNonVendorRequest;
use App\Http\Requests\UpdateNonVendorRequest;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\NonVendor;
use App\Models\Po;
use App\Models\RealCost;
use App\Models\Utang;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;

class NonVendorController extends BaseController
{
    public function index()
    {
        $module = 'Persetujuan PO Non Vendor';
        return view('admin.persetujuannonvendor.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = NonVendor::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update(UpdateNonVendorRequest $updateNonVendorRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updateNonVendorRequest->sisa_tagihan);
        try {
            $data = NonVendor::where('uuid', $params)->first();
            $data->sisa_tagihan = $numericValue ? $numericValue : $data->total_po;
            $data->save();

            $uuidArray = explode(',', $data->uuid_realCost);
            Po::whereIn('uuid_penjualan', $uuidArray)->update(['status' => $updateNonVendorRequest->status]);

            if ($numericValue != 0 && $numericValue != $data->total_po) {
                $utang = new Utang();
                $utang->uuid_persetujuanPo = $data->uuid;
                $utang->utang = $data->total_po - $numericValue;
                $utang->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function exportToPDF(Request $request)
    {
        $uuidArray = explode(',', $request->uuid_penjualan);
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

        $tempo = $request->tempo;
        $no_invoice = $request->no_invoice;

        // Tanggal sekarang
        $tanggalSekarang = Carbon::now();

        // Tanggal 31 pada bulan ini
        $tanggal31 = Carbon::parse($tempo)->addDay();

        // Hitung jumlah hari
        $jumlahHari = $tanggalSekarang->diffInDays($tanggal31);

        // return view('procurement.po.invoicenonvendor', compact('realCost', 'client', 'tempo', 'jumlahHari', 'orderedPajak', 'no_invoice'))->render();

        $html = view('procurement.po.invoicenonvendor', compact('realCost', 'client', 'tempo', 'jumlahHari', 'orderedPajak', 'no_invoice'))->render();

        // Buat nama file PDF dengan nomor urut
        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);

        if (auth()->user()->lokasi === 'makassar') {
            $no_po = 'PO/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        } else {
            $no_po = 'PO/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        }

        // Pastikan $client dan $vendor tidak null sebelum mengakses propertinya
        $clientEvent = $client ? $client->event : '';

        $pdfFileName = 'Purchase Invoice-' . $clientEvent . time() . '.pdf';

        $pdfFilePath = 'pdf/' . $pdfFileName; // Direktori dalam direktori public

        SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

        // Simpan informasi PDF ke dalam database menggunakan model Po
        $pdfInfoCollection = Po::whereIn('uuid_penjualan', $uuidArray)->get();

        foreach ($pdfInfoCollection as $pdfInfo) {
            $pdfInfo->file = $no_invoice;
            $pdfInfo->save();
        }

        $subtotalTotal = 0;
        $subTotalPajak = 0;
        foreach ($realCost as $row) {
            $jumlah = $row->satuan_real_cost * $row->freq * $row->qty - $row->disc_item;
            $subtotalTotal += $jumlah;
        }
        foreach ($orderedPajak as $row_pajak) {
            if ($row_pajak->pajak_data) {
                // $jumlahPajak = ($row_pajak->satuan_real_cost * $row_pajak->qty * $row_pajak->freq - $row_pajak->disc_item) * ($row_pajak->pajak_data->pajak / 100);
                // $subTotalPajak += $jumlahPajak;

                foreach ($row_pajak->pajak_data as $pajakData) {
                    // Hitung jumlah pajak untuk setiap data pajak
                    $jumlahPajakPerData = ($row_pajak->satuan_real_cost * $row_pajak->qty * $row_pajak->freq - $row_pajak->disc_item) * ($pajakData['pajak'] / 100);

                    // Periksa apakah "PPH" muncul di awal deskripsi pajak (tanpa memperhatikan huruf besar atau kecil)
                    if (stripos($pajakData['deskripsi_pajak'], 'pph') === 0) {
                        $jumlahPajakPerData *= -1; // Jika jenis pajak adalah PPH, kurangi jumlah pajak
                    }

                    // Hitung total pajak
                    $subTotalPajak += $jumlahPajakPerData;
                }
            }
        }
        try {
            $data = new NonVendor();
            $data->uuid_realCost = $request->uuid_penjualan;
            $data->no_po = $no_po;
            $data->jatuh_tempo = $tempo;
            $data->client = $client->nama_client;
            $data->event = $client->event;
            $data->total_po = $subtotalTotal + $subTotalPajak;
            $data->file = $pdfFileName;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        // Kembalikan link untuk diakses oleh pengguna
        return response()->json([
            'success' => true,
            'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
            'message' => 'PDF Po has been generated and saved successfully.',
        ]);
    }

    public function reload(Request $request)
    {
        $uuidArray = explode(',', $request->uuid_realCost);
        try {
            $dataRealCost = NonVendor::where('uuid', $request->uuid)->first();
            if ($dataRealCost->file && file_exists(public_path('pdf/' . $dataRealCost->file))) {
                unlink(public_path('pdf/' . $dataRealCost->file));
            }
            $dataRealCost->delete();

            RealCost::whereIn('uuid', $uuidArray)->update(['ket' => $request->ket]);
            Po::whereIn('uuid_penjualan', $uuidArray)->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Delete data success');
    }
}
