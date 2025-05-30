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
            ['username' => 'admin', 'nama' => 'Admin', 'program_studi' => '-', 'jurusan' => '-', 'email' => 'admin@example.com', 'password' => bcrypt('admin123'), 'peran' => 'admin'],
            ['username' => 'teknisi', 'nama' => 'Teknisi 1', 'program_studi' => '-', 'jurusan' => '-', 'email' => 'teknisi1@example.com', 'password' => bcrypt('teknisi123'), 'peran' => 'teknisi'],
            ['username' => 'mahasiswa', 'nama' => 'Mahasiswa', 'program_studi' => 'Teknik Informatika', 'jurusan' => 'Teknologi Informasi', 'email' => 'mahasiswa@example.com', 'password' => bcrypt('mahasiswa123'), 'peran' => 'pelapor'],
            ['username' => 'tendik', 'nama' => 'Tendik', 'program_studi' => '-', 'jurusan' => '-', 'email' => 'tendik@example.com', 'password' => bcrypt('tendik123'), 'peran' => 'pelapor'],
            ['username' => 'dosen', 'nama' => 'Dosen', 'program_studi' => '-', 'jurusan' => 'Teknologi Informasi', 'email' => 'dosen@example.com', 'password' => bcrypt('dosen123'), 'peran' => 'pelapor'],
            ['username' => 'sarpras', 'nama' => 'Sarpras', 'program_studi' => '-', 'jurusan' => '-', 'email' => 'sarpras@example.com', 'password' => bcrypt('sarpras123'), 'peran' => 'sarpras']
        ]);
    }
}

