<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    protected $table = 'pengguna';

    protected $primaryKey = 'pengguna_id';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'username',
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'foto_profil'
    ];

    // Define the attributes that should be hidden for arrays
    protected $hidden = [
        'username',
        'kata_sandi',
        'remember_token',
    ];

    // Di dalam UserModel
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

}
