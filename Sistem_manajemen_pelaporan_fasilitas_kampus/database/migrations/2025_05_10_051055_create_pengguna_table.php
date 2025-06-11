<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('pengguna_id');
            $table->string('username')->unique();
            $table->string('nama');
            $table->string('prodi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('email')->unique();
            $table->string('no_telp')->nullable();
            $table->string('password');
            $table->enum('peran', ['admin', 'pelapor', 'sarpras', 'teknisi']);
            $table->string('foto_profil')->nullable()->default('default.jpg');
            $table->string('pertanyaan_masa_kecil')->nullable();
            $table->string('jawaban_masa_kecil')->nullable();
            $table->string('pertanyaan_keluarga')->nullable();
            $table->string('jawaban_keluarga')->nullable();
            $table->string('pertanyaan_tempat')->nullable();
            $table->string('jawaban_tempat')->nullable();
            $table->string('pertanyaan_pengalaman')->nullable();
            $table->string('jawaban_pengalaman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
