<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $totalRuangan = 8 * 21; // 8 lantai × 21 ruangan
        $fasilitasList = [
            ['nama' => 'AC', 'kategori' => 'Elektronik'],
            ['nama' => 'Lampu', 'kategori' => 'Elektronik'],
            ['nama' => 'Proyektor', 'kategori' => 'Elektronik'],
            ['nama' => 'Access Point', 'kategori' => 'Jaringan'],
            ['nama' => 'Papan Tulis', 'kategori' => 'Perlengkapan Kelas'],
            ['nama' => 'Lemari', 'kategori' => 'Furniture'],
            ['nama' => 'Meja', 'kategori' => 'Furniture'],
            ['nama' => 'Komputer', 'kategori' => 'Elektronik'],
            ['nama' => 'Stop Kontak', 'kategori' => 'Listrik']
        ];

        for ($ruanganId = 1; $ruanganId <= $totalRuangan; $ruanganId++) {
            foreach ($fasilitasList as $fasilitas) {
                DB::table('fasilitas')->insert([
                    'ruangan' => $ruanganId,
                    'nama' => $fasilitas['nama'],
                    'kategori' => $fasilitas['kategori'],
                    'deskripsi' => $fasilitas['nama'] . " di ruangan $ruanganId",
                    'status' => 'normal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}


