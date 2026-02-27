<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Bimbingan;
use App\Models\Milestone;

class TugasAkhir extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
}