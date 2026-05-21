@extends('layouts.dosen')

@section('title','Dashboard Dosen')

@section('page-content')


<div class="main">

    <div class="topbar">
        <h2>Monitoring Tugas Akhir</h2>
        <div class="topbar-right">
            <span>{{ $user->name }}</span>
        </div>
    </div>

    <div class="cards">
        <a href="{{ url('/dosen/total-mahasiswa') }}" class="card blue text-decoration-none">
            <h2>{{ $totalMahasiswa }}</h2>
            <p>Mahasiswa Aktif</p>
        </a>
        <a href="{{ url('/dosen/ahead-mahasiswa') }}" class="card green text-decoration-none">
            <h2>{{ $ahead }}</h2>
            <p>Ahead</p>
        </a>
        <a href="{{ url('/dosen/ideal-mahasiswa') }}" class="card yellow text-decoration-none">
            <h2>{{ $ideal }}</h2>
            <p>Ideal</p>
        </a>
        <a href="{{ url('/dosen/behind-mahasiswa') }}" class="card red text-decoration-none">
            <h2>{{ $behind }}</h2>
            <p>Behind</p>
        </a>
    </div>

    {{-- Ringkasan Bimbingan Bulanan --}}
    <h3 style="margin-bottom: 15px; font-size: 18px; font-weight: 600;">📅 Ringkasan Bimbingan Bulanan</h3>
    <div class="cards" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-bottom: 30px;">
        <div class="card" style="background: white; border: 1px solid #e2e8f0; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
            <span style="font-size: 13px; color: #64748b; font-weight: 600;">Rencana Bimbingan (Bulan Ini)</span>
            <span style="font-size: 26px; font-weight: 800; color: #4e73df;">{{ $rencanaBimbinganBulanIni }}</span>
        </div>
        <div class="card" style="background: white; border: 1px solid #e2e8f0; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
            <span style="font-size: 13px; color: #64748b; font-weight: 600;">Bimbingan Terlaksana (Bulan Ini)</span>
            <span style="font-size: 26px; font-weight: 800; color: #1cc88a;">{{ $terlaksanaBulanIni }}</span>
        </div>
        <div class="card" style="background: white; border: 1px solid #e2e8f0; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
            <span style="font-size: 13px; color: #64748b; font-weight: 600;">Jumlah Bimbingan Aktif</span>
            <span style="font-size: 26px; font-weight: 800; color: #f6c23e;">{{ $jumlahBimbinganAktif }}</span>
        </div>
    </div>

    {{-- TABLE PENGAJUAN BIMBINGAN (DI ATAS) --}}
    <div class="card-box" style="margin-bottom: 30px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">📥 Pengajuan Bimbingan Menunggu</h3>
            <span class="badge bg-primary">{{ $pengajuanBimbingans->count() }}</span>
        </div>

        <table class="table-custom" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead style="background: #f8f9fc;">
                <tr>
                    <th style="padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600;">NIM</th>
                    <th style="padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600;">Nama</th>
                    <th style="padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600;">Jadwal Bimbingan Aktual</th>
                    <th style="padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600;">Deskripsi</th>
                    <th style="padding: 12px; font-size: 13px; color: #6c757d; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanBimbingans as $bimbingan)
                @php
                    $mahasiswa = optional($bimbingan->tugasAkhir)->mahasiswa;
                    $user_mhs  = optional($mahasiswa)->user;
                @endphp
                <tr>
                    <td style="padding: 12px; border-top: 1px solid #eee; text-align: center;">{{ optional($mahasiswa)->nim ?? '-' }}</td>
                    <td style="padding: 12px; border-top: 1px solid #eee;">{{ optional($user_mhs)->name ?? '-' }}</td>
                    <td style="padding: 12px; border-top: 1px solid #eee; text-align: center;">{{ \Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y') }}{{ $bimbingan->waktu ? ' (' . \Carbon\Carbon::parse($bimbingan->waktu)->format('H:i') . ')' : '' }}</td>
                    <td style="padding: 12px; border-top: 1px solid #eee;">{{ Str::limit($bimbingan->deskripsi ?? $bimbingan->catatan, 50) }}</td>
                    <td style="padding: 12px; border-top: 1px solid #eee; text-align: center;">
                        <div class="action-buttons">
                            <button class="btn-icon btn-view" title="Detail" onclick="openPengajuanModal('{{ optional($mahasiswa)->nim }}', '{{ addslashes(optional($user_mhs)->name) }}', '{{ \Carbon\Carbon::parse($bimbingan->created_at)->format('d M Y') }}', '{{ \Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y') }}', '{{ $bimbingan->waktu }} - {{ $bimbingan->tempat }}', '{{ addslashes($bimbingan->deskripsi) }}')"><i class="fas fa-eye"></i></button>
                            <button class="btn-icon btn-approve" title="Setujui" onclick="openApproveModal('bimbingan', {{ $bimbingan->id }})"><i class="fas fa-check"></i></button>
                            <button class="btn-icon btn-reject" title="Tolak" onclick="openRejectModal('bimbingan', {{ $bimbingan->id }})"><i class="fas fa-times"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align: center; padding: 20px; color: #94a3b8;">Belum ada pengajuan bimbingan baru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="highlight-bar">
        <a href="{{ route('dosen.aktivitas_bimbingan') }}" class="highlight-item warning clickable-card">
            <div class="highlight-text">
                🔔 Mahasiswa tidak bimbingan &gt; 30 hari
            </div>
            <div class="highlight-number warning-num">{{ $tidakBimbingan30 }}</div>
        </a>
        <a href="{{ route('dosen.aktivitas_bimbingan') }}" class="highlight-item info clickable-card">
            <div class="highlight-text">
                📅 Bimbingan bulan ini
            </div>
            <div class="highlight-number info-num">{{ $bimbinganBulanIni }}</div>
        </a>
    </div>

    <div class="box">
        <div class="box-header">
            <h3>📈 Sebaran Milestone Mahasiswa</h3>
            <select id="filterTahun">
                <option value="all">Semua Angkatan</option>
                @foreach($all_years as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="chart-container">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <div class="box" style="margin-top: 30px;">
        <div class="box-header">
            <h3>📊 Grafik Ringkasan Bimbingan (6 Bulan Terakhir)</h3>
        </div>
        <div class="chart-container">
            <canvas id="bimbinganChart"></canvas>
        </div>
    </div>



    {{-- CARD SUMMARY --}}
    <h3 style="margin-top: 35px; margin-bottom: 15px; font-size: 18px; font-weight: 600;">📋 Menunggu Tinjauan Anda</h3>
    <div class="highlight-bar">
        <a href="#tabel-verifikasi-milestone" class="highlight-item clickable-card" style="border-left: 4px solid #4e73df;">
            <div class="highlight-text" style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #eef2ff; color: #4e73df; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block;">Menunggu Verifikasi</span>
                    <span style="font-size: 15px; color: #1e293b; font-weight: 700;">Bukti Milestone</span>
                </div>
            </div>
            <div class="highlight-number" style="font-size: 20px; width: 48px; height: 48px; background: #eef2ff; color: #4e73df;">{{ $verifikasiMilestones->count() }}</div>
        </a>
        <a href="#tabel-verifikasi-bimbingan" class="highlight-item clickable-card" style="border-left: 4px solid #1cc88a;">
            <div class="highlight-text" style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #e6f7ee; color: #1cc88a; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <span style="font-size: 12px; color: #64748b; font-weight: 600; display: block;">Menunggu Verifikasi</span>
                    <span style="font-size: 15px; color: #1e293b; font-weight: 700;">Bukti Bimbingan</span>
                </div>
            </div>
            <div class="highlight-number" style="font-size: 20px; width: 48px; height: 48px; background: #e6f7ee; color: #1cc88a;">{{ $verifikasiBimbingans->count() }}</div>
        </a>
    </div>

    {{-- Tables for Verification --}}

    {{-- TABLE VERIFIKASI BUKTI MILESTONE --}}
    <div class="card-box" id="tabel-verifikasi-milestone">
        <div class="card-header">
            <span>Verifikasi Bukti Milestone</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tgl Upload</th>
                    <th>Jenis Milestone</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($verifikasiMilestones as $milestone)
                @php
                    $mahasiswa = optional($milestone->tugasAkhir)->mahasiswa;
                    $user_mhs  = optional($mahasiswa)->user;
                @endphp
                <tr>
                    <td>{{ optional($mahasiswa)->nim ?? '-' }}</td>
                    <td>{{ optional($user_mhs)->name ?? '-' }}</td>
                    <td>{{ $milestone->tanggal_upload ? \Carbon\Carbon::parse($milestone->tanggal_upload)->format('d M Y') : '-' }}</td>
                    <td>{{ $milestone->jenis_milestone }}</td>
                    <td>
                        @if(is_array($milestone->file_path))
                            @foreach($milestone->file_path as $key => $path)
                                <a href="{{ asset('storage/'.$path) }}" target="_blank" class="badge bg-secondary text-decoration-none d-block mb-1">
                                    📄 {{ is_numeric($key) ? 'Bukti ' . ($key + 1) : ucfirst(str_replace('_', ' ', $key)) }}
                                </a>
                            @endforeach
                        @elseif($milestone->file_path)
                            <a href="{{ asset('storage/'.$milestone->file_path) }}" target="_blank" class="btn-proof">
                                📄 Lihat Bukti
                            </a>
                        @else
                            <span style="color:#aaa">-</span>
                        @endif
                    </td>
                    <td><span class="status-badge verify">Menunggu</span></td>
                    <td class="action-buttons">
                        {{-- Lihat --}}
                        <button class="btn-icon btn-view"
                            onclick="openVerifikasiModal(
                                '{{ optional($mahasiswa)->nim ?? '-' }}',
                                '{{ addslashes(optional($user_mhs)->name ?? '-') }}',
                                '{{ $milestone->jenis_milestone }}',
                                '{{ $milestone->tanggal_upload ? \Carbon\Carbon::parse($milestone->tanggal_upload)->format('d M Y') : '-' }}',
                                {{ json_encode($milestone->file_path) }},
                                '{{ addslashes($milestone->catatan_revisi ?? '-') }}'
                            )">
                            <i class="fas fa-eye"></i>
                        </button>
                        {{-- Setujui --}}
                        <button class="btn-icon btn-approve"
                            onclick="openApproveModal('milestone', {{ $milestone->id }})">
                            <i class="fas fa-check"></i>
                        </button>
                        {{-- Tolak --}}
                        <button class="btn-icon btn-reject"
                            onclick="openRejectModal('milestone', {{ $milestone->id }})">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#aaa; padding:20px;">
                        Tidak ada bukti milestone yang menunggu verifikasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- TABLE VERIFIKASI BUKTI BIMBINGAN --}}
    <div class="card-box mt-4" id="tabel-verifikasi-bimbingan">
        <div class="card-header">
            <span>Verifikasi Bukti Bimbingan</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jadwal Bimbingan</th>
                    <th>Catatan Mahasiswa</th>
                    <th>Berkas Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($verifikasiBimbingans as $vb)
                @php
                    $mahasiswa_vb = optional($vb->tugasAkhir)->mahasiswa;
                    $user_mhs_vb  = optional($mahasiswa_vb)->user;
                @endphp
                <tr>
                    <td>{{ optional($mahasiswa_vb)->nim ?? '-' }}</td>
                    <td>{{ optional($user_mhs_vb)->name ?? '-' }}</td>
                    <td>{{ $vb->updated_at ? \Carbon\Carbon::parse($vb->updated_at)->format('d M Y') : '-' }}</td>
                    <td>{{ Str::limit($vb->catatan_mahasiswa ?? $vb->deskripsi, 50) }}</td>
                    <td>
                        @if($vb->file_dokumen)
                            <a href="{{ asset('storage/'.$vb->file_dokumen) }}" target="_blank" class="btn-proof">
                                📄 Lihat
                            </a>
                        @else
                            <span style="color:#aaa">-</span>
                        @endif
                        @if($vb->link_kegiatan)
                            <a href="{{ $vb->link_kegiatan }}" target="_blank" style="font-size:12px; margin-left:5px;">🔗 Link</a>
                        @endif
                    </td>
                    <td><span class="status-badge verify">Menunggu</span></td>
                    <td class="action-buttons">
                        {{-- Detail --}}
                        <button class="btn-icon btn-view"
                            onclick="openVerifikasiBimbinganModal(
                                '{{ optional($mahasiswa_vb)->nim ?? '-' }}',
                                '{{ addslashes(optional($user_mhs_vb)->name ?? '-') }}',
                                '{{ \Carbon\Carbon::parse($vb->tanggal)->format('d M Y') }}',
                                '{{ addslashes($vb->nama_dokumen ?? $vb->nama_kegiatan ?? '-') }}',
                                '{{ addslashes($vb->catatan_mahasiswa ?? $vb->deskripsi ?? '-') }}',
                                '{{ $vb->file_dokumen ? asset('storage/'.$vb->file_dokumen) : '' }}',
                                '{{ $vb->link_kegiatan ?? '' }}'
                            )">
                            <i class="fas fa-eye"></i>
                        </button>
                        {{-- Setujui --}}
                        <button class="btn-icon btn-approve"
                            onclick="openApproveModal('bimbingan_verifikasi', {{ $vb->id }})">
                            <i class="fas fa-check"></i>
                        </button>
                        {{-- Tolak --}}
                        <button class="btn-icon btn-reject"
                            onclick="openRejectModal('bimbingan_verifikasi', {{ $vb->id }})">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#aaa; padding:20px;">
                        Tidak ada bukti bimbingan yang menunggu verifikasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


{{-- Modal Pengajuan Bimbingan --}}
<div id="pengajuanModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Detail Pengajuan Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('pengajuanModal')">✖</span>
        </div>
        <div class="modal-body">
            <div class="log-grid">
                <div class="label">NIM</div>
                <div class="colon">:</div>
                <div class="value" id="pm-nim"></div>

                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value" id="pm-nama"></div>

                <div class="label">Tanggal Pengajuan</div>
                <div class="colon">:</div>
                <div class="value" id="pm-tgl-pengajuan"></div>

                <div class="label">Rencana Bimbingan</div>
                <div class="colon">:</div>
                <div class="value" id="pm-rencana"></div>

                <div class="label">Nama Kegiatan</div>
                <div class="colon">:</div>
                <div class="value" id="pm-topik"></div>

                <div class="label">Catatan</div>
                <div class="colon">:</div>
                <div class="value" id="pm-catatan"></div>

                <div class="label">Status</div>
                <div class="colon">:</div>
                <div class="value"><span class="status-badge waiting">Menunggu</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Verifikasi Milestone --}}
<div id="verifikasiModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Detail Verifikasi Milestone</h4>
            <span class="close-modal" onclick="closeModal('verifikasiModal')">✖</span>
        </div>
        <div class="modal-body">
            <div class="log-grid">
                <div class="label">NIM</div>
                <div class="colon">:</div>
                <div class="value" id="vm-nim"></div>

                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value" id="vm-nama"></div>

                <div class="label">Jenis Milestone</div>
                <div class="colon">:</div>
                <div class="value" id="vm-jenis"></div>

                <div class="label">Tgl Upload</div>
                <div class="colon">:</div>
                <div class="value" id="vm-tgl"></div>

                <div class="label">Bukti Bimbingan</div>
                <div class="colon">:</div>
                <div class="value" id="vm-bukti"></div>

                <div class="label">Catatan Revisi</div>
                <div class="colon">:</div>
                <div class="value" id="vm-catatan"></div>

                <div class="label">Status</div>
                <div class="colon">:</div>
                <div class="value"><span class="status-badge verify">Menunggu Verifikasi</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Verifikasi Bukti Bimbingan --}}
<div id="verifikasiBimbinganModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Detail Bukti Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('verifikasiBimbinganModal')">✖</span>
        </div>
        <div class="modal-body">
            <div class="log-grid">
                <div class="label">NIM</div>
                <div class="colon">:</div>
                <div class="value" id="vbm-nim"></div>

                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value" id="vbm-nama"></div>

                <div class="label">Jadwal Bimbingan</div>
                <div class="colon">:</div>
                <div class="value" id="vbm-jadwal"></div>

                <div class="label">Nama Dokumen</div>
                <div class="colon">:</div>
                <div class="value" id="vbm-nama-dok"></div>

                <div class="label">Catatan Mahasiswa</div>
                <div class="colon">:</div>
                <div class="value" id="vbm-catatan"></div>

                <div class="label">Berkas & Link</div>
                <div class="colon">:</div>
                <div class="value" id="vbm-berkas"></div>

                <div class="label">Status</div>
                <div class="colon">:</div>
                <div class="value"><span class="status-badge verify">Menunggu Verifikasi</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Setujui --}}
<div id="approveModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Setujui</h4>
            <span class="close-modal" onclick="closeModal('approveModal')">✖</span>
        </div>
        <div class="modal-body">
            <div class="mb-2">Catatan (opsional):</div>
            <textarea id="approve-catatan" style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;" rows="3"></textarea>
        </div>
        <div class="modal-footer-log">
            <button class="btn-close-log" onclick="closeModal('approveModal')">Batal</button>
            <button class="btn-acc" onclick="submitAction('disetujui')">Setujui</button>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div id="rejectModal" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Tolak Pengajuan</h4>
            <span class="close-modal" onclick="closeModal('rejectModal')">✖</span>
        </div>
        <div class="modal-body">
            <div class="mb-2">Alasan penolakan:</div>
            <textarea id="reject-catatan" style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;" rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>
        </div>
        <div class="modal-footer-log">
            <button class="btn-close-log" onclick="closeModal('rejectModal')">Batal</button>
            <button class="btn-reject-btn" onclick="submitAction('ditolak')">Tolak</button>
        </div>
    </div>
</div>

{{-- Hidden form untuk submit --}}
<form id="actionForm" method="POST" style="display:none;">
    @csrf
    @method('POST')
    <input type="hidden" name="status" id="action-status">
    <input type="hidden" name="catatan" id="action-catatan">
</form>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dosen/dosen-shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dosen/d-dashboard.css') }}">
@endpush

@push('scripts')
<script>
    window.dashboardData = {
        milestoneLabels: @json(array_keys($milestoneStats)),
        milestoneSudah: @json(array_column($milestoneStats, 'sudah')),
        milestoneBelum: @json(array_column($milestoneStats, 'belum')),
        statsByYear: @json($statsByYear ?? []),
        chartBimbingan: @json($chartBimbingan)
    };
</script>
<script src="{{ asset('js/dosen/d-dashboard.js') }}"></script>
@endpush
