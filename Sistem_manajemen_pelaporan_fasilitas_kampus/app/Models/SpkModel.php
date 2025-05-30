<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpkModel extends Model
{
    use HasFactory;

    protected $table = 'spk';

    protected $primaryKey = 'spk_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'laporan_id',
        'tingkat_keparahan',
        'dampak_operasional',
        'frekuensi_penggunaan',
        'risiko_keamanan',
        'biaya_perbaikan',
        'waktu_perbaikan',
    ];
    
    function laporan(): BelongsTo
    {
        return $this->BelongsTo(LaporanModel::class, 'laporan_id', 'laporan_id');
    }
}
