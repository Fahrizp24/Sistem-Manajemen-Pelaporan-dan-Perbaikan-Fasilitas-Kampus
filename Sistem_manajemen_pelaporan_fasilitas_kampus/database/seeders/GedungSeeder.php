<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gedung')->insert([
            ['gedung_nama' => 'Gedung JTI'],
        ]);
    }
}

