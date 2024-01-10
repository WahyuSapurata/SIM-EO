<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePiutangRequest;
use App\Http\Requests\UpdatePiutangRequest;
use App\Models\DataVendor;
use App\Models\Invoice;
use App\Models\PersetujuanInvoice;
use App\Models\Piutang;

class PiutangController extends BaseController
{
    public function index()
    {
        $module = 'Piutang';
        return view('admin.piutang.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Piutang::all();
        $dataPersetujuanInvoice = PersetujuanInvoice::all();
        $dataInvoice = Invoice::all();
        $dataVendor = DataVendor::all();

        $combinedData = $dataFull->map(function ($item) use ($dataPersetujuanInvoice, $dataInvoice, $dataVendor) {
            $persetujuanInvoice = $dataPersetujuanInvoice->where('uuid', $item->uuid_persetujuanInvoice)->first();
            $invoice = $dataInvoice->where('uuid', $persetujuanInvoice->uuid_invoice)->first();
            $vendor = $dataVendor->where('uuid', $invoice->uuid_vendor)->first();

            $item->no_invoice = $invoice->no_invoice;
            $item->tanggal_invoice = $invoice->tanggal_invoice;
            $item->vendor = $vendor->nama_perusahaan;
            $item->deskripsi = $invoice->deskripsi;
            $item->file = $invoice->file;

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function update(UpdatePiutangRequest $updatePiutangRequest, $params)
    {
        // Hapus karakter non-numerik (koma dan spasi)
        $numericValue = (int) str_replace(['Rp', ',', ' '], '', $updatePiutangRequest->tagihan);
        try {
            $data = Piutang::where('uuid', $params)->first();
            $data->tagihan = $numericValue;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
