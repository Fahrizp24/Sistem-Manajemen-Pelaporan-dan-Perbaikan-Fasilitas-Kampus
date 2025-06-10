<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $statusOptions = ['diajukan', 'diterima', 'konfirmasi', 'memilih teknisi', 'diperbaiki', 
        'telah diperbaiki', 'revisi', 'selesai', 'tidak diterima'];
        $urgensiOptions = ['rendah', 'sedang', 'tinggi'];
        $statusesTanpaTeknisi = ['diajukan', 'diterima', 'konfirmasi', 'memilih teknisi'];

        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $pelaporIds = DB::table('pengguna')->where('peran', 'pelapor')->pluck('pengguna_id')->toArray();
        $fasilitasIds = DB::table('fasilitas')->pluck('fasilitas_id')->toArray();

        // 1. 10 laporan 'selesai' bulan lalu
        for ($i = 1; $i <= 10; $i++) {
            DB::table('laporan')->insert([
                'pelapor_id' => $pelaporIds[array_rand($pelaporIds)],
                'fasilitas_id' => $fasilitasIds[array_rand($fasilitasIds)],
                'ditugaskan_oleh' => 6,
                'teknisi_id' => 2,
                'deskripsi' => "Laporan selesai bulan lalu ke-$i",
                'foto' => 'default.jpg',
                'status' => 'selesai',
                'urgensi' => $urgensiOptions[array_rand($urgensiOptions)],
                'foto_pengerjaan' => 'bukti_default.jpg',
                'alasan_penolakan' => '-',
                'created_at' => $lastMonth,
                'updated_at' => $lastMonth,
            ]);
        }

        // 2. 20 laporan 'diterima' (bulan ini, tanpa teknisi/penugasan)
        for ($i = 11; $i <= 30; $i++) {
            DB::table('laporan')->insert([
                'pelapor_id' => $pelaporIds[array_rand($pelaporIds)],
                'fasilitas_id' => $fasilitasIds[array_rand($fasilitasIds)],
                'ditugaskan_oleh' => null,
                'teknisi_id' => null,
                'deskripsi' => "Laporan diterima ke-$i",
                'foto' => 'default.jpg',
                'status' => 'diterima',
                'urgensi' => $urgensiOptions[array_rand($urgensiOptions)],
                'foto_pengerjaan' => null,
                'alasan_penolakan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. 20 laporan acak (bisa teknisi/null tergantung status)
        for ($i = 31; $i <= 50; $i++) {
            $status = $statusOptions[array_rand($statusOptions)];
            $isTanpaTeknisi = in_array($status, $statusesTanpaTeknisi);
            $alasan = $status === 'tidak diterima' ? 'Tidak terjadi kerusakan' : '-';

            DB::table('laporan')->insert([
                'pelapor_id' => $pelaporIds[array_rand($pelaporIds)],
                'fasilitas_id' => $fasilitasIds[array_rand($fasilitasIds)],
                'ditugaskan_oleh' => $isTanpaTeknisi ? null : 6,
                'teknisi_id' => $isTanpaTeknisi ? null : 2,
                'deskripsi' => "Laporan acak ke-$i",
                'foto' => 'default.jpg',
                'status' => $status,
                'urgensi' => $urgensiOptions[array_rand($urgensiOptions)],
                'foto_pengerjaan' => $isTanpaTeknisi ? null : 'foto_pengerjaan.jpg',
                'alasan_penolakan' => $alasan,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
