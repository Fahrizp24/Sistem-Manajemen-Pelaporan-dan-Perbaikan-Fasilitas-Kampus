<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('spk', function (Blueprint $table) {
            $table->id('spk_id');
            $table->unsignedBigInteger('laporan_id');
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan')->onDelete('cascade');
            $table->decimal('tingkat_keparahan', 5, 2);
            $table->decimal('dampak_operasional', 5, 2);
            $table->decimal('frekuensi_penggunaan', 5, 2);
            $table->decimal('risiko_keamanan', 5, 2);
            $table->decimal('biaya_perbaikan', 10, 2);
            $table->decimal('waktu_perbaikan', 5, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk');
    }
};
