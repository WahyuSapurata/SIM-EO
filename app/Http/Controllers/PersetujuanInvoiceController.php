<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersetujuanInvoiceRequest;
use App\Http\Requests\UpdatePersetujuanInvoiceRequest;
use App\Models\Invoice;
use App\Models\PersetujuanInvoice;
use App\Models\Piutang;
use Illuminate\Http\Request;

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

            if ($numericValue != 0 && $numericValue != $invoice->total) {
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

    public function reload(Request $request)
    {
        try {
            $dataInvoice = Invoice::where('uuid', $request->uuid)->first();

            // Menghapus file PDF jika ada
            if ($dataInvoice->file && file_exists(public_path('pdf-invoice/' . $dataInvoice->file))) {
                unlink(public_path('pdf-invoice/' . $dataInvoice->file));
            }

            // Mengupdate data Invoice
            $dataInvoice->update(['ket' => $request->ket, 'file' => null]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse('success', 'Update data success');
    }
}
