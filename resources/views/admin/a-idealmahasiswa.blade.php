@extends('layouts.admin')

@section('title', 'Ideal Mahasiswa')

@section('page-content')
<div class="main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h2 class="mb-0">Mahasiswa Ideal 🟡</h2>
    </div>

    <div class="stat-card stat-yellow mb-4 d-flex align-items-center justify-content-between">
        <div>
            <p class="mb-1 opacity-75">Mahasiswa Progres Sesuai Target</p>
            <h2 class="mb-0" style="font-size: 38px;">{{ $mahasiswas->count() }}</h2>
        </div>
        <i class="fas fa-user-check" style="font-size: 40px; opacity: 0.3;"></i>
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
                        <th>Semester</th>
                        <th>Milestone Aktif</th>
                        <th>Deadline</th>
                        <th style="width: 80px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                    @php $active = $mhs->active_milestone ?? null; @endphp
                    <tr data-tahun="{{ $mhs->tahun_masuk }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="text-muted">{{ $mhs->angkatan_formatted }}</span></td>
                        <td><strong>{{ $mhs->nim }}</strong></td>
                        <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border" style="color: #475569 !important;">{{ $mhs->semester ?? '-' }}</span></td>
                        <td>
                            @if($active)
                                <span class="badge badge-yellow">{{ $active->jenis_milestone }}</span>
                            @else
                                <span class="text-muted" style="font-size: 12px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($active && $active->deadline)
                                @php
                                    $dl   = \Carbon\Carbon::parse($active->deadline);
                                    $sisa = (int) now()->diffInDays($dl, false);
                                @endphp
                                <div class="d-flex flex-column align-items-center">
                                    <span style="font-size: 13px;">{{ $dl->format('d/m/Y') }}</span>
                                    @if($sisa >= 0)
                                        <span style="background:#dcfce7;color:#16a34a;padding:1px 8px;border-radius:10px;font-size:10px;font-weight:600;margin-top:2px;">
                                            {{ $sisa }} hari lagi
                                        </span>
                                    @else
                                        <span style="background:#fee2e2;color:#dc2626;padding:1px 8px;border-radius:10px;font-size:10px;font-weight:600;margin-top:2px;">
                                            Lewat {{ abs($sisa) }} hari
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
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
                        <td colspan="8" style="text-align:center;color:#aaa;padding:40px">
                            <i class="fas fa-info-circle mb-2" style="font-size: 24px;"></i><br>
                            Tidak ada mahasiswa dalam kategori ideal.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin._table-styles', ['color' => 'yellow'])
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