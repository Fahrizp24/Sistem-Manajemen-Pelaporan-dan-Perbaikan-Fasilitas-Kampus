<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class UmpanBalikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('umpan_balik')->insert([
            [
                'laporan_id' => 1,
                'pengguna_id' => 3,
                'penilaian' => 5,
                'komentar' => 'Perbaikan cepat dan memuaskan!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
