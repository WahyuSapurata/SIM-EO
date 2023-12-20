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

        Route::prefix('master-data')->group(function () {
            Route::get('/datauser', 'DataUser@index')->name('datauser');
            Route::get('/get-datauser', 'DataUser@get')->name('get-datauser');
            Route::post('/add-datauser', 'DataUser@store')->name('add-datauser');
            Route::get('/show-datauser/{params}', 'DataUser@show')->name('show-datauser');
            Route::post('/update-datauser/{params}', 'DataUser@update')->name('update-datauser');
            Route::delete('/delete-datauser/{params}', 'DataUser@delete')->name('delete-datauser');

            Route::get('/datavendor', 'DataVendorController@index')->name('datavendor');
            Route::get('/get-datavendor', 'DataVendorController@get')->name('get-datavendor');
            Route::post('/add-datavendor', 'DataVendorController@store')->name('add-datavendor');
            Route::get('/show-datavendor/{params}', 'DataVendorController@show')->name('show-datavendor');
            Route::post('/update-datavendor/{params}', 'DataVendorController@update')->name('update-datavendor');
            Route::delete('/delete-datavendor/{params}', 'DataVendorController@delete')->name('delete-datavendor');

            Route::get('/dataclient', 'DataClientController@index')->name('dataclient');
            Route::get('/get-dataclient', 'DataClientController@get')->name('get-dataclient');
            Route::post('/add-dataclient', 'DataClientController@store')->name('add-dataclient');
            Route::get('/show-dataclient/{params}', 'DataClientController@show')->name('show-dataclient');
            Route::post('/update-dataclient/{params}', 'DataClientController@update')->name('update-dataclient');
            Route::delete('/delete-dataclient/{params}', 'DataClientController@delete')->name('delete-dataclient');
        });

        Route::get('/ubahpassword', 'UbahPassword@index')->name('ubahpassword');
        Route::post('/update-password/{params}', 'UbahPassword@update')->name('update-password');
    });

    Route::group(['prefix' => 'procurement', 'middleware' => ['auth'], 'as' => 'procurement.'], function () {
        Route::get('/dashboard-procurement', 'Dashboard@dashboard_procurement')->name('dashboard-procurement');
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
