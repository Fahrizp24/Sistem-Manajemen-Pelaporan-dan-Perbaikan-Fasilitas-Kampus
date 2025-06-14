<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LantaiSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua gedung dari tabel 'gedung'
        $gedungList = DB::table('gedung')->get();

        foreach ($gedungList as $gedung) {
            for ($i = 1; $i <= 6; $i++) {
                DB::table('lantai')->insert([
                    'gedung_id' => $gedung->gedung_id,
                    'lantai_nama' => "Lantai $i",
                    'lantai_deskripsi' => "Lantai $i Gedung " . $gedung->gedung_nama,
                ]);
            }
        }
    }
}
