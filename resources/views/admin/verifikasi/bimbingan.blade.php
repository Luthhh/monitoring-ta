@extends('layouts.admin')

@section('title', 'Verifikasi Bimbingan')

@section('page-content')

<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<div class="box">
    <div class="box-header">
        <h3>📑 Daftar Tunggu Verifikasi Bukti Bimbingan</h3>
        <span class="badge" style="background: #1cc88a; color: white;">{{ $bimbingans->total() }} Menunggu</span>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>Mahasiswa</th>
                <th>Jadwal Bimbingan</th>
                <th>Catatan Mahasiswa</th>
                <th>Berkas Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bimbingans as $vb)
            <tr>
                <td style="text-align: left;">{{ $vb->tugasAkhir->mahasiswa->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($vb->tanggal)->format('d M Y') }} ({{ $vb->waktu ?? '-' }})</td>
                <td style="text-align: left;">{{ Str::limit($vb->catatan_mahasiswa ?? $vb->deskripsi, 100) }}</td>
                <td>
                    @if($vb->file_dokumen)
                        <a href="{{ asset('storage/'.$vb->file_dokumen) }}" target="_blank" class="badge bg-secondary text-decoration-none">
                            📄 Lihat File
                        </a>
                    @else - @endif
                </td>
                <td>
                    <a href="{{ route('admin.detail-mahasiswa', $vb->tugasAkhir->mahasiswa->id) }}" class="btn-icon btn-view" title="Lihat Profil">
                        <i class="fas fa-user"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center; color: #999; padding: 20px;">Belum ada bukti bimbingan masuk.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $bimbingans->links() }}
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
.btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 10px; text-decoration: none !important; }
.btn-view { background: #eef2ff; color: #4f46e5; }
</style>
@endpush

@endsection
