<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GedungController;

Route::pattern('id', '[0-9]+');

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::prefix('admin')->group(function () {
    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/', [AdminController::class, 'laporan'])->name('admin.laporan');
        Route::get('/{id}', [LaporanController::class, 'show_laporan'])->name('admin.show_laporan');
        Route::post('/{id}', [AdminController::class, 'update_laporan'])->name('admin.update_laporan');
    });

    // Fasilitas
    Route::prefix('fasilitas')->group(function () {
        Route::get('/', [AdminController::class, 'list_fasilitas'])->name('admin.fasilitas');
        Route::get('/create', [FasilitasController::class, 'create_fasilitas'])->name('admin.create_fasilitas');
        Route::post('/', [FasilitasController::class, 'store_fasilitas'])->name('admin.store_fasilitas');
        Route::get('/{id}', [FasilitasController::class, 'show_fasilitas'])->name('admin.show_fasilitas');
        Route::get('/{id}/edit', [FasilitasController::class, 'edit_fasilitas'])->name('admin.edit_fasilitas');
        Route::post('/{id}', [FasilitasController::class, 'update_fasilitas'])->name('admin.update_fasilitas');
        Route::delete('/{id}', [FasilitasController::class, 'destroy_fasilitas'])->name('admin.destroy_fasilitas');
    });

    // Gedung
    Route::prefix('gedung')->group(function () {
        Route::get('/', [AdminController::class, 'list_gedung'])->name('admin.gedung');
        Route::get('/create', [GedungController::class, 'create_gedung'])->name('admin.create_gedung');
        Route::post('/', [GedungController::class, 'store_gedung'])->name('admin.store_gedung');
        Route::get('/{id}', [GedungController::class, 'show_gedung'])->name('admin.show_gedung');
        Route::get('/{id}/edit', [GedungController::class, 'edit_gedung'])->name('admin.edit_gedung');
        Route::post('/{id}', [GedungController::class, 'update_gedung'])->name('admin.update_gedung');
        Route::delete('/{id}', [AdminController::class, 'destroy_gedung'])->name('admin.destroy_gedung');
    });

    // Pengguna
    Route::prefix('pengguna')->group(function () {
        Route::get('/', [AdminController::class, 'list_pengguna'])->name('admin.pengguna');
        Route::get('/create', [AdminController::class, 'create_pengguna'])->name('admin.create_pengguna');
        Route::post('/', [AdminController::class, 'store_pengguna'])->name('admin.store_pengguna');
        Route::get('/{id}', [AdminController::class, 'show_pengguna'])->name('admin.show_pengguna');
        Route::get('/{id}/edit', [AdminController::class, 'edit_pengguna'])->name('admin.edit_pengguna');
        Route::post('/{id}', [AdminController::class, 'update_pengguna'])->name('admin.update_pengguna');
        Route::delete('/{id}', [AdminController::class, 'destroy_pengguna'])->name('admin.destroy_pengguna');
    });

    // Statistik & Laporan
    Route::get('/statistik', [AdminController::class, 'statistik'])->name('admin.statistik');
    Route::prefix('laporan_periodik')->group(function () {
        Route::get('/', [AdminController::class, 'laporan_periodik'])->name('admin.laporan_periodik');
        Route::get('/{id}', [AdminController::class, 'show_laporan_periodik'])->name('admin.show_laporan_periodik');
        Route::post('/{id}', [AdminController::class, 'update_laporan_periodik'])->name('admin.update_laporan_periodik');
    });
});

Route::prefix('teknisi')->group(function () {
    Route::prefix('penugasan')->group(function () {
        Route::get('/', [TeknisiController::class, 'penugasan']);
        Route::get('/{id}/edit', [TeknisiController::class, 'edit']);
        Route::post('/{id}', [TeknisiController::class, 'update_laporan']);
    });

    Route::prefix('riwayat_penugasan')->group(function () {
        Route::get('/', [TeknisiController::class, 'riwayat_penugasan'])->name('teknisi.riwayat_penugasan');
        Route::get('/{id}', [TeknisiController::class, 'show_riwayat'])->name('teknisi.show_riwayat');
    });
});

Route::prefix('sarpras')->group(function () {
    Route::prefix('laporan_masuk')->group(function () {
        Route::get('/', [SarprasController::class, 'list_laporan'])->name('sarpras.laporan');
        Route::get('/{id}', [LaporanController::class, 'show_laporan'])->name('sarpras.show_laporan');
        Route::post('/{id}', [SarprasController::class, 'update_laporan'])->name('sarpras.update_laporan');
    });

    Route::get('/sistem_rekomendasi', [SarprasController::class, 'spk'])->name('sarpras.spk');
    Route::get('/statistik', [SarprasController::class, 'statistik'])->name('sarpras.statistik');
});

Route::prefix('pelapor')->group(function () {
    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [PelaporController::class, 'profile'])->name('pelapor.profile');
        Route::put('/', [PelaporController::class, 'updateProfile'])->name('pelapor.updateProfile');
        Route::put('/change-password', [PelaporController::class, 'updatePassword'])->name('pelapor.updatePassword');
    });

    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/', [PelaporController::class, 'list_laporan'])->name('pelapor.laporan');
        Route::get('/create', [PelaporController::class, 'create_laporan'])->name('pelapor.createLaporan');
        Route::post('/', [PelaporController::class, 'store_laporan'])->name('pelapor.storeLaporan');
        Route::get('/{id}', [PelaporController::class, 'show_laporan'])->name('pelapor.showLaporan');
    });

    // Laporan Saya
    Route::prefix('laporan_saya')->group(function () {
        Route::get('/', [PelaporController::class, 'laporan_saya'])->name('pelapor.laporan_saya');
        Route::get('/{id}', [LaporanController::class, 'show_laporan_saya'])->name('pelapor.show_laporan_saya');
        Route::post('/{id}', [PelaporController::class, 'store_rating_laporan'])->name('pelapor.store_rating_laporan');
    });
});
// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
    });

    // Teknisi Routes
    Route::middleware(['role:teknisi'])->group(function () {
        
    });

    // Sarpras Routes
    Route::middleware(['role:sarpras'])->group(function () {

    });

    // Pelapor Routes
    Route::middleware(['role:pelapor'])->group(function () {

    });
});