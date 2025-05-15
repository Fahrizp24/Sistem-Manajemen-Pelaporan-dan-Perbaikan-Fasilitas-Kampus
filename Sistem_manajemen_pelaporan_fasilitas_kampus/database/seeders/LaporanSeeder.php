<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('laporan')->insert([
            [
                'pelapor_id' => 3, // Mahasiswa Uji
                'fasilitas_id' => 1,
                'deskripsi' => 'AC mati sejak pagi',
                'foto' => null,
                'status' => 'diajukan',
                'teknisi_id' => 2,
                'ditugaskan_oleh' => 1,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
