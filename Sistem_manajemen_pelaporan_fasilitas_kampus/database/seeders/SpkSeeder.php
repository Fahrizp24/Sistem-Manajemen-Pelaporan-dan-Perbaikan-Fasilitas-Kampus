<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('spk')->insert([
            [
                'laporan_id' => 4,
                'spk_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laporan_id' => 5,
                'spk_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
  
        ]);
    }
}
