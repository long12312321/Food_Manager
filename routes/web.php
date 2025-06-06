<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', 'TopController@index')->name('top');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/export', 'HomeController@exportExcel')->name('home.export');

Route::post('/employee/store', 'TopController@exportQR')->name('top.export.qr');

Route::post('/employee/scan', 'HomeController@scanQR')->name('home.scan.qr');

Route::get('/employees-table', 'HomeController@getEmployees')->name('home.employees');

