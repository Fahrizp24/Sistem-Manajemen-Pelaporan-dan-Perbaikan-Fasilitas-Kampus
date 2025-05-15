<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fasilitas')->insert([
        [
            'nama' => 'AC Laboratorium 1',
            'deskripsi' => 'AC rusak tidak menyala',
            'lokasi' => 'Lantai 2',
            'kategori' => 'AC',
            'gedung_id' => 1,
            'status' => 'rusak',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        ]);

    }
}
