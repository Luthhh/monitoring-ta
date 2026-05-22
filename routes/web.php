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

Route::get('/temp-reset-production-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true
        ]);
        return 'Database completely reset and re-seeded to original state successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


Route::middleware('auth')->group(function () {

    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // Mahasiswa
        Route::get('/total-mahasiswa', [AdminController::class, 'totalMahasiswa'])->name('admin.total-mahasiswa');
        Route::get('/ahead-mahasiswa', [AdminController::class, 'aheadMahasiswa'])->name('admin.ahead-mahasiswa');
        Route::get('/ideal-mahasiswa', [AdminController::class, 'idealMahasiswa'])->name('admin.ideal-mahasiswa');
        Route::get('/behind-mahasiswa', [AdminController::class, 'behindMahasiswa'])->name('admin.behind-mahasiswa');
        Route::get('/aktivitas-bimbingan', [AdminController::class, 'aktivitasBimbingan'])->name('admin.aktivitas-bimbingan');
        Route::get('/data-mahasiswa/{id}', [AdminController::class, 'detailMahasiswa'])->name('admin.detail-mahasiswa');
        Route::view('/data-mahasiswa', 'admin.a-datamahasiswa')->name('admin.data-mahasiswa');
        Route::get('/kritis-mahasiswa', [AdminController::class, 'kritisMahasiswa'])->name('admin.kritis-mahasiswa');
        Route::get('/mendekati-batas-studi', [AdminController::class, 'batasStudiMahasiswa'])->name('admin.batas-studi');
        Route::get('/ontrack-mahasiswa', [AdminController::class, 'ontrackMahasiswa'])->name('admin.ontrack-mahasiswa');
        Route::get('/export-mahasiswa', [AdminController::class, 'exportMahasiswa'])->name('admin.export-mahasiswa');

        // Manajemen Mahasiswa CRUD
        Route::get('/manajemen-mahasiswa', [AdminController::class, 'manajemenMahasiswa'])->name('admin.manajemen-mahasiswa');
        Route::post('/mahasiswa/store', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
        Route::put('/mahasiswa/{id}', [AdminController::class, 'updateMahasiswa'])->name('admin.mahasiswa.update');
        Route::delete('/mahasiswa/{id}', [AdminController::class, 'destroyMahasiswa'])->name('admin.mahasiswa.destroy');

        // Import / Export Mahasiswa
        Route::get('/mahasiswa/export-excel', [AdminController::class, 'exportMahasiswa'])->name('admin.mahasiswa.export-excel');
        Route::get('/mahasiswa/export-csv', [AdminController::class, 'exportMahasiswaCsv'])->name('admin.mahasiswa.export-csv');
        Route::post('/mahasiswa/import', [AdminController::class, 'importMahasiswa'])->name('admin.mahasiswa.import');
        Route::get('/mahasiswa/template', [AdminController::class, 'templateImportMahasiswa'])->name('admin.mahasiswa.template');

        // Manajemen Dosen CRUD
        Route::view('/data-dosen', 'admin.a-datadosen');
        Route::get('/manajemen-dosen', [AdminController::class, 'index'])->name('admin.manajemen-dosen');
        Route::post('/dosen/store', [AdminController::class, 'store'])->name('admin.dosen.store');
        Route::put('/dosen/{id}', [AdminController::class, 'update'])->name('admin.dosen.update');
        Route::delete('/dosen/{id}', [AdminController::class, 'destroy'])->name('admin.dosen.destroy');

        // Export Dosen
        Route::get('/dosen/export-excel', [AdminController::class, 'exportDosen'])->name('admin.dosen.export-excel');

        // Notifikasi Admin
        Route::get('/notifikasi', [AdminController::class, 'notifikasi'])->name('admin.notifikasi');
        Route::post('/notifikasi/mark-read', [AdminController::class, 'markNotifRead'])->name('admin.notif.mark-read');
        Route::get('/notifikasi/unread', [AdminController::class, 'getUnreadNotifs'])->name('admin.notif.unread');
        Route::post('/kirim-pengingat/{id}', [AdminController::class, 'kirimPengingat'])->name('admin.kirim_pengingat');
        Route::post('/upload-bap/{id}', [AdminController::class, 'uploadBap'])->name('admin.upload-bap');

        // Pages Verifikasi Terpisah
        Route::get('/verifikasi/milestone', [AdminController::class, 'verifikasiMilestone'])->name('admin.verifikasi.milestone');
        Route::get('/verifikasi/bimbingan', [AdminController::class, 'verifikasiBimbingan'])->name('admin.verifikasi.bimbingan');
        Route::get('/verifikasi/bap', [AdminController::class, 'verifikasiBap'])->name('admin.verifikasi.bap');
    });

    Route::prefix('dosen')->middleware('role:dosen')->group(function () {

        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
        Route::get('/total-mahasiswa', [DosenController::class, 'totalMahasiswa'])->name('dosen.total_mahasiswa');
        Route::get('/ahead-mahasiswa', [DosenController::class, 'aheadMahasiswa'])->name('dosen.ahead_mahasiswa');
        Route::get('/ideal-mahasiswa', [DosenController::class, 'idealMahasiswa'])->name('dosen.ideal_mahasiswa');
        Route::get('/behind-mahasiswa', [DosenController::class, 'behindMahasiswa'])->name('dosen.behind_mahasiswa');
        Route::get('/profile', [DosenController::class, 'profile'])->name('dosen.profile');
        Route::put('/profile/update', [DosenController::class, 'update'])->name('dosen.profile.update');
        Route::get('/aktivitas-bimbingan', [DosenController::class, 'aktivitasBimbingan'])->name('dosen.aktivitas_bimbingan');
        Route::post('/update-bimbingan-status/{id}', [DosenController::class, 'updateBimbinganStatus'])->name('dosen.update_bimbingan_status');
        Route::post('/update-milestone-status/{id}', [DosenController::class, 'updateMilestoneStatus'])->name('dosen.update_milestone_status');
        Route::get('/data-mahasiswa', [DosenController::class, 'dataMahasiswa'])->name('dosen.data_mahasiswa');
        Route::get('/data-mahasiswa/{id}', [DosenController::class, 'detailMahasiswa'])->name('dosen.detail_mahasiswa');
        Route::post('/kirim-pengingat/{id}', [DosenController::class, 'kirimPengingat'])->name('dosen.kirim_pengingat');
        Route::get('/notifikasi', [DosenController::class, 'notifikasi'])->name('dosen.notifikasi');
        Route::post('/notifikasi/mark-read', [DosenController::class, 'markNotifRead'])->name('dosen.notif.mark-read');
        Route::get('/notifikasi/unread', [DosenController::class, 'getUnreadNotifs'])->name('dosen.notif.unread');
    });

    Route::prefix('mahasiswa')->middleware('role:mahasiswa')->group(function () {

        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
        Route::post('/upload-verifikasi', [MahasiswaController::class, 'uploadVerifikasi']);
        Route::post('/update-timeline', [MahasiswaController::class, 'updateTimeline']);
        Route::post('/upload-bukti-bimbingan/{id}', [MahasiswaController::class, 'uploadBuktiBimbingan']);
        
        Route::get('/tambah-bimbingan', [MahasiswaController::class, 'createBimbingan']);
        Route::post('/tambah-bimbingan', [MahasiswaController::class, 'storeBimbingan']);
        Route::get('/profile', [MahasiswaController::class, 'profile'])->name('mahasiswa.profile');
        Route::put('/profile/update', [MahasiswaController::class, 'updateProfile'])->name('mahasiswa.profile.update');
        Route::put('/profile/update-pembimbing', [MahasiswaController::class, 'updatePembimbing'])->name('mahasiswa.profile.update-pembimbing');
        Route::post('/update-judul', [MahasiswaController::class, 'updateJudul'])->name('mahasiswa.update-judul');
        Route::delete('/bimbingan/{id}', [MahasiswaController::class, 'deleteBimbingan'])->name('mahasiswa.bimbingan.delete');
        Route::get('/notifikasi', [MahasiswaController::class, 'notifikasi'])->name('mahasiswa.notifikasi');
        Route::post('/notifikasi/mark-read', [MahasiswaController::class, 'markNotifRead'])->name('mahasiswa.notif.mark-read');
        Route::get('/notifikasi/unread', [MahasiswaController::class, 'getUnreadNotifs'])->name('mahasiswa.notif.unread');
        Route::view('/detail-mahasiswa', 'mahasiswa.detail-mahasiswa');

        Route::get('/card/{status?}', [MahasiswaController::class, 'index']);
    });

});