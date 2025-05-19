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
            $table->id('fasilitas_id');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('kategori');
            $table->foreignId('gedung_id')->constrained('gedung', 'gedung_id')->onDelete('cascade');
            $table->enum('status', ['baik', 'rusak', 'perlu perhatian'])->default('baik');
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
