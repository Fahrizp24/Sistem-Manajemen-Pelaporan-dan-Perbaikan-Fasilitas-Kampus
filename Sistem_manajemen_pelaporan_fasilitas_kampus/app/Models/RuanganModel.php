<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganModel extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    // Primary key tabel
    protected $primaryKey = 'ruangan_id';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_ruangan',
        'lantai_id'
    ];

    public function lantai()
    {
        return $this->belongsTo(LantaiModel::class, 'lantai_id', 'lantai_id');
    }

    function fasilitas()
    {
        return $this->hasMany(FasilitasModel::class, 'ruangan_id', 'ruangan_id');
        
    }
}
