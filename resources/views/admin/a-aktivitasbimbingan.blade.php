@extends('layouts.admin')

@section('title','Aktivitas Bimbingan')

@section('page-content')

<div class="page-wrapper">
    <h2 class="page-title">Detail Mahasiswa Kritis</h2>

    @if(session('success'))
        <div class="alert alert-success mb-3" style="background:#e6f7ee; color:#1a7f4b; border:1px solid #b7e4c7; border-radius:10px; padding:12px 18px; font-size:14px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Mahasiswa > 30 hari tidak bimbingan --}}
    <div class="card-box">
        <div class="card-header">
            <span>🔔 Mahasiswa Tidak Bimbingan &gt; 30 Hari</span>
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
                    <th>Status TA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tidakBimbingan30 as $mhs)
                @php
                    $tglTerakhir = $mhs->last_bimbingan ? \Carbon\Carbon::parse($mhs->last_bimbingan->tanggal)->format('d M Y') : 'Belum Pernah';
                    $milestoneActive = $mhs->active_milestone ? $mhs->active_milestone->jenis_milestone : '-';
                    $targetDate = ($mhs->active_milestone && $mhs->active_milestone->deadline) ? \Carbon\Carbon::parse($mhs->active_milestone->deadline)->format('d M Y') : 'Belum Atur';
                @endphp
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->user->name ?? '-' }}</td>
                    <td>
                        {{ $tglTerakhir }}
                        @if($mhs->last_bimbingan)
                            <div style="font-size:11px; color:#e74a3b; margin-top:2px;">{{ (int) now()->diffInDays(\Carbon\Carbon::parse($mhs->last_bimbingan->tanggal)) }} hari lalu</div>
                        @else
                            <div style="font-size:11px; color:#e74a3b; margin-top:2px;">Sejak awal</div>
                        @endif
                    </td>
                    <td>{{ $milestoneActive }}</td>
                    <td>{{ $targetDate }}</td>
                    <td>
                        <span class="status-badge {{ $mhs->status_ta ?? 'behind' }}">
                            {{ ucfirst($mhs->status_ta ?? 'behind') }}
                        </span>
                    </td>
                    <td onclick="event.stopPropagation();">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.detail-mahasiswa', $mhs->id) }}" class="btn-detail-sm" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.kirim_pengingat', $mhs->id) }}" method="POST" class="d-inline form-confirm" data-text="Kirim notifikasi pengingat ke mahasiswa ini?">
                                @csrf
                                <button type="submit" class="btn-remind-sm">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="font-weight: 500; padding:20px; text-align:center;">
                        🎉 Semua mahasiswa aktif melakukan bimbingan dalam 30 hari terakhir.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('styles')
<style>
/* scope biar aman */
.page-wrapper {
    padding: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* CARD */
.card-box {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    border: 1px solid #f1f1f1;
}

/* HEADER */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    margin-bottom: 14px;
}

/* BADGE */
.badge-danger {
    background: #ffe5e5;
    color: #e74a3b;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 600;
}

/* TABLE */
.table-custom {
    width: 100%;
    border-collapse: collapse;
}

.table-custom th {
    background: #f8f9fc;
    padding: 10px;
    text-align: left;
    font-size: 13px;
    color: #6c757d;
}

.table-custom td {
    padding: 10px;
    border-top: 1px solid #eee;
    font-size: 14px;
    word-break: break-word;
}


table tbody tr:hover td {
    background-color: #f8f9fc;
}

/* badge status */
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
    text-align: center;
}

.status-badge.ahead  { background: #e6f7ee; color: #1cc88a; }
.status-badge.ideal  { background: #fff4e5; color: #f6a500; }
.status-badge.behind { background: #ffe5e5; color: #e74a3b; }

.btn-detail-sm {
    background: #0dcaf0;
    color: white;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    text-decoration: none;
    display: inline-block;
    transition: 0.2s;
}
.btn-detail-sm:hover { background: #0ab4d8; color: white; }

/* tombol ingatkan */
.btn-remind-sm {
    border: none;
    background: #e74a3b;
    color: white;
    padding: 6px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
    display: inline-block;
}
.btn-remind-sm:hover {
    background: #be2e21;
}

</style>
@endpush