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
                'deskripsi' => 'Proyektor ruang kelas E401 merk Epson',
                'kategori' => 'Elektronik',
                'gedung_id' => 1,
                'status' => 'rusak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'AC Split',
                'deskripsi' => 'Air conditioner ruang dosen lantai 2',
                'kategori' => 'Pendingin',
                'gedung_id' => 1,
                'status' => 'rusak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Kursi Kuliah',
                'deskripsi' => 'Kursi lipat ruang aula besar',
                'kategori' => 'Furniture',
                'gedung_id' => 1,
                'status' => 'rusak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Papan Tulis Whiteboard',
                'deskripsi' => 'Papan tulis magnetik ukuran 2x1.5m',
                'kategori' => 'Alat Tulis',
                'gedung_id' => 1,
                'status' => 'normal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Sound System',
                'deskripsi' => 'Sistem audio untuk ruang seminar',
                'kategori' => 'Elektronik',
                'gedung_id' => 1,
                'status' => 'rusak',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

