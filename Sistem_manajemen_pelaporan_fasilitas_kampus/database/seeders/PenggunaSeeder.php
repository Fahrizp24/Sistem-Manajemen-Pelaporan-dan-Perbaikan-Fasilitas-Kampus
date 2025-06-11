<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengguna')->insert([
            [
                'username' => 'admin',
                'nama' => 'Admin',
                'prodi' => '-',
                'jurusan' => '-',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'peran' => 'admin',
                'foto_profil' => 'default.jpg',
                'pertanyaan_masa_kecil' => 'Siapa nama teman masa kecil Anda?',
                'jawaban_masa_kecil' => 'Andi',
                'pertanyaan_keluarga' => 'Siapa nama ibu kandung Anda?',
                'jawaban_keluarga' => 'Siti',
                'pertanyaan_tempat' => 'Di mana Anda dilahirkan?',
                'jawaban_tempat' => 'Surabaya',
                'pertanyaan_pengalaman' => 'Apa pekerjaan pertama Anda?',
                'jawaban_pengalaman' => 'Guru'
            ],
            [
                'username' => 'teknisi',
                'nama' => 'Teknisi 1',
                'prodi' => '-',
                'jurusan' => '-',
                'email' => 'teknisi1@example.com',
                'password' => bcrypt('teknisi123'),
                'peran' => 'teknisi',
                'foto_profil' => 'default.jpg',
                'pertanyaan_masa_kecil' => 'Siapa nama teman masa kecil Anda?',
                'jawaban_masa_kecil' => 'Joko',
                'pertanyaan_keluarga' => 'Siapa nama ibu kandung Anda?',
                'jawaban_keluarga' => 'Murni',
                'pertanyaan_tempat' => 'Di mana Anda dilahirkan?',
                'jawaban_tempat' => 'Blitar',
                'pertanyaan_pengalaman' => 'Apa pekerjaan pertama Anda?',
                'jawaban_pengalaman' => 'Teknisi Komputer'
            ],
            [
                'username' => 'mahasiswa',
                'nama' => 'Mahasiswa',
                'prodi' => 'Teknik Informatika',
                'jurusan' => 'Teknologi Informasi',
                'email' => 'mahasiswa@example.com',
                'password' => bcrypt('mahasiswa123'),
                'peran' => 'pelapor',
                'foto_profil' => 'default.jpg',
                'pertanyaan_masa_kecil' => 'Siapa nama teman masa kecil Anda?',
                'jawaban_masa_kecil' => 'Aldi',
                'pertanyaan_keluarga' => 'Siapa nama ibu kandung Anda?',
                'jawaban_keluarga' => 'Rina',
                'pertanyaan_tempat' => 'Di mana Anda dilahirkan?',
                'jawaban_tempat' => 'Malang',
                'pertanyaan_pengalaman' => 'Apa pekerjaan pertama Anda?',
                'jawaban_pengalaman' => 'Magang'
            ],
            [
                'username' => 'tendik',
                'nama' => 'Tendik',
                'prodi' => '-',
                'jurusan' => '-',
                'email' => 'tendik@example.com',
                'password' => bcrypt('tendik123'),
                'peran' => 'pelapor',
                'foto_profil' => 'default.jpg',
                'pertanyaan_masa_kecil' => 'Siapa nama teman masa kecil Anda?',
                'jawaban_masa_kecil' => 'Budi',
                'pertanyaan_keluarga' => 'Siapa nama ibu kandung Anda?',
                'jawaban_keluarga' => 'Siti',
                'pertanyaan_tempat' => 'Di mana Anda dilahirkan?',
                'jawaban_tempat' => 'Malang',
                'pertanyaan_pengalaman' => 'Apa pekerjaan pertama Anda?',
                'jawaban_pengalaman' => 'Kasir'
            ],
            [
                'username' => 'dosen',
                'nama' => 'Dosen',
                'prodi' => '-',
                'jurusan' => 'Teknologi Informasi',
                'email' => 'dosen@example.com',
                'password' => bcrypt('dosen123'),
                'peran' => 'pelapor',
                'foto_profil' => 'default.jpg',
                'pertanyaan_masa_kecil' => 'Siapa nama teman masa kecil Anda?',
                'jawaban_masa_kecil' => 'Toni',
                'pertanyaan_keluarga' => 'Siapa nama ibu kandung Anda?',
                'jawaban_keluarga' => 'Ani',
                'pertanyaan_tempat' => 'Di mana Anda dilahirkan?',
                'jawaban_tempat' => 'Jakarta',
                'pertanyaan_pengalaman' => 'Apa pekerjaan pertama Anda?',
                'jawaban_pengalaman' => 'Asisten Dosen'
            ],
            [
                'username' => 'sarpras',
                'nama' => 'Sarpras',
                'prodi' => '-',
                'jurusan' => '-',
                'email' => 'sarpras@example.com',
                'password' => bcrypt('sarpras123'),
                'peran' => 'sarpras',
                'foto_profil' => 'default.jpg',
                'pertanyaan_masa_kecil' => 'Siapa nama teman masa kecil Anda?',
                'jawaban_masa_kecil' => 'Yanto',
                'pertanyaan_keluarga' => 'Siapa nama ibu kandung Anda?',
                'jawaban_keluarga' => 'Rukmini',
                'pertanyaan_tempat' => 'Di mana Anda dilahirkan?',
                'jawaban_tempat' => 'Kediri',
                'pertanyaan_pengalaman' => 'Apa pekerjaan pertama Anda?',
                'jawaban_pengalaman' => 'Petugas Kebersihan'
            ],
        ]);
    }
}
