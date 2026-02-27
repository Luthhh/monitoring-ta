<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::view('/dashboard', 'admin.a-dashboard');
        Route::view('/total-mahasiswa', 'admin.a-totalmahasiswa');
        Route::view('/ahead-mahasiswa', 'admin.a-aheadmahasiswa');
        Route::view('/ideal-mahasiswa', 'admin.a-idealmahasiswa');
        Route::view('/behind-mahasiswa', 'admin.a-behindmahasiswa');
        Route::view('/manajemen-mahasiswa', 'admin.a-manajemenmahasiswa');
        Route::get('/manajemen-dosen', [AdminController::class, 'index'])
            ->name('admin.manajemen-dosen');
        Route::post('/admin/dosen/store', [AdminController::class, 'store'])
            ->name('admin.dosen.store');
        Route::put('/admin/dosen/{id}', [AdminController::class, 'update'])
            ->name('admin.dosen.update');
        Route::delete('/admin/dosen/{id}', [AdminController::class, 'destroy'])
            ->name('admin.dosen.destroy');
    });


    Route::prefix('dosen')->group(function () {
        Route::view('/dashboard', 'dosen.d-dashboard');
        Route::get('/total-mahasiswa',
        [DosenController::class, 'totalMahasiswa']);
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