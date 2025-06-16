<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmpanBalikModel extends Model
{
    use HasFactory;

    protected $table = 'umpan_balik';

    protected $primaryKey = 'umpan_balik_id';

   public $timestamps = true;

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
