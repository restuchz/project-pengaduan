<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Petugas extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'nama_petugas',
        'username',
        'password',
        'telp',
        'level',
    ];
}
