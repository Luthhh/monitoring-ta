@extends('layouts.dosen')

@section('title', 'Mahasiswa Ahead')

@section('page-content')
<div class="main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dosen.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h2 class="mb-0">Mahasiswa Ahead 🟢</h2>
    </div>

    <div class="stat-card stat-green mb-4">
        <h2>{{ $mahasiswas->count() }}</h2>
        <p>Mahasiswa progres di atas target</p>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th><th>NIM</th><th>Nama</th><th>Peran</th>
                    <th>Milestone Terakhir</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $i => $mhs)
                @php $isP1 = $mhs->pembimbing1_id == $dosen->id; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                    <td><span class="role-badge {{ $isP1 ? 'p1':'p2' }}">{{ $isP1 ? 'P1':'P2' }}</span></td>
                    <td>
                        @if($mhs->last_milestone ?? null)
                            <span class="badge-ms">{{ $mhs->last_milestone->jenis_milestone }}</span>
                        @else <span style="color:#aaa">-</span> @endif
                    </td>
                    <td>
                        <a href="{{ route('dosen.detail_mahasiswa', $mhs->id) }}" class="btn-icon btn-view">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#aaa;padding:20px">Tidak ada mahasiswa ahead.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@include('dosen._table-styles')
@endsection