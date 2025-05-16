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
            [
                'nama' => 'Admin Sistem',
                'email' => 'admin@example.com',
                'kata_sandi' => Hash::make('password'),
                'peran' => 'admin',
                'foto_profil' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Teknisi 1',
                'email' => 'teknisi1@example.com',
                'kata_sandi' => Hash::make('password'),
                'peran' => 'teknisi',
                'foto_profil' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

