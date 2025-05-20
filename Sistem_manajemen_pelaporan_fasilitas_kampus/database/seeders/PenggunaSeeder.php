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
            ['username' => 'admin', 'nama' => 'Admin', 'email' => 'admin@example.com', 'kata_sandi' => bcrypt('admin123'), 'peran' => 'admin', 'foto_profil' => null],
            ['username' => 'teknisi', 'nama' => 'Teknisi 1', 'email' => 'teknisi1@example.com', 'kata_sandi' => bcrypt('teknisi123'), 'peran' => 'teknisi', 'foto_profil' => null],
            ['username' => 'mahasiswa', 'nama' => 'Mahasiswa', 'email' => 'mahasiswa@example.com', 'kata_sandi' => bcrypt('mahasiswa123'), 'peran' => 'mahasiswa', 'foto_profil' => null],
            ['username' => 'tendik', 'nama' => 'Tendik', 'email' => 'tendik@example.com', 'kata_sandi' => bcrypt('tendik123'), 'peran' => 'tendik', 'foto_profil' => null],
            ['username' => 'dosen', 'nama' => 'Dosen', 'email' => 'dosen@example.com', 'kata_sandi' => bcrypt('dosen123'), 'peran' => 'dosen', 'foto_profil' => null],
            ['username' => 'sarpras', 'nama' => 'Sarpras', 'email' => 'sarpras@example.com', 'kata_sandi' => bcrypt('sarpras123'), 'peran' => 'sarana', 'foto_profil' => null]
        ]);
    }
}

