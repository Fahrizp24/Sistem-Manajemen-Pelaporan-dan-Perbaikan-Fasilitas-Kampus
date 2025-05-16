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
                'pelapor_id' => 1,
                'fasilitas_id' => 1,
                'ditugaskan_oleh' => 1,
                'teknisi_id' => 2,
                'deskripsi' => 'Proyektor tidak bisa dinyalakan',
                'foto' => null,
                'status' => 'diajukan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

