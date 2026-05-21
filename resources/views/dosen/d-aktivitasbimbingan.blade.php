@extends('layouts.dosen')

@section('title','Detail Aktivitas Bimbingan')

@section('page-content')

<!-- Modal Log Bimbingan -->
<div id="logModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Detail Bimbingan</h4>
            <span class="close-modal" onclick="closeLogModal()">✖</span>
        </div>
        <div class="modal-body">
            <div class="log-grid">
                <div class="label">Nama Kegiatan</div><div class="colon">:</div><div class="value" id="log-kegiatan">-</div>
                <div class="label">Rencana Bimbingan</div><div class="colon">:</div><div class="value" id="log-bimbingan">-</div>
                <div class="label">Tipe</div><div class="colon">:</div><div class="value" id="log-tempat">-</div>
                <div class="label">Durasi</div><div class="colon">:</div><div class="value" id="log-durasi">-</div>
                <div class="label">Catatan Dosen</div><div class="colon">:</div><div class="value" id="log-topik">-</div>
                <div class="label">Status</div><div class="colon">:</div><div class="value" id="log-status">-</div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Kirim Pengingat --}}
<div id="reminderModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>📢 Kirim Pengingat</h4>
            <span class="close-modal" onclick="closeReminderModal()">✖</span>
        </div>
        <form id="reminderForm" method="POST">
            @csrf
            <div class="modal-body">
                <p style="font-size:14px; margin-bottom:12px; text-align: left;">Kirim pengingat ke: <strong id="reminderNama"></strong></p>
                <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block; text-align: left;">Pesan:</label>
                <textarea name="pesan" class="form-control" rows="3"
                    placeholder="Contoh: Mohon segera melakukan bimbingan...">Mohon segera melakukan bimbingan. Harap menghubungi dosen pembimbing Anda secepatnya.</textarea>
            </div>
            <div class="modal-footer-log mt-3" style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn-close-log" onclick="closeReminderModal()" style="background:#858796; color:white; border:none; padding:8px 18px; border-radius:8px; cursor:pointer;">Batal</button>
                <button type="submit" class="btn-acc" style="background:#1cc88a; color:white; border:none; padding:8px 18px; border-radius:8px; cursor:pointer;">Kirim</button>
            </div>
        </form>
    </div>
</div>

<div class="page-wrapper">
    <h2 class="page-title">Detail Aktivitas Bimbingan</h2>

    {{--  Mahasiswa > 30 hari --}}
    <div class="card-box">
        <div class="card-header">
            <span>🔔 Mahasiswa Tidak Bimbingan > 30 Hari</span>
            <span class="badge-danger">{{ $tidakBimbingan30->count() }}</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Terakhir Bimbingan</th>
                    <th>Milestone</th>
                    <th>Target Milestone</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tidakBimbingan30 as $mhs)
                @php
                    $roleLabel = ($mhs->pembimbing1_id == $dosen->id) ? 'P1' : 'P2';
                    $roleClass = ($mhs->pembimbing1_id == $dosen->id) ? 'p1' : 'p2';
                    $tglTerakhir = $mhs->last_bimbingan ? \Carbon\Carbon::parse($mhs->last_bimbingan->tanggal)->format('d M Y') : 'Belum Pernah';
                    $milestoneActive = $mhs->active_milestone ? $mhs->active_milestone->jenis_milestone : '-';
                    $targetDate = ($mhs->active_milestone && $mhs->active_milestone->deadline) ? \Carbon\Carbon::parse($mhs->active_milestone->deadline)->format('d M Y') : 'Belum Atur';
                @endphp
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>
                        <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        {{ $mhs->user->name }}
                    </td>
                    <td>{{ $tglTerakhir }}</td>
                    <td>{{ $milestoneActive }}</td>
                    <td>{{ $targetDate }}</td>
                    <td><span class="status-badge pending">Belum</span></td>
                    <td onclick="event.stopPropagation();">
                        <button type="button" class="btn-remind" title="Ingatkan Mahasiswa" onclick="openReminderModal({{ $mhs->id }}, '{{ addslashes($mhs->user->name) }}')">
                            <i class="fas fa-bell"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="font-weight: 500; padding:20px;">Semua mahasiswa secara aktif melakukan bimbingan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Bimbingan bulan ini --}}
    <div class="card-box">
        <div class="card-header">
            <span>📅 Bimbingan Bulan Ini</span>
            <span class="badge-success">{{ $bimbinganBulanIniList->count() }}</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Terakhir Bimbingan</th>
                    <th>Milestone</th>
                    <th>Target Milestone</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbinganBulanIniList as $bim)
                @php
                    $mhs = $bim->tugasAkhir->mahasiswa;
                    $roleLabel = ($mhs->pembimbing1_id == $dosen->id) ? 'P1' : 'P2';
                    $roleClass = ($mhs->pembimbing1_id == $dosen->id) ? 'p1' : 'p2';
                    $activeMilestone = $bim->tugasAkhir->milestones->whereIn('status', ['pending', 'menunggu_verifikasi'])->first();
                    $msName = $activeMilestone ? $activeMilestone->jenis_milestone : '-';
                    $targetDate = ($activeMilestone && $activeMilestone->deadline) ? \Carbon\Carbon::parse($activeMilestone->deadline)->format('d M Y') : 'Belum Atur';
                    
                    $logData = [
                        'nim' => $mhs->nim ?? '-',
                        'name' => $mhs->user->name ?? '-',
                        'role' => $roleLabel,
                        'roleClass' => $roleClass,
                        'kegiatan' => $bim->nama_dokumen ?? $bim->nama_kegiatan ?? $bim->deskripsi ?? '-',
                        'tgl_bimbingan' => \Carbon\Carbon::parse($bim->tanggal)->format('d M Y'),
                        'tempat' => $bim->tipe_penyelenggaraan ?? '-',
                        'durasi' => ($bim->durasi_jam ?? '-') . ' Jam',
                        'topik' => $bim->catatan ?? '-',
                        'dokumen' => $bim->file_dokumen ? asset('storage/' . $bim->file_dokumen) : null,
                        'status' => ucfirst(str_replace('_', ' ', $bim->status))
                    ];
                @endphp
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>
                        <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        {{ $mhs->user->name }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($bim->tanggal)->format('d M Y') }}</td>
                    <td>{{ $msName }}</td>
                    <td>{{ $targetDate }}</td>
                    <td><span class="status-badge done">Sudah</span></td>
                    <td onclick="event.stopPropagation();">
                        <button class="btn-view-log" data-log="{{ base64_encode(json_encode($logData)) }}" onclick="openLogModal(this)">
                            👁 Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="font-weight: 500; padding:20px;">Belum ada aktivitas bimbingan bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dosen/dosen-shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dosen/d-aktivitasbimbingan.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dosen/d-aktivitasbimbingan.js') }}"></script>
    <script>
    function openReminderModal(id, nama) {
        document.getElementById('reminderNama').textContent = nama;
        document.getElementById('reminderForm').action = '/dosen/kirim-pengingat/' + id;
        document.getElementById('reminderModal').style.display = 'flex';
    }
    function closeReminderModal() {
        document.getElementById('reminderModal').style.display = 'none';
    }
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('reminderModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    </script>
    <style>
    .form-control { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; resize: vertical; }
    </style>
@endpush