<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('laporan')->insert([
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 1,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Proyektor tidak menyala',
                'foto' => 'proyektor_rusak.jpg',
                'status' => 'diajukan',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 2,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'AC bocor',
                'foto' => 'ac_bocor.jpg',
                'status' => 'diterima',
                'urgensi' => 'sedang',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 5,
                'fasilitas_id' => 3,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Kursi patah',
                'foto' => 'kursi_patah.jpg',
                'status' => 'diperbaiki',
                'urgensi' => 'rendah',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Lampu mati',
                'foto' => 'lampu_mati.jpg',
                'status' => 'selesai',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 5,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Printer error',
                'foto' => 'printer_error.jpg',
                'status' => 'tidak diterima',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 5,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Printer error',
                'foto' => 'printer_error.jpg',
                'status' => 'diperbaiki',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 1,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Printer error',
                'foto' => 'printer_error.jpg',
                'status' => 'diperbaiki',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 2,
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => 'Printer error',
                'foto' => 'printer_error.jpg',
                'status' => 'selesai',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

