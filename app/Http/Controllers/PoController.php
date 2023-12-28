<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePoRequest;
use App\Http\Requests\UpdatePoRequest;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\DataVendor;
use App\Models\Penjualan;
use App\Models\Po;
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
        $pajak = DataPajak::where('uuid', $request->pajak)->first();

        $uuidArray = explode(',', $request->uuid_penjualan);
        $penjualan = Penjualan::whereIn('uuid', $uuidArray)->get();
        $client = DataClient::where('uuid', $penjualan[0]->uuid_client)->first();

        // Buat objek mPDF dengan orientasi lanskap
        //$mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal']);



        // Contoh konten PDF
        $html = view('procurement.po.invoice', compact('vendor', 'pajak', 'penjualan', 'client'))->render();

        // Tambahkan konten ke PDF
        $mpdf->WriteHTML($html);

        // Tampilkan PDF di browser (inline) dengan orientasi lanskap
        $mpdf->Output('Purchase Invoice' . $client->event . ' - ' . $vendor->nama_perusahaan . '.pdf', 'I');
    }


    // public function show($params)
    // {
    //     $data = array();
    //     try {
    //         $data = Penjualan::where('uuid', $params)->first();
    //     } catch (\Exception $e) {
    //         return $this->sendError($e->getMessage(), $e->getMessage(), 400);
    //     }
    //     return $this->sendResponse($data, 'Show data success');
    // }

    // public function update(StorePenjualanRequest $storePenjualanRequest, $params)
    // {
    //     // Hapus karakter non-numerik (koma dan spasi)
    //     $numericValue = (int) str_replace(['Rp', ',', ' '], '', $storePenjualanRequest->harga_satuan);
    //     try {
    //         $data = Penjualan::where('uuid', $params)->first();
    //         $data->uuid_client = $storePenjualanRequest->uuid_client;
    //         $data->kegiatan = $storePenjualanRequest->kegiatan;
    //         $data->qty = $storePenjualanRequest->qty;
    //         $data->satuan_kegiatan = $storePenjualanRequest->satuan_kegiatan;
    //         $data->freq = $storePenjualanRequest->freq;
    //         $data->satuan = $storePenjualanRequest->satuan;
    //         $data->harga_satuan = $numericValue;
    //         $data->ket = $storePenjualanRequest->ket;
    //         $data->save();
    //     } catch (\Exception $e) {
    //         return $this->sendError($e->getMessage(), $e->getMessage(), 400);
    //     }

    //     return $this->sendResponse($data, 'Update data success');
    // }

    // public function delete($params)
    // {
    //     $data = array();
    //     try {
    //         $data = Penjualan::where('uuid', $params)->first();
    //         $data->delete();
    //     } catch (\Exception $e) {
    //         return $this->sendError($e->getMessage(), $e->getMessage(), 400);
    //     }
    //     return $this->sendResponse($data, 'Delete data success');
    // }

    // public function import_penjualan(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'file_excel' => 'nullable|mimes:xls,xlsx,csv|max:20048', // Batas ukuran file diatur menjadi 2MB (sesuaikan sesuai kebutuhan)
    //         ]);

    //         $uuid_client = $request->input('uuid_client');
    //         $file = $request->file('file_excel');
    //         Excel::import(new ImportPenjualan($uuid_client), $file);

    //         return $this->sendResponse('success', 'Excel data uploaded and saved successfully');
    //     } catch (\Exception $e) {
    //         return $this->sendError('Error uploading and saving Excel: ' . $e->getMessage(), $e->getMessage(), 200);
    //     }
    // }
}
