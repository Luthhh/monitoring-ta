<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use App\Models\Milestone;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $dosenRole = Role::where('name', 'dosen')->first();

        // 1. Create some additional Dosen if needed
        $dosens = Dosen::all();
        $faker = \Faker\Factory::create('id_ID');
        if ($dosens->count() < 5) {
            for ($i = 1; $i <= 5; $i++) {
                $name = $faker->name();
                $nip = rand(1970, 1995) . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT) . 
                       rand(2010, 2022) . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . rand(1, 2) . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                
                $user = User::create([
                    'name' => $name,
                    'email' => 'dosen_' . strtolower(str_replace([' ', '.'], '', $name)) . '@apps.ipb.ac.id',
                    'password' => Hash::make('password'),
                    'role_id' => $dosenRole->id,
                ]);

                $dosens[] = Dosen::create([
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'prodi' => 'Informatika',
                ]);
            }
            $dosens = Dosen::all();
        }

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

        $prodis = ['Informatika', 'Teknologi Informasi', 'Sistem Informasi'];

        $faker = \Faker\Factory::create('id_ID');

        // Loop Semester 1-8
        for ($semester = 1; $semester <= 8; $semester++) {
            // Create 3 students per semester for variety
            for ($j = 1; $j <= 3; $j++) {
                $tahunMasuk = 2024 - ceil($semester / 2);
                $yy = substr($tahunMasuk, -2);
                $nim = 'J0403' . $yy . str_pad(rand(1000, 1999), 4, '0', STR_PAD_LEFT);
                
                $name = $faker->name();
                $user = User::create([
                    'name' => $name,
                    'email' => strtolower(str_replace(' ', '', $name)) . $semester . '_' . $j . '@apps.ipb.ac.id',
                    'password' => Hash::make('password'),
                    'role_id' => $mahasiswaRole->id,
                ]);

                // Determine supervisor count (1 or 2)
                $numPembimbing = rand(1, 2);
                $p1 = $dosens->random();
                $p2 = ($numPembimbing == 2) ? $dosens->where('id', '!=', $p1->id)->random() : null;

                $mahasiswa = Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $nim,
                    'prodi' => $prodis[array_rand($prodis)],
                    'tahun_masuk' => $tahunMasuk,
                    'semester' => $semester,
                    'pembimbing1_id' => $p1->id,
                    'pembimbing2_id' => $p2 ? $p2->id : null,
                ]);

                // Create Tugas Akhir
                // In this app, it seems most students have TA records to track milestones
                $ta = TugasAkhir::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'judul' => 'Penelitian Tentang ' . Str::random(10) . ' untuk Semester ' . $semester,
                    'status' => $semester == 8 ? 'Selesai' : 'Proses',
                    'tanggal_mulai' => Carbon::now()->subMonths(rand(1, 12)),
                ]);

                // Create Milestones with varying progress
                // We'll calculate how many milestones are "disetujui" based on semester
                // Max is 11 milestones. 
                $numCompletedMilestones = floor(($semester / 8) * count($milestoneTypes));
                
                foreach ($milestoneTypes as $index => $type) {
                    $status = 'pending';
                    $tglUpload = null;
                    $tglSetuju = null;

                    if ($index < $numCompletedMilestones) {
                        $status = 'disetujui';
                        $tglUpload = Carbon::now()->subMonths(12 - $index);
                        $tglSetuju = Carbon::now()->subMonths(12 - $index)->addDays(7);
                    } elseif ($index == $numCompletedMilestones) {
                        // Current milestone could be pending or awaiting verification
                        $status = rand(0, 1) ? 'menunggu_verifikasi' : 'pending';
                        if ($status == 'menunggu_verifikasi') {
                            $tglUpload = Carbon::now()->subDays(rand(1, 5));
                        }
                    }

                    Milestone::create([
                        'tugas_akhir_id' => $ta->id,
                        'jenis_milestone' => $type,
                        'status' => $status,
                        'deadline' => Carbon::now()->addMonths(($index - $numCompletedMilestones + 1) * 1),
                        'tanggal_upload' => $tglUpload,
                        'tanggal_disetujui' => $tglSetuju,
                        'file_path' => $tglUpload ? 'dummy_file.pdf' : null,
                    ]);
                }

                // Create Bimbingan with varying counts
                $numBimbingan = rand(2, 20);
                for ($k = 1; $k <= $numBimbingan; $k++) {
                    $bStatus = 'selesai'; // Default completed
                    if ($k == $numBimbingan) {
                        $statuses = ['pending', 'disetujui', 'menunggu_verifikasi', 'selesai'];
                        $bStatus = $statuses[array_rand($statuses)];
                    }

                    Bimbingan::create([
                        'tugas_akhir_id' => $ta->id,
                        'dosen_id' => (rand(0, 1) == 0 || !$p2) ? $p1->id : $p2->id,
                        'tanggal' => Carbon::now()->subDays($k * 5),
                        'waktu' => '10:00',
                        'tempat' => rand(0, 1) ? 'Ruang Dosen' : 'Zoom',
                        'deskripsi' => 'Bimbingan ke-' . $k . ' membahas progress ' . Str::random(5),
                        'hasil_bimbingan' => $bStatus == 'selesai' ? 'Lanjutkan ke bab berikutnya' : null,
                        'status' => $bStatus,
                        'tahun_semester' => '2023/2024-Genap',
                    ]);
                }
            }
        }

        // 3. Create 5 Special "Lulus Tepat Waktu" Students (Semester <= 4 with SKL milestone approved)
        $namaTepatWaktu = [
            'Ahmad Fauzi',
            'Siti Aminah',
            'Rizky Pratama',
            'Putri Lestari',
            'Budi Setiawan'
        ];
        
        foreach ($namaTepatWaktu as $idx => $name) {
            $nim = 'J0403222' . str_pad(100 + $idx, 3, '0', STR_PAD_LEFT);
            $user = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@apps.ipb.ac.id',
                'password' => Hash::make('password'),
                'role_id' => $mahasiswaRole->id,
            ]);

            $p1 = $dosens->random();
            $p2 = $dosens->where('id', '!=', $p1->id)->random();

            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'prodi' => $prodis[array_rand($prodis)],
                'tahun_masuk' => 2022,
                'semester' => 4,
                'pembimbing1_id' => $p1->id,
                'pembimbing2_id' => $p2->id,
            ]);

            $ta = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => 'Sistem Informasi ' . ($idx + 1) . ' Terintegrasi Berbasis Web',
                'status' => 'Selesai',
                'tanggal_mulai' => Carbon::now()->subMonths(10),
            ]);

            // All milestones are approved
            foreach ($milestoneTypes as $index => $type) {
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

            // Create some bimbingans
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
