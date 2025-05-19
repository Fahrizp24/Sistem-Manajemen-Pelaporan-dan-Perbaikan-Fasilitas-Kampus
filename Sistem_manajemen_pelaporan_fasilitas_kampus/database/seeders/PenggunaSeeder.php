<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengguna')->insert([
            ['nama' => 'Admin', 'email' => 'admin@example.com', 'kata_sandi' => bcrypt('admin123'), 'peran' => 'admin', 'foto_profil' => null],
            ['nama' => 'Teknisi 1', 'email' => 'teknisi1@example.com', 'kata_sandi' => bcrypt('teknisi123'), 'peran' => 'teknisi', 'foto_profil' => null],
            ['nama' => 'Mahasiswa', 'email' => 'mahasiswa@example.com', 'kata_sandi' => bcrypt('mahasiswa123'), 'peran' => 'mahasiswa', 'foto_profil' => null],
        ]);
    }
}

