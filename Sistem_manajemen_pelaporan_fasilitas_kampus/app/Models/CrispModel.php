<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrispModel extends Model
{
    use HasFactory;

    protected $table = 'crisp';

    protected $primaryKey = 'crisp_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'kriteria_id',
        'nama',
        'deskripsi',
        'poin'
    ];
    public function kriteria()
    {
        return $this->belongsTo(KriteriaModel::class, 'kriteria_id');
    }
    
}
