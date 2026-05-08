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
        'waktu',
        'tempat',
        'deskripsi',
        'hasil_bimbingan',
        'catatan_mahasiswa',
        'catatan',
        'status',
        'tahun_semester',
        'nama_kegiatan',
        'tanggal_selesai',
        'durasi_jam',
        'tipe_penyelenggaraan',
        'nama_dokumen',
        'file_dokumen',
        'link_kegiatan',
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