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
            $table->foreignId('ditugaskan_oleh')->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->foreignId('teknisi_id')->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->enum('status', ['diajukan', 'diproses', 'selesai', 'ditolak'])->default('diajukan');
            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi'])->default('sedang');
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
