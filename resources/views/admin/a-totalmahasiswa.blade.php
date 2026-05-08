@extends('layouts.admin')

@section('title', 'Total Mahasiswa')

@section('page-content')

<div class="main">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h2 class="mb-0">Total Mahasiswa 🔵</h2>
    </div>

    <!-- Statistik -->
    <div class="stat-card stat-blue mb-4 d-flex align-items-center justify-content-between">
        <div>
            <p class="mb-1 opacity-75">Total Mahasiswa Terdaftar</p>
            <h2 class="mb-0" style="font-size: 38px;">{{ $mahasiswas->count() }}</h2>
        </div>
        <i class="fas fa-user-graduate" style="font-size: 40px; opacity: 0.3;"></i>
    </div>

    <form action="{{ route('admin.total-mahasiswa') }}" method="GET" class="table-tools p-3 bg-white mb-4 shadow-sm" id="filterForm" style="border-radius: 12px; border: 1px solid #f0f0f0;">
        <div class="row g-3 w-100 align-items-center">
            <div class="col-md-4">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
                    <input type="text" name="search" id="searchInput" class="form-control ps-5" placeholder="Cari nama atau NIM..." value="{{ request('search') }}" style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                </div>
            </div>
            <div class="col-md-2">
                <select name="tahun" id="sortTahun" class="form-select" style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunMasukList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="semester" id="sortSemester" class="form-select" style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                    <option value="">Semua Semester</option>
                    @for($i=1; $i<=8; $i++)
                        <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <select name="dosen" id="sortDosen" class="form-select" style="border-radius: 10px; border: 1px solid #e0e0e0; height: 42px;">
                    <option value="">Semua Dosen Pembimbing</option>
                    @foreach($dosens as $d)
                        <option value="{{ $d->user->name }}" {{ request('dosen') == $d->user->name ? 'selected' : '' }}>{{ $d->user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <!-- Tabel -->
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
                        <th style="width: 80px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                    <tr data-tahun="{{ $mhs->tahun_masuk }}" 
                        data-semester="{{ $mhs->semester }}" 
                        data-d1="{{ optional($mhs->pembimbing1)->user->name ?? '' }}" 
                        data-d2="{{ optional($mhs->pembimbing2)->user->name ?? '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td><span class="text-muted">{{ $mhs->angkatan_formatted }}</span></td>
                        <td><strong>{{ $mhs->nim }}</strong></td>
                        <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border" style="color: #475569 !important;">{{ $mhs->semester ?? '-' }}</span></td>
                        <td>
                            @if($mhs->last_milestone ?? null)
                                <span class="badge badge-blue">{{ $mhs->last_milestone->jenis_milestone }}</span>
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
                            Belum ada data mahasiswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>

@include('admin._table-styles', ['color' => 'blue'])
<style>
    #tabelMahasiswa td:nth-child(4) { max-width: none; }
</style>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const filterForm = document.getElementById("filterForm");
    const searchInput = document.getElementById("searchInput");
    const sortSemester = document.getElementById("sortSemester");
    const sortTahun = document.getElementById("sortTahun");
    const sortDosen = document.getElementById("sortDosen");

    const submitForm = () => filterForm.submit();

    if (sortSemester) sortSemester.addEventListener("change", submitForm);
    if (sortTahun) sortTahun.addEventListener("change", submitForm);
    if (sortDosen) sortDosen.addEventListener("change", submitForm);

    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(submitForm, 1000);
        });
    }

});
</script>
@endpush