<?php

namespace App\Http\Controllers;

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
        return view('dashboard.admin', compact('module'));
    }

    public function dashboard_procurement()
    {
        $module = 'Dashboard';
        return view('dashboard.procurement', compact('module'));
    }

    public function dashboard_finance()
    {
        $module = 'Dashboard';
        return view('dashboard.finance', compact('module'));
    }

    public function dashboard_direktur()
    {
        $module = 'Dashboard';
        return view('dashboard.direktur', compact('module'));
    }

    public function dashboard_pajak()
    {
        $module = 'Dashboard';
        return view('dashboard.pajak', compact('module'));
    }
}
