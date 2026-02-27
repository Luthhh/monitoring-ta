<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TugasAkhir;
use App\Models\Dosen;

class Bimbingan extends Model
{
    protected $fillable = [
        'tugas_akhir_id',
        'dosen_id',
        'tanggal',
        'catatan',
        'status',
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}