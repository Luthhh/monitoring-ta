<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Models\Milestone;
use App\Models\Bimbingan;
use App\Models\Notification;
use Carbon\Carbon;

class MahasiswaController extends Controller
{
    private $milestoneMapping = [
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

    // ─── Dashboard Mahasiswa ─────────────────────────────────────────────────
    /**
     * Menampilkan Dashboard Mahasiswa.
     * 
     * Mengambil data Tugas Akhir, Milestone, dan riwayat Bimbingan mahasiswa yang sedang login.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;
        
        $milestones = collect();
        $bimbingans = collect();
        
        if ($tugasAkhir) {
            $milestones = $tugasAkhir->milestones->keyBy('jenis_milestone');
            $bimbingans = $tugasAkhir->bimbingans()->with('dosen.user')->latest()->get();
        }

        $milestoneMapping = $this->milestoneMapping;

        return view('mahasiswa.m-dashboard', compact('user', 'mahasiswa', 'tugasAkhir', 'milestones', 'milestoneMapping', 'bimbingans'));
    }

    // ─── Profil Mahasiswa ────────────────────────────────────────────────────
    /**
     * Menampilkan Profil Mahasiswa.
     * 
     * Mengambil data user, detail mahasiswa, dan daftar dosen untuk pilihan pembimbing.
     */
    public function profile()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;
        $dosens = Dosen::with('user')->get();

        return view('mahasiswa.m-profile', compact('user', 'mahasiswa', 'tugasAkhir', 'dosens'));
    }

    // ─── Perbarui Profil Mahasiswa ───────────────────────────────────────────
    /**
     * Memperbarui Profil Mahasiswa.
     * 
     * Mengupdate data dasar user, password (opsional), SK Pembimbing (PDF), dan Foto Profil.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'sk_pembimbing' => 'nullable|file|mimes:pdf|max:2048',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->hasFile('sk_pembimbing')) {
            // Hapus file lama jika ada
            if ($mahasiswa->sk_pembimbing) {
                Storage::disk('public')->delete($mahasiswa->sk_pembimbing);
            }
            $path = $request->file('sk_pembimbing')->store('sk-pembimbing', 'public');
            $mahasiswa->update(['sk_pembimbing' => $path]);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
            $path = $request->file('foto')->store('profile-photos', 'public');
            $mahasiswa->update(['foto' => $path]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ─── Perbarui Dosen Pembimbing ───────────────────────────────────────────
    /**
     * Perbarui Dosen Pembimbing.
     * 
     * Mengubah data Dosen Pembimbing 1 dan Dosen Pembimbing 2 untuk mahasiswa yang bersangkutan.
     */
    public function updatePembimbing(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $request->validate([
            'pembimbing1_id' => 'nullable|exists:dosens,id',
            'pembimbing2_id' => 'nullable|exists:dosens,id|different:pembimbing1_id',
        ]);

        $mahasiswa->update([
            'pembimbing1_id' => $request->pembimbing1_id,
            'pembimbing2_id' => $request->pembimbing2_id,
        ]);

        return back()->with('success', 'Dosen pembimbing berhasil diperbarui.');
    }

    // ─── Perbarui Judul Tugas Akhir ─────────────────────────────────────────
    /**
     * Perbarui Judul Tugas Akhir.
     * 
     * Mengubah atau menginisialisasi judul Tugas Akhir mahasiswa.
     */
    public function updateJudul(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $ta = $mahasiswa->tugasAkhir;

        if (!$ta) {
            $ta = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $request->judul,
                'status' => 'Proses',
            ]);
        } else {
            $ta->update(['judul' => $request->judul]);
        }

        return back()->with('success', 'Judul Tugas Akhir berhasil diperbarui.');
    }

    // ─── Tambah Pengajuan Bimbingan ──────────────────────────────────────────
    /**
     * Halaman Tambah Bimbingan.
     * 
     * Menampilkan form pengajuan jadwal bimbingan baru dengan pilihan dosen pembimbing yang bersangkutan.
     */
    public function createBimbingan()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $dosens = Dosen::whereIn('id', [$mahasiswa->pembimbing1_id, $mahasiswa->pembimbing2_id])
            ->with('user')->get();

        return view('mahasiswa.tambah-bimbingan', compact('mahasiswa', 'dosens'));
    }

    // ─── Simpan Pengajuan Bimbingan ──────────────────────────────────────────
    /**
     * Menyimpan Pengajuan Bimbingan Baru.
     * 
     * Mahasiswa mengajukan jadwal bimbingan ke dosen pembimbing. Otomatis membuat Tugas Akhir jika belum ada.
     */
    public function storeBimbingan(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = $mahasiswa->tugasAkhir;

        if (!$ta) {
            $ta = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => 'Belum ada judul',
                'status' => 'Proses',
            ]);
        }

        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'tempat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $bimbingan = Bimbingan::create([
            'tugas_akhir_id' => $ta->id,
            'dosen_id' => $request->dosen_id,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'tempat' => $request->tempat,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        $dosen = \App\Models\Dosen::find($request->dosen_id);
        if ($dosen) {
            Notification::create([
                'user_id' => $dosen->user_id,
                'title' => '📝 Pengajuan Bimbingan Baru',
                'message' => 'Mahasiswa ' . Auth::user()->name . ' mengajukan jadwal bimbingan pada ' . \Carbon\Carbon::parse($request->tanggal)->format('d M Y') . '.',
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Bimbingan berhasil dicatat/diajukan.');
    }

    // ─── Upload Bukti Bimbingan ──────────────────────────────────────────────
    /**
     * Mengunggah Bukti Pelaksanaan Bimbingan.
     * 
     * Mengupload file dokumen bukti bimbingan dan mengubah status menjadi 'menunggu_verifikasi'.
     */
    public function uploadBuktiBimbingan(Request $request, $id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'link_kegiatan' => 'nullable|url',
            'catatan_mahasiswa' => 'nullable|string',
        ]);

        if ($request->hasFile('file_dokumen')) {
            $path = $request->file('file_dokumen')->store('bukti-bimbingan', 'public');
            
            $bimbingan->update([
                'nama_dokumen' => $request->nama_dokumen,
                'file_dokumen' => $path,
                'link_kegiatan' => $request->link_kegiatan,
                'catatan_mahasiswa' => $request->catatan_mahasiswa,
                'status' => 'menunggu_verifikasi',
            ]);

            $dosen = \App\Models\Dosen::find($bimbingan->dosen_id);
            if ($dosen) {
                Notification::create([
                    'user_id' => $dosen->user_id,
                    'title' => '📄 Bukti Bimbingan Diunggah',
                    'message' => 'Mahasiswa ' . Auth::user()->name . ' telah mengunggah bukti bimbingan. Menunggu verifikasi Anda.',
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            return back()->with('success', 'Bukti bimbingan berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload bukti.');
    }

    // ─── Hapus Pengajuan Bimbingan ───────────────────────────────────────────
    /**
     * Hapus Pengajuan Bimbingan.
     * 
     * Menghapus pengajuan bimbingan yang masih berstatus 'pending'.
     */
    public function deleteBimbingan($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        if ($bimbingan->status === 'pending') {
            $bimbingan->delete();
            return back()->with('success', 'Pengajuan bimbingan berhasil dihapus.');
        }
        return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat dihapus.');
    }

    // ─── Perbarui Timeline Milestone ─────────────────────────────────────────
    /**
     * Perbarui Timeline Milestone.
     * 
     * Mengubah atau menetapkan tanggal tenggat waktu (deadline) untuk setiap tahapan milestone tugas akhir.
     */
    public function updateTimeline(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = $mahasiswa->tugasAkhir;

        if (!$ta) {
            $ta = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => 'Belum ada judul',
                'status' => 'Proses',
            ]);
        }

        $request->validate([
            'milestones' => 'required|array',
            'milestones.*' => 'nullable|date',
        ]);

        foreach ($request->milestones as $jenis => $tanggal) {
            if (!$tanggal) continue;

            $milestone = Milestone::where('tugas_akhir_id', $ta->id)
                ->where('jenis_milestone', $jenis)
                ->first();

            if ($milestone) {
                if ($milestone->status !== 'disetujui') {
                    $milestone->update(['deadline' => $tanggal]);
                }
            } else {
                Milestone::create([
                    'tugas_akhir_id' => $ta->id,
                    'jenis_milestone' => $jenis,
                    'deadline' => $tanggal,
                    'status' => 'pending',
                ]);
            }
        }

        return back()->with('success', 'Seluruh timeline berhasil diperbarui.');
    }

    // ─── Upload Bukti Milestone ──────────────────────────────────────────────
    /**
     * Mengunggah Bukti Capaian Milestone.
     * 
     * Mahasiswa mengupload bukti file (PDF/Gambar) untuk milestone tertentu (misal: Kolokium, Proposal).
     * Membutuhkan minimal 1 bimbingan yang sudah 'selesai'.
     */
    public function uploadVerifikasi(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = $mahasiswa->tugasAkhir;

        $request->validate([
            'milestone' => 'required',
            'bukti_file' => 'required|array|max:2',
            'bukti_file.*' => 'file|mimes:pdf,jpg,jpeg,png|max:4096',
            'catatan' => 'nullable|string',
        ]);

        // Cek syarat minimal bimbingan selesai (1 bimbingan per milestone)
        $milestoneName = $request->milestone;
        $milestoneId = array_search($milestoneName, $this->milestoneMapping);

        if ($milestoneId === false) {
            return back()->with('error', 'Gagal! Milestone tidak valid.');
        }

        $jumlahBimbinganSelesai = $ta ? $ta->bimbingans()->where('status', 'selesai')->count() : 0;
        
        if ($jumlahBimbinganSelesai < $milestoneId) {
            return back()->with('error', 'Gagal! Anda harus melakukan minimal ' . $milestoneId . ' kali bimbingan yang sudah diverifikasi (Selesai) sebelum dapat mengupload bukti milestone "' . $milestoneName . '". Saat ini Anda baru memiliki ' . $jumlahBimbinganSelesai . ' bimbingan selesai.');
        }

        if ($request->hasFile('bukti_file')) {
            $paths = [];
            foreach ($request->file('bukti_file') as $file) {
                $paths[] = $file->store('bukti-milestone', 'public');
            }
            
            $milestone = Milestone::where('tugas_akhir_id', $ta->id)
                ->where('jenis_milestone', $request->milestone)
                ->first();

            if ($milestone) {
                $milestone->update([
                    'file_path' => $paths,
                    'catatan_revisi' => $request->catatan,
                    'status' => 'menunggu_verifikasi',
                    'tanggal_upload' => now(),
                ]);
            } else {
                Milestone::create([
                    'tugas_akhir_id' => $ta->id,
                    'jenis_milestone' => $request->milestone,
                    'file_path' => $paths,
                    'catatan_revisi' => $request->catatan,
                    'status' => 'menunggu_verifikasi',
                    'tanggal_upload' => now(),
                ]);
            }

            $dosen1 = \App\Models\Dosen::find($mahasiswa->pembimbing1_id);
            $dosen2 = \App\Models\Dosen::find($mahasiswa->pembimbing2_id);

            $msg = 'Mahasiswa ' . Auth::user()->name . ' telah mengunggah bukti milestone ' . $request->milestone . '. Menunggu verifikasi Anda.';
            
            if ($dosen1) {
                Notification::create([
                    'user_id' => $dosen1->user_id,
                    'title' => '🏆 Bukti Milestone Baru',
                    'message' => $msg,
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }
            if ($dosen2) {
                Notification::create([
                    'user_id' => $dosen2->user_id,
                    'title' => '🏆 Bukti Milestone Baru',
                    'message' => $msg,
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            return back()->with('success', 'Bukti milestone berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload bukti.');
    }

    // ─── Halaman Notifikasi Mahasiswa ────────────────────────────────────────
    /**
     * Halaman Notifikasi Mahasiswa.
     * 
     * Menampilkan riwayat notifikasi untuk mahasiswa dan otomatis menandai semuanya telah dibaca.
     */
    public function notifikasi()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('d F Y');
            });

        Notification::where('user_id', $user->id)->update(['is_read' => true]);

        return view('mahasiswa.m-notifikasi', compact('notifications'));
    }

    // ─── Tandai Notifikasi Dibaca ────────────────────────────────────────────
    /**
     * Tandai Notifikasi Dibaca.
     * 
     * Mengubah status notifikasi yang dipilih oleh mahasiswa menjadi telah dibaca.
     */
    public function markNotifRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->whereIn('id', $request->ids ?? [])
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    // ─── Ambil Notifikasi Belum Dibaca ───────────────────────────────────────
    /**
     * Ambil Notifikasi Belum Dibaca.
     * 
     * Mengambil 10 notifikasi terbaru yang belum dibaca dalam format JSON untuk widget/dropdown.
     */
    public function getUnreadNotifs()
    {
        $notifs = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->take(10)
            ->get();
        return response()->json($notifs);
    }
}
