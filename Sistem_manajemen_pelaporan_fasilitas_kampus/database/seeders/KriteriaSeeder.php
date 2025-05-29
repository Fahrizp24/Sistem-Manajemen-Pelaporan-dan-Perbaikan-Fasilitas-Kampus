<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('kriteria')->insert([
            ['kode' => 'C1', 'nama' => 'Tingkat Keparahan Kerusakan', 'bobot' => 0.25,'jenis' => 'benefit', 'deskripsi' => 'Mengukur seberapa parah kerusakan yang terjadi pada fasilitas.'],
            ['kode' => 'C2', 'nama' => 'Dampak terhadap Operasional', 'bobot' => 0.20, 'jenis' => 'benefit', 'deskripsi' => 'Menilai dampak kerusakan terhadap operasional fasilitas.'],
            ['kode' => 'C3', 'nama' => 'Frekuensi Penggunaan Fasilitas', 'bobot' => 0.15, 'jenis' => 'benefit', 'deskripsi' => 'Mengukur seberapa sering fasilitas digunakan.'],
            ['kode' => 'C4', 'nama' => 'Tingkat Risiko Keamanan', 'bobot' => 0.20, 'jenis' => 'benefit', 'deskripsi' => 'Menilai risiko keamanan yang ditimbulkan oleh kerusakan fasilitas.'],
            ['kode' => 'C5', 'nama' => 'Biaya Perbaikan', 'bobot' => 0.10, 'jenis' => 'cost', 'deskripsi' => 'Menghitung estimasi biaya yang diperlukan untuk perbaikan fasilitas.'],
            ['kode' => 'C6', 'nama' => 'Waktu yang Dibutuhkan untuk Perbaikan', 'bobot' => 0.10, 'jenis' => 'cost', 'deskripsi' => 'Estimasi waktu yang diperlukan untuk menyelesaikan perbaikan fasilitas.'],
        ]);
       
    }
}

