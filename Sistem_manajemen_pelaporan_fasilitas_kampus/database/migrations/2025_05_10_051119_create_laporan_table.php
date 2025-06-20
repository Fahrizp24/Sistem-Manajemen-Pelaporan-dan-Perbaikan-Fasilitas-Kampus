<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('pelapor_id')->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas', 'fasilitas_id')->onDelete('cascade');
            $table->foreignId('ditugaskan_oleh')->nullable()->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->foreignId('teknisi_id')->nullable()->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->text('deskripsi');
            $table->string('foto')->nullable()->default('default.jpg');
            $table->enum('status', ['diajukan', 'diterima', 'konfirmasi', 'memilih teknisi', 'diperbaiki','telah diperbaiki','revisi','selesai', 'tidak diterima'])->default('diajukan');
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi'])->nullable();
            $table->string('foto_pengerjaan')->nullable();
            $table->foreignId('ditolak_oleh')->nullable()->constrained('pengguna', 'pengguna_id')->onDelete('cascade')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->text('alasan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
