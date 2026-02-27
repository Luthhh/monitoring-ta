<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Dosen;

class Mahasiswa extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'prodi',
        'tahun_masuk',
        'semester',
        'pembimbing_id',
    ];

    // Relasi ke User (akun login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class);
    }

    public function pembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing1_id');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing2_id');
    }
}