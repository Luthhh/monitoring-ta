@extends('layouts.dosen')

@section('title', 'Mahasiswa Ideal')

@section('page-content')
<div class="main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dosen.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <h2 class="mb-0">Mahasiswa Ideal 🟡</h2>
    </div>

    <div class="stat-card stat-yellow mb-4">
        <h2>{{ $mahasiswas->count() }}</h2>
        <p>Mahasiswa progres sesuai target</p>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th><th>NIM</th><th>Nama</th><th>Peran</th>
                    <th>Milestone Aktif</th><th>Deadline</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $i => $mhs)
                @php
                    $isP1  = $mhs->pembimbing1_id == $dosen->id;
                    $active = $mhs->active_milestone ?? null;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td style="text-align:left">{{ $mhs->user->name ?? '-' }}</td>
                    <td><span class="role-badge {{ $isP1 ? 'p1':'p2' }}">{{ $isP1 ? 'P1':'P2' }}</span></td>
                    <td>
                        @if($active)
                            <span class="badge-ms">{{ $active->jenis_milestone }}</span>
                        @else <span style="color:#aaa">-</span> @endif
                    </td>
                    <td>
                        @if($active && $active->deadline)
                            @php
                                $dl   = \Carbon\Carbon::parse($active->deadline);
                                $sisa = (int) now()->diffInDays($dl, false);
                            @endphp
                            {{ $dl->format('d M Y') }}
                            @if($sisa >= 0)
                                <span style="background:#dcfce7;color:#16a34a;padding:2px 6px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap;">
                                    {{ $sisa }} hari lagi
                                </span>
                            @else
                                <span style="background:#fee2e2;color:#dc2626;padding:2px 6px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap;">
                                    Lewat {{ abs($sisa) }} hari
                                </span>
                            @endif
                        @else <span style="color:#aaa">-</span> @endif
                    </td>
                    <td>
                        <a href="{{ route('dosen.detail_mahasiswa', $mhs->id) }}" class="btn-icon btn-view">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:#aaa;padding:20px">Tidak ada mahasiswa ideal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@include('dosen._table-styles')
@endsection