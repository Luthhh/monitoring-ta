<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TugasAkhir;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Milestone extends Model
{
    protected $fillable = [
        'tugas_akhir_id',
        'jenis_milestone',
        'status',
        'deadline',
        'file_path',
        'file_bap',
        'catatan_revisi',
        'tanggal_upload',
        'tanggal_disetujui',
    ];

    protected function filePath(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) return $value;
                $decoded = json_decode($value, true);
                return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $value;
            },
            set: fn ($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }
}
