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
                DB::table('spk_kriteria')->insert([
                    'spk_id' => $spk->spk_id,
                    'kriteria_id' => $kriteria_id,
                    'nilai' => rand(1, 10),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
