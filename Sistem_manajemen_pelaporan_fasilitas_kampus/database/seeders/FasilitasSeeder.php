<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $totalRuangan = 8 * 21; // 8 lantai × 21 ruangan_id
        $fasilitasList = [
            ['fasilitas_nama' => 'AC', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Lampu', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Proyektor', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Access Point', 'kategori' => 'Jaringan'],
            ['fasilitas_nama' => 'Papan Tulis', 'kategori' => 'Perlengkapan Kelas'],
            ['fasilitas_nama' => 'Lemari', 'kategori' => 'Furniture'],
            ['fasilitas_nama' => 'Meja', 'kategori' => 'Furniture'],
            ['fasilitas_nama' => 'Komputer', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Stop Kontak', 'kategori' => 'Listrik']
        ];

        for ($ruanganId = 1; $ruanganId <= $totalRuangan; $ruanganId++) {
            foreach ($fasilitasList as $fasilitas) {
                DB::table('fasilitas')->insert([
                    'ruangan_id' => $ruanganId,
                    'fasilitas_nama' => $fasilitas['fasilitas_nama'],
                    'kategori' => $fasilitas['kategori'],
                    'fasilitas_deskripsi' => $fasilitas['fasilitas_nama'] . " di ruangan_id $ruanganId",
                    'status' => 'normal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}


