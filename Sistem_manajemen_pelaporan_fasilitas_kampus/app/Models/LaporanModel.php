<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LaporanModel extends Model
{

    // Nama tabel di database
    protected $table = 'laporan';

    // Primary key tabel
    protected $primaryKey = 'id_laporan';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'pelapor_id',
        'fasilitas_id',
        'deskripsi',
        'foto',
        'status',
        'tanggal_laporan',
        'tingkat_urgensi',
        'tanggal_selesai'
    ];

    // Jika tabel tidak menggunakan timestamps (created_at, updated_at)
    public $timestamps = true;

    // Relasi ke model User (contoh jika ada relasi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke model Fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(FasilitasModel::class, 'fasilitas_id');
    }
    
}