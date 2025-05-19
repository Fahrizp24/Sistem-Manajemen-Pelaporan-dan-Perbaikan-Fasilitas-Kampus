<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('laporan')->insert([
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 1,
                'ditugaskan_oleh' => 1,
                'teknisi_id' => 2,
                'deskripsi' => 'Proyektor tidak menyala.',
                'foto' => 'proyektor_rusak.jpg',
                'status' => 'diajukan',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

