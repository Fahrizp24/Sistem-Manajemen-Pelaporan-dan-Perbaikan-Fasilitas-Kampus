<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengguna')->insert([
            [
                'nama' => 'Admin SIPFAK',
                'email' => 'admin@sipfak.test',
                'kata_sandi' => bcrypt('admin123'),
                'peran' => 'admin',
                'foto_profil' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Teknisi 1',
                'email' => 'teknisi1@sipfak.test',
                'kata_sandi' => bcrypt('teknisi123'),
                'peran' => 'teknisi',
                'foto_profil' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mahasiswa Uji',
                'email' => 'mahasiswa@sipfak.test',
                'kata_sandi' => bcrypt('mahasiswa123'),
                'peran' => 'mahasiswa',
                'foto_profil' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
