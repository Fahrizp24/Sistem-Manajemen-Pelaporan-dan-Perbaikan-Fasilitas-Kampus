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

        $fasilitas = DB::table('fasilitas')
            ->where('status', 'rusak')
            ->get();

        foreach ($fasilitas as $fasilitasItem) {
            DB::table('spk')->insert([
                'fasilitas_id' => $fasilitasItem->fasilitas_id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
