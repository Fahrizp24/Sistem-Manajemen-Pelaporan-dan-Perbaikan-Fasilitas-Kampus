<?php

use App\Http\Controllers\LaporanKerusakanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContohController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admincontroller;


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

Route::get('/', [ContohController::class, 'index']);

Route::group(['prefix' => 'admin'], function () { });

Route::group(['prefix' => 'sarpras'], function () { });

Route::group(['prefix' => 'teknisi'], function () { });

// Group route khusus tendik, dosen, dan mahasiswa
Route::group(['prefix' => 'pelapor'], function () {
    Route::get('/', [PelaporController::class, 'index'])->name('pelapor.index');
    Route::get('/createLaporan', [PelaporController::class, 'createLaporan'])->name('pelapor.createLaporan');
    Route::post('/storeLaporan', [PelaporController::class, 'storeLaporan'])->name('pelapor.storeLaporan');
    Route::get('/laporan/{id}', [PelaporController::class, 'Laporan'])->name('pelapor.Laporan');
    // Route::get('/laporan/{id}/edit', [PelaporController::class, 'editLaporan'])->name('pelapor.editLaporan');
    // Route::put('/laporan/{id}', [PelaporController::class, 'updateLaporan'])->name('pelapor.updateLaporan');
    Route::get('/profile', [PelaporController::class, 'profile'])->name('pelapor.profile');
    Route::get('/profile/edit', [PelaporController::class, 'editProfile'])->name('pelapor.editProfile');
    Route::put('/profile', [PelaporController::class, 'updateProfile'])->name('pelapor.updateProfile');
    Route::get('/profile/change-password', [PelaporController::class, 'changePassword'])->name('pelapor.changePassword');
    Route::put('/profile/change-password', [PelaporController::class, 'updatePassword'])->name('pelapor.updatePassword');
    Route::get('/laporan_saya', [PelaporController::class, 'show_laporan_saya'])->name('laporan-kerusakan.show');
    Route::get('/laporan_saya/{id}', [PelaporController::class, 'show_laporan_saya_detail'])->name('laporan-kerusakan.show_detail');
    Route::get('laporan_kerusakan', [PelaporController::class, 'laporan_kerusakan'])->name('laporan_kerusakan');
});

Route::prefix('laporan-kerusakan')->group(function () {
    Route::get('/', [LaporanKerusakanController::class, 'index'])->name('laporan-kerusakan.index');
    Route::get('/create', [LaporanKerusakanController::class, 'create'])->name('laporan-kerusakan.create');
    Route::post('/', [LaporanKerusakanController::class, 'store'])->name('laporan-kerusakan.store');

});
// Halaman form laporan kerusakan
Route::middleware(['auth'])->group(function () {
    // Laporan Kerusakan Routes
});