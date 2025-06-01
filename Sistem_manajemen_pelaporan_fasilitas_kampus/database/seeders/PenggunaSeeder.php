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
            ['username' => 'admin', 'nama' => 'Admin', 'prodi' => '-', 'jurusan' => '-', 'email' => 'admin@example.com', 'password' => bcrypt('admin123'), 'peran' => 'admin'],
            ['username' => 'teknisi', 'nama' => 'Teknisi 1', 'prodi' => '-', 'jurusan' => '-', 'email' => 'teknisi1@example.com', 'password' => bcrypt('teknisi123'), 'peran' => 'teknisi'],
            ['username' => 'mahasiswa', 'nama' => 'Mahasiswa', 'prodi' => 'Teknik Informatika', 'jurusan' => 'Teknologi Informasi', 'email' => 'mahasiswa@example.com', 'password' => bcrypt('mahasiswa123'), 'peran' => 'pelapor'],
            ['username' => 'tendik', 'nama' => 'Tendik', 'prodi' => '-', 'jurusan' => '-', 'email' => 'tendik@example.com', 'password' => bcrypt('tendik123'), 'peran' => 'pelapor'],
            ['username' => 'dosen', 'nama' => 'Dosen', 'prodi' => '-', 'jurusan' => 'Teknologi Informasi', 'email' => 'dosen@example.com', 'password' => bcrypt('dosen123'), 'peran' => 'pelapor'],
            ['username' => 'sarpras', 'nama' => 'Sarpras', 'prodi' => '-', 'jurusan' => '-', 'email' => 'sarpras@example.com', 'password' => bcrypt('sarpras123'), 'peran' => 'sarpras']
        ]);
    }
}

