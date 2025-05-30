<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KriteriaModel extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $primaryKey = 'kriteria_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'kode',
        'nama',
        'bobot'
    ];
    
    function crisp(): HasMany
    {
        return $this->hasMany(CrispModel::class, 'kriteria_id', 'kriteria_id');
    }

    public function spk()
    {
        return $this->belongsToMany(SPKModel::class, 'spk_kriteria', 'kriteria_id', 'spk_id')
            ->withPivot('nilai')
            ->withTimestamps();
    }
}
