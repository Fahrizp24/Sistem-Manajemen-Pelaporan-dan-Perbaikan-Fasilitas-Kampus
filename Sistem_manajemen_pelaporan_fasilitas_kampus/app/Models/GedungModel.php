<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GedungModel extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $primaryKey = 'gedung_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'gedung_nama'
    ];

    function lantai()
    {
        return $this->hasMany(LantaiModel::class, 'gedung_id', 'gedung_id');
        
    }

}
