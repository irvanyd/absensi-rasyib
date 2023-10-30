<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\presensiController;
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


//back to dashboard if login condition
Route::middleware(['guest:pegawai'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/loginProcess', [AuthController::class, 'loginProcess']);
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
});