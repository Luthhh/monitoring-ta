<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Milestone;
use App\Models\Notification;
use App\Models\TugasAkhir;

class DosenController extends Controller
{
    // ─── Helper: klasifikasi status TA ─────────────────────────────────────
    private function klasifikasiMahasiswas($dosen)
    {
        $now = Carbon::now();

        $milestoneMapping = [
            '1'  => 'Penetapan Komisi Pembimbing',
            '2'  => 'Sidang Komisi 1',
            '3'  => 'Kolokium',
            '4'  => 'Proposal',
            '5'  => 'Penelitian dan Bimbingan',
            '6'  => 'Evaluasi dan Monitoring',
            '7'  => 'Sidang Komisi 2',
            '8'  => 'Seminar',
            '9'  => 'Publikasi Ilmiah',
            '10' => 'Ujian Tesis',
            '11' => 'SKL',
        ];

        $mahasiswas = Mahasiswa::with([
            'user',
            'tugasAkhir.milestones',
            'tugasAkhir.bimbingans',
            'pembimbing1.user',
            'pembimbing2.user',
        ])->where(function ($q) use ($dosen) {
            $q->where('pembimbing1_id', $dosen->id)
              ->orWhere('pembimbing2_id', $dosen->id);
        })->get();

        foreach ($mahasiswas as $mhs) {
            $ta = $mhs->tugasAkhir;

            if (!$ta) {
                $mhs->status_ta      = 'behind';
                $mhs->last_milestone = null;
                $mhs->last_bimbingan = null;
                continue;
            }

            $allMilestones = $ta->milestones;
            $allBimbingans = $ta->bimbingans;

            $lastApproved = $allMilestones
                ->where('status', 'disetujui')
                ->sortByDesc('tanggal_disetujui')
                ->first();

            $lastBimbingan = $allBimbingans
                ->whereIn('status', ['menunggu_verifikasi', 'selesai'])
                ->sortByDesc('tanggal')
                ->first();

            // Cari Active Milestone (Milestone yang harus dikerjakan saat ini berdasarkan urutan)
            $activeMilestone = null;
            foreach ($milestoneMapping as $key => $jenis) {
                $m = $allMilestones->where('jenis_milestone', $jenis)->first();
                if ($m && $m->status !== 'disetujui') {
                    $activeMilestone = $m;
                    break;
                }
            }

            $mhs->last_milestone   = $lastApproved;
            $mhs->last_bimbingan   = $lastBimbingan;
            $mhs->active_milestone = $activeMilestone;

            // ── KLASIFIKASI PURE TIMELINE ────────────────────────────────────────────
            if (!$activeMilestone) {
                // Semua milestone sudah disetujui (Lulus)
                $mhs->status_ta = 'ahead';
            } else {
                if ($activeMilestone->status === 'menunggu_verifikasi') {
                    // "jika mahasisaw mengupload bukti melakukan milestone tersebut maka statusnya menjadi ahead"
                    $mhs->status_ta = 'ahead';
                } else {
                    // Status == 'pending' (belum upload)
                    if ($activeMilestone->deadline) {
                        $deadline = Carbon::parse($activeMilestone->deadline)->startOfDay();
                        $today = $now->copy()->startOfDay();
                        
                        if ($today->gt($deadline)) {
                            // "tapi jika di hari H milestone tersebut mahasiswa tidak mengupload bukti ... masuk ke behind"
                            $mhs->status_ta = 'behind';
                        } else {
                            // "mahasiswa yang sudah menetakn timeline untuk milestone berikutnya maka dia akan masuk ke ideal, 
                            // sampai tenggat waktu milestone tersebut maka masih masuk ideal"
                            $mhs->status_ta = 'ideal';
                        }
                    } else {
                        // Belum mengatur timeline untuk active milestone
                        $daysSinceApproved = $lastApproved && $lastApproved->tanggal_disetujui 
                            ? $now->diffInDays(Carbon::parse($lastApproved->tanggal_disetujui)) 
                            : null;
                            
                        if ($daysSinceApproved !== null && $daysSinceApproved <= 30) {
                            $mhs->status_ta = 'ahead'; // Masih dalam buffer 30 hari karena baru menyelesaikan milestone
                        } elseif ($daysSinceApproved !== null && $daysSinceApproved > 30) {
                            $mhs->status_ta = 'behind'; // Sudah kelamaan ga set timeline baru
                        } else {
                            // Belum pernah ada milestone yang disetujui (Milestone 1)
                            $taStart = $ta->tanggal_mulai ? Carbon::parse($ta->tanggal_mulai) : Carbon::parse($ta->created_at);
                            if ($now->diffInDays($taStart) > 30) {
                                $mhs->status_ta = 'behind'; // Lebih 30 hari dari daftar TA tapi nggak set timeline milestone 1
                            } else {
                                $mhs->status_ta = 'ideal'; // Baru mulai TA, belum set timeline
                            }
                        }
                    }
                }
            }
        }

        return $mahasiswas;
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────
    public function dashboard()
    {
        $user   = Auth::user();
        $dosen  = $user->dosen;

        $totalMahasiswa = Mahasiswa::where('pembimbing1_id', $dosen->id)
            ->orWhere('pembimbing2_id', $dosen->id)
            ->count();

        $mahasiswas = $this->klasifikasiMahasiswas($dosen);

        $ahead  = $mahasiswas->where('status_ta', 'ahead')->count();
        $ideal  = $mahasiswas->where('status_ta', 'ideal')->count();
        $behind = $mahasiswas->where('status_ta', 'behind')->count();

        // Pengajuan Bimbingan pending
        $pengajuanBimbingans = Bimbingan::with('tugasAkhir.mahasiswa.user')
            ->where('dosen_id', $dosen->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Verifikasi Bukti Milestone
        $verifikasiMilestones = Milestone::with('tugasAkhir.mahasiswa.user')
            ->whereHas('tugasAkhir.mahasiswa', function ($q) use ($dosen) {
                $q->where('pembimbing1_id', $dosen->id)
                  ->orWhere('pembimbing2_id', $dosen->id);
            })
            ->where('status', 'menunggu_verifikasi')
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        // Verifikasi Bukti Bimbingan
        $verifikasiBimbingans = Bimbingan::with('tugasAkhir.mahasiswa.user')
            ->where('dosen_id', $dosen->id)
            ->where('status', 'menunggu_verifikasi')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Mahasiswa tidak bimbingan > 30 hari
        $tidakBimbingan30 = 0;
        foreach ($mahasiswas as $mhs) {
            $last = $mhs->last_bimbingan;
            if (!$last || now()->diffInDays(Carbon::parse($last->tanggal)) > 30) {
                $tidakBimbingan30++;
            }
        }

        // Bimbingan bulan ini (disetujui, menunggu verifikasi, atau selesai)
        $bimbinganBulanIni = Bimbingan::where('dosen_id', $dosen->id)
            ->whereIn('status', ['disetujui', 'menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Unread notifications
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)->count();

        // Milestone stats per jenis untuk chart (hanya mahasiswa bimbingan dosen ini)
        $mahasiswaIds = $mahasiswas->pluck('id');
        $tugasAkhirIds = \App\Models\TugasAkhir::whereIn('mahasiswa_id', $mahasiswaIds)->pluck('id');

        $milestoneNames = [
            'Penetapan Komisi Pembimbing','Sidang Komisi 1','Kolokium','Proposal',
            'Penelitian dan Bimbingan','Evaluasi dan Monitoring','Sidang Komisi 2',
            'Seminar','Publikasi Ilmiah','Ujian Tesis','SKL'
        ];
        $milestoneStats = [];
        foreach ($milestoneNames as $nama) {
            $milestoneStats[$nama] = [
                'sudah' => Milestone::whereIn('tugas_akhir_id', $tugasAkhirIds)
                    ->where('jenis_milestone', $nama)->where('status', 'disetujui')->count(),
                'belum' => Milestone::whereIn('tugas_akhir_id', $tugasAkhirIds)
                    ->where('jenis_milestone', $nama)->where('status', '!=', 'disetujui')->count(),
            ];
        }

        // Stats by year for dynamic filtering
        $statsByYear = [];
        $mhsYears = $mahasiswas->pluck('tahun_masuk')->unique()->filter()->values();
        foreach ($mhsYears as $year) {
            $yearMhsIds = $mahasiswas->where('tahun_masuk', $year)->pluck('id');
            $yearTaIds = \App\Models\TugasAkhir::whereIn('mahasiswa_id', $yearMhsIds)->pluck('id');
            
            $statsByYear[$year] = ['milestones' => []];
            
            foreach ($milestoneNames as $nama) {
                $statsByYear[$year]['milestones'][$nama] = [
                    'sudah' => Milestone::whereIn('tugas_akhir_id', $yearTaIds)
                        ->where('jenis_milestone', $nama)->where('status', 'disetujui')->count(),
                    'belum' => Milestone::whereIn('tugas_akhir_id', $yearTaIds)
                        ->where('jenis_milestone', $nama)->where('status', '!=', 'disetujui')->count(),
                ];
            }
        }

        // Ringkasan Bimbingan Bulanan (Rencana = selain ditolak)
        $rencanaBimbinganBulanIni = Bimbingan::where('dosen_id', $dosen->id)
            ->whereIn('status', ['pending', 'disetujui', 'menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $terlaksanaBulanIni = Bimbingan::where('dosen_id', $dosen->id)
            ->whereIn('status', ['menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $jumlahBimbinganAktif = Bimbingan::where('dosen_id', $dosen->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Data Chart Bimbingan (6 bulan terakhir)
        $chartBimbingan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M');
            $year = $date->year;
            $monthNum = $date->month;

            $chartBimbingan['labels'][] = $month;
            $chartBimbingan['rencana'][] = Bimbingan::where('dosen_id', $dosen->id)
                ->whereMonth('tanggal', $monthNum)
                ->whereYear('tanggal', $year)
                ->count();
            $chartBimbingan['terlaksana'][] = Bimbingan::where('dosen_id', $dosen->id)
                ->whereIn('status', ['menunggu_verifikasi', 'selesai'])
                ->whereMonth('tanggal', $monthNum)
                ->whereYear('tanggal', $year)
                ->count();
        }

        return view('dosen.d-dashboard', compact(
            'user', 'dosen', 'totalMahasiswa', 'ahead', 'ideal', 'behind',
            'pengajuanBimbingans', 'verifikasiMilestones', 'verifikasiBimbingans',
            'tidakBimbingan30', 'bimbinganBulanIni', 'unreadCount', 'milestoneStats', 'statsByYear',
            'rencanaBimbinganBulanIni', 'terlaksanaBulanIni', 'jumlahBimbinganAktif', 'chartBimbingan'
        ));
    }

    // ─── Profile ─────────────────────────────────────────────────────────────
    public function profile()
    {
        $user  = Auth::user();
        $dosen = $user->dosen;
        
        $jmlPembimbing1 = Mahasiswa::where('pembimbing1_id', $dosen->id)->count();
        $jmlPembimbing2 = Mahasiswa::where('pembimbing2_id', $dosen->id)->count();

        return view('dosen.d-profile', compact('user', 'dosen', 'jmlPembimbing1', 'jmlPembimbing2'));
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $dosen = $user->dosen;

        $request->validate([
            'nip'   => 'required',
            'name'  => 'required',
            'prodi' => 'required',
            'email' => ['required', 'email', 'unique:users,email,' . $user->id, 'regex:/@apps\.ipb\.ac\.id$/i'],
            'password' => ['nullable', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.regex' => 'Email harus menggunakan domain institusi (@apps.ipb.ac.id).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, huruf kecil, dan angka.',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email]);
        if ($request->filled('password')) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }
        $dosen->update(['nip' => $request->nip, 'prodi' => $request->prodi]);

        return back()->with('success', 'Biodata berhasil diperbarui');
    }

    // ─── Total / Daftar Mahasiswa ─────────────────────────────────────────────
    public function totalMahasiswa()
    {
        $dosen      = Auth::user()->dosen;
        $mahasiswas = $this->klasifikasiMahasiswas($dosen);

        $jmlPembimbing1 = $mahasiswas->where('pembimbing1_id', $dosen->id)->count();
        $jmlPembimbing2 = $mahasiswas->where('pembimbing2_id', $dosen->id)->count();

        return view('dosen.d-totalmahasiswa', compact('mahasiswas', 'dosen', 'jmlPembimbing1', 'jmlPembimbing2'));
    }

    public function aheadMahasiswa()
    {
        $dosen      = Auth::user()->dosen;
        $mahasiswas = $this->klasifikasiMahasiswas($dosen)->where('status_ta', 'ahead')->values();
        return view('dosen.d-aheadmahasiswa', compact('mahasiswas', 'dosen'));
    }

    public function idealMahasiswa()
    {
        $dosen      = Auth::user()->dosen;
        $mahasiswas = $this->klasifikasiMahasiswas($dosen)->where('status_ta', 'ideal')->values();
        return view('dosen.d-idealmahasiswa', compact('mahasiswas', 'dosen'));
    }

    public function behindMahasiswa()
    {
        $dosen      = Auth::user()->dosen;
        $mahasiswas = $this->klasifikasiMahasiswas($dosen)->where('status_ta', 'behind')->values();
        return view('dosen.d-behindmahasiswa', compact('mahasiswas', 'dosen'));
    }

    // ─── Detail Mahasiswa ────────────────────────────────────────────────────
    public function dataMahasiswa()
    {
        $dosen      = Auth::user()->dosen;
        $mahasiswas = $this->klasifikasiMahasiswas($dosen);
        return view('dosen.d-datamahasiswa', compact('mahasiswas', 'dosen'));
    }

    public function detailMahasiswa($id)
    {
        $dosen     = Auth::user()->dosen;
        $mahasiswa = Mahasiswa::with([
            'user', 'pembimbing1.user', 'pembimbing2.user',
            'tugasAkhir.milestones', 'tugasAkhir.bimbingans.dosen.user'
        ])->findOrFail($id);

        // Pastikan mahasiswa ini bimbingan dosen yang login
        if ($mahasiswa->pembimbing1_id !== $dosen->id && $mahasiswa->pembimbing2_id !== $dosen->id) {
            abort(403, 'Anda bukan pembimbing mahasiswa ini.');
        }

        $tugasAkhir = $mahasiswa->tugasAkhir;
        $bimbingans = $tugasAkhir ? $tugasAkhir->bimbingans->sortByDesc('created_at') : collect();
        $milestones = $tugasAkhir ? $tugasAkhir->milestones->keyBy('jenis_milestone') : collect();

        return view('dosen.d-detail-mahasiswa', compact('mahasiswa', 'dosen', 'tugasAkhir', 'bimbingans', 'milestones'));
    }

    public function aktivitasBimbingan()
    {
        $dosen = Auth::user()->dosen;
        
        $mahasiswas = $this->klasifikasiMahasiswas($dosen);
        $tidakBimbingan30 = $mahasiswas->filter(function ($mhs) {
            $last = $mhs->last_bimbingan ?? null;
            return !$last || Carbon::now()->diffInDays(Carbon::parse($last->tanggal)) > 30;
        });

        $bimbinganBulanIniList = Bimbingan::with(['tugasAkhir.mahasiswa.user', 'tugasAkhir.milestones', 'tugasAkhir.mahasiswa.pembimbing1', 'tugasAkhir.mahasiswa.pembimbing2'])
            ->where('dosen_id', $dosen->id)
            ->whereIn('status', ['disetujui', 'menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dosen.d-aktivitasbimbingan', compact('tidakBimbingan30', 'bimbinganBulanIniList', 'dosen'));
    }

    // ─── Update Status Bimbingan ─────────────────────────────────────────────
    public function updateBimbinganStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak,selesai']);

        $bimbingan = Bimbingan::findOrFail($id);

        if ($bimbingan->dosen_id !== Auth::user()->dosen->id) {
            abort(403);
        }

        $oldStatus = $bimbingan->status;

        $updateData = ['status' => $request->status];
        if ($request->catatan) {
            $updateData['catatan'] = $request->catatan;
        }

        // Jika Dosen MENOLAK bukti (mengembalikan ke 'disetujui' agar bisa diupload ulang)
        if ($oldStatus === 'menunggu_verifikasi' && $request->status === 'disetujui') {
            $updateData['file_dokumen']  = null;
            $updateData['nama_dokumen']  = null;
            $updateData['link_kegiatan'] = null;
        }

        $bimbingan->update($updateData);

        // Kirim notifikasi ke mahasiswa
        $mahasiswa = optional($bimbingan->tugasAkhir)->mahasiswa;
        if ($mahasiswa) {
            if ($request->status === 'selesai') {
                $title = '✅ Bukti Bimbingan Diterima';
                $msg   = 'Bukti bimbingan tanggal ' . Carbon::parse($bimbingan->tanggal)->format('d M Y') . ' telah divalidasi dan selesai.';
            } elseif ($request->status === 'ditolak') {
                $title = '❌ Pengajuan Bimbingan Ditolak';
                $msg   = 'Pengajuan bimbingan tanggal ' . Carbon::parse($bimbingan->tanggal)->format('d M Y') . ' telah ditolak.';
            } else {
                // $request->status === 'disetujui'
                if ($oldStatus === 'menunggu_verifikasi') {
                    $title = '⚠️ Bukti Bimbingan Ditolak / Revisi';
                    $msg   = 'Bukti untuk bimbingan tanggal ' . Carbon::parse($bimbingan->tanggal)->format('d M Y') . ' ditolak. Silakan upload ulang bukti yang benar.';
                } else {
                    $title = '👍 Bimbingan Disetujui';
                    $msg   = 'Pengajuan bimbingan tanggal ' . Carbon::parse($bimbingan->tanggal)->format('d M Y') . ' disetujui. Jangan lupa upload bukti setelah bimbingan.';
                }
            }

            Notification::create([
                'user_id'    => $mahasiswa->user_id,
                'title'      => $title,
                'message'    => $msg,
                'is_read'    => false,
                'created_at' => now(),
            ]);
        }

        return back()->with('success', 'Status bimbingan diperbarui menjadi ' . ucfirst($request->status) . '.');
    }

    // ─── Update Status Milestone ─────────────────────────────────────────────
    public function updateMilestoneStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);

        $milestone = Milestone::with('tugasAkhir.mahasiswa')->findOrFail($id);
        $dosen     = Auth::user()->dosen;
        $mahasiswa = optional($milestone->tugasAkhir)->mahasiswa;

        if (!$mahasiswa ||
            ($mahasiswa->pembimbing1_id !== $dosen->id && $mahasiswa->pembimbing2_id !== $dosen->id)) {
            abort(403);
        }

        $updateData = ['status' => $request->status];
        if ($request->status === 'disetujui') {
            $updateData['tanggal_disetujui'] = now();
        }
        if ($request->catatan) {
            $updateData['catatan_revisi'] = $request->catatan;
        }

        $milestone->update($updateData);

        // Kirim notifikasi ke mahasiswa
        if ($mahasiswa) {
            Notification::create([
                'user_id'    => $mahasiswa->user_id,
                'title'      => $request->status === 'disetujui' ? 'Milestone Disetujui' : 'Milestone Ditolak',
                'message'    => 'Milestone "' . $milestone->jenis_milestone . '" Anda telah ' .
                                ($request->status === 'disetujui' ? 'disetujui.' : 'ditolak.'),
                'is_read'    => false,
                'created_at' => now(),
            ]);
        }

        // Kirim notifikasi ke Admin jika milestone disetujui
        if ($request->status === 'disetujui') {
            $admins = \App\Models\User::whereHas('role', function($q) {
                $q->where('name', 'admin');
            })->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id'    => $admin->id,
                    'title'      => '📝 BAP Milestone Perlu Diisi',
                    'message'    => 'Dosen ' . $dosen->user->name . ' telah memverifikasi milestone "' . $milestone->jenis_milestone . '" milik mahasiswa ' . ($mahasiswa->user->name ?? 'N/A') . '. Mohon segera unggah file BAP.',
                    'is_read'    => false,
                    'created_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Status milestone diperbarui menjadi ' . $request->status . '.');
    }

    // ─── Kirim Pengingat ke Mahasiswa ────────────────────────────────────────
    public function kirimPengingat(Request $request, $id)
    {
        $dosen     = Auth::user()->dosen;
        $mahasiswa = Mahasiswa::findOrFail($id);

        if ($mahasiswa->pembimbing1_id !== $dosen->id && $mahasiswa->pembimbing2_id !== $dosen->id) {
            abort(403);
        }

        Notification::create([
            'user_id'    => $mahasiswa->user_id,
            'title'      => '📢 Pengingat dari Dosen Pembimbing',
            'message'    => $request->pesan ?? 'Mohon segera melakukan bimbingan. Harap segera menghubungi dosen pembimbing Anda.',
            'is_read'    => false,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Pengingat berhasil dikirim ke mahasiswa.');
    }

    // ─── Notifikasi ──────────────────────────────────────────────────────────
    public function notifikasi()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($n) => Carbon::parse($n->created_at)->format('d F Y'));

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('dosen.d-notifikasi', compact('notifications'));
    }

    public function markNotifRead(Request $request)
    {
        $ids = $request->ids ?? [];
        $query = Notification::where('user_id', Auth::id())->where('is_read', false);
        if (!empty($ids)) $query->whereIn('id', $ids);
        $query->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function getUnreadNotifs()
    {
        $notifs = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'message', 'created_at']);
        return response()->json($notifs);
    }
}