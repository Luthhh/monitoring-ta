@extends('layouts.admin')

@section('title', 'Behind Mahasiswa')

@section('page-content')
<div class="main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h2 class="mb-0">Mahasiswa Behind 🔴</h2>
    </div>

    <div class="stat-card stat-red mb-4 d-flex align-items-center justify-content-between">
        <div>
            <p class="mb-1 opacity-75">Mahasiswa Progres di Bawah Target</p>
            <h2 class="mb-0" style="font-size: 38px;">{{ $mahasiswas->count() }}</h2>
        </div>
        <i class="fas fa-user-xmark" style="font-size: 40px; opacity: 0.3;"></i>
    </div>

    <div class="table-tools p-3 bg-white mb-4 shadow-sm" style="border-radius: 12px; border: 1px solid #f0f0f0;">
        <div class="row g-3 w-100 align-items-center">
            <div class="col-md-8">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
                    <input type="text" id="searchInput" class="form-control ps-5" placeholder="Cari nama atau NIM..." style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                </div>
            </div>
            <div class="col-md-4">
                <select id="sortTahun" class="form-select" style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                    <option value="">Semua Tahun</option>
                    @foreach($all_years as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card p-0 overflow-hidden" style="border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        <div class="table-responsive">
            <table id="tabelMahasiswa" class="mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Tahun Masuk</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Milestone Terakhir</th>
                        <th>Alasan Behind</th>
                        <th style="width: 80px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                    @php
                        $lastB  = $mhs->last_bimbingan ?? null;
                        $lastM  = $mhs->last_milestone ?? null;
                        $active = $mhs->active_milestone ?? null;
                        $now    = \Carbon\Carbon::now();

                        // Tentukan alasan behind
                        $alasan = [];
                        if (!$lastB || $now->diffInDays(\Carbon\Carbon::parse($lastB->tanggal)) > 30) {
                            $hari = $lastB ? $now->diffInDays(\Carbon\Carbon::parse($lastB->tanggal)) : '??';
                            $alasan[] = "Tidak bimbingan > 30 hari (" . ($hari ?? '-') . " hari)";
                        }
                        if ($active && $active->deadline && $now->greaterThan(\Carbon\Carbon::parse($active->deadline))) {
                            $alasan[] = "Melewati deadline milestone";
                        }
                        if (!$active) {
                            $alasan[] = "Belum daftar milestone baru";
                        }
                    @endphp
                    <tr data-tahun="{{ $mhs->tahun_masuk }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="text-muted">{{ $mhs->angkatan_formatted }}</span></td>
                        <td><strong>{{ $mhs->nim }}</strong></td>
                        <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                        <td>
                            @if($lastM)
                                <span class="badge bg-light text-dark border" style="color: #475569 !important;">{{ $lastM->jenis_milestone }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="text-align:left">
                            @foreach($alasan as $a)
                                <div class="danger-badge d-block mb-1" style="background:#fff1f0; border:1px solid #ffa39e; color:#cf1322; padding:2px 8px; border-radius:6px; font-size:11px;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $a }}
                                </div>
                            @endforeach
                            @if(empty($alasan)) <span class="text-muted">-</span> @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.detail-mahasiswa', $mhs->id) }}" class="btn-icon btn-view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#aaa;padding:40px">
                            <i class="fas fa-check-circle mb-2" style="font-size: 24px; color: #52c41a;"></i><br>
                            Bagus! Tidak ada mahasiswa dalam kategori behind.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin._table-styles', ['color' => 'red'])
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const s = document.getElementById('searchInput');
    const t = document.getElementById('sortTahun');
    [s, t].forEach(el => el?.addEventListener('input', filter));
    function filter() {
        const q = s.value.toLowerCase(), tahun = t.value;
        document.querySelectorAll('#tabelMahasiswa tbody tr').forEach(row => {
            const nama = (row.cells[3]?.innerText || '').toLowerCase();
            const nim  = (row.cells[2]?.innerText || '').toLowerCase();
            const rt   = row.getAttribute('data-tahun') || '';
            let show = true;
            if (q && !nama.includes(q) && !nim.includes(q)) show = false;
            if (tahun && rt !== tahun) show = false;
            row.style.display = show ? '' : 'none';
        });
    }
});
</script>
@endpush