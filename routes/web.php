<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

        Route::prefix('persetujuan-po')->group(function () {
            Route::get('/persetujuanpo', 'PersetujuanPo@index')->name('persetujuanpo');
            Route::get('/get-persetujuanpo', 'PersetujuanPo@get')->name('get-persetujuanpo');
            Route::post('/reload-persetujuanpo', 'PersetujuanPo@reload')->name('reload-persetujuanpo');
            Route::post('/update-persetujuanpo/{params}', 'PersetujuanPo@update')->name('update-persetujuanpo');
            Route::get('/export-persetujuanpo', 'PersetujuanPo@exportToExcel')->name('export-persetujuanpo');

            Route::get('/pesetujuannonvendor', 'NonVendorController@index')->name('pesetujuannonvendor');
            Route::get('/get-pesetujuannonvendor', 'NonVendorController@get')->name('get-pesetujuannonvendor');
            Route::post('/reload-pesetujuannonvendor', 'NonVendorController@reload')->name('reload-pesetujuannonvendor');
            Route::post('/update-pesetujuannonvendor/{params}', 'NonVendorController@update')->name('update-pesetujuannonvendor');
            Route::get('/export-pesetujuannonvendor', 'NonVendorController@exportToExcel')->name('export-pesetujuannonvendor');
        });

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
            Route::post('/import-datavendor', 'DataVendorController@import_data_vendor')->name('import-datavendor');

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

            Route::get('/kategori', 'KategoriController@index')->name('kategori');
            Route::get('/get-kategori', 'KategoriController@get')->name('get-kategori');
            Route::post('/add-kategori', 'KategoriController@store')->name('add-kategori');
            Route::get('/show-kategori/{params}', 'KategoriController@show')->name('show-kategori');
            Route::post('/update-kategori/{params}', 'KategoriController@update')->name('update-kategori');
            Route::delete('/delete-kategori/{params}', 'KategoriController@delete')->name('delete-kategori');
        });

        Route::get('/dataitemvendor/{params}', 'ItemVendorController@index')->name('dataitemvendor');
        Route::get('/get-dataitemvendor/{params}', 'ItemVendorController@get')->name('get-dataitemvendor');
        Route::post('/add-dataitemvendor', 'ItemVendorController@store')->name('add-dataitemvendor');
        Route::get('/show-dataitemvendor/{params}', 'ItemVendorController@show')->name('show-dataitemvendor');
        Route::post('/update-dataitemvendor/{params}', 'ItemVendorController@update')->name('update-dataitemvendor');
        Route::delete('/delete-dataitemvendor/{params}', 'ItemVendorController@delete')->name('delete-dataitemvendor');
        Route::post('/add-import-vendor', 'ItemVendorController@import_vendor')->name('add-import-vendor');

        Route::prefix('data-invoice')->group(function () {
            Route::get('/invoice', 'InvoiceController@index')->name('invoice');
            Route::get('/add-export-invoice', 'InvoiceController@exportToPDF')->name('add-export-invoice');
            Route::get('/get-invoice', 'InvoiceController@get')->name('get-invoice');
            Route::get('/show-invoice/{params}', 'InvoiceController@show')->name('show-invoice');
            Route::get('/update-invoice', 'InvoiceController@update')->name('update-invoice');
            Route::delete('/delete-invoice/{params}', 'InvoiceController@delete')->name('delete-invoice');
            Route::get('/export-invoice', 'InvoiceController@exportToExcel')->name('export-invoice');

            Route::get('/persetujuaninvoice', 'PersetujuanInvoiceController@index')->name('persetujuaninvoice');
            Route::get('/get-persetujuaninvoice', 'PersetujuanInvoiceController@get')->name('get-persetujuaninvoice');
            Route::post('/reload-persetujuaninvoice', 'PersetujuanInvoiceController@reload')->name('reload-persetujuaninvoice');
            Route::post('/update-persetujuaninvoice/{params}', 'PersetujuanInvoiceController@update')->name('update-persetujuaninvoice');
            Route::get('/export-persetujuaninvoice', 'PersetujuanInvoiceController@exportToExcel')->name('export-persetujuaninvoice');
        });

        Route::prefix('Utang-piutang')->group(function () {
            Route::get('/utang', 'UtangController@index')->name('utang');
            Route::get('/get-utang', 'UtangController@get')->name('get-utang');
            Route::post('/update-utang/{params}', 'UtangController@update')->name('update-utang');
            Route::get('/export-utang', 'UtangController@exportToExcel')->name('export-utang');

            Route::get('/piutang', 'PiutangController@index')->name('piutang');
            Route::get('/get-piutang', 'PiutangController@get')->name('get-piutang');
            Route::post('/update-piutang/{params}', 'PiutangController@update')->name('update-piutang');
            Route::post('/lunas/{params}', 'PiutangController@lunas')->name('lunas');
            Route::get('/export-piutang', 'PiutangController@exportToExcel')->name('export-piutang');
        });

        Route::prefix('data-laporan')->group(function () {
            Route::get('/laporan', 'Laporan@index')->name('laporan');
            Route::get('/get-saldo', 'Laporan@get')->name('get-saldo');
            Route::get('/get-laporan/{params}', 'Laporan@getLaporan')->name('get-laporan');
            Route::get('/export-laporan/{params}', 'Laporan@exportToExcel')->name('export-laporan');
            Route::post('/add-saldo', 'Laporan@store')->name('add-saldo');

            Route::get('/laporan-fee', 'FeeManajementController@index')->name('laporan-fee');
            Route::get('/get-laporan-fee', 'FeeManajementController@get_laporanFee')->name('get-laporan-fee');
            // Route::get('/export-laporan-fee/{params}', 'Laporan@exportToExcel')->name('export-laporan-fee');

            Route::get('/laporan-laba', 'LaporanLabaController@index')->name('laporan-laba');
            Route::get('/get-laporan-laba', 'LaporanLabaController@get')->name('get-laporan-laba');
            Route::post('/add-laporan-laba', 'LaporanLabaController@store')->name('add-laporan-laba');
            Route::get('/show-laporan-laba/{params}', 'LaporanLabaController@show')->name('show-laporan-laba');
            Route::post('/update-laporan-laba/{params}', 'LaporanLabaController@update')->name('update-laporan-laba');
            Route::delete('/delete-laporan-laba/{params}', 'LaporanLabaController@delete')->name('delete-laporan-laba');
        });

        Route::prefix('data-operasional')->group(function () {
            Route::get('/operasionalkantor', 'OperasionalKantorController@index')->name('operasionalkantor');
            Route::get('/get-operasionalkantor', 'OperasionalKantorController@get')->name('get-operasionalkantor');
            Route::post('/add-operasionalkantor', 'OperasionalKantorController@store')->name('add-operasionalkantor');
            Route::get('/show-operasionalkantor/{params}', 'OperasionalKantorController@show')->name('show-operasionalkantor');
            Route::post('/update-operasionalkantor/{params}', 'OperasionalKantorController@update')->name('update-operasionalkantor');
            Route::delete('/delete-operasionalkantor/{params}', 'OperasionalKantorController@delete')->name('delete-operasionalkantor');
            Route::get('/export-operasionalkantor', 'OperasionalKantorController@exportToExcel')->name('export-operasionalkantor');

            Route::get('/persetujuanoperasionalkantor', 'PersetujuanOperasionalKantorController@index')->name('persetujuanoperasionalkantor');
            Route::post('/update-persetujuanoperasionalkantor/{params}', 'PersetujuanOperasionalKantorController@update')->name('update-persetujuanoperasionalkantor');
            Route::get('/export-persetujuanoperasionalkantor', 'PersetujuanOperasionalKantorController@exportToExcel')->name('export-persetujuanoperasionalkantor');
        });

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
        Route::get('/get-realCost/{params}', 'RealCostController@get')->name('get-realCost');
        Route::get('/show-realCost/{params}', 'RealCostController@show')->name('show-realCost');
        Route::post('/update-realCost/{params}', 'RealCostController@update')->name('update-realCost');
        Route::delete('/delete-realCost/{params}', 'RealCostController@delete')->name('delete-realCost');
        Route::get('/export-realCost/{params}', 'RealCostController@exportToExcel')->name('export-realCost');

        Route::get('/export-invoiceNonVendor', 'NonVendorController@exportToPDF')->name('export-invoiceNonVendor');

        Route::get('/persetujuanpo-prc', 'PersetujuanPo@index')->name('persetujuanpo-prc');
        Route::get('/pesetujuannonvendor-prc', 'NonVendorController@index')->name('pesetujuannonvendor-prc');

        Route::post('/disabled-data', 'PoController@disabled')->name('disabled-data');
    });

    Route::group(['prefix' => 'finance', 'middleware' => ['auth'], 'as' => 'finance.'], function () {
        Route::get('/dashboard-finance', 'Dashboard@dashboard_finance')->name('dashboard-finance');
        Route::get('/invoice-finance', 'InvoiceController@index')->name('invoice-finance');
    });

    Route::group(['prefix' => 'direktur', 'middleware' => ['auth'], 'as' => 'direktur.'], function () {
        Route::get('/dashboard-direktur', 'Dashboard@dashboard_direktur')->name('dashboard-direktur');
    });

    Route::group(['prefix' => 'pajak', 'middleware' => ['auth'], 'as' => 'pajak.'], function () {
        Route::get('/dashboard-pajak', 'Dashboard@dashboard_pajak')->name('dashboard-pajak');

        Route::prefix('laporan')->group(function () {
            Route::get('/laporan-pajak', 'LaporanPajak@index')->name('laporan-pajak');
            Route::get('/get-laporan-pajak', 'LaporanPajak@get_laporanPajak')->name('get-laporan-pajak');

            Route::get('/faktur-keluar', 'FakturKeluarController@index')->name('faktur-keluar');
            Route::get('/get-faktur-keluar', 'FakturKeluarController@get_faktur_keluar')->name('get-faktur-keluar');
            Route::get('/show-faktur-keluar/{params}', 'FakturKeluarController@show')->name('show-faktur-keluar');
            Route::post('/storeUpdate-faktur-keluar/{params}', 'FakturKeluarController@storeUpdate')->name('storeUpdate-faktur-keluar');

            Route::get('/faktur-masuk', 'FakturMasukController@index')->name('faktur-masuk');
            Route::get('/get-faktur-masuk', 'FakturMasukController@get_faktur_masuk')->name('get-faktur-masuk');
            Route::get('/show-faktur-masuk/{params}', 'FakturMasukController@show')->name('show-faktur-masuk');
            Route::post('/storeUpdate-faktur-masuk/{params}', 'FakturMasukController@storeUpdate')->name('storeUpdate-faktur-masuk');

            Route::get('/pemotongan-pajak', 'PemotonganPajakController@index')->name('pemotongan-pajak');
            Route::get('/get-pemotongan-pajak', 'PemotonganPajakController@get')->name('get-pemotongan-pajak');
            Route::post('/import-pemotongan-pajak', 'PemotonganPajakController@import_pemotongan_pajak')->name('import-pemotongan-pajak');
            Route::get('/show-pemotongan-pajak/{params}', 'PemotonganPajakController@show')->name('show-pemotongan-pajak');
            Route::post('/update-pemotongan-pajak/{params}', 'PemotonganPajakController@update')->name('update-pemotongan-pajak');
            Route::delete('/delete-pemotongan-pajak/{params}', 'PemotonganPajakController@delete')->name('delete-pemotongan-pajak');
        });
    });

    Route::get('/logout', 'Auth@logout')->name('logout');
});
