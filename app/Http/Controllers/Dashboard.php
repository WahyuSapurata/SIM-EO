<?php

namespace App\Http\Controllers;

use App\Models\DataClient;
use App\Models\DataPajak;
use App\Models\FeeManajement;
use App\Models\Invoice;
use App\Models\NonVendor;
use App\Models\OperasionalKantor;
use App\Models\Penjualan;
use App\Models\PersetujuanPo;
use App\Models\SaldoAwal;
use App\Models\User;
use App\Models\Utang;
use Illuminate\Http\Request;

class Dashboard extends BaseController
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->back();
        }
        return redirect()->route('login.login-akun');
    }

    public function dashboard_admin()
    {
        $module = 'Dashboard';
        $lokasiUser = auth()->user()->lokasi;
        $invoice = Invoice::join('users', 'invoices.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('invoices.*') // Sesuaikan dengan nama kolom pada invoices
            ->get();
        $data = PersetujuanPo::join('users', 'persetujuan_pos.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('persetujuan_pos.*') // Sesuaikan dengan nama kolom pada persetujuan_pos
            ->get();
        $nonVendor = NonVendor::join('users', 'non_vendors.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('non_vendors.*') // Sesuaikan dengan nama kolom pada non_vendors
            ->get();
        $budgetClient = Penjualan::join('users', 'penjualans.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('penjualans.*') // Sesuaikan dengan nama kolom pada penjualans
            ->get();
        $saldo = SaldoAwal::join('users', 'saldo_awals.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('saldo_awals.*') // Sesuaikan dengan nama kolom pada saldo_awals
            ->get();
        $utang = Utang::join('users', 'utangs.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('utangs.*') // Sesuaikan dengan nama kolom pada utangs
            ->get();

        $dataFees = FeeManajement::all();
        $lokasiUser = auth()->user()->lokasi;
        $dataUser = User::all();
        // Menampilkan OperasionalKantor berdasarkan lokasi user dengan melakukan join
        $dataFee = $dataFees->map(function ($item) use ($dataUser) {
            $dataClient = DataClient::where('uuid', $item->uuid_client)->first();
            $user = $dataUser->where('uuid', $dataClient->uuid_user)->first();
            $item->lokasi_user = $user->lokasi;
            return $item;
        });
        $fee = $dataFee->where('lokasi_user', $lokasiUser)->values();

        $dataOperasional = OperasionalKantor::join('users', 'operasional_kantors.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('operasional_kantors.*') // Sesuaikan dengan nama kolom pada operasional_kantors
            ->get();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;
        $totalUtang = 0;

        $operasional = 0;

        foreach ($invoice as $row_invoice) {
            $totalInvoice += $row_invoice->tagihan;
        }

        foreach ($nonVendor as $row_nonVendor) {
            $totalNonVendor += $row_nonVendor->sisa_tagihan;
        }
        foreach ($data as $row) {
            $subTotalPo += $row->sisa_tagihan;
        }

        foreach ($budgetClient as $row_budget) {
            $totalBudget += $row_budget->harga_satuan * $row_budget->qty * $row_budget->freq;
        }

        foreach ($saldo as $row_saldo) {
            $totalSaldo += $row_saldo->saldo;
        }

        foreach ($fee as $row_fee) {
            $totalFee += $row_fee->total_fee;
        }

        foreach ($dataOperasional as $row_operasional) {
            $operasional += $row_operasional->sisa_tagihan;
        }

        foreach ($utang as $row_utang) {
            $totalUtang += $row_utang->tagihan;
        }

        $totalPo = $subTotalPo + $totalNonVendor + $totalUtang;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo - $operasional;
        return view('dashboard.admin', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas', 'operasional'));
    }

    public function dashboard_procurement()
    {
        $module = 'Dashboard';
        $lokasiUser = auth()->user()->lokasi;
        $invoice = Invoice::join('users', 'invoices.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('invoices.*') // Sesuaikan dengan nama kolom pada invoices
            ->get();
        $data = PersetujuanPo::join('users', 'persetujuan_pos.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('persetujuan_pos.*') // Sesuaikan dengan nama kolom pada persetujuan_pos
            ->get();
        $nonVendor = NonVendor::join('users', 'non_vendors.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('non_vendors.*') // Sesuaikan dengan nama kolom pada non_vendors
            ->get();
        $budgetClient = Penjualan::join('users', 'penjualans.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('penjualans.*') // Sesuaikan dengan nama kolom pada penjualans
            ->get();
        $saldo = SaldoAwal::join('users', 'saldo_awals.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('saldo_awals.*') // Sesuaikan dengan nama kolom pada saldo_awals
            ->get();
        $utang = Utang::join('users', 'utangs.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('utangs.*') // Sesuaikan dengan nama kolom pada utangs
            ->get();

        $dataFees = FeeManajement::all();
        $lokasiUser = auth()->user()->lokasi;
        $dataUser = User::all();
        // Menampilkan OperasionalKantor berdasarkan lokasi user dengan melakukan join
        $dataFee = $dataFees->map(function ($item) use ($dataUser) {
            $dataClient = DataClient::where('uuid', $item->uuid_client)->first();
            $user = optional($dataUser)->where('uuid', optional($dataClient)->uuid_user)->first();
            $item->lokasi_user = $user->lokasi;
            return $item;
        });
        $fee = $dataFee->where('lokasi_user', $lokasiUser)->values();

        $dataOperasional = OperasionalKantor::join('users', 'operasional_kantors.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('operasional_kantors.*') // Sesuaikan dengan nama kolom pada operasional_kantors
            ->get();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;
        $totalUtang = 0;

        $operasional = 0;

        foreach ($invoice as $row_invoice) {
            $totalInvoice += $row_invoice->tagihan;
        }

        foreach ($nonVendor as $row_nonVendor) {
            $totalNonVendor += $row_nonVendor->sisa_tagihan;
        }
        foreach ($data as $row) {
            $subTotalPo += $row->sisa_tagihan;
        }

        foreach ($budgetClient as $row_budget) {
            $totalBudget += $row_budget->harga_satuan * $row_budget->qty * $row_budget->freq;
        }

        foreach ($saldo as $row_saldo) {
            $totalSaldo += $row_saldo->saldo;
        }

        foreach ($fee as $row_fee) {
            $totalFee += $row_fee->total_fee;
        }

        foreach ($dataOperasional as $row_operasional) {
            $operasional += $row_operasional->sisa_tagihan;
        }

        foreach ($utang as $row_utang) {
            $totalUtang += $row_utang->tagihan;
        }

        $totalPo = $subTotalPo + $totalNonVendor + $totalUtang;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo - $operasional;
        return view('dashboard.procurement', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas', 'operasional'));
    }

    public function dashboard_finance()
    {
        $module = 'Dashboard';
        $lokasiUser = auth()->user()->lokasi;
        $invoice = Invoice::join('users', 'invoices.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('invoices.*') // Sesuaikan dengan nama kolom pada invoices
            ->get();
        $data = PersetujuanPo::join('users', 'persetujuan_pos.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('persetujuan_pos.*') // Sesuaikan dengan nama kolom pada persetujuan_pos
            ->get();
        $nonVendor = NonVendor::join('users', 'non_vendors.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('non_vendors.*') // Sesuaikan dengan nama kolom pada non_vendors
            ->get();
        $budgetClient = Penjualan::join('users', 'penjualans.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('penjualans.*') // Sesuaikan dengan nama kolom pada penjualans
            ->get();
        $saldo = SaldoAwal::join('users', 'saldo_awals.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('saldo_awals.*') // Sesuaikan dengan nama kolom pada saldo_awals
            ->get();
        $utang = Utang::join('users', 'utangs.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('utangs.*') // Sesuaikan dengan nama kolom pada utangs
            ->get();

        $dataFees = FeeManajement::all();
        $lokasiUser = auth()->user()->lokasi;
        $dataUser = User::all();
        // Menampilkan OperasionalKantor berdasarkan lokasi user dengan melakukan join
        $dataFee = $dataFees->map(function ($item) use ($dataUser) {
            $dataClient = DataClient::where('uuid', $item->uuid_client)->first();
            $user = $dataUser->where('uuid', $dataClient->uuid_user)->first();
            $item->lokasi_user = $user->lokasi;
            return $item;
        });
        $fee = $dataFee->where('lokasi_user', $lokasiUser)->values();

        $dataOperasional = OperasionalKantor::join('users', 'operasional_kantors.uuid_user', '=', 'users.uuid')
            ->where('users.lokasi', $lokasiUser)
            ->select('operasional_kantors.*') // Sesuaikan dengan nama kolom pada operasional_kantors
            ->get();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;
        $totalUtang = 0;

        $operasional = 0;

        foreach ($invoice as $row_invoice) {
            $totalInvoice += $row_invoice->tagihan;
        }

        foreach ($nonVendor as $row_nonVendor) {
            $totalNonVendor += $row_nonVendor->sisa_tagihan;
        }
        foreach ($data as $row) {
            $subTotalPo += $row->sisa_tagihan;
        }

        foreach ($budgetClient as $row_budget) {
            $totalBudget += $row_budget->harga_satuan * $row_budget->qty * $row_budget->freq;
        }

        foreach ($saldo as $row_saldo) {
            $totalSaldo += $row_saldo->saldo;
        }

        foreach ($fee as $row_fee) {
            $totalFee += $row_fee->total_fee;
        }

        foreach ($dataOperasional as $row_operasional) {
            $operasional += $row_operasional->sisa_tagihan;
        }

        foreach ($utang as $row_utang) {
            $totalUtang += $row_utang->tagihan;
        }

        $totalPo = $subTotalPo + $totalNonVendor + $totalUtang;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo - $operasional;
        return view('dashboard.finance', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas', 'operasional'));
    }

    public function dashboard_direktur()
    {
        $module = 'Dashboard';
        $invoice = Invoice::all();
        $data = PersetujuanPo::all();
        $nonVendor = NonVendor::all();
        $budgetClient = Penjualan::all();
        $saldo = SaldoAwal::all();
        $fee = FeeManajement::all();

        $utang = Utang::all();

        $dataOperasional = OperasionalKantor::all();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;

        $totalUtang = 0;

        $operasional = 0;

        foreach ($invoice as $row_invoice) {
            $totalInvoice += $row_invoice->tagihan;
        }

        foreach ($nonVendor as $row_nonVendor) {
            $totalNonVendor += $row_nonVendor->sisa_tagihan;
        }
        foreach ($data as $row) {
            $subTotalPo += $row->sisa_tagihan;
        }

        foreach ($budgetClient as $row_budget) {
            $totalBudget += $row_budget->harga_satuan * $row_budget->qty * $row_budget->freq;
        }

        foreach ($saldo as $row_saldo) {
            $totalSaldo += $row_saldo->saldo;
        }

        foreach ($fee as $row_fee) {
            $totalFee += $row_fee->total_fee;
        }

        foreach ($dataOperasional as $row_operasional) {
            $operasional += $row_operasional->sisa_tagihan;
        }

        foreach ($utang as $row_utang) {
            $totalUtang += $row_utang->tagihan;
        }

        $totalPo = $subTotalPo + $totalNonVendor + $totalUtang;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo - $operasional;
        return view('dashboard.direktur', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas', 'operasional'));
    }

    public function dashboard_pajak()
    {
        $module = 'Dashboard';
        $dataPajak = DataPajak::all();
        return view('dashboard.pajak', compact('module', 'dataPajak'));
    }
}
