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
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi');
            $table->string('kategori'); // AC, Proyektor, Jaringan, dll
            $table->foreignId('gedung_id')->constrained('gedung')->onDelete('cascade');
            $table->string('status'); // baik, rusak, perlu perhatian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
