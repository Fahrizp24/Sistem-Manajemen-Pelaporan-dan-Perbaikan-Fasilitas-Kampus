<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpkKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('spk_kriteria')->insert([
            [
                'spk_id' => 1,
                'kriteria_id' => 1,
                'nilai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 1,
                'kriteria_id' => 2,
                'nilai' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 1,
                'kriteria_id' => 3,
                'nilai' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 1,
                'kriteria_id' => 4,
                'nilai' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 1,
                'kriteria_id' => 5,
                'nilai' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'spk_id' => 1,
                'kriteria_id' => 6,
                'nilai' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 2,
                'kriteria_id' => 1,
                'nilai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 2,
                'kriteria_id' => 2,
                'nilai' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'spk_id' => 2,
                'kriteria_id' => 3,
                'nilai' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'spk_id' => 2,
                'kriteria_id' => 4,
                'nilai' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'spk_id' => 2,
                'kriteria_id' => 5,
                'nilai' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'spk_id' => 2,
                'kriteria_id' => 6,
                'nilai' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
  
        ]);
    }
}
