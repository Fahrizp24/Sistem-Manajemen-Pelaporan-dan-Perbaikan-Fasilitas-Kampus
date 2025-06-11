<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FasilitasModel extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $primaryKey = 'fasilitas_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'ruangan_id',
        'fasilitas_nama',
        'fasilitas_deskripsi',
        'kategori',
        'status'
    ];

    function ruangan()
    {
        return $this->belongsTo(RuanganModel::class, 'ruangan_id', 'ruangan_id');
    }

    function lantai()
    {
        return $this->belongsTo(LantaiModel::class);
    }

    function gedung()
    {
        return $this->belongsTo(GedungModel::class);
    }

    function laporan()
    {
        return $this->hasMany(LaporanModel::class, 'fasilitas_id', 'fasilitas_id');
    }
}
