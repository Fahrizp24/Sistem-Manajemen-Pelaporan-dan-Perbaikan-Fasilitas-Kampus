<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $semuaFasilitas = [
            ['fasilitas_nama' => 'AC', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Lampu', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Proyektor', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Access Point', 'kategori' => 'Jaringan'],
            ['fasilitas_nama' => 'Papan Tulis', 'kategori' => 'Perlengkapan Kelas'],
            ['fasilitas_nama' => 'Lemari', 'kategori' => 'Furniture'],
            ['fasilitas_nama' => 'Meja', 'kategori' => 'Furniture'],
            ['fasilitas_nama' => 'Komputer', 'kategori' => 'Elektronik'],
            ['fasilitas_nama' => 'Stop Kontak', 'kategori' => 'Listrik'],
            ['fasilitas_nama' => 'Kursi', 'kategori' => 'Furniture'],
            ['fasilitas_nama' => 'Wastafel', 'kategori' => 'Sanitasi'],
            ['fasilitas_nama' => 'Closet', 'kategori' => 'Sanitasi'],
            ['fasilitas_nama' => 'Pintu', 'kategori' => 'Bangunan'],
        ];

        // Ambil fasilitas_id dari laporan yang statusnya tertentu
        $fasilitasRusakIds = DB::table('laporan')
            ->whereIn('status', ['diperbaiki', 'telah diperbaiki', 'revisi', 'selesai'])
            ->pluck('fasilitas_id')
            ->toArray();

        $ruangans = DB::table('ruangan')->get();

        foreach ($ruangans as $ruangan) {
            $nama = strtolower($ruangan->ruangan_nama);
            $fasilitasUntukRuangan = [];

            if (str_contains($nama, 'lobby')) {
                $fasilitasUntukRuangan = ['Kursi', 'Meja', 'Lampu'];
            } elseif (str_contains($nama, 'lorong')) {
                $fasilitasUntukRuangan = ['Kursi', 'Lampu'];
            } elseif (str_contains($nama, 'kamar mandi')) {
                $fasilitasUntukRuangan = ['Wastafel', 'Closet', 'Pintu', 'Lampu'];
            } else {
                $fasilitasUntukRuangan = collect($semuaFasilitas)
                    ->pluck('fasilitas_nama')
                    ->diff(['Wastafel', 'Closet'])
                    ->toArray();
            }

            foreach ($semuaFasilitas as $fasilitas) {
                if (in_array($fasilitas['fasilitas_nama'], $fasilitasUntukRuangan)) {
                    // Simpan data fasilitas lalu ambil ID-nya
                    $id = DB::table('fasilitas')->insertGetId([
                        'ruangan_id' => $ruangan->ruangan_id,
                        'fasilitas_nama' => $fasilitas['fasilitas_nama'],
                        'kategori' => $fasilitas['kategori'],
                        'fasilitas_deskripsi' => "{$fasilitas['fasilitas_nama']} di {$ruangan->ruangan_nama}",
                        'status' => 'normal',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Update status jika masuk ke daftar rusak
                    if (in_array($id, $fasilitasRusakIds)) {
                        DB::table('fasilitas')
                            ->where('fasilitas_id', $id)
                            ->update(['status' => 'rusak']);
                    }
                }
            }
        }
    }
}
