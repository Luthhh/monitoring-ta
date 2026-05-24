<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Milestone;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Get role and dosens
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        if (!$mahasiswaRole) {
            $mahasiswaRole = Role::create(['name' => 'mahasiswa']);
        }

        $dosenRole = Role::where('name', 'dosen')->first();
        if (!$dosenRole) {
            $dosenRole = Role::create(['name' => 'dosen']);
        }

        $dosens = Dosen::all();
        if ($dosens->isEmpty()) {
            $dosenUser = User::firstOrCreate(
                ['email' => 'dosen.dummy@apps.ipb.ac.id'],
                [
                    'name' => 'Dosen Pembimbing',
                    'password' => Hash::make('password'),
                    'role_id' => $dosenRole->id,
                ]
            );
            $dosen = Dosen::firstOrCreate(
                ['user_id' => $dosenUser->id],
                [
                    'nip' => '1987123456',
                    'prodi' => 'Informatika',
                ]
            );
            $dosens = collect([$dosen]);
        }

        $p1 = $dosens->first();
        $p2 = $dosens->count() > 1 ? $dosens->skip(1)->first() : $p1;

        $prodis = ['Informatika', 'Teknologi Informasi', 'Sistem Informasi'];
        $milestoneTypes = [
            'Penetapan Komisi Pembimbing',
            'Sidang Komisi 1',
            'Kolokium',
            'Proposal',
            'Penelitian dan Bimbingan',
            'Evaluasi dan Monitoring',
            'Sidang Komisi 2',
            'Seminar',
            'Publikasi Ilmiah',
            'Ujian Tesis',
            'SKL',
        ];

        $namaTepatWaktu = [
            'Ahmad Fauzi',
            'Siti Aminah',
            'Rizky Pratama',
            'Putri Lestari',
            'Budi Setiawan'
        ];

        foreach ($namaTepatWaktu as $idx => $name) {
            $email = strtolower(str_replace(' ', '', $name)) . '@apps.ipb.ac.id';
            $nim = 'J0403222' . str_pad(100 + $idx, 3, '0', STR_PAD_LEFT);

            // Avoid duplicate users
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role_id' => $mahasiswaRole->id,
                ]);
            }

            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                $mahasiswa = Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $nim,
                    'prodi' => $prodis[array_rand($prodis)],
                    'tahun_masuk' => 2022,
                    'semester' => 4,
                    'pembimbing1_id' => $p1->id,
                    'pembimbing2_id' => $p2->id,
                ]);
            }

            $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();
            if (!$ta) {
                $ta = TugasAkhir::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'judul' => 'Sistem Informasi ' . ($idx + 1) . ' Terintegrasi Berbasis Web',
                    'status' => 'Selesai',
                    'tanggal_mulai' => Carbon::now()->subMonths(10),
                ]);
            }

            // Create milestones if they don't exist
            foreach ($milestoneTypes as $index => $type) {
                $exists = Milestone::where('tugas_akhir_id', $ta->id)->where('jenis_milestone', $type)->exists();
                if (!$exists) {
                    Milestone::create([
                        'tugas_akhir_id' => $ta->id,
                        'jenis_milestone' => $type,
                        'status' => 'disetujui',
                        'deadline' => Carbon::now()->subMonths(10 - $index),
                        'tanggal_upload' => Carbon::now()->subMonths(10 - $index)->subDays(7),
                        'tanggal_disetujui' => Carbon::now()->subMonths(10 - $index),
                        'file_path' => 'dummy_file.pdf',
                        'file_bap' => $type !== 'SKL' ? 'bap_milestone.pdf' : null,
                    ]);
                }
            }

            // Create bimbingans if they don't exist
            $bCount = Bimbingan::where('tugas_akhir_id', $ta->id)->count();
            if ($bCount == 0) {
                for ($k = 1; $k <= 12; $k++) {
                    Bimbingan::create([
                        'tugas_akhir_id' => $ta->id,
                        'dosen_id' => ($k % 2 == 0) ? $p1->id : $p2->id,
                        'tanggal' => Carbon::now()->subMonths(11)->addDays($k * 15),
                        'waktu' => '09:00',
                        'tempat' => 'Ruang Dosen',
                        'deskripsi' => 'Bimbingan milestone progres ke-' . $k,
                        'hasil_bimbingan' => 'Disetujui untuk lanjut.',
                        'status' => 'selesai',
                        'tahun_semester' => '2023/2024-Genap',
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
