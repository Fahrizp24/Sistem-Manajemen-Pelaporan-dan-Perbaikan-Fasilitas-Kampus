<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuanganSeeder::class,
            FasilitasSeeder::class,
            LaporanSeeder::class,
            UmpanBalikSeeder::class,
            KriteriaSeeder::class,
            CrispSeeder::class,
            SpkSeeder::class,
            SpkKriteriaSeeder::class,
        ]);
    }
}
