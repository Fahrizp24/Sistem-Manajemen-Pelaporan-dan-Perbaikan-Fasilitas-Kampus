<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'foto_profil'
    ];

    // Define the attributes that should be hidden for arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
