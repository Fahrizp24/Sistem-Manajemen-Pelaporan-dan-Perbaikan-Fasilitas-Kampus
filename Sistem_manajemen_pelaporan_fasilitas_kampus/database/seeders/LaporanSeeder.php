<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $statusOptions = ['diajukan', 'konfirmasi', 'memilih teknisi', 'diperbaiki', 'telah diperbaiki', 'revisi', 'tidak diterima'];
        $urgensiOptions = ['rendah', 'sedang', 'tinggi'];

        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $pelaporIds = DB::table('pengguna')->where('peran', 'pelapor')->pluck('pengguna_id')->toArray();
        $sarprasIds = DB::table('pengguna')->where('peran', 'sarpras')->pluck('pengguna_id')->toArray();
        $teknisiIds = DB::table('pengguna')->where('peran', 'teknisi')->pluck('pengguna_id')->toArray();
        $fasilitasIds = DB::table('fasilitas')->pluck('fasilitas_id')->toArray();

        // 1. 10 laporan 'selesai' bulan lalu
        for ($i = 1; $i <= 10; $i++) {
            DB::table('laporan')->insert([
                'pelapor_id' => $pelaporIds[array_rand($pelaporIds)],
                'fasilitas_id' => $fasilitasIds[array_rand($fasilitasIds)],
                'ditugaskan_oleh' => $sarprasIds[array_rand($sarprasIds)],
                'teknisi_id' => rand(0, 1) ? $teknisiIds[array_rand($teknisiIds)] : null,
                'deskripsi' => 'Laporan selesai bulan lalu ke-' . $i,
                'foto' => 'default.jpg',
                'status' => 'selesai',
                'urgensi' => $urgensiOptions[array_rand($urgensiOptions)],
                'alasan penolakan' => '-',
                'created_at' => $lastMonth,
                'updated_at' => $lastMonth,
            ]);
        }

        // 2. 20 laporan 'diterima' (bulan ini)
        for ($i = 11; $i <= 30; $i++) {
            DB::table('laporan')->insert([
                'pelapor_id' => $pelaporIds[array_rand($pelaporIds)],
                'fasilitas_id' => $fasilitasIds[array_rand($fasilitasIds)],
                'ditugaskan_oleh' => $sarprasIds[array_rand($sarprasIds)],
                'teknisi_id' => rand(0, 1) ? $teknisiIds[array_rand($teknisiIds)] : null,
                'deskripsi' => 'Laporan diterima ke-' . $i,
                'foto' => 'default.jpg',
                'status' => 'diterima',
                'urgensi' => $urgensiOptions[array_rand($urgensiOptions)],
                'alasan penolakan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. Sisa 20 laporan dengan status acak
        for ($i = 31; $i <= 50; $i++) {
            $status = $statusOptions[array_rand($statusOptions)];
            $alasan = ($status === 'tidak diterima') ? 'tidak terjadi kerusakan' : '-';

            DB::table('laporan')->insert([
                'pelapor_id' => $pelaporIds[array_rand($pelaporIds)],
                'fasilitas_id' => $fasilitasIds[array_rand($fasilitasIds)],
                'ditugaskan_oleh' => $sarprasIds[array_rand($sarprasIds)],
                'teknisi_id' => rand(0, 1) ? $teknisiIds[array_rand($teknisiIds)] : null,
                'deskripsi' => 'Laporan status acak ke-' . $i,
                'foto' => 'default.jpg',
                'status' => $status,
                'urgensi' => $urgensiOptions[array_rand($urgensiOptions)],
                'alasan penolakan' => $alasan,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}