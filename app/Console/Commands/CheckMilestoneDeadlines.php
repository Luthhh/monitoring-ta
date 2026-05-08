<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMilestoneDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:check-deadlines';
    protected $description = 'Cek deadline milestone dan kirim notifikasi';

    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $admins = \App\Models\User::where('role', 'admin')->get();

        // 1. Notifikasi H-7 Deadline Milestone
        $milestonesH7 = \App\Models\Milestone::whereIn('status', ['pending', 'ditolak'])
            ->whereNotNull('deadline')
            ->whereDate('deadline', $now->copy()->addDays(7)->toDateString())
            ->with('tugasAkhir.mahasiswa.user')
            ->get();

        foreach ($milestonesH7 as $m) {
            $user = optional(optional($m->tugasAkhir)->mahasiswa)->user;
            if ($user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title'   => '⚠️ Peringatan Target Milestone',
                    'message' => "Deadline untuk milestone '{$m->jenis_milestone}' tinggal 7 hari lagi (" . \Carbon\Carbon::parse($m->deadline)->format('d M Y') . "). Segera upload bukti kegiatan.",
                    'is_read' => false,
                ]);
            }
        }

        // 2. Notifikasi Melewati Deadline Milestone
        $milestonesPast = \App\Models\Milestone::whereIn('status', ['pending', 'ditolak'])
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', $now->toDateString())
            ->with('tugasAkhir.mahasiswa.user')
            ->get();

        foreach ($milestonesPast as $m) {
            $mhs = optional($m->tugasAkhir)->mahasiswa;
            $user = optional($mhs)->user;
            if ($user) {
                // Notif ke Mahasiswa
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title'   => '🚨 Deadline Terlewat',
                    'message' => "Anda telah melewati target waktu untuk milestone '{$m->jenis_milestone}'. Mohon segera konsultasi dengan pembimbing.",
                    'is_read' => false,
                ]);

                // Notif ke Admin (Mahasiswa Kritis)
                foreach ($admins as $admin) {
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title'   => '⚠️ Mahasiswa Kritis (Lewat Deadline)',
                        'message' => "Mahasiswa {$user->name} ({$mhs->nim}) telah melewati deadline milestone '{$m->jenis_milestone}'.",
                        'is_read' => false,
                    ]);
                }
            }
        }

        // 3. Pengingat Dokumen Lanjutan (BAP)
        // Mahasiswa yang milestone Seminar atau Ujian Tesis sudah 'disetujui' tapi dokumen tambahan mungkin bisa diingatkan jika diperlukan.
        // Di sini saya memfilter milestone yang belum upload dokumen (jika sistem menganggap dokumen diupload saat pengajuan). 
        // Jika ada status menunggu_verifikasi untuk Seminar, tapi belum ada bukti_file_tambahan (jika ada kolomnya), 
        // kita akan ingatkan milestone yang statusnya 'menunggu_verifikasi' tanpa file khusus. Atau yang baru disetujui.
        
        // Asumsi: "Pengingat untuk upload BAP" ditujukan pada mahasiswa yang baru saja selesai bimbingan/seminar 
        // atau jika statusnya disetujui namun belum ada verifikasi BAP. Karena Milestone model tidak menyimpan info jika BAP terupload, 
        // kita anggap pesan notifikasi dikirim jika milestone 'Seminar'/'Ujian Tesis' disetujui.
        // Untuk otomatisasi harian: ingatkan mahasiswa yang milestone-nya sudah disetujui dalam 1 hari terakhir.
        $milestonesApproved = \App\Models\Milestone::whereIn('jenis_milestone', ['Seminar', 'Ujian Tesis'])
            ->where('status', 'disetujui')
            ->whereDate('tanggal_disetujui', $now->copy()->subDays(1)->toDateString())
            ->with('tugasAkhir.mahasiswa.user')
            ->get();

        foreach ($milestonesApproved as $m) {
            $user = optional(optional($m->tugasAkhir)->mahasiswa)->user;
            if ($user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title'   => '📄 Pengingat Upload BAP',
                    'message' => "Milestone '{$m->jenis_milestone}' Anda telah disetujui. Pastikan Anda telah melengkapi dan mengupload Berita Acara (BAP) jika diwajibkan oleh fakultas.",
                    'is_read' => false,
                ]);
            }
        }

        // 4. Laporan Harian Mahasiswa Kritis untuk Admin
        // Defisini Kritis: Semester 7 belum seminar atau Semester 8+
        $allMhs = \App\Models\Mahasiswa::with('tugasAkhir.milestones', 'user')->get();
        $kritisCount = 0;
        $batasStudiCount = 0;

        foreach ($allMhs as $mhs) {
            if ($mhs->semester == 8) {
                $batasStudiCount++;
            } elseif ($mhs->semester == 7) {
                $hasSeminar = false;
                if ($mhs->tugasAkhir && $mhs->tugasAkhir->milestones) {
                    $hasSeminar = $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                }
                if (!$hasSeminar) {
                    $kritisCount++;
                }
            }
        }

        if ($kritisCount > 0 || $batasStudiCount > 0) {
            // Hanya kirim notifikasi seminggu sekali (e.g., hari Senin) agar admin tidak dispam setiap hari
            if ($now->isMonday()) {
                foreach ($admins as $admin) {
                    $pesan = "Ringkasan Mingguan:\n";
                    if ($kritisCount > 0) $pesan .= "- Terdapat $kritisCount mahasiswa berstatus kritis (Semester 7 belum Seminar).\n";
                    if ($batasStudiCount > 0) $pesan .= "- Terdapat $batasStudiCount mahasiswa mendekati batas studi (Semester 8).\n";

                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title'   => '📊 Laporan Mingguan Mahasiswa Kritis',
                        'message' => $pesan,
                        'is_read' => false,
                    ]);
                }
            }
        }

        $this->info('Pengecekan deadline selesai.');
    }
}
