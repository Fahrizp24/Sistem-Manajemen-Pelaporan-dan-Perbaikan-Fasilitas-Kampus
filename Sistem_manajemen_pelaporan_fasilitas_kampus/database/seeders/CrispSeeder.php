<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrispSeeder extends Seeder
{
    public function run()
    {
        DB::table('crisp')->insert([
            // C1: Tingkat Keparahan Kerusakan
            ['kriteria_id' => 1, 'judul' => 'Minor', 'deskripsi' => 'Hanya mengganggu estetika atau minor, tidak menghambat fungsi utama.', 'poin' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 1, 'judul' => 'Sebagian terganggu', 'deskripsi' => 'Mengurangi fungsi sebagian (misal: pintu macet, lampu padam beberapa titik).', 'poin' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 1, 'judul' => 'Fungsi utama rusak', 'deskripsi' => 'Fungsi utama tidak bisa digunakan sama sekali (misal: lantai penyok, dinding runtuh parsial).', 'poin' => '5', 'created_at' => now(), 'updated_at' => now()],

            // C2: Dampak terhadap Operasional
            ['kriteria_id' => 2, 'judul' => 'Tidak terganggu', 'deskripsi' => 'Tidak mengganggu kegiatan sama sekali atau hanya sesaat.', 'poin' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 2, 'judul' => 'Sebagian terganggu', 'deskripsi' => 'Mengganggu sebagian proses (misal: perkuliahan dipindah).', 'poin' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 2, 'judul' => 'Total berhenti', 'deskripsi' => 'Menghentikan seluruh kegiatan utama.', 'poin' => '5', 'created_at' => now(), 'updated_at' => now()],

            // C3: Frekuensi Penggunaan Fasilitas
            ['kriteria_id' => 3, 'judul' => '<5 kali/minggu', 'deskripsi' => 'Kurang dari 5 kali penggunaan per minggu.', 'poin' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 3, 'judul' => '5–15 kali/minggu', 'deskripsi' => '5–15 kali penggunaan per minggu.', 'poin' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 3, 'judul' => '>15 kali/minggu', 'deskripsi' => '>15 kali penggunaan per minggu.', 'poin' => '5', 'created_at' => now(), 'updated_at' => now()],

            // C4: Tingkat Risiko Keamanan
            ['kriteria_id' => 4, 'judul' => 'Tidak berbahaya', 'deskripsi' => 'Tidak ada risiko cedera atau bahaya.', 'poin' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 4, 'judul' => 'Berisiko ringan', 'deskripsi' => 'Ada potensi risiko, tapi mudah dihindari.', 'poin' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 4, 'judul' => 'Berbahaya', 'deskripsi' => 'Berbahaya langsung bagi pengguna.', 'poin' => '5', 'created_at' => now(), 'updated_at' => now()],

            // C5: Biaya Perbaikan
            ['kriteria_id' => 5, 'judul' => '<1.000.000', 'deskripsi' => 'Biaya perbaikan kurang dari Rp 1.000.000.', 'poin' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 5, 'judul' => '1–5 juta', 'deskripsi' => 'Biaya perbaikan antara Rp 1.000.000 – Rp 5.000.000.', 'poin' => '2', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 5, 'judul' => '5–10 juta', 'deskripsi' => 'Biaya perbaikan antara Rp 5.000.001 – Rp 10.000.000.', 'poin' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 5, 'judul' => '10–25 juta', 'deskripsi' => 'Biaya perbaikan antara Rp 10.000.001 – Rp 25.000.000.', 'poin' => '4', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 5, 'judul' => '>25 juta', 'deskripsi' => 'Biaya perbaikan lebih dari Rp 25.000.000.', 'poin' => '5', 'created_at' => now(), 'updated_at' => now()],

            // C6: Waktu Perbaikan (Downtime)
            ['kriteria_id' => 6, 'judul' => '<1 hari', 'deskripsi' => 'Downtime kurang dari 1 hari kerja.', 'poin' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 6, 'judul' => '1–3 hari', 'deskripsi' => 'Downtime antara 1–3 hari kerja.', 'poin' => '2', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 6, 'judul' => '4–7 hari', 'deskripsi' => 'Downtime antara 4–7 hari kerja.', 'poin' => '3', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 6, 'judul' => '8–14 hari', 'deskripsi' => 'Downtime antara 8–14 hari kerja.', 'poin' => '4', 'created_at' => now(), 'updated_at' => now()],
            ['kriteria_id' => 6, 'judul' => '>14 hari', 'deskripsi' => 'Downtime lebih dari 14 hari kerja.', 'poin' => '5', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
