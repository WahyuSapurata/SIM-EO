<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;

class InvoiceController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Invoice';
        return view('admin.invoice.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = Invoice::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    // public function store(Request $request)
    // {
    //     try {
    //         // Memecah string UUID menjadi dua UUID terpisah
    //         $uuids = explode(',', $request->uuid_penjualan);

    //         // Membuat objek Po untuk setiap UUID
    //         foreach ($uuids as $uuid) {
    //             $data = new Po();
    //             $data->uuid_penjualan = $uuid;
    //             $data->status = 'progres';
    //             $data->save();
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendError($e->getMessage(), $e->getMessage(), 400);
    //     }

    //     return $this->sendResponse('success', 'Added data success');
    // }

    public function exportToPDF(StoreInvoiceRequest $storeInvoiceRequest)
    {
        // dd($storeInvoiceRequest->all());

        return view('admin.invoice.pdf_invoice')->render();
    }
}
