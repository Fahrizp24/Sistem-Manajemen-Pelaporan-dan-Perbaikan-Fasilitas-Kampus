<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $ruanganList = [
            'Lobby',
            'Lorong Barat',
            'Lorong Timur',
            'Kamar Mandi Laki-laki',
            'Kamar Mandi Perempuan',
            'Laboratorium Arsitektur Komputer',
            'Laboratorium Basisdata',
            'Laboratorium Internet dan Web',
            'Laboratorium Jaringan Komputer',
            'Laboratorium Multimedia',
            'Laboratorium Pemrograman Komputer',
            'Laboratorium Project',
            'Laboratorium Sistem Informasi',
        ];

        for ($lantaiId = 1; $lantaiId <= 8; $lantaiId++) {
            foreach ($ruanganList as $nama) {
                DB::table('ruangan')->insert([
                    'lantai_id' => $lantaiId,
                    'ruangan_nama' => $nama,
                    'ruangan_deskripsi' => $nama . ' Lantai ' . $lantaiId,
                ]);
            }
        }
    }
}
