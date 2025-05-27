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
        'nama',
        'deskripsi',
        'kategori',
        'gedung_id',
        'status'
    ];

    function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }
    function laporan()
    {
        return $this->hasMany(LaporanModel::class, 'fasilitas_id', 'fasilitas_id');
    }
}
