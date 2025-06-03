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
        Schema::create('lantai', function (Blueprint $table) {
            $table->id('lantai_id');
            $table->string('lantai_nama');
            $table->string('lantai_deskripsi')->nullable();
            $table->foreignId('gedung_id')->constrained('gedung', 'gedung_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lantai');
    }
};
