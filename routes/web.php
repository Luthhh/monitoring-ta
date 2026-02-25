<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;


Route::get('/login', function () {
    return view('auth.login');
});

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.a-dashboard');
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
    Route::get('/behind-mahasiswa', function () {
        return view('admin.a-behindmahasiswa');
    });

});

Route::prefix('dosen')->group(function () {

    Route::get('/dashboard', function () {
        return view('dosen.d-dashboard');
    });
    Route::get('/total-mahasiswa', function () {
        return view('dosen.d-totalmahasiswa');
    });
    Route::get('/ahead-mahasiswa', function () {
        return view('dosen.d-aheadmahasiswa');
    });
    Route::get('/ideal-mahasiswa', function () {
        return view('dosen.d-idealmahasiswa');
    });
    Route::get('/behind-mahasiswa', function () {
        return view('dosen.d-behindmahasiswa');
    });
    Route::get('/profile', function () {
        return view('dosen.d-profile');
    });
    Route::get('/aktivitas-bimbingan', function () {
        return view('dosen.d-aktivitasbimbingan');
    });
    Route::get('/notifikasi', function () {
        return view('dosen.d-notifikasi');
    });

    

});

Route::prefix('mahasiswa')->group(function () {

    Route::get('/dashboard', function () {
        return view('mahasiswa.m-dashboard');
    });

    Route::get('/tambah-bimbingan', function () {
        return view('mahasiswa.tambah-bimbingan');
    });
    Route::get('/profile', function () {
        return view('mahasiswa.m-profile');
    });
    Route::get('/notifikasi', function () {
        return view('mahasiswa.m-notifikasi');
    });
    Route::get('/card/{status?}', [MahasiswaController::class, 'index']);
    Route::get('/detail-mahasiswa', function () {
        return view('mahasiswa.detail-mahasiswa');
    });


    

});


