use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use HasFactory;

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{

    // Define the table associated with the model (optional if it matches the plural of the model name)
    protected $table = 'users';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Specify if the primary key is auto-incrementing (default is true)
    public $incrementing = true;

    // Specify the data type of the primary key (default is 'int')
    protected $keyType = 'int';

    // Disable timestamps if your table doesn't have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // Define the attributes that are mass assignable
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Define the attributes that should be hidden for arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define the attributes that should be cast to native types
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}