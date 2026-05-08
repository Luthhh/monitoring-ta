@extends('layouts.admin')

@section('title', 'Verifikasi Milestone')

@section('page-content')

<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<div class="box">
    <div class="box-header">
        <h3>📝 Daftar Tunggu Verifikasi Milestone</h3>
        <span class="badge" style="background: #4e73df; color: white;">{{ $milestones->total() }} Menunggu</span>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Milestone</th>
                <th>Tgl Upload</th>
                <th>Dokumen Bukti</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse($milestones as $m)
            @php
                $ta = $m->tugasAkhir;
                $mhs = $ta ? $ta->mahasiswa : null;
            @endphp
            <tr>
                <td>{{ $mhs->nim ?? '-' }}</td>
                <td style="text-align: left;">{{ $mhs->user->name ?? '-' }}</td>
                <td>{{ $m->jenis_milestone }}</td>
                <td>{{ $m->tanggal_upload ? \Carbon\Carbon::parse($m->tanggal_upload)->format('d M Y') : '-' }}</td>
                <td>
                    @if(is_array($m->file_path))
                        @foreach($m->file_path as $key => $path)
                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="badge-proof">
                                <i class="fas fa-file-lines"></i> {{ is_numeric($key) ? 'Bukti ' . ($key + 1) : ucfirst(str_replace('_', ' ', $key)) }}
                            </a>
                        @endforeach
                    @elseif($m->file_path)
                        <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="badge-proof">
                            <i class="fas fa-file-lines"></i> Bukti
                        </a>
                    @else
                        <span class="text-muted" style="font-size: 11px;">Belum upload</span>
                    @endif
                </td>
                <td><span class="status-badge waiting">Menunggu</span></td>
                <td>
                    <a href="{{ route('admin.detail-mahasiswa', $mhs->id) }}" class="btn-icon btn-view">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #999; padding: 20px;">Tidak ada verifikasi menunggu.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $milestones->links() }}
    </div>
</div>

@push('scripts')
<style>
.topbar { display: flex; justify-content: space-between; margin-bottom: 25px; align-items: center; }
.box { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
.box-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: #f8f9fc; padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600; text-align: center; }
.table-custom td { padding: 12px; border-top: 1px solid #eee; font-size: 13px; text-align: center; }
.status-badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.status-badge.waiting { background: #fffbeb; color: #b45309; }
.badge-proof { display: inline-flex; align-items: center; gap: 5px; background: #f1f5f9; color: #475569; padding: 5px 10px; border-radius: 8px; text-decoration: none !important; font-size: 11px; font-weight: 500; border: 1px solid #e2e8f0; }
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 10px; text-decoration: none !important; }
.btn-view { background: #eef2ff; color: #4f46e5; }
</style>
@endpush

@endsection
