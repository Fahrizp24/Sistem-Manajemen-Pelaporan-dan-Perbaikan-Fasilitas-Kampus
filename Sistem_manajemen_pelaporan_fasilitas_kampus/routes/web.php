<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\Admincontroller;
use App\Http\Controllers\GedungController;

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

Route::pattern('id', '[0-9]+'); //ketika ada parametr {id}, maka harus berupa angka
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

// Group route khusus tendik, dosen, dan mahasiswa
Route::group(['prefix' => 'pelapor'], function () {
    //menu profile
    Route::group(['prefix' => 'profile'], function () { 
        Route::get('/', [PelaporController::class, 'profile'])->name('pelapor.profile');
        Route::put('/', [PelaporController::class, 'updateProfile'])->name('pelapor.updateProfile');
        Route::put('/change-password', [PelaporController::class, 'updatePassword'])->name('pelapor.updatePassword');
    });
    //menu laporan
    Route::group(['prefix' => 'laporan'], function () {
        Route::get('/', [PelaporController::class, 'list_laporan'])->name('pelapor.laporan');
        Route::get('/create', [PelaporController::class, 'create_laporan'])->name('pelapor.createLaporan');
        Route::post('/', [PelaporController::class, 'store_laporan'])->name('pelapor.storeLaporan');
        Route::get('/{id}', [PelaporController::class, 'show_laporan'])->name('pelapor.showLaporan');
    });
    //menu laporan saya
    Route::group(['prefix' => 'laporan_saya'], function () {
        Route::get('/', [PelaporController::class, 'laporan_saya'])->name('pelapor.laporan_saya');
        Route::get('/{id}', [LaporanController::class, 'show_laporan_saya'])->name('pelapor.show_laporan_saya');
        Route::post('/{id}', [PelaporController::class, 'store_rating_laporan'])->name('pelapor.store_rating_laporan');
    });
});

// Group route khusus admin
Route::group(['prefix' => 'admin'], function () {
    //menu laporan
    Route::group(['prefix' => 'laporan'], function () {
        Route::get('/', [Admincontroller::class, 'list_laporan'])->name('admin.laporan');
        Route::get('/{id}', [LaporanController::class, 'show_laporan'])->name('admin.show_laporan');
        Route::post('/{id}', [Admincontroller::class, 'update_laporan'])->name('admin.update_laporan');
    });
    //menu fasilitas
    Route::group(['prefix' => 'fasilitas'], function () {
        Route::get('/', [Admincontroller::class, 'list_fasilitas'])->name('admin.fasilitas');
        Route::get('/{id}', [FasilitasController::class, 'show_fasilitas'])->name('admin.show_fasilitas');
        Route::get('/create', [FasilitasController::class, 'create_fasilitas'])->name('admin.create_fasilitas');
        Route::post('/', [FasilitasController::class, 'store_fasilitas'])->name('admin.store_fasilitas');
        Route::get('/{id}/edit', [FasilitasController::class, 'edit_fasilitas'])->name('admin.edit_fasilitas');
        Route::post('/{id}', [FasilitasController::class, 'update_fasilitas'])->name('admin.update_fasilitas');
        Route::get('/{id}/delete', [FasilitasController::class, 'delete_fasilitas'])->name('admin.destroy_fasilitas');
        Route::post('/{id}/delete', [FasilitasController::class, 'destroy_fasilitas'])->name('admin.destroy_fasilitas');
    });
    //menu gedung
    Route::group(['prefix' => 'gedung'], function () {
        Route::get('/', [Admincontroller::class, 'list_gedung'])->name('admin.gedung');
        Route::get('/{id}', [GedungController::class, 'show_gedung'])->name('admin.show_gedung');
        Route::get('/create', [GedungController::class, 'create_gedung'])->name('admin.create_gedung');
        Route::post('/', [GedungController::class, 'store_gedung'])->name('admin.store_gedung');
        Route::get('/{id}/edit', [GedungController::class, 'edit_gedung'])->name('admin.edit_gedung');
        Route::post('/{id}', [GedungController::class, 'update_gedung'])->name('admin.update_gedung');
        Route::get('/{id}/delete', [GedungController::class, 'delete_gedung'])->name('admin.destroy_gedung');
        Route::post('/{id}/delete', [Admincontroller::class, 'destroy_gedung'])->name('admin.destroy_gedung');
    });
    //menu kelola pengguna
    Route::group(['prefix' => 'pengguna'], function () {
        Route::get('/', [Admincontroller::class, 'list_pengguna'])->name('admin.pengguna');
        Route::get('/{id}', [Admincontroller::class, 'show_pengguna'])->name('admin.show_pengguna');
        Route::get('/create', [Admincontroller::class, 'create_pengguna'])->name('admin.create_pengguna');
        Route::post('/', [Admincontroller::class, 'store_pengguna'])->name('admin.store_pengguna');
        Route::get('/{id}/edit', [Admincontroller::class, 'edit_pengguna'])->name('admin.edit_pengguna');
        Route::post('/{id}', [Admincontroller::class, 'update_pengguna'])->name('admin.update_pengguna');
        Route::get('/{id}/delete', [Admincontroller::class, 'delete_pengguna'])->name('admin.destroy_pengguna');
        Route::post('/{id}/delete', [Admincontroller::class, 'destroy_pengguna'])->name('admin.destroy_pengguna'); 
    });
    //menu statistik
    Route::group(['prefix' => 'statistik'], function () {
        Route::get('/', [Admincontroller::class, 'statistik'])->name('admin.statistik');
    });
    //menu laporan periodik
    Route::group(['prefix' => 'laporan_periodik'], function () {
        Route::get('/', [Admincontroller::class, 'laporan_periodik'])->name('admin.laporan_periodik');
        Route::get('/{id}', [Admincontroller::class, 'show_laporan_periodik'])->name('admin.show_laporan_periodik');
        Route::post('/{id}', [Admincontroller::class, 'update_laporan_periodik'])->name('admin.update_laporan_periodik');
    });

});

//Groupe Route untuk teknisi
Route::group(['prefix' => 'teknisi'], function () {
    //menu laporan
    Route::group(['prefix' => 'penugasan'], function () {
        Route::get('/', [TeknisiController::class, 'list_laporan'])->name('teknisi.laporan');
        Route::get('/{id}', [LaporanController::class, 'show_laporan'])->name('teknisi.show_laporan');
        Route::post('/{id}', [TeknisiController::class, 'update_laporan'])->name('teknisi.update_laporan');
    });
    //menu fasilitas
    Route::group(['prefix' => 'riwayat'], function () {
        Route::get('/', [TeknisiController::class, 'list_fasilitas'])->name('teknisi.fasilitas');
        Route::get('/{id}', [TeknisiController::class, 'show_fasilitas'])->name('teknisi.show_fasilitas');
    });
});

//Group route khusus sarpras
Route::group(['prefix' => 'sarpras'], function () {
    //menu laporan
    Route::group(['prefix' => 'laporan_masuk'], function () {
        Route::get('/', [SarprasController::class, 'list_laporan'])->name('sarpras.laporan');
        Route::get('/{id}', [LaporanController::class, 'show_laporan'])->name('sarpras.show_laporan');
        Route::post('/{id}', [SarprasController::class, 'update_laporan'])->name('sarpras.update_laporan');
    });
    //menu spk
    Route::group(['prefix' => 'sistem_rekomendasi'], function () {
        Route::get('/', [SarprasController::class, 'spk'])->name('sarpras.spk');
    });
    //menu statistik
    Route::group(['prefix' => 'statistik'], function () {
        Route::get('/', [SarprasController::class, 'statistik'])->name('sarpras.statistik');
    });
});

Route::get('/', function () {
    return redirect()->route('login');
});


// Halaman form laporan kerusakan
Route::middleware(['auth'])->group(function () {
    // Laporan Kerusakan Routes
});
