<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('kriteria')->insert([
            ['kode' => 'C1', 'nama' => 'Tingkat Keparahan Kerusakan', 'bobot' => 0.25],
            ['kode' => 'C2', 'nama' => 'Dampak terhadap Operasional', 'bobot' => 0.20],
            ['kode' => 'C3', 'nama' => 'Frekuensi Penggunaan Fasilitas', 'bobot' => 0.15],
            ['kode' => 'C4', 'nama' => 'Tingkat Risiko Keamanan', 'bobot' => 0.20],
            ['kode' => 'C5', 'nama' => 'Biaya Perbaikan', 'bobot' => 0.10],
            ['kode' => 'C6', 'nama' => 'Waktu yang Dibutuhkan untuk Perbaikan', 'bobot' => 0.10],
        ]);
    }
}

