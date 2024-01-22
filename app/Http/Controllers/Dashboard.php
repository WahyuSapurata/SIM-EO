<?php

namespace App\Http\Controllers;

use App\Models\FeeManajement;
use App\Models\Invoice;
use App\Models\NonVendor;
use App\Models\Penjualan;
use App\Models\PersetujuanPo;
use App\Models\SaldoAwal;
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
        $invoice = Invoice::all();
        $data = PersetujuanPo::all();
        $nonVendor = NonVendor::all();
        $budgetClient = Penjualan::all();
        $saldo = SaldoAwal::all();
        $fee = FeeManajement::all();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;

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
        $totalPo = $subTotalPo + $totalNonVendor;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo;
        return view('dashboard.admin', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas'));
    }

    public function dashboard_procurement()
    {
        $module = 'Dashboard';
        $invoice = Invoice::all();
        $data = PersetujuanPo::all();
        $nonVendor = NonVendor::all();
        $budgetClient = Penjualan::all();
        $saldo = SaldoAwal::all();
        $fee = FeeManajement::all();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;

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
        $totalPo = $subTotalPo + $totalNonVendor;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo;
        return view('dashboard.procurement', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas'));
    }

    public function dashboard_finance()
    {
        $module = 'Dashboard';
        $invoice = Invoice::all();
        $data = PersetujuanPo::all();
        $nonVendor = NonVendor::all();
        $budgetClient = Penjualan::all();
        $saldo = SaldoAwal::all();
        $fee = FeeManajement::all();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;

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
        $totalPo = $subTotalPo + $totalNonVendor;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo;
        return view('dashboard.finance', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas'));
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

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;

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
        $totalPo = $subTotalPo + $totalNonVendor;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo;
        return view('dashboard.direktur', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas'));
    }

    public function dashboard_pajak()
    {
        $module = 'Dashboard';
        $invoice = Invoice::all();
        $data = PersetujuanPo::all();
        $nonVendor = NonVendor::all();
        $budgetClient = Penjualan::all();
        $saldo = SaldoAwal::all();
        $fee = FeeManajement::all();

        $totalInvoice = 0;
        $subTotalPo = 0;
        $totalBudget = 0;
        $totalSaldo = 0;
        $totalPersetujuan = 0;
        $totalNonVendor = 0;
        $totalFee = 0;

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
        $totalPo = $subTotalPo + $totalNonVendor;
        $piutang = $totalBudget - $totalPo + $totalFee;
        $kas = $totalSaldo + $totalInvoice - $totalPo;
        return view('dashboard.pajak', compact('module', 'totalInvoice', 'totalPo', 'piutang', 'kas'));
    }
}
