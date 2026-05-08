@extends('layouts.dosen')

@section('title', 'Data Mahasiswa')

@section('page-content')

<div class="main">
    <h2>Data Mahasiswa Bimbingan</h2>

    @if(session('success'))
        <div class="alert-success mb-3">✅ {{ session('success') }}</div>
    @endif

    <div class="table-tools">
        <input type="text" id="searchInput" placeholder="🔍 Cari nama / NIM...">
        <select id="sortStatus">
            <option value="">Semua Status</option>
            <option value="ahead">Ahead</option>
            <option value="ideal">Ideal</option>
            <option value="behind">Behind</option>
        </select>
        <select id="sortPeran">
            <option value="">Semua Peran</option>
            <option value="P1">Pembimbing 1</option>
            <option value="P2">Pembimbing 2</option>
        </select>
    </div>

    <div class="card">
        <table id="tabelMahasiswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th>Milestone Terakhir</th>
                    <th>Target Milestone Berikutnya</th>
                    <th>Status TA</th>
                    <th>Bimbingan Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $index => $mhs)
                @php
                    $isP1 = $mhs->pembimbing1_id == $dosen->id;
                    $lastMilestone = $mhs->last_milestone ?? null;
                    $lastBimbingan = $mhs->last_bimbingan ?? null;
                    $statusTA = $mhs->status_ta ?? 'behind';
                @endphp
                <tr data-status="{{ $statusTA }}" data-peran="{{ $isP1 ? 'P1' : 'P2' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                    <td><span class="role-badge {{ $isP1 ? 'p1' : 'p2' }}">{{ $isP1 ? 'P1' : 'P2' }}</span></td>
                    <td>
                        @if($lastMilestone)
                            <span class="badge badge-blue" style="font-size: 11px;">{{ $lastMilestone->jenis_milestone }}</span>
                        @else
                            <span style="color:#aaa">-</span>
                        @endif
                    </td>
                    <td>
                        @if($mhs->active_milestone)
                            <div style="font-size: 11px; font-weight: 700; color: #1f2937;">{{ $mhs->active_milestone->jenis_milestone }}</div>
                            <div style="font-size: 10px; color: {{ \Carbon\Carbon::parse($mhs->active_milestone->deadline)->isPast() ? '#dc3545' : '#4e73df' }}; font-weight: 600;">
                                Target: {{ $mhs->active_milestone->deadline ? \Carbon\Carbon::parse($mhs->active_milestone->deadline)->format('d/m/Y') : 'Belum diatur' }}
                            </div>
                        @else
                            <span style="color:#198754; font-size: 11px; font-weight: 700;">✓ Semua Selesai</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $statusTA }}">
                            {{ ucfirst($statusTA) }}
                        </span>
                    </td>
                    <td>
                        @if($lastBimbingan)
                            {{ \Carbon\Carbon::parse($lastBimbingan->tanggal)->format('d M Y') }}
                            @php $days = (int) now()->diffInDays(\Carbon\Carbon::parse($lastBimbingan->tanggal)); @endphp
                            @if($days > 30)
                                <span class="badge-danger ms-1">{{ $days }} hari lalu</span>
                            @endif
                        @else
                            <span style="color:#aaa">Belum ada</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('dosen.detail_mahasiswa', $mhs->id) }}" class="btn-icon btn-view" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn-icon btn-alert"
                            onclick="openReminderModal({{ $mhs->id }}, '{{ addslashes($mhs->user->name ?? '') }}')"
                            title="Kirim Pengingat">
                            <i class="fas fa-bell"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#aaa; padding:20px;">Belum ada mahasiswa bimbingan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Kirim Pengingat --}}
<div id="reminderModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>📢 Kirim Pengingat</h4>
            <span class="close-modal" onclick="closeModal('reminderModal')">✖</span>
        </div>
        <form id="reminderForm" method="POST">
            @csrf
            <div class="modal-body">
                <p style="font-size:14px; margin-bottom:12px;">Kirim pengingat ke: <strong id="reminderNama"></strong></p>
                <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block">Pesan:</label>
                <textarea name="pesan" class="form-control" rows="3"
                    placeholder="Contoh: Mohon segera melakukan bimbingan...">Mohon segera melakukan bimbingan. Harap menghubungi dosen pembimbing Anda secepatnya.</textarea>
            </div>
            <div class="modal-footer-log mt-3">
                <button type="button" class="btn-close-log" onclick="closeModal('reminderModal')">Batal</button>
                <button type="submit" class="btn-acc">Kirim</button>
            </div>
        </form>
    </div>
</div>

<style>
.main { padding: 30px; }
.alert-success { background:#e6f7ee; color:#1a7f4b; border:1px solid #b7e4c7; border-radius:10px; padding:12px 18px; font-size:14px; font-weight:500; }
.table-tools { display:flex; gap:12px; margin-bottom:15px; flex-wrap:wrap; }
.table-tools input, .table-tools select { padding:8px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; }
.card { background:white; padding:25px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.05); }
table { width:100%; border-collapse:collapse; margin-top:15px; }
table th, table td { padding:14px; text-align:center; font-size:14px; vertical-align:middle; }
table thead { background:#f1f2f6; }
table tbody tr { border-bottom:1px solid #eee; cursor:default; }
td { word-break:break-word; }
.badge-blue { background:#02048d; color:white; padding:4px 10px; border-radius:20px; font-size:12px; display:inline-block; }
.badge-danger { background:#ffe5e5; color:#e74a3b; padding:3px 8px; border-radius:12px; font-size:11px; font-weight:600; }
.status-badge { padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }
.status-badge.ahead { background:#e6f7ee; color:#1cc88a; }
.status-badge.ideal { background:#fff4e5; color:#f6a500; }
.status-badge.behind { background:#ffe5e5; color:#e74a3b; }
.role-badge { font-size:11px; padding:2px 6px; border-radius:6px; font-weight:600; }
.role-badge.p1 { background:#e8f0ff; color:#3b4cca; }
.role-badge.p2 { background:#e6f4ea; color:#1cc88a; }
.action-buttons { display:flex; gap:8px; justify-content:center; }
.btn-icon { border:none; width:34px; height:34px; border-radius:8px; cursor:pointer; color:white; display:flex; align-items:center; justify-content:center; font-size:14px; transition:0.2s; text-decoration:none; }
.btn-view { background:#0dcaf0; }
.btn-alert { background:#f39c12; }
.btn-icon:hover { transform:scale(1.05); opacity:0.9; }
.modal-log { display:none; position:fixed; inset:0; background:rgba(15,23,42,0.45); backdrop-filter:blur(4px); z-index:999; justify-content:center; align-items:center; padding:20px; }
.modal-content-log { background:white; width:480px; max-width:100%; border-radius:20px; padding:24px; animation:fadeIn .25s ease; box-shadow:0 25px 50px rgba(0,0,0,0.15); }
.modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; padding-bottom:10px; border-bottom:1px solid #f1f5f9; }
.modal-header h4 { font-size:18px; font-weight:600; color:#1f2937; }
.close-modal { cursor:pointer; font-size:18px; }
.form-control { width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; resize:vertical; }
.modal-footer-log { display:flex; justify-content:flex-end; gap:8px; margin-top:14px; }
.btn-close-log { background:#858796; color:white; border:none; padding:8px 18px; border-radius:8px; cursor:pointer; }
.btn-acc { background:#1cc88a; color:white; border:none; padding:8px 18px; border-radius:8px; cursor:pointer; }
@keyframes fadeIn { from{transform:scale(0.95);opacity:0;} to{transform:scale(1);opacity:1;} }
</style>

@endsection

@push('scripts')
<script>
function openReminderModal(id, nama) {
    document.getElementById('reminderNama').textContent = nama;
    document.getElementById('reminderForm').action = '/dosen/kirim-pengingat/' + id;
    document.getElementById('reminderModal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('reminderModal')) closeModal('reminderModal');
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('searchInput');
    const sortStatus   = document.getElementById('sortStatus');
    const sortPeran    = document.getElementById('sortPeran');

    [searchInput, sortStatus, sortPeran].forEach(el => el.addEventListener('input', filterTable));

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const status = sortStatus.value;
        const peran  = sortPeran.value;
        document.querySelectorAll('#tabelMahasiswa tbody tr').forEach(row => {
            const nama    = (row.cells[2]?.innerText ?? '').toLowerCase();
            const nim     = (row.cells[1]?.innerText ?? '').toLowerCase();
            const rowStat = row.dataset.status ?? '';
            const rowPeran= row.dataset.peran ?? '';
            let show = true;
            if (search && !nama.includes(search) && !nim.includes(search)) show = false;
            if (status && rowStat !== status) show = false;
            if (peran  && rowPeran !== peran)  show = false;
            row.style.display = show ? '' : 'none';
        });
    }
});
</script>
@endpush