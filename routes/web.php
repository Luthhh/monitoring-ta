<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.a-dashboard');
    });
    route::get('/profile', function () {
        return view('admin.a-profile');
    });
    Route::get('/total-mahasiswa', function () {
        return view('admin.a-totalmahasiswa');
    });
    Route::get('/ahead-mahasiswa', function () {
        return view('admin.a-aheadmahasiswa');
    });
    Route::get('/ideal-mahasiswa', function () {
        return view('admin.a-idealmahasiswa');
    });
    Route::get('/aktivitas-bimbingan', function () {
        return view('admin.a-aktivitasbimbingan');
    });
    Route::get('/behind-mahasiswa', function () {
        return view('admin.a-behindmahasiswa');
    });
    Route::get('/manajemen-mahasiswa', function () {
        return view('admin.a-manajemenmahasiswa');
    });
    Route::get('/manajemen-dosen', function () {
        return view('admin.a-manajemendosen');
    });
    Route::get('/data-mahasiswa', function () {
        return view('admin.a-datamahasiswa');
    });
    Route::get('/data-dosen', function () {
        return view('admin.a-datadosen');
    });
    Route::get('/kritis-mahasiswa', function () {
        return view('admin.a-kritismahasiswa');
    });
    Route::get('/mendekati-batas-studi', function () {
        return view('admin.a-batasstudi');
    });

    

});

    Route::prefix('dosen')->group(function () {
        Route::view('/dashboard', 'dosen.d-dashboard');
        Route::view('/total-mahasiswa', 'dosen.d-totalmahasiswa');
        Route::view('/ahead-mahasiswa', 'dosen.d-aheadmahasiswa');
        Route::view('/ideal-mahasiswa', 'dosen.d-idealmahasiswa');
        Route::view('/behind-mahasiswa', 'dosen.d-behindmahasiswa');
        Route::get('/profile', [DosenController::class, 'profile'])->name('dosen.profile');
        Route::put('/profile/update', [DosenController::class, 'update'])
    ->name('dosen.profile.update');
        Route::view('/aktivitas-bimbingan', 'dosen.d-aktivitasbimbingan');
        Route::view('/notifikasi', 'dosen.d-notifikasi');
    });


    Route::prefix('mahasiswa')->group(function () {
        Route::view('/dashboard', 'mahasiswa.m-dashboard');
        Route::view('/tambah-bimbingan', 'mahasiswa.tambah-bimbingan');
        Route::view('/profile', 'mahasiswa.m-profile');
        Route::view('/notifikasi', 'mahasiswa.m-notifikasi');
        Route::view('/detail-mahasiswa', 'mahasiswa.detail-mahasiswa');

        Route::get('/card/{status?}', [MahasiswaController::class, 'index']);
    });

});