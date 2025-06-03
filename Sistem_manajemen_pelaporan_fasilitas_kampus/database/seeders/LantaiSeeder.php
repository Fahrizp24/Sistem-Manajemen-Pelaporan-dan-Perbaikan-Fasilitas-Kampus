<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LantaiSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 8; $i++) {
            DB::table('lantai')->insert([
                'gedung_id' => 1, // Asumsikan Gedung A ID-nya 1
                'lantai_nama' => 'Lantai ' . $i,
            ]);
        }
    }
}