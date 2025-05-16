<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GedungModel extends Model
{
    use HasFactory;

    protected $table = 'umpan_balik';

    protected $primaryKey = 'umpan_balik_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'laporan_id',
        'pengguna_id',
        'penilaian',
        'komentar'
    ];

    function laporan()
    {
        return $this->belongsTo(LaporanModel::class, 'laporan_id', 'id_laporan');
    }
    function pengguna()
    {
        return $this->belongsTo(UserModel::class, 'pengguna_id', 'id_pengguna');
    }
}
