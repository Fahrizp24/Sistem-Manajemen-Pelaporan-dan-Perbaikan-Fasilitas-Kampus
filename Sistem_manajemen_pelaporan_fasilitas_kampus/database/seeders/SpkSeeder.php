<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpkSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $laporanDiterima = DB::table('laporan')
            ->where('status', 'diterima')
            ->get();

        foreach ($laporanDiterima as $laporan) {
            DB::table('spk')->insert([
                'laporan_id' => $laporan->laporan_id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
