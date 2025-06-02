<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $defaultRuangan = [
            'Lobby',
            'Lorong Barat',
            'Lorong Timur',
            'Kamar Mandi Laki-laki',
            'Kamar Mandi Perempuan',
        ];

        $totalPerLantai = 21;

        for ($lantaiId = 1; $lantaiId <= 8; $lantaiId++) {
            for ($i = 0; $i < $totalPerLantai; $i++) {
                DB::table('ruangan')->insert([
                    'lantai_id' => $lantaiId,
                    'nama_ruangan' => $i < count($defaultRuangan)
                        ? $defaultRuangan[$i]
                        : 'Ruang ' . ($i - count($defaultRuangan) + 1),
                ]);
            }
        }
    }
}
