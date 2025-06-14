<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gedung')->insert([
            ['gedung_nama' => 'Sipil - TI'],
            ['gedung_nama' => 'AA'],
            ['gedung_nama' => 'Grapol'],
            ['gedung_nama' => 'AS'],
            ['gedung_nama' => 'Mesin'],
            ['gedung_nama' => 'AN'],
            ['gedung_nama' => 'AK'],
            ['gedung_nama' => 'Auper'],
            ['gedung_nama' => 'Masjid Raya'],
        ]);
    }
}

