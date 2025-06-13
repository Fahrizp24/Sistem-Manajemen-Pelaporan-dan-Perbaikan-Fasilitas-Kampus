<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LaporanModel extends Model
{

    // Nama tabel di database
    protected $table = 'laporan';

    // Primary key tabel
    protected $primaryKey = 'laporan_id';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'pelapor_id',
        'fasilitas_id',
        'ditugaskan_oleh',
        'teknisi_id', 
        'deskripsi',
        'foto',
        'status',
        'urgensi',
        'foto_pengerjaan',
        'ditolak_oleh',
        'alasan_penolakan',
        'alasan_revisi',
    ]; 

    // Jika tabel tidak menggunakan timestamps (created_at, updated_at)
    public $timestamps = true;

    // Relasi ke model User (contoh jika ada relasi)
    public function pelapor()
    {
        return $this->belongsTo(UserModel::class, 'pelapor_id', 'pengguna_id');
    }

    // Relasi ke model Fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(FasilitasModel::class, 'fasilitas_id', 'fasilitas_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(RuanganModel::class);
    }

    public function lantai()
    {
        return $this->belongsTo(LantaiModel::class);
    }

    public function gedung()
    {
        return $this->belongsTo(GedungModel::class);
    }

    public function sarpras()
    {
        return $this->belongsTo(UserModel::class, 'ditugaskan_oleh', 'pengguna_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(UserModel::class, 'teknisi_id', 'pengguna_id');
    }
}
