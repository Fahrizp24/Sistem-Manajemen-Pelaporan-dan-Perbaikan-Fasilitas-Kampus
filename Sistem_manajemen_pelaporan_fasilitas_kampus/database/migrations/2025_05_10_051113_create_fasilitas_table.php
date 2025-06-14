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
            $table->string('fasilitas_nama');
            $table->text('fasilitas_deskripsi')->nullable();
            $table->foreignId('ruangan_id')->constrained('ruangan', 'ruangan_id')->onDelete('cascade');
            $table->enum('status', ['normal', 'rusak'])->default('normal');
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
