<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Dosen;
use App\Models\Mahasiswa;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->hasOne(Dosen::class);
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }
}