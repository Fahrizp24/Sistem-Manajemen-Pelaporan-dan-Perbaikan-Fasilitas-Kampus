<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UmpanBalikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('umpan_balik')->insert([
            [
                'laporan_id' => 1,
                'pengguna_id' => 1,
                'penilaian' => 4,
                'komentar' => 'Respon cepat dan tanggap.',
                'created_at' => now(),
            ],
        ]);
    }
}

