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
        'no_telp',
        'password',
        'peran',
        'program_studi',
        'jurusan',
    ];

    // Define the attributes that should be hidden for arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Di dalam UserModel
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRoleName() : string {
        return $this->peran; // mendapatkan nama role
    }

    public function hasRole(): bool {
        $roles = ['admin', 'pelapor', 'sarpras', 'teknisi', 'superadmin'];
        return in_array($this->peran, $roles);
    }
    
    public function getRole(){
        return $this->peran; //mengecek apakah sebuah user memiliki role
    }
}
