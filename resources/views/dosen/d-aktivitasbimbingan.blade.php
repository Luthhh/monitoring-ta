@extends('layouts.dosen')

@section('title','Detail Aktivitas Bimbingan')

@section('page-content')

<!-- Modal Log Bimbingan -->
<div id="logModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Detail Bimbingan</h4>
            <span class="close-modal" onclick="closeLogModal()">✖</span>
        </div>
        <div class="modal-body">
            <div class="log-grid">
                <div class="label">Nama Kegiatan</div><div class="colon">:</div><div class="value" id="log-kegiatan">-</div>
                <div class="label">Rencana Bimbingan</div><div class="colon">:</div><div class="value" id="log-bimbingan">-</div>
                <div class="label">Tipe</div><div class="colon">:</div><div class="value" id="log-tempat">-</div>
                <div class="label">Durasi</div><div class="colon">:</div><div class="value" id="log-durasi">-</div>
                <div class="label">Catatan Dosen</div><div class="colon">:</div><div class="value" id="log-topik">-</div>
                <div class="label">Status</div><div class="colon">:</div><div class="value" id="log-status">-</div>
            </div>
        </div>
    </div>
</div>

<div class="page-wrapper">
    <h2 class="page-title">Detail Aktivitas Bimbingan</h2>

    {{--  Mahasiswa > 30 hari --}}
    <div class="card-box">
        <div class="card-header">
            <span>🔔 Mahasiswa Tidak Bimbingan > 30 Hari</span>
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
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tidakBimbingan30 as $mhs)
                @php
                    $roleLabel = ($mhs->pembimbing1_id == $dosen->id) ? 'P1' : 'P2';
                    $roleClass = ($mhs->pembimbing1_id == $dosen->id) ? 'p1' : 'p2';
                    $tglTerakhir = $mhs->last_bimbingan ? \Carbon\Carbon::parse($mhs->last_bimbingan->tanggal)->format('d M Y') : 'Belum Pernah';
                    $milestoneActive = $mhs->active_milestone ? $mhs->active_milestone->jenis_milestone : '-';
                    $targetDate = ($mhs->active_milestone && $mhs->active_milestone->deadline) ? \Carbon\Carbon::parse($mhs->active_milestone->deadline)->format('d M Y') : 'Belum Atur';
                @endphp
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>
                        <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        {{ $mhs->user->name }}
                    </td>
                    <td>{{ $tglTerakhir }}</td>
                    <td>{{ $milestoneActive }}</td>
                    <td>{{ $targetDate }}</td>
                    <td><span class="status-badge pending">Belum</span></td>
                    <td onclick="event.stopPropagation();">
                        <form action="{{ url('/dosen/kirim-pengingat/' . $mhs->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-remind" title="Ingatkan Mahasiswa"><i class="fas fa-bell"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="font-weight: 500; padding:20px;">Semua mahasiswa secara aktif melakukan bimbingan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Bimbingan bulan ini --}}
    <div class="card-box">
        <div class="card-header">
            <span>📅 Bimbingan Bulan Ini</span>
            <span class="badge-success">{{ $bimbinganBulanIniList->count() }}</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Terakhir Bimbingan</th>
                    <th>Milestone</th>
                    <th>Target Milestone</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbinganBulanIniList as $bim)
                @php
                    $mhs = $bim->tugasAkhir->mahasiswa;
                    $roleLabel = ($mhs->pembimbing1_id == $dosen->id) ? 'P1' : 'P2';
                    $roleClass = ($mhs->pembimbing1_id == $dosen->id) ? 'p1' : 'p2';
                    $activeMilestone = $bim->tugasAkhir->milestones->whereIn('status', ['pending', 'menunggu_verifikasi'])->first();
                    $msName = $activeMilestone ? $activeMilestone->jenis_milestone : '-';
                    $targetDate = ($activeMilestone && $activeMilestone->deadline) ? \Carbon\Carbon::parse($activeMilestone->deadline)->format('d M Y') : 'Belum Atur';
                    
                    $logData = [
                        'nim' => $mhs->nim ?? '-',
                        'name' => $mhs->user->name ?? '-',
                        'role' => $roleLabel,
                        'roleClass' => $roleClass,
                        'kegiatan' => $bim->nama_kegiatan ?? '-',
                        'tgl_bimbingan' => \Carbon\Carbon::parse($bim->tanggal)->format('d M Y'),
                        'tempat' => $bim->tipe_penyelenggaraan ?? '-',
                        'durasi' => ($bim->durasi_jam ?? '-') . ' Jam',
                        'topik' => $bim->catatan ?? '-',
                        'dokumen' => $bim->file_dokumen ? asset('storage/' . $bim->file_dokumen) : null,
                        'status' => ucfirst(str_replace('_', ' ', $bim->status))
                    ];
                @endphp
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>
                        <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        {{ $mhs->user->name }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($bim->tanggal)->format('d M Y') }}</td>
                    <td>{{ $msName }}</td>
                    <td>{{ $targetDate }}</td>
                    <td><span class="status-badge done">Sudah</span></td>
                    <td onclick="event.stopPropagation();">
                        <button class="btn-view-log" data-log="{{ base64_encode(json_encode($logData)) }}" onclick="openLogModal(this)">
                            👁 Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="font-weight: 500; padding:20px;">Belum ada aktivitas bimbingan bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dosen/dosen-shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dosen/d-aktivitasbimbingan.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dosen/d-aktivitasbimbingan.js') }}"></script>
@endpush