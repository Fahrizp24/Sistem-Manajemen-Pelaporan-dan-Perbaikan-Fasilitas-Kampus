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
            ['username' => 'admin', 'nama' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('admin123'), 'peran' => 'admin', 'foto_profil' => null],
            ['username' => 'teknisi', 'nama' => 'Teknisi 1', 'email' => 'teknisi1@example.com', 'password' => bcrypt('teknisi123'), 'peran' => 'teknisi', 'foto_profil' => null],
            ['username' => 'mahasiswa', 'nama' => 'Mahasiswa', 'email' => 'mahasiswa@example.com', 'password' => bcrypt('mahasiswa123'), 'peran' => 'pelapor', 'foto_profil' => null],
            ['username' => 'tendik', 'nama' => 'Tendik', 'email' => 'tendik@example.com', 'password' => bcrypt('tendik123'), 'peran' => 'pelapor', 'foto_profil' => null],
            ['username' => 'dosen', 'nama' => 'Dosen', 'email' => 'dosen@example.com', 'password' => bcrypt('dosen123'), 'peran' => 'pelapor', 'foto_profil' => null],
            ['username' => 'sarpras', 'nama' => 'Sarpras', 'email' => 'sarpras@example.com', 'password' => bcrypt('sarpras123'), 'peran' => 'sarpras', 'foto_profil' => null]
        ]);
    }
}

