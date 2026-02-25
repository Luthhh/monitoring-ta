<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index($status = null)
    {

        $dataMahasiswa = [
            [
                'nama' => 'Luthfi Dika Chandra',
                'nim' => 'J0403221143',
                'tahun' => '2020/2021',
                'topik' => 'Sistem Monitoring Tugas Akhir',
                'progress' => 'Penetapan Komisi Pembimbing',
                'status' => 'ahead'
            ],
            [
                'nama' => 'Dini Nurul Azizah',
                'nim' => 'J0403221149',
                'tahun' => '2020/2021',
                'topik' => 'Chatbot Informasi Paspor',
                'progress' => 'Proposal',
                'status' => 'ideal'
            ],
            [
                'nama' => 'Ahmad Fauzan',
                'nim' => 'J0403221150',
                'tahun' => '2020/2021',
                'topik' => 'Sistem Rekomendasi Beasiswa',
                'progress' => 'Bimbingan',
                'status' => 'behind'
            ],
            [
                'nama' => 'Siti Rahmawati',
                'nim' => 'J0403221151',
                'tahun' => '2021/2022',
                'topik' => 'Website E-Commerce UMKM',
                'progress' => 'Seminar Proposal',
                'status' => 'ahead'
            ],
            [
                'nama' => 'Rizky Pratama',
                'nim' => 'J0403221152',
                'tahun' => '2021/2022',
                'topik' => 'Aplikasi Mobile Absensi',
                'progress' => 'Tesis',
                'status' => 'ideal'
            ],
        ];

        // filter berdasarkan status jika ada
        if ($status) {
            $dataMahasiswa = array_filter($dataMahasiswa, function ($m) use ($status) {
                return $m['status'] == $status;
            });
        }

        $total = count($dataMahasiswa);

    // Default
    $warna = '#4e73df';
    $judul = 'Total Mahasiswa';
    $icon = 'fa-users';

    if ($status == 'ahead') {
        $warna = '#1cc88a';
        $judul = 'Mahasiswa Ahead';
        $icon = 'fa-rocket';
    }

    if ($status == 'ideal') {
        $warna = '#f6c23e';
        $judul = 'Mahasiswa Ideal';
        $icon = 'fa-balance-scale';
    }

    if ($status == 'behind') {
        $warna = '#e74a3b';
        $judul = 'Mahasiswa Behind';
        $icon = 'fa-exclamation-triangle';
    }

    return view('mahasiswa.index', compact(
        'dataMahasiswa',
        'total',
        'warna',
        'judul',
        'icon',
        'status'
    ));

    }
}

