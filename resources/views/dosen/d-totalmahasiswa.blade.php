@extends('layouts.dosen')

@section('title', 'Total Mahasiswa')

@section('page-content')

    <div class="main">

        <h1>Daftar Mahasiswa</h1>

        <div class="cards-stats">
            <div class="card-stat" style="border-left: 5px solid #02048d;">
                <div class="stat-label">Mahasiswa Pembimbing 1</div>
                <div class="stat-value" style="color: #02048d;">{{ $jmlPembimbing1 }}</div>
            </div>
            <div class="card-stat" style="border-left: 5px solid #1cc88a;">
                <div class="stat-label">Mahasiswa Pembimbing 2</div>
                <div class="stat-value" style="color: #1cc88a;">{{ $jmlPembimbing2 }}</div>
            </div>
            <div class="card-stat" style="border-left: 5px solid #64748b;">
                <div class="stat-label">Total Bimbingan Aktif</div>
                <div class="stat-value" style="color: #334155;">{{ $mahasiswas->count() }}</div>
            </div>
        </div>

        <div class="table-tools">
            <input type="text" id="searchInput" placeholder="🔍 Cari nama mahasiswa...">
            <select id="sortTahun">
                <option value="">Semua Tahun</option>
                @foreach($all_years as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
            <select id="sortSemester">
                <option value="">Semua Semester</option>
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3">Semester 3</option>
                <option value="4">Semester 4</option>
                <option value="5">Semester 5</option>
                <option value="6">Semester 6</option>
                <option value="7">Semester 7</option>
                <option value="8">Semester 8</option>
            </select>
            <select id="sortPeran">
                <option value="">Peran Semua</option>
                <option value="P1">Pembimbing 1</option>
                <option value="P2">Pembimbing 2</option>
            </select>
        </div>

    <!-- Tabel -->
    <div class="card">
        <table id="tabelMahasiswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Masuk</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Milestone Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $index => $mhs)
                @php
                    $isP1  = $mhs->pembimbing1_id == $dosen->id;
                    $lastMilestone = null;
                    if ($mhs->tugasAkhir && $mhs->tugasAkhir->milestones) {
                        $lastMilestone = $mhs->tugasAkhir->milestones
                            ->where('status', 'disetujui')
                            ->sortByDesc('updated_at')
                            ->first();
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mhs->angkatan_formatted }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>
                        <span class="role-badge {{ $isP1 ? 'p1' : 'p2' }}">{{ $isP1 ? 'P1' : 'P2' }}</span>
                        {{ $mhs->user->name ?? '-' }}
                    </td>
                    <td>{{ $mhs->semester ?? '-' }}</td>
                    <td>
                        @if($lastMilestone)
                            <span class="badge badge-blue">
                                {{ $lastMilestone->jenis_milestone }}
                            </span>
                        @else
                            <span style="color:#aaa;">-</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('dosen.detail_mahasiswa', $mhs->id) }}" class="btn-icon btn-view" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#aaa; padding:20px;">
                        Belum ada mahasiswa bimbingan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dosen/dosen-shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dosen/d-totalmahasiswa.css') }}">
@endpush

@endsection
@push('scripts')
    <script src="{{ asset('js/dosen/d-totalmahasiswa.js') }}"></script>
@endpush