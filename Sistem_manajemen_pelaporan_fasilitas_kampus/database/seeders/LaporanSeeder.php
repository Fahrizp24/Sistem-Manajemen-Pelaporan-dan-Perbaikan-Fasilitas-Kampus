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
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Proyektor tidak menyala',
                'foto' => 'default.jpg',
                'status' => 'diajukan',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 1,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Proyektor tidak Terang',
                'foto' => 'default.jpg',
                'status' => 'diajukan',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 2,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'layar kaca tidak menyala',
                'foto' => 'default.jpg',
                'status' => 'diajukan',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 3,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'AC bocor',
                'foto' => 'default.jpg',
                'status' => 'diterima',
                'urgensi' => 'sedang',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'AC bocor',
                'foto' => 'default.jpg',
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
                'foto' => 'default.jpg',
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
                'foto' => 'default.jpg',
                'status' => 'selesai',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 2,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Lampu mati',
                'foto' => 'default.jpg',
                'status' => 'konfirmasi',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Lampu mati',
                'foto' => 'default.jpg',
                'status' => 'konfirmasi',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Lampu tengah mati',
                'foto' => 'default.jpg',
                'status' => 'memilih teknisi',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Lampu mati',
                'foto' => 'default.jpg',
                'status' => 'memilih teknisi',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 3,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'AC mati',
                'foto' => 'default.jpg',
                'status' => 'memilih teknisi',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'pelapor_id' => 4,
                'fasilitas_id' => 4,
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => 'Printer error',
                'foto' => 'default.jpg',
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
                'foto' => 'default.jpg',
                'status' => 'diperbaiki',
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
                'foto' => 'default.jpg',
                'status' => 'diperbaiki',
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
                'foto' => 'default.jpg',
                'status' => 'telah diperbaiki',
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
                'foto' => 'default.jpg',
                'status' => 'telah diperbaiki',
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
                'foto' => 'default.jpg',
                'status' => 'selesai',
                'urgensi' => 'tinggi',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
