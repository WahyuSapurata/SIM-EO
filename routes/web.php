<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', 'Dashboard@index')->name('home.index');

    Route::group(['prefix' => 'login', 'middleware' => ['guest'], 'as' => 'login.'], function () {
        Route::get('/login-akun', 'Auth@show')->name('login-akun');
        Route::post('/login-proses', 'Auth@login_proses')->name('login-proses');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
        Route::get('/dashboard-admin', 'Dashboard@dashboard_admin')->name('dashboard-admin');

        Route::get('/persetujuanpo', 'PersetujuanPo@index')->name('persetujuanpo');
        Route::get('/get-persetujuanpo', 'PersetujuanPo@get')->name('get-persetujuanpo');
        Route::post('/update-persetujuanpo/{params}', 'PersetujuanPo@update')->name('update-persetujuanpo');

        Route::prefix('master-data')->group(function () {
            Route::get('/datauser', 'DataUser@index')->name('datauser');
            Route::get('/get-datauser', 'DataUser@get')->name('get-datauser');
            Route::post('/add-datauser', 'DataUser@store')->name('add-datauser');
            Route::get('/show-datauser/{params}', 'DataUser@show')->name('show-datauser');
            Route::post('/update-datauser/{params}', 'DataUser@update')->name('update-datauser');
            Route::delete('/delete-datauser/{params}', 'DataUser@delete')->name('delete-datauser');

            Route::get('/dataclient', 'DataClientController@index')->name('dataclient');

            Route::get('/datavendor', 'DataVendorController@index')->name('datavendor');
            Route::get('/get-datavendor', 'DataVendorController@get')->name('get-datavendor');
            Route::post('/add-datavendor', 'DataVendorController@store')->name('add-datavendor');
            Route::get('/show-datavendor/{params}', 'DataVendorController@show')->name('show-datavendor');
            Route::post('/update-datavendor/{params}', 'DataVendorController@update')->name('update-datavendor');
            Route::delete('/delete-datavendor/{params}', 'DataVendorController@delete')->name('delete-datavendor');

            Route::get('/datapajak', 'DataPajakController@index')->name('datapajak');
            Route::get('/get-datapajak', 'DataPajakController@get')->name('get-datapajak');
            Route::post('/add-datapajak', 'DataPajakController@store')->name('add-datapajak');
            Route::get('/show-datapajak/{params}', 'DataPajakController@show')->name('show-datapajak');
            Route::post('/update-datapajak/{params}', 'DataPajakController@update')->name('update-datapajak');
            Route::delete('/delete-datapajak/{params}', 'DataPajakController@delete')->name('delete-datapajak');

            Route::get('/databank', 'DataBankController@index')->name('databank');
            Route::get('/get-databank', 'DataBankController@get')->name('get-databank');
            Route::post('/add-databank', 'DataBankController@store')->name('add-databank');
            Route::get('/show-databank/{params}', 'DataBankController@show')->name('show-databank');
            Route::post('/update-databank/{params}', 'DataBankController@update')->name('update-databank');
            Route::delete('/delete-databank/{params}', 'DataBankController@delete')->name('delete-databank');
        });

        Route::get('/dataitemvendor/{params}', 'ItemVendorController@index')->name('dataitemvendor');
        Route::get('/get-dataitemvendor/{params}', 'ItemVendorController@get')->name('get-dataitemvendor');
        Route::post('/add-dataitemvendor', 'ItemVendorController@store')->name('add-dataitemvendor');
        Route::get('/show-dataitemvendor/{params}', 'ItemVendorController@show')->name('show-dataitemvendor');
        Route::post('/update-dataitemvendor/{params}', 'ItemVendorController@update')->name('update-dataitemvendor');
        Route::delete('/delete-dataitemvendor/{params}', 'ItemVendorController@delete')->name('delete-dataitemvendor');
        Route::post('/add-import-vendor', 'ItemVendorController@import_vendor')->name('add-import-vendor');

        Route::get('/invoice', 'InvoiceController@index')->name('invoice');
        Route::get('/add-export-invoice', 'InvoiceController@exportToPDF')->name('add-export-invoice');
        Route::get('/get-invoice', 'InvoiceController@get')->name('get-invoice');
        Route::get('/show-invoice/{params}', 'InvoiceController@show')->name('show-invoice');
        Route::post('/update-invoice/{params}', 'InvoiceController@update')->name('update-invoice');
        Route::delete('/delete-invoice/{params}', 'InvoiceController@delete')->name('delete-invoice');

        Route::get('/persetujuaninvoice', 'PersetujuanInvoiceController@index')->name('persetujuaninvoice');
        Route::get('/get-persetujuaninvoice', 'PersetujuanInvoiceController@get')->name('get-persetujuaninvoice');
        Route::post('/update-persetujuaninvoice/{params}', 'PersetujuanInvoiceController@update')->name('update-persetujuaninvoice');

        Route::get('/utang', 'UtangController@index')->name('utang');
        Route::get('/get-utang', 'UtangController@get')->name('get-utang');
        Route::post('/update-utang/{params}', 'UtangController@update')->name('update-utang');

        Route::get('/piutang', 'PiutangController@index')->name('piutang');
        Route::get('/get-piutang', 'PiutangController@get')->name('get-piutang');
        Route::post('/update-piutang/{params}', 'PiutangController@update')->name('update-piutang');

        Route::get('/laporan', 'Laporan@index')->name('laporan');
        Route::get('/get-saldo', 'Laporan@get')->name('get-saldo');
        Route::get('/get-laporan', 'Laporan@getLaporan')->name('get-laporan');
        Route::post('/add-saldo', 'Laporan@store')->name('add-saldo');

        Route::get('/ubahpassword', 'UbahPassword@index')->name('ubahpassword');
        Route::post('/update-password/{params}', 'UbahPassword@update')->name('update-password');
    });

    Route::group(['prefix' => 'procurement', 'middleware' => ['auth'], 'as' => 'procurement.'], function () {
        Route::get('/dashboard-procurement', 'Dashboard@dashboard_procurement')->name('dashboard-procurement');

        Route::get('/get-dataclient', 'DataClientController@get')->name('get-dataclient');
        Route::post('/add-dataclient', 'DataClientController@store')->name('add-dataclient');
        Route::get('/show-dataclient/{params}', 'DataClientController@show')->name('show-dataclient');
        Route::post('/update-dataclient/{params}', 'DataClientController@update')->name('update-dataclient');
        Route::delete('/delete-dataclient/{params}', 'DataClientController@delete')->name('delete-dataclient');

        Route::get('/penjualan', 'PenjualanController@index')->name('penjualan');
        Route::get('/penjualan/{params}', 'PenjualanController@penjualan')->name('penjualan-params');
        Route::get('/get-penjualan/{params}', 'PenjualanController@get')->name('get-penjualan');
        Route::post('/add-penjualan', 'PenjualanController@store')->name('add-penjualan');
        Route::get('/show-penjualan/{params}', 'PenjualanController@show')->name('show-penjualan');
        Route::post('/update-penjualan/{params}', 'PenjualanController@update')->name('update-penjualan');
        Route::delete('/delete-penjualan/{params}', 'PenjualanController@delete')->name('delete-penjualan');

        Route::post('/add-import-penjualan', 'PenjualanController@import_penjualan')->name('add-import-penjualan');

        Route::get('/export-excel/{params}', 'ExportExcel@exportToExcel')->name('export-excel');

        Route::get('/po', 'PoController@index')->name('po');
        Route::get('/po-client/{params}', 'PoController@po')->name('po-client');
        Route::get('/get-po', 'PoController@get')->name('get-po');
        Route::post('/add-po', 'PoController@store')->name('add-po');
        Route::get('/show-po/{params}', 'PoController@show')->name('show-po');
        Route::post('/update-po/{params}', 'PoController@update')->name('update-po');
        Route::delete('/delete-po/{params}', 'PoController@delete')->name('delete-po');

        Route::post('/add-fee-management', 'FeeManajementController@store')->name('add-fee-management');
        Route::get('/fee-management/{params}', 'FeeManajementController@get')->name('fee-management-params');

        Route::get('/export-invoice', 'PoController@exportToPDF')->name('export-invoice');
        // Route::get('/export-invoice-pdf', 'PoController@viewPdf')->name('export-invoice-pdf');
        // Route::get('/tes', 'PoController@tes')->name('tes');

        Route::post('/add-realCost', 'RealCostController@store')->name('add-realCost');
        Route::get('/get-realCost', 'RealCostController@get')->name('get-realCost');
        Route::get('/show-realCost/{params}', 'RealCostController@show')->name('show-realCost');
        Route::post('/update-realCost/{params}', 'RealCostController@update')->name('update-realCost');
    });

    Route::group(['prefix' => 'finance', 'middleware' => ['auth'], 'as' => 'finance.'], function () {
        Route::get('/dashboard-finance', 'Dashboard@dashboard_finance')->name('dashboard-finance');
    });

    Route::group(['prefix' => 'direktur', 'middleware' => ['auth'], 'as' => 'direktur.'], function () {
        Route::get('/dashboard-direktur', 'Dashboard@dashboard_direktur')->name('dashboard-direktur');
    });

    Route::group(['prefix' => 'pajak', 'middleware' => ['auth'], 'as' => 'pajak.'], function () {
        Route::get('/dashboard-pajak', 'Dashboard@dashboard_pajak')->name('dashboard-pajak');
    });

    Route::get('/logout', 'Auth@logout')->name('logout');
});
