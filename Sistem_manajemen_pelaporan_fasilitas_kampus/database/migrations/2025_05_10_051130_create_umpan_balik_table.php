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
        Schema::create('umpan_balik', function (Blueprint $table) {
            $table->id('umpan_balik_id');
            $table->foreignId('laporan_id')->constrained('laporan', 'laporan_id')->onDelete('cascade');
            $table->foreignId('pengguna_id')->constrained('pengguna', 'pengguna_id')->onDelete('cascade');
            $table->tinyInteger('penilaian');
            $table->text('komentar')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umpan_balik');
    }
};
