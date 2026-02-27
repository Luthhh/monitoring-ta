<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TugasAkhir;

class Milestone extends Model
{
    protected $fillable = [
        'tugas_akhir_id',
        'jenis_milestone',
        'status',
        'deadline',
        'file_path',
        'catatan_revisi',
        'tanggal_upload',
        'tanggal_disetujui',
    ];

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }
}