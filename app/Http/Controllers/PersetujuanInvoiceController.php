<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersetujuanInvoiceRequest;
use App\Http\Requests\UpdatePersetujuanInvoiceRequest;
use App\Models\Invoice;
use App\Models\PersetujuanInvoice;
use App\Models\Piutang;

class PersetujuanInvoiceController extends BaseController
{
    public function index()
    {
        $module = 'Persetujuan Invoice';
        return view('admin.persetjuaninvoice.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = PersetujuanInvoice::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function update(StorePersetujuanInvoiceRequest $storePersetujuanInvoiceRequest, $params)
    {
        try {
            $data = new PersetujuanInvoice();
            $data->uuid_invoice = $storePersetujuanInvoiceRequest->uuid_invoice;
            $data->status = $storePersetujuanInvoiceRequest->status;
            $data->save();

            // Gunakan fungsi str_replace dan intval untuk mengonversi tagihan ke nilai numerik
            $numericValue = intval(str_replace(['Rp', ',', ' '], '', $storePersetujuanInvoiceRequest->tagihan));

            // Pastikan $params adalah array
            $params = is_array($params) ? $params : [$params];

            // Perbarui tagihan pada Invoice berdasarkan $params
            $invoice = Invoice::where('uuid', $params)->first();
            $invoice->update(['tagihan' => $numericValue === 0 ? $invoice->total : $numericValue]);

            if ($numericValue != 0) {
                $utang = new Piutang();
                $utang->uuid_persetujuanInvoice = $data->uuid;
                $utang->utang = $invoice->total - $numericValue;
                $utang->save();
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }
}
