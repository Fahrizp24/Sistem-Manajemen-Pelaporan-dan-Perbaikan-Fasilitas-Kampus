<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkModel extends Model
{
    protected $table = 'spk';
    protected $primaryKey = 'spk_id';
    public $timestamps = true;
    protected $fillable = [
        'laporan_id',
        'spk_id',
    ];

    public function kriteria()
    {
        return $this->belongsToMany(KriteriaModel::class, 'spk_kriteria', 'spk_id', 'kriteria_id')
            ->withPivot('nilai')
            ->withTimestamps();
    }

    public function laporan()
    {
        return $this->belongsTo(LaporanModel::class, 'laporan_id');
    }
}
