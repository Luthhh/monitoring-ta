<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Role;
use App\Models\TugasAkhir;
use App\Models\Milestone;
use App\Models\Bimbingan;
use App\Models\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use App\Exports\DosenExport;
use App\Imports\MahasiswaImport;

class AdminController extends Controller
{
    // ─── Klasifikasi Status Tugas Akhir Mahasiswa ────────────────────────────
    /**
     * Klasifikasi Status Tugas Akhir Mahasiswa.
     * 
     * Menentukan kategori kemajuan (Ahead, Ideal, Behind) untuk masing-masing mahasiswa dalam koleksi.
     */
    private function klasifikasiMahasiswas($mahasiswas)
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
                // Semua milestone sudah disetujui
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

    // ─── Dashboard ──────────────────────────────────────────────────────────
    /**
     * Dashboard Administrator.
     * 
     * Menampilkan statistik global mahasiswa (tepat waktu vs tidak tepat waktu), 
     * milestone yang tertunda, dan ringkasan aktivitas bimbingan bulanan.
     */
    public function dashboard()
    {
        $allMhs = Mahasiswa::with(['tugasAkhir.milestones', 'tugasAkhir.bimbingans'])->get();
        $this->klasifikasiMahasiswas($allMhs);

        $totalMahasiswa = $allMhs->count();

        // Group into Tepat Waktu vs Tidak Tepat Waktu for the primary chart
        // Tepat Waktu: Semester <= 4 (Dalam Proses/Ahead/Ideal/Tepat Waktu Lulus)
        $groupTepatWaktuCount = $allMhs->filter(function($m) {
            $isLulusTepat = $m->tugasAkhir && $m->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0 && $m->semester <= 4;
            $isOnTrack = $m->semester <= 4 && ($m->status_ta === 'ahead' || $m->status_ta === 'ideal');
            return $isLulusTepat || $isOnTrack;
        })->count();

        $groupTidakTepatWaktuCount = $totalMahasiswa - $groupTepatWaktuCount;

        // Sync original variables for other uses if needed (keeping ahead/ideal/behind for small cards)
        $ahead  = $allMhs->where('status_ta', 'ahead')->count();
        $ideal  = $allMhs->where('status_ta', 'ideal')->count();
        $behind = $allMhs->where('status_ta', 'behind')->count();
        
        $mhsKritis = $allMhs->filter(function ($mhs) {
            // Mahasiswa semester 7 ke atas yang BELUM Seminar
            if ($mhs->semester >= 7) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                return !$hasSeminar;
            }
            return false;
        })->count();

        $mhsMendekati = $allMhs->filter(function ($mhs) {
            // Mahasiswa semester 8 ke atas yang SUDAH Seminar (tapi belum lulus SKL)
            if ($mhs->semester >= 8) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                $hasLulus = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0;
                return $hasSeminar && !$hasLulus;
            }
            // Mahasiswa semester 7 yang SUDAH Seminar
            if ($mhs->semester == 7) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                return $hasSeminar;
            }
            return false;
        })->count();
        $mhsTepatWaktu = $groupTepatWaktuCount;

        // Milestone stats per jenis untuk chart
        $milestoneNames = [
            'Penetapan Komisi Pembimbing','Sidang Komisi 1','Kolokium','Proposal',
            'Penelitian dan Bimbingan','Evaluasi dan Monitoring','Sidang Komisi 2',
            'Seminar','Publikasi Ilmiah','Ujian Tesis','SKL'
        ];
        $milestoneStats = [];
        foreach ($milestoneNames as $nama) {
            $milestoneStats[$nama] = [
                'sudah'   => Milestone::where('jenis_milestone', $nama)->where('status', 'disetujui')->count(),
                'belum'   => Milestone::where('jenis_milestone', $nama)->where('status', '!=', 'disetujui')->count(),
            ];
        }

        // Mahasiswa tidak bimbingan > 30 hari
        $tidakBimbingan30 = $allMhs->filter(function ($mhs) {
            $last = $mhs->last_bimbingan ?? null;
            return !$last || now()->diffInDays(Carbon::parse($last->tanggal)) > 30;
        })->count();

        $statsByYear = [];
        $mhsYears = $allMhs->pluck('tahun_masuk')->unique()->filter()->values();
        foreach ($mhsYears as $year) {
            $yearMhs = $allMhs->where('tahun_masuk', $year);
            
            // 1. Tepat Waktu group (Current criteria)
            $yGroupTepat = $yearMhs->filter(function($m) {
                $isLulusTepat = $m->tugasAkhir && $m->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0 && $m->semester <= 4;
                $isOnTrack = $m->semester <= 4 && ($m->status_ta === 'ahead' || $m->status_ta === 'ideal');
                return $isLulusTepat || $isOnTrack;
            })->count();

            $yGroupTidakTepat = $yearMhs->count() - $yGroupTepat;

            $statsByYear[$year] = [
                'tepat_waktu'       => $yGroupTepat,
                'tidak_tepat_waktu' => $yGroupTidakTepat,
                'milestones'        => []
            ];

            foreach ($milestoneNames as $nama) {
                $countSudah = $yearMhs->filter(function($m) use ($nama) {
                    return $m->tugasAkhir && $m->tugasAkhir->milestones->where('jenis_milestone', $nama)->where('status', 'disetujui')->count() > 0;
                })->count();
                $statsByYear[$year]['milestones'][$nama] = [
                    'sudah' => $countSudah,
                    'belum' => $yearMhs->count() - $countSudah
                ];
            }
        }

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)->count();

        // Bimbingan bulan ini (semua mahasiswa, disetujui, menunggu verifikasi, atau selesai)
        $bimbinganBulanIni = Bimbingan::whereIn('status', ['disetujui', 'menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Milestones yang sudah disetujui tapi belum ada file BAP
        $bapPending = Milestone::where('status', 'disetujui')
            ->whereNull('file_bap')
            ->where('jenis_milestone', '!=', 'SKL')
            ->with('tugasAkhir.mahasiswa.user')
            ->orderBy('tanggal_disetujui', 'desc')
            ->limit(5)
            ->get();

        $bapPendingCount = Milestone::where('status', 'disetujui')
            ->whereNull('file_bap')
            ->where('jenis_milestone', '!=', 'SKL')
            ->count();

        // Monthly Summary for cards (Global)
        $rencanaBimbinganBulanIni = Bimbingan::whereIn('status', ['pending', 'disetujui', 'menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $terlaksanaBulanIni = Bimbingan::whereIn('status', ['menunggu_verifikasi', 'selesai'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        $jumlahBimbinganAktif = Bimbingan::whereIn('status', ['pending', 'disetujui'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Data Chart Bimbingan (6 bulan terakhir - Global)
        $chartBimbingan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $monthName = $date->format('M');

            $planned = Bimbingan::whereIn('status', ['pending', 'disetujui', 'menunggu_verifikasi', 'selesai'])->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->count();
            $completed = Bimbingan::whereIn('status', ['menunggu_verifikasi', 'selesai'])->whereMonth('tanggal', $month)->whereYear('tanggal', $year)->count();

            $chartBimbingan[] = [
                'month' => $monthName,
                'planned' => $planned,
                'completed' => $completed
            ];
        }

        // Verifikasi Milestone & Bimbingan (Limit 5 untuk Dashboard)
        $verifikasiMilestones = Milestone::where('status', 'menunggu_verifikasi')
            ->with('tugasAkhir.mahasiswa.user')
            ->orderBy('tanggal_upload', 'desc')
            ->limit(5)
            ->get();
            
        $verifikasiBimbingans = Bimbingan::where('status', 'menunggu_verifikasi')
            ->with('tugasAkhir.mahasiswa.user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $totalVerifikasiMilestones = Milestone::where('status', 'menunggu_verifikasi')->count();
        $totalVerifikasiBimbingans = Bimbingan::where('status', 'menunggu_verifikasi')->count();

        return view('admin.a-dashboard', compact(
            'totalMahasiswa', 'ahead', 'ideal', 'behind',
            'mhsKritis', 'mhsMendekati', 'mhsTepatWaktu',
            'groupTepatWaktuCount', 'groupTidakTepatWaktuCount',
            'tidakBimbingan30', 'milestoneStats', 'statsByYear',
            'bimbinganBulanIni', 'unreadCount', 'bapPendingCount', 'bapPending', 
            'verifikasiMilestones', 'verifikasiBimbingans', 'totalVerifikasiMilestones', 'totalVerifikasiBimbingans',
            'rencanaBimbinganBulanIni', 'terlaksanaBulanIni', 'jumlahBimbinganAktif', 'chartBimbingan'
        ));
    }

    // ─── Laporan Mahasiswa Kritis ────────────────────────────────────────────
    /**
     * Laporan Mahasiswa Kritis.
     * 
     * Menampilkan daftar mahasiswa semester 7 ke atas yang belum melaksanakan Seminar.
     */
    public function kritisMahasiswa()
    {
        $allMhs = Mahasiswa::with(['tugasAkhir.milestones', 'user'])->get();
        $this->klasifikasiMahasiswas($allMhs);

        $mahasiswas = $allMhs->filter(function ($mhs) {
            // Mahasiswa semester 7 ke atas yang BELUM Seminar
            if ($mhs->semester >= 7) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                return !$hasSeminar;
            }
            return false;
        });

        return view('admin.a-kritismahasiswa', compact('mahasiswas'));
    }

    // ─── Laporan Batas Studi Mahasiswa ───────────────────────────────────────
    /**
     * Laporan Mahasiswa Mendekati Batas Studi.
     * 
     * Menampilkan mahasiswa semester 8+ yang sudah seminar tapi belum lulus, atau semester 7 yang sudah seminar.
     */
    public function batasStudiMahasiswa()
    {
        $allMhs = Mahasiswa::with(['tugasAkhir.milestones', 'user'])->get();
        $this->klasifikasiMahasiswas($allMhs);

        $mahasiswas = $allMhs->filter(function ($mhs) {
            // Mahasiswa semester 8 ke atas yang SUDAH Seminar (tapi belum lulus SKL)
            if ($mhs->semester >= 8) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                $hasLulus = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0;
                return $hasSeminar && !$hasLulus;
            }
            // Mahasiswa semester 7 yang SUDAH Seminar
            if ($mhs->semester == 7) {
                $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                return $hasSeminar;
            }
            return false;
        });

        return view('admin.a-batasstudi', compact('mahasiswas'));
    }

    // ─── Laporan Mahasiswa Lulus Tepat Waktu ─────────────────────────────────
    /**
     * Laporan Mahasiswa Lulus Tepat Waktu.
     * 
     * Menampilkan mahasiswa yang berhasil lulus (SKL) di semester 4 atau kurang.
     */
    public function ontrackMahasiswa()
    {
        $allMhs = Mahasiswa::with(['tugasAkhir.milestones', 'user'])->get();
        $this->klasifikasiMahasiswas($allMhs);

        $mahasiswas = $allMhs->filter(function ($mhs) {
            $isLulusTepat = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0 && $mhs->semester <= 4;
            $isOnTrack = $mhs->semester <= 4 && ($mhs->status_ta === 'ahead' || $mhs->status_ta === 'ideal');
            return $isLulusTepat || $isOnTrack;
        });

        return view('admin.a-ontrackmahasiswa', compact('mahasiswas'));
    }


    // ─── Halaman Notifikasi Admin ────────────────────────────────────────────
    /**
     * Halaman Notifikasi Admin.
     * 
     * Menampilkan riwayat notifikasi untuk admin dan otomatis menandai semuanya telah dibaca.
     */
    public function notifikasi()
    {
        $user = auth()->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($n) => \Carbon\Carbon::parse($n->created_at)->format('d F Y'));

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.a-notifikasi', compact('notifications'));
    }

    // ─── Tandai Notifikasi Dibaca ────────────────────────────────────────────
    /**
     * Tandai Notifikasi Dibaca.
     * 
     * Mengubah status notifikasi terpilih menjadi telah dibaca (is_read = true).
     */
    public function markNotifRead(Request $request)
    {
        $ids = $request->ids ?? [];
        $query = Notification::where('user_id', auth()->id())->where('is_read', false);
        if (!empty($ids)) $query->whereIn('id', $ids);
        $query->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    // ─── Ambil Notifikasi Belum Dibaca ───────────────────────────────────────
    /**
     * Ambil Notifikasi Belum Dibaca.
     * 
     * Mengambil maksimal 10 data notifikasi terbaru admin yang berstatus belum dibaca.
     */
    public function getUnreadNotifs()
    {
        $notifs = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'message', 'created_at']);
        return response()->json($notifs);
    }

    // ─── Manajemen Data Dosen ────────────────────────────────────────────────
    /**
     * Manajemen Data Dosen.
     * 
     * Mengelola akun dosen pembimbing (CRUD) dan menampilkan daftar dosen beserta NIP dan Prodi.
     */
    public function index(Request $request)
    {
        $query = Dosen::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('nip', 'like', "%$search%");
        }

        $dosens = $query->paginate(10)->withQueryString();
        return view('admin.a-manajemendosen', compact('dosens'));
    }

    // ─── Tambah Data Dosen ───────────────────────────────────────────────────
    /**
     * Tambah Data Dosen.
     * 
     * Mendaftarkan dosen baru beserta akun pengguna, NIP, dan Program Studi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip'      => 'required|unique:dosens',
            'name'     => 'required',
            'email'    => ['required', 'email', 'unique:users', 'regex:/@apps\.ipb\.ac\.id$/i'],
            'prodi'    => 'required',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.regex' => 'Email harus menggunakan domain institusi (@apps.ipb.ac.id).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, huruf kecil, dan angka.',
        ]);

        $roleDosen = Role::where('name', 'dosen')->first();
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $roleDosen->id,
        ]);
        Dosen::create(['user_id' => $user->id, 'nip' => $request->nip, 'prodi' => $request->prodi]);

        return back()->with('success', 'Dosen berhasil ditambahkan');
    }

    // ─── Perbarui Data Dosen ─────────────────────────────────────────────────
    /**
     * Perbarui Data Dosen.
     * 
     * Mengubah data biodata dosen (NIP, nama, prodi, email) beserta kata sandi opsional.
     */
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $request->validate([
            'nip'      => 'required|unique:dosens,nip,' . $dosen->id,
            'name'     => 'required',
            'email'    => ['required', 'email', 'unique:users,email,' . $dosen->user->id, 'regex:/@apps\.ipb\.ac\.id$/i'],
            'prodi'    => 'required',
            'password' => ['nullable', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.regex' => 'Email harus menggunakan domain institusi (@apps.ipb.ac.id).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, huruf kecil, dan angka.',
        ]);

        $dosen->update(['nip' => $request->nip, 'prodi' => $request->prodi]);
        $dosen->user->update(['name' => $request->name, 'email' => $request->email]);
        if ($request->filled('password')) {
            $dosen->user->update(['password' => Hash::make($request->password)]);
        }
        return back()->with('success', 'Data dosen berhasil diupdate');
    }

    // ─── Hapus Data Dosen ────────────────────────────────────────────────────
    /**
     * Hapus Data Dosen.
     * 
     * Menghapus data dosen pembimbing beserta akun pengguna (user) terkait.
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->user()->delete();
        $dosen->delete();
        return back()->with('success', 'Dosen berhasil dihapus');
    }

    // ─── Manajemen Mahasiswa ─────────────────────────────────────────────────
    /**
     * Manajemen Data Mahasiswa.
     * 
     * Mengelola akun mahasiswa (CRUD), termasuk filtering berdasarkan tahun masuk, semester, dan nama pembimbing.
     */
    public function manajemenMahasiswa(Request $request)
    {
        $query = Mahasiswa::with(['user', 'pembimbing1.user', 'pembimbing2.user', 'tugasAkhir.milestones', 'tugasAkhir.bimbingans']);
        
        // Server-side Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($inner) use ($search) {
                    $inner->where('name', 'like', "%$search%");
                })->orWhere('nim', 'like', "%$search%");
            });
        }
        
        if ($request->filled('tahun')) {
            $query->where('tahun_masuk', $request->tahun);
        }
        
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('dosen')) {
            $dosenName = $request->dosen;
            $query->where(function($q) use ($dosenName) {
                $q->whereHas('pembimbing1.user', function($inner) use ($dosenName) {
                    $inner->where('name', 'like', "%$dosenName%");
                })->orWhereHas('pembimbing2.user', function($inner) use ($dosenName) {
                    $inner->where('name', 'like', "%$dosenName%");
                });
            });
        }

        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        $this->klasifikasiMahasiswas($mahasiswas->getCollection());

        $tahunMasukList = Mahasiswa::select('tahun_masuk')->distinct()->orderBy('tahun_masuk', 'desc')->pluck('tahun_masuk');
        $dosens = Dosen::with('user')->get();

        // Untuk statistik di atas, kita tetap butuh count keseluruhan
        // Tapi kita bisa gunakan query terpisah agar lebih ringan
        $allMhsForStats = Mahasiswa::with(['tugasAkhir.milestones'])->get();
        $this->klasifikasiMahasiswas($allMhsForStats);
        
        $ahead  = $allMhsForStats->where('status_ta', 'ahead')->count();
        $ideal  = $allMhsForStats->where('status_ta', 'ideal')->count();
        $behind = $allMhsForStats->where('status_ta', 'behind')->count();

        return view('admin.a-manajemenmahasiswa', compact('mahasiswas', 'tahunMasukList', 'dosens', 'ahead', 'ideal', 'behind'));
    }

    // ─── Tambah Data Mahasiswa ───────────────────────────────────────────────
    /**
     * Tambah Data Mahasiswa.
     * 
     * Mendaftarkan mahasiswa baru beserta akun pengguna, NIM, program studi, angkatan, dan semester.
     */
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nim'         => 'required|unique:mahasiswas',
            'name'        => 'required',
            'email'       => ['required', 'email', 'unique:users', 'regex:/@apps\.ipb\.ac\.id$/i'],
            'prodi'       => 'required',
            'tahun_masuk' => 'required',
            'semester'    => 'required|integer|min:1',
            'password'    => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.regex' => 'Email harus menggunakan domain institusi (@apps.ipb.ac.id).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, huruf kecil, dan angka.',
        ]);

        $roleMahasiswa = Role::where('name', 'mahasiswa')->first();
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $roleMahasiswa->id,
        ]);
        Mahasiswa::create([
            'user_id'     => $user->id,
            'nim'         => $request->nim,
            'prodi'       => $request->prodi,
            'tahun_masuk' => $request->tahun_masuk,
            'semester'    => $request->semester,
        ]);
        return back()->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    // ─── Perbarui Data Mahasiswa ─────────────────────────────────────────────
    /**
     * Perbarui Data Mahasiswa.
     * 
     * Mengubah data mahasiswa (NIM, nama, program studi, tahun masuk, semester) beserta password opsional.
     */
    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $request->validate([
            'nim'         => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'name'        => 'required',
            'email'       => ['required', 'email', 'unique:users,email,' . $mahasiswa->user->id, 'regex:/@apps\.ipb\.ac\.id$/i'],
            'prodi'       => 'required',
            'tahun_masuk' => 'required',
            'semester'    => 'required|integer|min:1',
            'password'    => ['nullable', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.regex' => 'Email harus menggunakan domain institusi (@apps.ipb.ac.id).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, huruf kecil, dan angka.',
        ]);

        $mahasiswa->update([
            'nim' => $request->nim, 'prodi' => $request->prodi,
            'tahun_masuk' => $request->tahun_masuk, 'semester' => $request->semester,
        ]);
        $mahasiswa->user->update(['name' => $request->name, 'email' => $request->email]);
        if ($request->filled('password')) {
            $mahasiswa->user->update(['password' => Hash::make($request->password)]);
        }
        return back()->with('success', 'Data mahasiswa berhasil diupdate');
    }

    // ─── Hapus Data Mahasiswa ────────────────────────────────────────────────
    /**
     * Hapus Data Mahasiswa.
     * 
     * Menghapus data mahasiswa dari sistem beserta akun pengguna (user) terkait.
     */
    public function destroyMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->user()->delete();
        $mahasiswa->delete();
        return back()->with('success', 'Mahasiswa berhasil dihapus');
    }

    // ─── Total Mahasiswa ─────────────────────────────────────────────────────
    /**
     * Tampilkan Halaman Total Mahasiswa.
     * 
     * Menampilkan daftar seluruh mahasiswa dengan informasi status, dosen pembimbing, dan milestone.
     */
    public function totalMahasiswa(Request $request)
    {
        $query = Mahasiswa::with(['user', 'tugasAkhir.milestones', 'tugasAkhir.bimbingans', 'pembimbing1.user', 'pembimbing2.user']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($inner) use ($search) {
                    $inner->where('name', 'like', "%$search%");
                })->orWhere('nim', 'like', "%$search%");
            });
        }
        
        if ($request->filled('tahun')) $query->where('tahun_masuk', $request->tahun);
        if ($request->filled('semester')) $query->where('semester', $request->semester);
        if ($request->filled('dosen')) {
            $dosenName = $request->dosen;
            $query->where(function($q) use ($dosenName) {
                $q->whereHas('pembimbing1.user', function($inner) use ($dosenName) {
                    $inner->where('name', 'like', "%$dosenName%");
                })->orWhereHas('pembimbing2.user', function($inner) use ($dosenName) {
                    $inner->where('name', 'like', "%$dosenName%");
                });
            });
        }

        $mahasiswas = $query->paginate(15)->withQueryString();
        $this->klasifikasiMahasiswas($mahasiswas->getCollection());
        
        $tahunMasukList = Mahasiswa::select('tahun_masuk')->distinct()->orderBy('tahun_masuk', 'desc')->pluck('tahun_masuk');
        $dosens = Dosen::with('user')->get();
        
        return view('admin.a-totalmahasiswa', compact('mahasiswas', 'tahunMasukList', 'dosens'));
    }

    // ─── Mahasiswa Kategori Ahead ────────────────────────────────────────────
    /**
     * Tampilkan Mahasiswa Ahead.
     * 
     * Menampilkan daftar mahasiswa yang progres tugas akhirnya tergolong cepat (Ahead).
     */
    public function aheadMahasiswa()
    {
        $mahasiswas = Mahasiswa::with(['user', 'tugasAkhir.milestones', 'tugasAkhir.bimbingans'])->get();
        $this->klasifikasiMahasiswas($mahasiswas);
        $mahasiswas = $mahasiswas->where('status_ta', 'ahead')->values();
        return view('admin.a-aheadmahasiswa', compact('mahasiswas'));
    }

    // ─── Mahasiswa Kategori Ideal ────────────────────────────────────────────
    /**
     * Tampilkan Mahasiswa Ideal.
     * 
     * Menampilkan daftar mahasiswa yang progres tugas akhirnya tergolong tepat waktu (Ideal).
     */
    public function idealMahasiswa()
    {
        $mahasiswas = Mahasiswa::with(['user', 'tugasAkhir.milestones', 'tugasAkhir.bimbingans'])->get();
        $this->klasifikasiMahasiswas($mahasiswas);
        $mahasiswas = $mahasiswas->where('status_ta', 'ideal')->values();
        return view('admin.a-idealmahasiswa', compact('mahasiswas'));
    }

    // ─── Mahasiswa Kategori Behind ───────────────────────────────────────────
    /**
     * Tampilkan Mahasiswa Behind.
     * 
     * Menampilkan daftar mahasiswa yang progres tugas akhirnya tergolong terlambat (Behind).
     */
    public function behindMahasiswa()
    {
        $mahasiswas = Mahasiswa::with(['user', 'tugasAkhir.milestones', 'tugasAkhir.bimbingans'])->get();
        $this->klasifikasiMahasiswas($mahasiswas);
        $mahasiswas = $mahasiswas->where('status_ta', 'behind')->values();
        return view('admin.a-behindmahasiswa', compact('mahasiswas'));
    }

    // ─── Aktivitas Bimbingan Mahasiswa ───────────────────────────────────────
    /**
     * Aktivitas Bimbingan Mahasiswa.
     * 
     * Menampilkan daftar mahasiswa yang pasif atau tidak bimbingan selama lebih dari 30 hari.
     */
    public function aktivitasBimbingan()
    {
        $mahasiswas = Mahasiswa::with(['user', 'tugasAkhir.milestones', 'tugasAkhir.bimbingans'])->get();
        $this->klasifikasiMahasiswas($mahasiswas);

        $tidakBimbingan30 = $mahasiswas->filter(function ($mhs) {
            $last = $mhs->last_bimbingan ?? null;
            return !$last || now()->diffInDays(Carbon::parse($last->tanggal)) > 30;
        });

        return view('admin.a-aktivitasbimbingan', compact('tidakBimbingan30'));
    }

    // ─── Detail Informasi Mahasiswa ──────────────────────────────────────────
    /**
     * Detail Informasi Mahasiswa.
     * 
     * Melihat riwayat lengkap log bimbingan dan status milestone untuk mahasiswa tertentu secara mendalam.
     */
    public function detailMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::with([
            'user', 'pembimbing1.user', 'pembimbing2.user',
            'tugasAkhir.milestones', 'tugasAkhir.bimbingans.dosen.user'
        ])->findOrFail($id);

        $tugasAkhir = $mahasiswa->tugasAkhir;
        $bimbingans = $tugasAkhir ? $tugasAkhir->bimbingans->sortByDesc('created_at') : collect();
        $milestones = $tugasAkhir ? $tugasAkhir->milestones->keyBy('jenis_milestone') : collect();

        return view('admin.a-detailmahasiswa', compact('mahasiswa', 'tugasAkhir', 'bimbingans', 'milestones'));
    }

    // ─── Import / Export ─────────────────────────────────────────────────────
    /**
     * Export Data Mahasiswa ke Excel.
     * 
     * Mengunduh data mahasiswa berdasarkan kategori tertentu (kritis, ahead, ideal, behind, dll).
     */
    public function exportMahasiswa(Request $request)
    {
        $kategori = $request->query('kategori');
        $collection = null;

        if ($kategori) {
            $allMhs = Mahasiswa::with(['user', 'pembimbing1.user', 'pembimbing2.user', 'tugasAkhir.milestones'])->get();
            $this->klasifikasiMahasiswas($allMhs);

            if ($kategori == 'kritis') {
                $collection = $allMhs->filter(function ($mhs) {
                    // Mahasiswa semester 7 ke atas yang BELUM Seminar
                    if ($mhs->semester >= 7) {
                        $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                        return !$hasSeminar;
                    }
                    return false;
                });
            } elseif ($kategori == 'ahead') {
                $collection = $allMhs->where('status_ta', 'ahead');
            } elseif ($kategori == 'ideal') {
                $collection = $allMhs->where('status_ta', 'ideal');
            } elseif ($kategori == 'behind') {
                $collection = $allMhs->where('status_ta', 'behind');
            } elseif ($kategori == 'ontrack') {
                $collection = $allMhs->filter(function ($mhs) {
                    if ($mhs->semester <= 4) {
                        $hasLulus = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0;
                        return $hasLulus;
                    }
                    return false;
                });
            } elseif ($kategori == 'batas-studi') {
                $collection = $allMhs->filter(function ($mhs) {
                    // Mahasiswa semester 8 ke atas yang SUDAH Seminar (tapi belum lulus SKL)
                    if ($mhs->semester >= 8) {
                        $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                        $hasLulus = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'SKL')->where('status', 'disetujui')->count() > 0;
                        return $hasSeminar && !$hasLulus;
                    }
                    // Mahasiswa semester 7 yang SUDAH Seminar
                    if ($mhs->semester == 7) {
                        $hasSeminar = $mhs->tugasAkhir && $mhs->tugasAkhir->milestones->where('jenis_milestone', 'Seminar')->where('status', 'disetujui')->count() > 0;
                        return $hasSeminar;
                    }
                    return false;
                });
            }
            
            if ($collection) {
                $collection = $collection->values();
            }
        }

        $filename = 'mahasiswa_' . ($kategori ? $kategori . '_' : '') . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new MahasiswaExport($collection), $filename);
    }

    // ─── Export Mahasiswa ke CSV ─────────────────────────────────────────────
    /**
     * Export Mahasiswa ke CSV.
     * 
     * Mengunduh seluruh data mahasiswa pembimbing dalam format CSV.
     */
    public function exportMahasiswaCsv()
    {
        return Excel::download(new MahasiswaExport(), 'mahasiswa_' . now()->format('Ymd_His') . '.csv',
            \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
    }

    // ─── Export Dosen ke Excel ───────────────────────────────────────────────
    /**
     * Export Dosen ke Excel.
     * 
     * Mengunduh seluruh data dosen pembimbing dalam format file Excel (.xlsx).
     */
    public function exportDosen()
    {
        return Excel::download(new DosenExport(), 'dosen_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Import Data Mahasiswa via Excel/CSV.
     * 
     * Menambahkan data mahasiswa secara massal menggunakan file template yang telah disediakan.
     */
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ], [
            'file.mimes' => 'File harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
        ]);

        try {
            $import = new MahasiswaImport();
            Excel::import($import, $request->file('file'));
            
            if (!empty($import->errors)) {
                return back()->withErrors($import->errors)->with('success', "Berhasil import {$import->importedCount} data.");
            }

            $msg = "Import berhasil. {$import->importedCount} data ditambahkan, {$import->skippedCount} baris dilewati.";
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->withErrors('Import gagal: ' . $e->getMessage());
        }
    }

    // ─── Download Template Import Mahasiswa ──────────────────────────────────
    /**
     * Download Template Import Mahasiswa.
     * 
     * Mengunduh template file CSV berisi kolom-kolom yang diperlukan untuk proses impor data mahasiswa secara massal.
     */
    public function templateImportMahasiswa()
    {
        $headers = ['nim', 'nama', 'email', 'prodi', 'tahun_masuk', 'semester', 'password'];
        $contoh  = ['J0403221001', 'Nama Mahasiswa', 'mahasiswa@email.com', 'Ilmu Komputer', '2022', '4', 'password123'];

        $callback = function () use ($headers, $contoh) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, $contoh);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_mahasiswa.csv"',
        ]);
    }

    // ─── Kirim Pengingat Admin ───────────────────────────────────────────────
    /**
     * Kirim Pengingat dari Admin.
     * 
     * Mengirimkan notifikasi pengingat kepada mahasiswa agar segera melakukan bimbingan atau memperbarui status.
     */
    public function kirimPengingat(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        Notification::create([
            'user_id'    => $mahasiswa->user_id,
            'title'      => '📢 Pengingat dari Administrator',
            'message'    => $request->pesan ?? 'Mohon segera melakukan bimbingan. Harap segera menghubungi dosen pembimbing Anda dan melaporkan progres tugas akhir.',
            'is_read'    => false,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Pengingat berhasil dikirim ke mahasiswa ' . $mahasiswa->user->name);
    }

    // ─── Halaman Verifikasi Milestone ────────────────────────────────────────
    /**
     * Halaman Verifikasi Milestone.
     * 
     * Menampilkan daftar milestone mahasiswa yang berstatus 'menunggu_verifikasi'.
     */
    public function verifikasiMilestone()
    {
        $milestones = Milestone::where('status', 'menunggu_verifikasi')
            ->with('tugasAkhir.mahasiswa.user')
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(15);
            
        return view('admin.verifikasi.milestone', compact('milestones'));
    }

    // ─── Halaman Verifikasi Bimbingan ────────────────────────────────────────
    /**
     * Halaman Verifikasi Bimbingan.
     * 
     * Menampilkan daftar bimbingan mahasiswa yang berstatus 'menunggu_verifikasi'.
     */
    public function verifikasiBimbingan()
    {
        $bimbingans = Bimbingan::where('status', 'menunggu_verifikasi')
            ->with('tugasAkhir.mahasiswa.user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
            
        return view('admin.verifikasi.bimbingan', compact('bimbingans'));
    }

    // ─── Halaman Verifikasi BAP ──────────────────────────────────────────────
    /**
     * Halaman Verifikasi BAP.
     * 
     * Menampilkan daftar milestone yang sudah disetujui dosen pembimbing tetapi belum diunggah BAP-nya oleh admin.
     */
    public function verifikasiBap()
    {
        $baps = Milestone::where('status', 'disetujui')
            ->whereNull('file_bap')
            ->where('jenis_milestone', '!=', 'SKL')
            ->with('tugasAkhir.mahasiswa.user')
            ->orderBy('tanggal_disetujui', 'desc')
            ->paginate(15);
            
        return view('admin.verifikasi.bap', compact('baps'));
    }


    // ─── Profile ─────────────────────────────────────────────────────────────
    /**
     * Profil Admin.
     * 
     * Menampilkan halaman profil administrator.
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.a-profile', compact('user'));
    }

    // ─── Perbarui Profil Admin ───────────────────────────────────────────────
    /**
     * Update Profil Admin.
     * 
     * Mengubah data nama, email, dan password administrator.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'     => 'required',
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id, 'regex:/@apps\.ipb\.ac\.id$/i'],
            'password' => ['nullable', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'email.regex' => 'Email harus menggunakan domain institusi (@apps.ipb.ac.id).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, huruf kecil, dan angka.',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return back()->with('success', 'Profil berhasil diupdate');
    }

    // ─── Unggah Berita Acara (BAP) ───────────────────────────────────────────
    /**
     * Unggah Berita Acara (BAP).
     * 
     * Admin mengunggah file BAP (PDF) untuk milestone yang sudah disetujui oleh dosen pembimbing.
     */
    public function uploadBap(Request $request, $id)
    {
        $request->validate([
            'file_bap' => 'required|file|mimes:pdf|max:4096',
        ]);

        $milestone = Milestone::findOrFail($id);

        if ($request->hasFile('file_bap')) {
            if ($milestone->file_bap) {
                Storage::disk('public')->delete($milestone->file_bap);
            }
            $path = $request->file('file_bap')->store('bap-milestone', 'public');
            $milestone->update(['file_bap' => $path]);

            // Notifikasi ke mahasiswa bahwa BAP sudah diupload
            if ($milestone->tugasAkhir && $milestone->tugasAkhir->mahasiswa) {
                Notification::create([
                    'user_id' => $milestone->tugasAkhir->mahasiswa->user_id,
                    'title' => '📄 BAP Tersedia',
                    'message' => 'BAP untuk milestone "' . $milestone->jenis_milestone . '" telah diunggah oleh admin. Silakan cek detail milestone Anda.',
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            return back()->with('success', 'BAP berhasil diunggah.');
        }

        return back()->with('error', 'Gagal mengunggah BAP.');
    }
}
