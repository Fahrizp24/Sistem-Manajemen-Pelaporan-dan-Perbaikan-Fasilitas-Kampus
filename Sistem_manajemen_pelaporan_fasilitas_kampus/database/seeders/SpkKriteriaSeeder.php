<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpkKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $kriteriaIds = DB::table('kriteria')->pluck('kriteria_id')->toArray();
        $spks = DB::table('spk')->get();

        foreach ($spks as $spk) {
            foreach ($kriteriaIds as $kriteria_id) {
                $nilai = 0;
                
                // Jika kriteria_id == 7, hitung jumlah laporan diterima berdasarkan fasilitas_id
                if ($kriteria_id == 7) {
                    $nilai = DB::table('laporan')
                        ->where('fasilitas_id', $spk->fasilitas_id)
                        ->where('status', 'diterima')
                        ->count();
                } else {
                    $nilai = rand(1, 5); // default random nilai
                }
        
                DB::table('spk_kriteria')->insert([
                    'spk_id' => $spk->spk_id,
                    'kriteria_id' => $kriteria_id,
                    'nilai' => $nilai,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
        
    }
}
