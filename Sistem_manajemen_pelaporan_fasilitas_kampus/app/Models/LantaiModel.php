<?php

namespace App\Models;

use Generator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LantaiModel extends Model
{
    use HasFactory;

    protected $table = 'lantai';

    // Primary key tabel
    protected $primaryKey = 'lantai_id';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'lantai_nama',
        'gedung_id'
    ];

    public function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }

    function ruangan()
    {
        return $this->hasMany(RuanganModel::class, 'lantai_id', 'lantai_id');
        
    }
}
