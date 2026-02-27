<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Mahasiswa;

class Dosen extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'prodi',
        'foto_profil',
    ];

    // Relasi ke User (akun login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Mahasiswa sebagai pembimbing
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'pembimbing_id');
    }
}