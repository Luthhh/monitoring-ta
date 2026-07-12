@extends('layouts.admin')

@section('title', 'Lulus Tepat Waktu')

@section('page-content')
<div class="main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h2 class="mb-0">Tepat Waktu (On-Track) 🟢</h2>
    </div>

    <div class="stat-card stat-green mb-4 d-flex align-items-center justify-content-between">
        <div>
            <p class="mb-1 opacity-75">Mahasiswa Semester 1-4 yang On-Track (Ahead/Ideal) atau Sudah Lulus</p>
            <h2 class="mb-0" style="font-size: 38px;">{{ $mahasiswas->count() }}</h2>
        </div>
        <i class="fas fa-graduation-cap" style="font-size: 40px; opacity: 0.3;"></i>
    </div>

    <div class="table-tools p-3 bg-white mb-4 shadow-sm" style="border-radius: 12px; border: 1px solid #f0f0f0;">
        <div class="row g-3 w-100 align-items-center">
            <div class="col-md-6">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
                    <input type="text" id="searchInput" class="form-control ps-5" placeholder="Cari nama atau NIM..." style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                </div>
            </div>
            <div class="col-md-3">
                <select id="sortTahun" class="form-select" style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                    <option value="">Semua Tahun</option>
                    @foreach($all_years as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.export-mahasiswa', ['kategori' => 'ontrack']) }}" class="btn w-100 d-flex align-items-center justify-content-center gap-2" style="background:#27ae60; color:white; border-radius:10px; height: 42px; font-weight: 500;">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
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
                        <th>Milestone Terakhir</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                    <tr data-tahun="{{ $mhs->tahun_masuk }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="text-muted">{{ $mhs->angkatan_formatted }}</span></td>
                        <td><strong>{{ $mhs->nim }}</strong></td>
                        <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border" style="color: #475569 !important;">{{ $mhs->semester ?? '-' }}</span></td>
                        <td>
                            @if($mhs->last_milestone ?? null)
                                <span class="badge badge-green">{{ $mhs->last_milestone->jenis_milestone }}</span>
                            @else
                                <span class="text-muted" style="font-size: 12px;">-</span>
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
                        <td colspan="7" style="text-align:center;color:#aaa;padding:40px">
                            <i class="fas fa-info-circle mb-2" style="font-size: 24px;"></i><br>
                            Tidak ada mahasiswa dalam kategori ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin._table-styles', ['color' => 'green'])
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