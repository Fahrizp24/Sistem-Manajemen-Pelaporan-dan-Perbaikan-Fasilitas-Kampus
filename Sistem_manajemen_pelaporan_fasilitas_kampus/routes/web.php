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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\RuanganController;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

Route::pattern('id', '[0-9]+');

// Public Routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('forgot_password', [AuthController::class, 'forgot_password'])->name('forgot_password');
Route::post('forgot_password/cek_username', [AuthController::class, 'cek_username'])->name('forgot_password.check');
Route::post('forgot_password/pertanyaan', [AuthController::class, 'validasi_pertanyaan'])->name('forgot_password.pertanyaan');
Route::post('forgot_password/reset_password', [AuthController::class, 'reset_password'])->name('forgot_password.reset_password');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Admin Routes
    Route::middleware(['authorize:admin'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::prefix('profile')->group(function () {
                Route::get('/', [AdminController::class, 'profile'])->name('admin.profile');
                Route::put('/', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
                Route::put('/change-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');
                Route::post('/change-foto', [AdminController::class, 'updateFoto'])->name('admin.updateFoto');
            });

            // Laporan
            Route::prefix('laporan_masuk')->group(function () {
                Route::get('/', [AdminController::class, 'laporan_masuk'])->name('admin.laporan');
                Route::post('/data', [AdminController::class, 'data_laporan'])->name('admin.data_laporan');
                Route::get('/show_laporan/{id}', [AdminController::class, 'show_laporan'])->name('admin.show_laporan');
                Route::post('/{id}', [AdminController::class, 'konfirmasi_laporan'])->name('admin.konfirmasi_laporan');
            });
            // Laporan 2
            Route::prefix('kelola_laporan')->group(function () {
                Route::get('/', [AdminController::class, 'laporan2'])->name('admin.laporan2');
                Route::get('/{id}', [LaporanController::class, 'show_laporan2'])->name('admin.show_laporan2');
                Route::post('/{id}', [AdminController::class, 'update_laporan2'])->name('admin.update_laporan2');
            });

            // Fasilitas
            Route::prefix('fasilitas')->group(function () {
                Route::post('/data', [FasilitasController::class, 'data_fasilitas'])->name('admin.data_fasilitas');
                Route::get('/', [FasilitasController::class, 'index'])->name('admin.fasilitas');
                Route::get('/import_fasilitas', [AdminController::class, 'import_fasilitas'])->name('admin.pengguna.import_fasilitas');
                Route::post('/import_fasilitas_store', [AdminController::class, 'import_fasilitas_store'])->name('admin.pengguna.import_fasilitas_store');
                Route::get('/create_ajax', [FasilitasController::class, 'create_fasilitas'])->name('admin.create_fasilitas');
                Route::post('/store_ajax', [FasilitasController::class, 'store_fasilitas'])->name('admin.store_fasilitas');
                Route::get('/{id}', [FasilitasController::class, 'show_fasilitas'])->name('admin.show_fasilitas');
                Route::get('/edit_ajax/{id}', [FasilitasController::class, 'edit_fasilitas'])->name('admin.edit_fasilitas');
                Route::put('/update/{id}', [FasilitasController::class, 'update_fasilitas'])->name('admin.update_fasilitas');
                Route::delete('/{id}', [FasilitasController::class, 'destroy_fasilitas'])->name('admin.destroy_fasilitas');
                Route::get('/get-lantai/{gedung_id}', [FasilitasController::class, 'getLantaiByGedung'])->name('admin.fasilitas.get_lantai');
                Route::get('/get-ruangan/{lantai_id}', [FasilitasController::class, 'getRuanganByLantai'])->name('admin.fasilitas.get_ruangan');
            });

            // Gedung
            Route::prefix('gedung')->group(function () {
                Route::post('/data', [GedungController::class, 'data_gedung'])->name('admin.data_gedung');
                Route::get('/', [GedungController::class, 'index'])->name('admin.gedung');
                Route::get('/create_gedung', [GedungController::class, 'create_gedung'])->name('admin.gedung.create_gedung');
                Route::post('/store', [GedungController::class, 'store_gedung'])->name('admin.store_gedung');
                Route::get('/{id}', [GedungController::class, 'show_gedung'])->name('admin.show_gedung');
                Route::get('/edit/{id}', [GedungController::class, 'edit_gedung'])->name('admin.edit_gedung');
                Route::put('/update/{id}', [GedungController::class, 'update_gedung'])->name('admin.update_gedung');
                Route::delete('/{id}', [GedungController::class, 'destroy_gedung'])->name('admin.destroy_gedung');
            });

            // Lantai
            Route::prefix('lantai')->group(function () {
                Route::post('/data', [LantaiController::class, 'data_lantai'])->name('admin.data_lantai');
                Route::get('/', [LantaiController::class, 'index'])->name('admin.lantai');
                Route::get('/create_lantai', [LantaiController::class, 'create_lantai'])->name('admin.create_lantai');
                Route::post('/store', [LantaiController::class, 'store_lantai'])->name('admin.store_lantai');
                Route::get('/{id}', [LantaiController::class, 'show_lantai'])->name('admin.show_lantai');
                Route::get('/edit/{id}', [LantaiController::class, 'edit_lantai'])->name('admin.edit_lantai');
                Route::put('/update/{id}', [LantaiController::class, 'update_lantai'])->name('admin.update_lantai');
                Route::delete('/{id}', [LantaiController::class, 'destroy_lantai'])->name('admin.destroy_lantai');
            });

            // Ruangan
            Route::prefix('ruangan')->group(function () {
                Route::post('/data', [RuanganController::class, 'data_ruangan'])->name('admin.data_ruangan');
                Route::get('/', [RuanganController::class, 'index'])->name('admin.ruangan');
                Route::get('/create_ruangan', [RuanganController::class, 'create_ruangan'])->name('admin.create_ruangan');
                Route::post('/store', [RuanganController::class, 'store_ruangan'])->name('admin.store_ruangan');
                Route::get('/{id}', [RuanganController::class, 'show_ruangan'])->name('admin.show_ruangan');
                Route::get('/edit/{id}', [RuanganController::class, 'edit_ruangan'])->name('admin.edit_ruangan');
                Route::put('/update/{id}', [RuanganController::class, 'update_ruangan'])->name('admin.update_ruangan');
                Route::delete('/{id}', [RuanganController::class, 'destroy_ruangan'])->name('admin.destroy_ruangan');
                Route::get('/get-lantai/{gedung_id}', [RuanganController::class, 'getLantaiByGedung'])->name('admin.ruangan.get_lantai');
            });

            // Pengguna
            Route::prefix('pengguna')->group(function () {
                Route::post('/data', [AdminController::class, 'data_pengguna'])->name('admin.data_pengguna');
                Route::get('/', [AdminController::class, 'kelola_pengguna'])->name('admin.pengguna');
                Route::get('/import_pengguna', [AdminController::class, 'import_pengguna'])->name('admin.pengguna.import_pengguna');
                Route::post('/import_pengguna_store', [AdminController::class, 'import_pengguna_store'])->name('admin.pengguna.import_pengguna_store');
                Route::get('/create_ajax', [AdminController::class, 'create_pengguna'])->name('admin.pengguna.create_pengguna');
                Route::post('/ajax', [AdminController::class, 'store_pengguna'])->name('admin.pengguna.ajax');
                Route::post('/store', [AdminController::class, 'store_pengguna'])->name('admin.store_pengguna');
                Route::get('/{id}', [AdminController::class, 'show_pengguna'])->name('admin.show_pengguna');
                Route::get('/edit_ajax/{id}', [AdminController::class, 'edit_pengguna'])->name('admin.pengguna.edit_pengguna');
                Route::put('/update/{id}', [AdminController::class, 'update_pengguna'])->name('admin.update_pengguna');
                Route::delete('/{id}', [AdminController::class, 'destroy_pengguna'])->name('admin.destroy');
                Route::post('/reset_password/{id}', [AdminController::class, 'reset_password_pengguna'])->name('admin.pengguna.reset_password');
            });

            // Statistik
            Route::get('/statistik', [AdminController::class, 'statistik'])->name('admin.statistik');

            // Laporan Periodik
            Route::prefix('laporan_periodik')->group(function () {
                Route::get('/', [AdminController::class, 'laporan_periodik'])->name('admin.laporan_periodik');
                Route::get('/{id}', [AdminController::class, 'show_laporan_periodik'])->name('admin.show_laporan_periodik');
                Route::post('/{id}', [AdminController::class, 'update_laporan_periodik'])->name('admin.update_laporan_periodik');
                Route::get('/export_laporan_periodik', [AdminController::class, 'export_laporan_periodik'])->name('admin.export_laporan_periodik');
            });

            //sistem rekomendasi
            Route::prefix('sistem_rekomendasi')->group(function () {
                Route::get('/', [AdminController::class, 'sistem_rekomendasi'])->name('admin.sistem_rekomendasi');
            });
        });
    });

    // Teknisi Routes
    Route::middleware(['authorize:teknisi'])->group(function () {
        Route::prefix('teknisi')->group(function () {
            Route::prefix('profile')->group(function () {
                Route::get('/', [TeknisiController::class, 'profile'])->name('teknisi.profile');
                Route::put('/', [TeknisiController::class, 'updateProfile'])->name('teknisi.updateProfile');
                Route::put('/change-password', [TeknisiController::class, 'updatePassword'])->name('teknisi.updatePassword');
                Route::post('/change-foto', [TeknisiController::class, 'updateFoto'])->name('teknisi.updateFoto');
            });

            Route::prefix('profile')->group(function () {
                Route::get('/', [TeknisiController::class, 'profile'])->name('teknisi.profile');
                Route::put('/', [TeknisiController::class, 'updateProfile'])->name('teknisi.updateProfile');
                Route::put('/change-password', [TeknisiController::class, 'updatePassword'])->name('teknisi.updatePassword');
            });

            Route::prefix('penugasan')->group(function () {
                Route::get('/', [TeknisiController::class, 'penugasan'])->name('teknisi.penugasan');
                Route::get('/{id}', [TeknisiController::class, 'detail_penugasan'])->name('teknisi.detail_penugasan');
                Route::post('/{id}', [TeknisiController::class, 'ajukanKeSarpras'])->name('teknisi.ajukan');
            });

            Route::prefix('riwayat_penugasan')->group(function () {
                Route::get('/', [TeknisiController::class, 'riwayat_penugasan'])->name('teknisi.riwayat_penugasan');
                Route::get('/{id}', [TeknisiController::class, 'detail_riwayat_penugasan'])->name('teknisi.detail_riwayat_penugasan');
            });
        });
    });

    // Sarpras Routes
    Route::middleware(['authorize:sarpras'])->group(function () {
        Route::prefix('sarpras')->group(function () {
            Route::prefix('profile')->group(function () {
                Route::get('/', [SarprasController::class, 'profile'])->name('sarpras.profile');
                Route::put('/', [SarprasController::class, 'updateProfile'])->name('sarpras.updateProfile');
                Route::put('/change-password', [SarprasController::class, 'updatePassword'])->name('sarpras.updatePassword');
                Route::post('/change-foto', [SarprasController::class, 'updateFoto'])->name('sarpras.updateFoto');
            });

            Route::prefix('laporan_masuk')->group(function () {
                Route::get('/', [SarprasController::class, 'list_laporan'])->name('sarpras.laporan');
                Route::get('/{id}', [SarprasController::class, 'show_laporan'])->name('sarpras.show_laporan');
                Route::post('/terima/{id}', [SarprasController::class, 'terima'])->name('sarpras.update_laporan');
                Route::post('/tolak/{id}', [SarprasController::class, 'tolak'])->name('sarpras.update_laporan');
                Route::post('/pilih_teknisi/{id}', [SarprasController::class, 'pilih_teknisi'])->name('sarpras.update_laporan');
                Route::post('/selesaikan/{id}', [SarprasController::class, 'selesaikan'])->name('sarpras.selesaikan_laporan');
                Route::post('/revisi/{id}', [SarprasController::class, 'revisi'])->name('sarpras.revisi_laporan');
            });

            Route::prefix('sistem_rekomendasi')->group(function () {
                Route::get('/', [SarprasController::class, 'sistem_pendukung_keputusan'])->name('sarpras.sistem_pendukung_keputusan');
                Route::post('/data_kriteria', [SarprasController::class, 'data_kriteria'])->name('sarpras.data_kriteria');
                Route::get('/create_kriteria', [SarprasController::class, 'create_kriteria'])->name('sarpras.create_kriteria');
                Route::post('/store_kriteria', [SarprasController::class, 'store_kriteria'])->name('sarpras.store_kriteria');
                Route::get('/edit_kriteria/{id}', [SarprasController::class, 'edit_kriteria'])->name('sarpras.edit_kriteria');
                Route::post('/update_kriteria', [SarprasController::class, 'update_kriteria'])->name('sarpras.update_kriteria');
                Route::delete('/kriteria/{id}', [SarprasController::class, 'destroy_kriteria'])->name('sarpras.destroy_kriteria');

                Route::post('/data_crisp', [SarprasController::class, 'data_crisp'])->name('sarpras.data_crisp');
                Route::get('/create_crisp', [SarprasController::class, 'create_crisp'])->name('sarpras.create_crisp');
                Route::post('/store_crisp', [SarprasController::class, 'store_crisp'])->name('sarpras.store_crisp');
                Route::get('/edit_crisp/{id}', [SarprasController::class, 'edit_crisp'])->name('sarpras.edit_crisp');
                Route::post('/update_crisp', [SarprasController::class, 'update_crisp'])->name('sarpras.update_crisp');
                Route::delete('/crisp/{id}', [SarprasController::class, 'destroy_crisp'])->name('sarpras.destroy_crisp');
            });

            Route::prefix('ajukan_laporan')->group(function () {
                Route::get('/', [SarprasController::class, 'ajukan_laporan'])->name('sarpras.ajukan_laporan');
                Route::post('/{id}', [SarprasController::class, 'proses_ajukan_laporan'])->name('sarpras.ajukan');
                Route::get('/{id}', [SarprasController::class, 'detail_laporan'])->name('sarpras.detail_laporan');
                Route::post('/data_laporan', [SarprasController::class, 'data_laporan'])->name('sarpras.data_laporan');
                Route::post('/proses_spk', [SarprasController::class, 'proses_spk'])->name('sarpras.proses_spk');
            });

            Route::prefix('statistik')->group(function () {
                Route::get('/', [SarprasController::class, 'statistik'])->name('sarpras.statistik');
                Route::get('/export_laporan_periodik', [SarprasController::class, 'export_laporan_periodik'])->name('sarpras.export_laporan_periodik');
            });
        });
    });

    // Pelapor Routes
    Route::middleware(['authorize:pelapor'])->group(function () {
        Route::prefix('pelapor')->group(function () {
            // Profile
            Route::prefix('profile')->group(function () {
                Route::get('/', [PelaporController::class, 'profile'])->name('pelapor.profile');
                Route::put('/', [PelaporController::class, 'updateProfile'])->name('pelapor.updateProfile');
                Route::put('/change-password', [PelaporController::class, 'updatePassword'])->name('pelapor.updatePassword');
                Route::post('/change-foto', [PelaporController::class, 'updateFoto'])->name('pelapor.updateFoto');
            });

            // Laporan
            Route::prefix('laporan')->group(function () {
                Route::get('/', [PelaporController::class, 'laporkan_kerusakan'])->name('pelapor.laporan');
                Route::get('/get_lantai_by_gedung', [PelaporController::class, 'get_lantai_by_gedung']);
                Route::get('/get_ruangan_by_lantai', [PelaporController::class, 'get_ruangan_by_lantai']);
                Route::get('/get_fasilitas_by_ruangan', [PelaporController::class, 'get_fasilitas_by_ruangan']);
                Route::post('/', [PelaporController::class, 'store_laporan'])->name('pelapor.store_laporan');
                Route::get('/{id}', [PelaporController::class, 'show_ajax_laporan'])->name('pelapor.showLaporan');
            });

            // Laporan Saya
            Route::prefix('laporan_saya')->group(function () {
                Route::get('/', [PelaporController::class, 'laporan_saya'])->name('pelapor.laporan_saya');
                Route::get('/{id}', [PelaporController::class, 'show_laporan_saya'])->name('pelapor.show_laporan_saya');
                Route::post('/{id}', [PelaporController::class, 'store_rating_laporan'])->name('pelapor.store_rating_laporan');
            });
        });
    });
});
// Profile Routes
