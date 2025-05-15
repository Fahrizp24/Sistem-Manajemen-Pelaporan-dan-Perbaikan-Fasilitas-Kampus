<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gedung')->insert([
            ['nama' => 'Gedung Teknik Informatika', 'deskripsi' => 'Gedung utama TI', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Gedung Sipil', 'deskripsi' => 'Gedung Teknik Sipil', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
