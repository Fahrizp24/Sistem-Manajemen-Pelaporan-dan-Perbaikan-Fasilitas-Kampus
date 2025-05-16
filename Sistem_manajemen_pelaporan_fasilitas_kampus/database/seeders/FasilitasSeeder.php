<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fasilitas')->insert([
            [
                'nama' => 'Proyektor',
                'deskripsi' => 'Proyektor kelas rusak',
                'lokasi' => 'Ruang A101',
                'kategori' => 'Elektronik',
                'gedung_id' => 1,
                'status' => 'rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

