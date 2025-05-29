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
        Schema::create('crisp', function (Blueprint $table) {
            $table->id('crisp_id');
            $table->unsignedBigInteger('kriteria_id');
            $table->foreign('kriteria_id')->references('kriteria_id')->on('kriteria')->onDelete('cascade');
            $table->string('judul');
            $table->string('deskripsi');
            $table->string('poin');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crisp');
    }
};
