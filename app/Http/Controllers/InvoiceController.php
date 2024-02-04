<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\DataBank;
use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\DataVendor;
use App\Models\Invoice;
use Barryvdh\Snappy\Facades\SnappyPdf;
use NumberFormatter;

class InvoiceController extends BaseController
{
    public function index()
    {
        $module = 'Daftar Invoice';
        return view('admin.invoice.index', compact('module'));
    }

    public function get()
    {
        // Mengambil data penjualan berdasarkan parameter
        if (auth()->user()->role === 'direktur') {
            $dataFull = Invoice::all();
        } else {
            $lokasiUser = auth()->user()->lokasi;

            // Menampilkan Penjualan berdasarkan lokasi user dengan melakukan join
            $dataFull = Invoice::join('users', 'invoices.uuid_user', '=', 'users.uuid')
                ->where('users.lokasi', $lokasiUser)
                ->select('invoices.*') // Sesuaikan dengan nama kolom pada penjualans
                ->get();
        }

        $dataVendor = DataClient::all();
        $dataPajak = DataPajak::all();

        $combinedData = $dataFull->map(function ($item) use ($dataVendor, $dataPajak) {
            $vendor = $dataVendor->where('uuid', $item->uuid_vendor)->first();
            $pajak = $dataPajak->where('uuid', $item->uuid_pajak)->first();

            // Periksa apakah $data tidak kosong sebelum mengakses propertinya
            if ($vendor) {
                // Menambahkan data user ke dalam setiap item absen
                $item->vendor = $vendor->nama_client ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->vendor = null;
            }

            // Periksa apakah $data tidak kosong sebelum mengakses propertinya
            if ($pajak) {
                // Menambahkan data user ke dalam setiap item absen
                $item->pajak = $pajak->deskripsi_pajak ?? null;
            } else {
                // Jika $data kosong, berikan nilai default atau kosong
                $item->pajak = null;
            }

            return $item;
        });

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($combinedData, 'Get data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = Invoice::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(UpdateInvoiceRequest $updateInvoiceRequest)
    {
        $data = Invoice::where('uuid', $updateInvoiceRequest->uuid)->first();

        $kop = $updateInvoiceRequest->kop;
        $uuid_vendor = $updateInvoiceRequest->uuid_vendor;
        $no_invoice = $updateInvoiceRequest->no_invoice;
        $tanggal_invoice = $updateInvoiceRequest->tanggal_invoice;
        $deskripsi = $updateInvoiceRequest->deskripsi;
        $penanggung_jawab = $updateInvoiceRequest->penanggung_jawab;
        $jabatan = $updateInvoiceRequest->jabatan;
        $uuid_bank = $updateInvoiceRequest->uuid_bank;
        $total = (int) str_replace(['Rp', ',', ' '], '', $updateInvoiceRequest->total);
        $uuid_pajak = $updateInvoiceRequest->uuid_pajak;


        $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
        $huruf = $formatter->format($total);

        // Buat nama file PDF dengan nomor urut
        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);
        if (auth()->user()->lokasi === 'makassar') {
            $no_inv = 'INV/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        } else {
            $no_inv = 'INV/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        }

        $dataClient = DataClient::where('uuid', $uuid_vendor)->first();

        $dataBank = DataBank::where('uuid', $uuid_bank)->first();

        $dataPajak = DataPajak::where('uuid', $uuid_pajak)->first();

        if ($data->file === null) {
            $this->validate($updateInvoiceRequest, [
                'kop' => 'required',
            ], [
                'required' => 'Kolom :attribute harus di isi.',
            ], [
                'kop' => 'Kop',
            ]);

            // return view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak'))->render();
            if ($kop === 'CV. INIEVENT LANCAR JAYA') {
                $html = view('admin.invoice.pdf_invoice', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
            } elseif ($kop === 'DoubleHelix Indonesia') {
                $html = view('admin.invoice.pdf_invoice_2', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
            } elseif ($kop === 'PT. LINGKARAN GANDA BERKARYA') {
                $html = view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak'))->render();
            } elseif ($kop === 'Kop Kosong') {
                $html = view('admin.invoice.pdf_invoice_kopkosong', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
            } elseif ($kop === 'PT. MAHAKARYA KREASI SOLUSI') {
                $html = view('admin.invoice.pdf_invoice_4', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
            }

            $pdfFileName = 'Purchase Invoice ' . $deskripsi . ' ' . time() . '.pdf';

            $pdfFilePath = 'pdf-invoice/' . $pdfFileName; // Direktori dalam direktori public

            SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

            try {
                $data->uuid_vendor = $uuid_vendor;
                $data->uuid_user = auth()->user()->uuid;
                $data->no_invoice = $no_inv;
                $data->tanggal = $updateInvoiceRequest->tanggal;
                $data->tanggal_invoice = $tanggal_invoice;
                $data->deskripsi = $deskripsi;
                $data->penanggung_jawab = $penanggung_jawab;
                $data->jabatan = $jabatan;
                $data->uuid_bank = $uuid_bank;
                $data->total = $total;
                $data->uuid_pajak = $uuid_pajak;
                $data->file = $pdfFileName;
                $data->save();
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), $e->getMessage(), 400);
            }

            // Kembalikan link untuk diakses oleh pengguna
            return response()->json([
                'success' => true,
                'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
                'message' => 'PDF Invoice has been generated and saved successfully.',
            ]);
        } else {
            try {
                $data->uuid_vendor = $updateInvoiceRequest->uuid_vendor;
                $data->uuid_user = auth()->user()->uuid;
                $data->no_invoice = $no_inv;
                $data->tanggal = $updateInvoiceRequest->tanggal;
                $data->tanggal_invoice = $updateInvoiceRequest->tanggal_invoice;
                $data->deskripsi = $updateInvoiceRequest->deskripsi;
                $data->penanggung_jawab = $updateInvoiceRequest->penanggung_jawab;
                $data->jabatan = $updateInvoiceRequest->jabatan;
                $data->uuid_bank = $updateInvoiceRequest->uuid_bank;
                $data->total = $total;
                $data->uuid_pajak = $updateInvoiceRequest->uuid_pajak;
                $data->save();
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), $e->getMessage(), 400);
            }

            return $this->sendResponse($data, 'Update data success');
        }
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = Invoice::where('uuid', $params)->first();
            if ($data->file && file_exists(public_path('pdf-invoice/' . $data->file))) {
                unlink(public_path('pdf-invoice/' . $data->file));
            }
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }

    public function exportToPDF(StoreInvoiceRequest $storeInvoiceRequest)
    {
        $kop = $storeInvoiceRequest->kop;
        $uuid_vendor = $storeInvoiceRequest->uuid_vendor;
        $no_invoice = $storeInvoiceRequest->no_invoice;
        $tanggal_invoice = $storeInvoiceRequest->tanggal_invoice;
        $deskripsi = $storeInvoiceRequest->deskripsi;
        $penanggung_jawab = $storeInvoiceRequest->penanggung_jawab;
        $jabatan = $storeInvoiceRequest->jabatan;
        $uuid_bank = $storeInvoiceRequest->uuid_bank;
        $total = (int) str_replace(['Rp', ',', ' '], '', $storeInvoiceRequest->total);
        $uuid_pajak = $storeInvoiceRequest->uuid_pajak;

        $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
        $huruf = $formatter->format($total);

        // Buat nama file PDF dengan nomor urut
        $tahun = date('Y'); // Mendapatkan tahun saat ini
        $duaAngkaTerakhir = substr($tahun, -2);
        if (auth()->user()->lokasi === 'makassar') {
            $no_inv = 'INV/MKS-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        } else {
            $no_inv = 'INV/JKT-' . $duaAngkaTerakhir . date('m') . $no_invoice;
        }

        $dataClient = DataClient::where('uuid', $uuid_vendor)->first();

        $dataBank = DataBank::where('uuid', $uuid_bank)->first();

        $dataPajak = DataPajak::where('uuid', $uuid_pajak)->first();

        // return view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak'))->render();
        if ($kop === 'CV. INIEVENT LANCAR JAYA') {
            $html = view('admin.invoice.pdf_invoice', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
        } elseif ($kop === 'DoubleHelix Indonesia') {
            $html = view('admin.invoice.pdf_invoice_2', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
        } elseif ($kop === 'PT. LINGKARAN GANDA BERKARYA') {
            $html = view('admin.invoice.pdf_invoice_3', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan', 'dataPajak'))->render();
        } elseif ($kop === 'Kop Kosong') {
            $html = view('admin.invoice.pdf_invoice_kopkosong', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
        } elseif ($kop === 'PT. MAHAKARYA KREASI SOLUSI') {
            $html = view('admin.invoice.pdf_invoice_4', compact('no_inv', 'tanggal_invoice', 'dataClient', 'deskripsi', 'total', 'huruf', 'dataBank', 'penanggung_jawab', 'jabatan'))->render();
        }

        $pdfFileName = 'Purchase Invoice ' . $deskripsi . ' ' . time() . '.pdf';

        $pdfFilePath = 'pdf-invoice/' . $pdfFileName; // Direktori dalam direktori public

        SnappyPdf::loadHTML($html)->save(public_path($pdfFilePath));

        try {
            $data = new Invoice();
            $data->uuid_vendor = $uuid_vendor;
            $data->uuid_user = auth()->user()->uuid;
            $data->no_invoice = $no_inv;
            $data->tanggal = $storeInvoiceRequest->tanggal;
            $data->tanggal_invoice = $tanggal_invoice;
            $data->deskripsi = $deskripsi;
            $data->penanggung_jawab = $penanggung_jawab;
            $data->jabatan = $jabatan;
            $data->uuid_bank = $uuid_bank;
            $data->total = $total;
            $data->uuid_pajak = $uuid_pajak;
            $data->file = $pdfFileName;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        // Kembalikan link untuk diakses oleh pengguna
        return response()->json([
            'success' => true,
            'pdf_link' => url($pdfFilePath), // Tautan ke file PDF yang disimpan
            'message' => 'PDF Invoice has been generated and saved successfully.',
        ]);
    }
}
