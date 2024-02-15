<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth as RequestsAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class Auth extends BaseController
{
    public function show()
    {
        return view('auth.login');
    }

    public function login_proses(RequestsAuth $authRequest)
    {
        $credential = $authRequest->getCredentials();

        if (!FacadesAuth::attempt($credential)) {
            return redirect()->route('login.login-akun')->with('failed', 'Username atau Password salah')->withInput($authRequest->only('username'));
        } else {
            return $this->authenticated();
        }
    }

    public function authenticated()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard-admin');
        } elseif (auth()->user()->role === 'procurement') {
            return redirect()->route('procurement.dashboard-procurement');
        } elseif (auth()->user()->role === 'finance') {
            return redirect()->route('finance.dashboard-finance');
        } elseif (auth()->user()->role === 'direktur') {
            return redirect()->route('direktur.dashboard-direktur');
        } elseif (auth()->user()->role === 'pajak') {
            return redirect()->route('pajak.faktur-keluar');
        }
    }

    public function logout()
    {
        FacadesAuth::logout();
        return redirect()->route('login.login-akun')->with('success', 'Berhasil Logout');
    }
}
