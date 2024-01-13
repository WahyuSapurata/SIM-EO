<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PersetujuanPo;
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
        $data = PersetujuanPo::all();
        $data_utang = Utang::all();
        $totalPo = 0;
        $totalRealCost = 0;
        $totalUtang = 0;
        foreach ($data as $row) {
            $totalPo += $row->total_po;
        }

        foreach ($data as $row_real_cost) {
            $totalRealCost += $row_real_cost->sisa_tagihan;
        }
        foreach ($data_utang as $utang) {
            $totalUtang += $utang->tagihan;
        }
        $subTotal = $totalRealCost + $totalUtang;
        $keuntungan = $totalPo + $subTotal - $totalRealCost;
        return view('dashboard.admin', compact('module', 'totalPo', 'subTotal', 'totalRealCost', 'keuntungan'));
    }

    public function dashboard_procurement()
    {
        $module = 'Dashboard';
        $data = PersetujuanPo::all();
        $data_utang = Utang::all();
        $totalPo = 0;
        $totalRealCost = 0;
        $totalUtang = 0;
        foreach ($data as $row) {
            $totalPo += $row->total_po;
        }

        foreach ($data as $row_real_cost) {
            $totalRealCost += $row_real_cost->sisa_tagihan;
        }
        foreach ($data_utang as $utang) {
            $totalUtang += $utang->tagihan;
        }
        $subTotal = $totalRealCost + $totalUtang;
        $keuntungan = $totalPo + $subTotal - $totalRealCost;
        return view('dashboard.procurement', compact('module', 'totalPo', 'subTotal', 'totalRealCost', 'keuntungan'));
    }

    public function dashboard_finance()
    {
        $module = 'Dashboard';
        $data = PersetujuanPo::all();
        $data_utang = Utang::all();
        $totalPo = 0;
        $totalRealCost = 0;
        $totalUtang = 0;
        foreach ($data as $row) {
            $totalPo += $row->total_po;
        }

        foreach ($data as $row_real_cost) {
            $totalRealCost += $row_real_cost->sisa_tagihan;
        }
        foreach ($data_utang as $utang) {
            $totalUtang += $utang->tagihan;
        }
        $subTotal = $totalRealCost + $totalUtang;
        $keuntungan = $totalPo + $subTotal - $totalRealCost;
        return view('dashboard.finance', compact('module', 'totalPo', 'subTotal', 'totalRealCost', 'keuntungan'));
    }

    public function dashboard_direktur()
    {
        $module = 'Dashboard';
        $data = PersetujuanPo::all();
        $data_utang = Utang::all();
        $totalPo = 0;
        $totalRealCost = 0;
        $totalUtang = 0;
        foreach ($data as $row) {
            $totalPo += $row->total_po;
        }

        foreach ($data as $row_real_cost) {
            $totalRealCost += $row_real_cost->sisa_tagihan;
        }
        foreach ($data_utang as $utang) {
            $totalUtang += $utang->tagihan;
        }
        $subTotal = $totalRealCost + $totalUtang;
        $keuntungan = $totalPo + $subTotal - $totalRealCost;
        return view('dashboard.direktur', compact('module', 'totalPo', 'subTotal', 'totalRealCost', 'keuntungan'));
    }

    public function dashboard_pajak()
    {
        $module = 'Dashboard';
        $data = PersetujuanPo::all();
        $data_utang = Utang::all();
        $totalPo = 0;
        $totalRealCost = 0;
        $totalUtang = 0;
        foreach ($data as $row) {
            $totalPo += $row->total_po;
        }

        foreach ($data as $row_real_cost) {
            $totalRealCost += $row_real_cost->sisa_tagihan;
        }
        foreach ($data_utang as $utang) {
            $totalUtang += $utang->tagihan;
        }
        $subTotal = $totalRealCost + $totalUtang;
        $keuntungan = $totalPo + $subTotal - $totalRealCost;
        return view('dashboard.pajak', compact('module', 'totalPo', 'subTotal', 'totalRealCost', 'keuntungan'));
    }
}
