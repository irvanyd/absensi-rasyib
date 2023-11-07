<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\presensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;

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


//back to dashboard if login condition (pegawai)
Route::middleware(['guest:pegawai'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/loginProcess', [AuthController::class, 'loginProcess']);
});

//back to dashboard if login condition (admin)
Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginAdmin');
    })->name('loginAdmin');
    Route::post('/adminLoginProcess', [AuthController::class, 'adminLoginProcess']);
});

//grouping pegawai access
Route::middleware(['auth:pegawai'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/logoutProcess', [AuthController::class, 'logoutProcess']);

    //presensi
    Route::get('/presensi/create', [presensiController::class, 'create']);
    Route::post('/presensi/store', [presensiController::class, 'store']);

    //edit profile
    Route::get('/editProfile', [presensiController::class, 'editProfile']);
    Route::post('/presensi/{nuptk}/updateProfile', [presensiController::class, 'updateProfile']);

    //history
    Route::get('/presensi/history', [presensiController::class, 'history']);
    Route::post('/getHistory', [presensiController::class, 'getHistory']);

    //izin
    Route::get('/presensi/izin', [presensiController::class,'izin']);
    Route::get('/presensi/createIzin', [presensiController::class, 'createIzin']);
    Route::post('/presensi/storeIzin', [presensiController::class, 'storeIzin']);

});

//grouping pegawai access
Route::middleware(['auth:user'])->group(function () {
    Route::get('/panel/dashboardAdmin', [DashboardController::class,'dashboardAdmin']);
    Route::get('/adminLogoutProcess', [AuthController::class, 'adminLogoutProcess']);

    //pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai/store', [PegawaiController::class, 'store']);
    Route::post('/pegawai/edit', [PegawaiController::class,'edit']);
    Route::post('/pegawai/{nuptk}/update', [PegawaiController::class,'update']);
    Route::post('/pegawai/{nuptk}/delete', [PegawaiController::class,'delete']);

    //departemen
    Route::get('/departemen', [DepartemenController::class,'index']);
    Route::post('/departemen/store', [DepartemenController::class,'store']);
    Route::post('/departemen/edit', [DepartemenController::class,'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class,'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class,'delete']);

    //presensi
    Route::get('/presensi/monitoring', [PresensiController::class,'monitoring']);
    Route::post('/getPresensi', [PresensiController::class,'getPresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class,'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class,'laporan']);
    Route::post('/presensi/cetakLaporan', [PresensiController::class,'cetakLaporan']);

});



