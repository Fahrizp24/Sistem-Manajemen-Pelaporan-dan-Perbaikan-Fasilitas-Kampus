
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LaporanModel extends Model
{

    // Nama tabel di database
    protected $table = 'laporan';

    // Primary key tabel
    protected $primaryKey = 'id';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'tanggal_laporan',
        'user_id',
    ];

    // Jika tabel tidak menggunakan timestamps (created_at, updated_at)
    public $timestamps = true;

    // Relasi ke model User (contoh jika ada relasi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}