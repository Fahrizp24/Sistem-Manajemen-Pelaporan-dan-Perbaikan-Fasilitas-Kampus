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
            $table->id();
            $table->foreignId('pelapor_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');
            $table->foreignId('teknisi_id')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->foreignId('ditugaskan_oleh')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('status'); 
            $table->text('catatan')->nullable();

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
