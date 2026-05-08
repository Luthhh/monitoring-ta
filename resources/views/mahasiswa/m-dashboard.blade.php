@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('page-content')

<div class="page-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Dashboard Tugas Akhir</h4>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted" style="font-size:13px;">Judul: <strong>{{ Str::limit($tugasAkhir?->judul ?? '-', 40) }}</strong></span>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editJudulModal">
                <i class="bi bi-pencil-fill me-1"></i> Edit Judul
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Peringatan Deadline Milestone --}}
    @php
        $activeMs = null;
        foreach($milestoneMapping as $id => $name) {
            $m = $milestones->get($name);
            if ($m && $m->status !== 'disetujui') {
                $activeMs = $m;
                break;
            }
        }
        
        $isPast = false;
        $isNear = false;
        $daysLeft = 0;

        if ($activeMs && $activeMs->deadline) {
            $deadline = \Carbon\Carbon::parse($activeMs->deadline)->startOfDay();
            $today = now()->startOfDay();
            
            if ($today->gt($deadline)) {
                $isPast = true;
            } else {
                $daysLeft = $today->diffInDays($deadline);
                if ($daysLeft <= 7) {
                    $isNear = true;
                }
            }
        }
    @endphp

    @if($isPast)
        <div class="alert alert-danger d-flex align-items-center gap-3 py-3 shadow-sm border-0" role="alert" style="border-radius: 12px; background: #fff5f5; border-left: 5px solid #e53e3e !important;">
            <div style="font-size: 24px;">🚨</div>
            <div>
                <h6 class="alert-heading fw-bold mb-1" style="color: #c53030;">Deadline Terlewat!</h6>
                <p class="mb-0" style="color: #742a2a; font-size: 14px;">
                    Anda telah melewati target waktu untuk milestone <strong>{{ $activeMs->jenis_milestone }}</strong> 
                    ({{ \Carbon\Carbon::parse($activeMs->deadline)->format('d M Y') }}). 
                    Segera lakukan bimbingan dan upload bukti kegiatan Anda.
                </p>
            </div>
        </div>
    @elseif($isNear)
        <div class="alert alert-warning d-flex align-items-center gap-3 py-3 shadow-sm border-0" role="alert" style="border-radius: 12px; background: #fffdf2; border-left: 5px solid #d69e2e !important;">
            <div style="font-size: 24px;">⚠️</div>
            <div>
                <h6 class="alert-heading fw-bold mb-1" style="color: #975a16;">Mendekati Deadline</h6>
                <p class="mb-0" style="color: #744210; font-size: 14px;">
                    Target waktu untuk milestone <strong>{{ $activeMs->jenis_milestone }}</strong> adalah 
                    <strong>{{ $daysLeft == 0 ? 'Hari Ini!' : ($daysLeft . ' hari lagi') }}</strong> 
                    ({{ \Carbon\Carbon::parse($activeMs->deadline)->format('d M Y') }}). Jangan sampai terlewat!
                </p>
            </div>
        </div>
    @endif
    
    {{-- Hitung bimbingan selesai di awal --}}
    @php
        $jumlahBimbinganSelesai = $tugasAkhir ? $tugasAkhir->bimbingans->where('status', 'selesai')->count() : 0;
    @endphp

    <!-- Status Verifikasi -->
    <div class="card card-custom p-4 mb-4">
        <div class="status-header mb-3">
            <h6 class="fw-semibold m-0">Status Verifikasi</h6>


            <div class="status-actions">


                @if(!$mahasiswa->pembimbing1_id)
                    <span class="btn-status upload btn-disabled"
                          title="Pilih dosen pembimbing dulu di halaman Profil"
                          style="opacity:0.5; cursor:not-allowed; pointer-events:none;">
                        🔒 Upload Verifikasi
                    </span>
                @elseif($jumlahBimbinganSelesai < 1)
                    <span class="btn-status upload btn-disabled"
                          title="Anda harus melakukan minimal 1 kali bimbingan yang sudah diverifikasi (Selesai) untuk mengupload milestone."
                          style="opacity:0.5; cursor:not-allowed; pointer-events:none; background: #6c757d;">
                        🔒 Upload Verifikasi
                    </span>
                @else
                    <button type="button" data-bs-toggle="modal" data-bs-target="#uploadModal" class="btn-status upload" style="border:none; cursor:pointer;">
                        Upload Verifikasi
                    </button>
                @endif

                @if(!$mahasiswa->pembimbing1_id)
                    <span class="btn-status timeline btn-disabled"
                          title="Pilih dosen pembimbing dulu di halaman Profil"
                          style="opacity:0.5; cursor:not-allowed; pointer-events:none;">
                        🔒 Ubah Timeline
                    </span>
                @else
                    <button type="button" data-bs-toggle="modal" data-bs-target="#timelineModal" class="btn-status timeline" style="border:none; cursor:pointer;">
                        Ubah Timeline
                    </button>
                @endif
            </div>
        </div>

        {{-- Banner peringatan belum pilih dospem --}}
        @if(!$mahasiswa->pembimbing1_id)
        <div style="background:#fff3cd; border:1px solid #ffc107; border-radius:10px; padding:14px 16px; margin-bottom:16px; display:flex; align-items:center; gap:12px;">
            <span style="font-size:22px;">⚠️</span>
            <div>
                <strong style="color:#856404;">Dosen Pembimbing belum dipilih</strong>
                <p style="margin:4px 0 0; font-size:13px; color:#856404;">
                    Anda perlu memilih Dosen Pembimbing sebelum dapat mengupload verifikasi milestone atau mengubah timeline.
                    <a href="{{ route('mahasiswa.profile') }}" style="color:#856404; font-weight:600; text-decoration:underline;">
                        → Lengkapi Profil Sekarang
                    </a>
                </p>
            </div>
        </div>
        @endif

        @php
            $ms = function($id) use ($milestones, $milestoneMapping) {
                $name = $milestoneMapping[$id] ?? null;
                if (!$name) return '';
                $m = $milestones->get($name);
                if (!$m) return '';
                if ($m->status == 'disetujui') return 'active';
                if ($m->status == 'menunggu_verifikasi') return 'bg-warning text-dark border-0';
                return '';
            };
            $ms_date = function($id) use ($milestones, $milestoneMapping) {
                $name = $milestoneMapping[$id] ?? null;
                if (!$name) return 'Belum diatur';
                $m = $milestones->get($name);
                if (!$m || !$m->deadline) return 'Belum diatur';
                return \Carbon\Carbon::parse($m->deadline)->format('d M Y');
            };
        @endphp
        <div class="row text-center milestone-wrapper">
        
            <div class="col">
                <div class="step-circle {{ $ms(1) }}" data-date="{{ $ms_date(1) }}">1</div>
                <div class="step-label">
                    Penetapan<br>
                    Komisi<br>
                    Pembimbing
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(2) }}" data-date="{{ $ms_date(2) }}">2</div>
                <div class="step-label">
                    Sidang<br>
                    Komisi<br>
                    1
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(3) }}" data-date="{{ $ms_date(3) }}">3</div>
                <div class="step-label">Kolokium</div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(4) }}" data-date="{{ $ms_date(4) }}">4</div>
                <div class="step-label">Proposal</div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(5) }}" data-date="{{ $ms_date(5) }}">5</div>
                <div class="step-label">
                    Penelitian<br>
                    dan<br>
                    Bimbingan
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(6) }}" data-date="{{ $ms_date(6) }}">6</div>
                <div class="step-label">
                    Evaluasi<br>
                    dan<br>
                    Monitoring
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(7) }}" data-date="{{ $ms_date(7) }}">7</div>
                <div class="step-label">
                    Sidang<br>
                    Komisi<br>
                    2
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(8) }}" data-date="{{ $ms_date(8) }}">8</div>
                <div class="step-label">Seminar</div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(9) }}" data-date="{{ $ms_date(9) }}">9</div>
                <div class="step-label">
                    Publikasi<br>
                    Ilmiah
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(10) }}" data-date="{{ $ms_date(10) }}">10</div>
                <div class="step-label">
                    Ujian<br>
                    Tesis
                </div>
            </div>
            <div class="col">
                <div class="step-circle {{ $ms(11) }}" data-date="{{ $ms_date(11) }}">11</div>
                <div class="step-label">SKL</div>
            </div>
        </div>
    </div>
</div>
<div class="page-wrapper">
    <h2 class="page-title">Riwayat Pengajuan Bimbingan</h2>

    {{-- TABLE PENGAJUAN BIMBINGAN --}}
    <div class="card-box">
        <div class="table-header">
            <h3>Pengajuan Bimbingan</h3>
            <a href="{{ url('/mahasiswa/tambah-bimbingan') }}" class="btn-create">
            + Ajukan Bimbingan
            </a>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>Jadwal Bimbingan</th>
                    <th>Tempat</th>
                    <th>Jadwal Terlaksana</th>
                    <th>Catatan Mahasiswa</th>
                    <th>Catatan Dosen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
                      @forelse($bimbingans as $bimbingan)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y') }}{{ $bimbingan->waktu ? ' (' . \Carbon\Carbon::parse($bimbingan->waktu)->format('H:i') . ')' : '' }}</td>
                    <td>{{ $bimbingan->tempat ?? '-' }}</td>
                    <td>
                        @if($bimbingan->status === 'selesai' || $bimbingan->status === 'menunggu_verifikasi')
                           {{ \Carbon\Carbon::parse($bimbingan->updated_at)->format('d M Y') }}
                        @else - @endif
                    </td>
                    <td>{{ Str::limit($bimbingan->catatan_mahasiswa ?? $bimbingan->deskripsi, 30) }}</td>
                    <td>{{ Str::limit($bimbingan->catatan, 30) }}</td>
                    <td>
                        @if($bimbingan->status == 'pending')
                            <span class="status-badge waiting">Menunggu</span>
                        @elseif($bimbingan->status == 'disetujui')
                            <span class="status-badge approved">Disetujui</span>
                        @elseif($bimbingan->status == 'menunggu_verifikasi')
                            <span class="status-badge waiting">Verifikasi</span>
                        @elseif($bimbingan->status == 'selesai')
                            <span class="status-badge approved" style="background:#d1f2eb; color:#0c6b58;">Selesai</span>
                        @elseif($bimbingan->status == 'ditolak')
                            <span class="status-badge pending">Ditolak</span>
                        @else
                            <span class="status-badge waiting">{{ ucfirst(str_replace('_', ' ', $bimbingan->status)) }}</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <button class="btn-icon btn-acc" onclick="openModal('bimbinganModal-{{ $bimbingan->id }}')" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($bimbingan->status == 'disetujui' && !$bimbingan->file_dokumen)
                        <button class="btn-icon btn-view ms-1 text-white border-0" style="padding: 6px 12px; border-radius: 6px;" onclick="openModal('uploadBuktiModal-{{ $bimbingan->id }}')" title="Upload Bukti">
                            <i class="fas fa-upload"></i>
                        </button>
                        @endif
                        @if($bimbingan->status == 'pending')
                        <form action="{{ route('mahasiswa.bimbingan.delete', $bimbingan->id) }}" method="POST" class="d-inline ms-1 form-confirm"
                               data-text="Hapus pengajuan ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-reject" title="Hapus" style="padding: 6px 12px; border-radius: 6px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Belum ada riwayat bimbingan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- TABLE RIWAYAT MILESTONE --}}
    <div class="card-box mt-4">
        <div class="table-header">
            <h3>Riwayat Milestone</h3>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>Milestone</th>
                    <th>Tgl Upload</th>
                    <th>Dokumen</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $mHistory = $milestones->filter(function($m) {
                        return in_array($m->status, ['menunggu_verifikasi', 'disetujui', 'ditolak']);
                    })->sortByDesc('tanggal_upload');
                @endphp
                @foreach($mHistory as $m)
                <tr>
                    <td class="fw-semibold">{{ $m->jenis_milestone }}</td>
                    <td>{{ $m->tanggal_upload ? \Carbon\Carbon::parse($m->tanggal_upload)->format('d M Y') : '-' }}</td>
                    <td>
                        @if(is_array($m->file_path))
                            @foreach($m->file_path as $key => $path)
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="badge bg-secondary text-decoration-none d-block mb-1">
                                    📄 {{ is_numeric($key) ? 'Bukti ' . ($key + 1) : ucfirst(str_replace('_', ' ', $key)) }}
                                </a>
                            @endforeach
                        @elseif($m->file_path)
                            <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="badge bg-secondary text-decoration-none d-block mb-1">
                                📄 Bukti
                            </a>
                        @endif

                        @if($m->file_bap)
                            <a href="{{ asset('storage/' . $m->file_bap) }}" target="_blank" class="badge bg-success text-decoration-none d-block">
                                📄 BAP Milestone
                            </a>
                        @endif

                        @if(!$m->file_path && !$m->file_bap)
                            -
                        @endif
                    </td>
                    <td>
                        @if($m->status == 'disetujui')
                            <span class="status-badge approved">Disetujui</span>
                        @elseif($m->status == 'menunggu_verifikasi')
                            <span class="status-badge waiting">Menunggu Verifikasi</span>
                        @elseif($m->status == 'ditolak')
                            <span class="status-badge pending">Ditolak / Revisi</span>
                        @endif
                    </td>
                    <td>{{ $m->catatan_revisi ?? '-' }}</td>
                </tr>
                @endforeach
                @if($mHistory->isEmpty())
                <tr>
                    <td colspan="5">Belum ada riwayat milestone.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@foreach($bimbingans as $bimbingan)
{{-- Modal Detail Bimbingan --}}
<div id="bimbinganModal-{{ $bimbingan->id }}" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Detail Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('bimbinganModal-{{ $bimbingan->id }}')">✖</span>
        </div>
        <div class="modal-body">

            <div class="log-grid">
                <div class="label">NIM</div>
                <div class="colon">:</div>
                <div class="value">{{ $mahasiswa->nim }}</div>

                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value">{{ $mahasiswa->user->name }}</div>

                <div class="label">Tanggal</div>
                <div class="colon">:</div>
                <div class="value">{{ \Carbon\Carbon::parse($bimbingan->tanggal)->format('d M Y') }}</div>

                <div class="label">Waktu & Tempat</div>
                <div class="colon">:</div>
                <div class="value">{{ $bimbingan->waktu ? \Carbon\Carbon::parse($bimbingan->waktu)->format('H:i') : '-' }} - {{ $bimbingan->tempat ?? '-' }}</div>

                <div class="label">Deskripsi</div>
                <div class="colon">:</div>
                <div class="value">{{ $bimbingan->deskripsi ?? '-' }}</div>

                <div class="label">Catatan Dosen</div>
                <div class="colon">:</div>
                <div class="value">{{ $bimbingan->catatan ?? '-' }}</div>

                <div class="label">Catatan Tambahan (Mhs)</div>
                <div class="colon">:</div>
                <div class="value">{{ $bimbingan->catatan_mahasiswa ?? '-' }}</div>

                <div class="label">Dokumen Bukti</div>
                <div class="colon">:</div>
                <div class="value">
                    @if($bimbingan->file_dokumen)
                        <a href="{{ asset('storage/' . $bimbingan->file_dokumen) }}" target="_blank" class="btn-proof text-decoration-none">
                            📄 Lihat Dokumen
                        </a>
                    @else
                        -
                    @endif
                </div>

                <div class="label">Status</div>
                <div class="colon">:</div>
                <div class="value">
                     @if($bimbingan->status == 'pending') <span class="status-badge waiting">Menunggu</span>
                     @elseif($bimbingan->status == 'disetujui') <span class="status-badge approved">Disetujui</span>
                     @elseif($bimbingan->status == 'menunggu_verifikasi') <span class="status-badge waiting">Verifikasi</span>
                     @elseif($bimbingan->status == 'selesai') <span class="status-badge approved" style="background:#d1f2eb; color:#0c6b58;">Selesai</span>
                     @elseif($bimbingan->status == 'ditolak') <span class="status-badge pending">Ditolak</span>
                     @else <span class="status-badge waiting">{{ ucfirst($bimbingan->status) }}</span>
                     @endif
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f1f2f6; padding-top: 15px; margin-top: 15px; text-align: right;">
                <button class="btn-cancel" onclick="closeModal('bimbinganModal-{{ $bimbingan->id }}')" style="background:#f1f2f6; border:none; padding:8px 16px; border-radius:8px; cursor:pointer;">Tutup</button>
            </div>
        </div>
    </div>
</div>

@if($bimbingan->status == 'disetujui' && !$bimbingan->file_dokumen)
{{-- Modal Upload Bukti Setelah Disetujui --}}
<div id="uploadBuktiModal-{{ $bimbingan->id }}" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Upload Bukti Kegiatan</h4>
            <span class="close-modal" onclick="closeModal('uploadBuktiModal-{{ $bimbingan->id }}')">✖</span>
        </div>
        <form action="{{ url('/mahasiswa/upload-bukti-bimbingan/' . $bimbingan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Dokumen <span style="color:red">*</span></label>
                    <input type="text" name="nama_dokumen" class="form-control" placeholder="Contoh: Sertifikat, Draft Revisi, Lembar Hadir" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">File Dokumen <span style="color:red">*</span></label>
                    <input type="file" name="file_dokumen" class="form-control" accept=".pdf,.doc,.docx" required>
                    <small class="text-muted">Maksimal 2MB (.pdf, .doc, .docx)</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Link (Opsional)</label>
                    <input type="url" name="link_kegiatan" class="form-control" placeholder="Contoh: Link drive atau repo github">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan Mahasiswa (Progres) <span style="color:red">*</span></label>
                    <textarea name="catatan_mahasiswa" class="form-control" rows="3" placeholder="Ceritakan progres yang telah dicapai..." required></textarea>
                </div>
            </div>
            <div class="modal-footer-log">
                <button type="button" class="btn-close-log" onclick="closeModal('uploadBuktiModal-{{ $bimbingan->id }}')">Batal</button>
                <button type="submit" class="btn-acc">Upload</button>
            </div>
        </form>
    </div>
</div>
@endif

@endforeach



@push('modals')
{{-- Modal Upload Verifikasi--}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Bukti Milestone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/mahasiswa/upload-verifikasi') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Milestone <span style="color:red">*</span></label>
                        <select name="milestone" class="form-control" required>
                            <option value="">-- Pilih Milestone --</option>
                            @php
                                $availableIndex = 1;
                                foreach($milestoneMapping as $id => $jenis) {
                                    $m = $milestones->get($jenis);
                                    if (!$m || $m->status !== 'disetujui') {
                                        $availableIndex = $id;
                                        break;
                                    }
                                    if ($id == 11) {
                                        $availableIndex = 11;
                                    }
                                }
                            @endphp
                            @foreach($milestoneMapping as $id => $jenis)
                                <option value="{{ $jenis }}" {{ $id > $availableIndex ? 'disabled' : '' }}>
                                    {{ $jenis }} {{ $id > $availableIndex ? '(Terkunci)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="milestone-note" class="alert alert-info py-2 px-3 mb-3 d-none" style="font-size: 13px;">
                        <i class="fas fa-info-circle me-2"></i> <span id="note-text"></span>
                    </div>

                    <div id="file-inputs-container">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" id="main-file-label">Upload Bukti 1 <span style="color:red">*</span></label>
                            <input type="file" name="bukti_file[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small style="text-muted">Format: PDF/JPG/PNG (max 4MB)</small>
                        </div>
                        <div class="mb-3" id="secondary-file-container">
                            <label class="form-label fw-semibold" id="secondary-file-label">Upload Bukti 2 (Opsional)</label>
                            <input type="file" name="bukti_file[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <div class="mb-2">Catatan :</div>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan (opsional)"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Ubah Timeline --}}
<div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timelineModalLabel">Pengaturan Timeline TA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/mahasiswa/update-timeline') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <p class="text-muted small mb-3">Tentukan target deadline untuk setiap tahapan (milestone) Tugas Akhir Anda. Milestone yang sudah selesai tidak dapat diubah.</p>
                    
                    <div class="timeline-inputs">
                        @foreach($milestoneMapping as $id => $jenis)
                            @php $m = $milestones->get($jenis); @endphp
                            <div class="mb-3 p-2 border-bottom {{ $m && $m->status == 'disetujui' ? 'bg-light opacity-75' : '' }}">
                                <label class="form-label fw-bold small mb-1">
                                    {{ $id }}. {{ $jenis }}
                                    @if($m && $m->status == 'disetujui')
                                        <span class="badge bg-success ms-2" style="font-size: 10px;">SELESAI</span>
                                    @endif
                                </label>
                                <input type="date" 
                                       name="milestones[{{ $jenis }}]" 
                                       class="form-control form-control-sm"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ $m && $m->deadline ? $m->deadline : '' }}"
                                       {{ $m && $m->status == 'disetujui' ? 'readonly' : 'required' }}>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Timeline</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Judul Tugas Akhir --}}
<div class="modal fade" id="editJudulModal" tabindex="-1" aria-labelledby="editJudulModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJudulModalLabel"><i class="bi bi-pencil-fill me-2"></i>Edit Judul Tugas Akhir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mahasiswa.update-judul') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Penelitian</label>
                        <textarea name="judul" class="form-control" rows="4"
                            placeholder="Masukkan judul tugas akhir Anda..."
                            required>{{ ($tugasAkhir && $tugasAkhir->judul != 'Belum ada judul') ? $tugasAkhir->judul : '' }}</textarea>
                        <small class="text-muted">Maksimal 500 karakter.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika Pop-up Peringatan Deadline
        @if(isset($isPast) && $isPast)
            Swal.fire({
                title: '🚨 Deadline Terlewat!',
                html: 'Anda telah melewati target waktu untuk milestone <br><strong>{{ $activeMs->jenis_milestone }}</strong>.<br><br>Mohon segera lakukan bimbingan dan upload bukti!',
                icon: 'error',
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#e53e3e'
            });
        @elseif(isset($isNear) && $isNear)
            Swal.fire({
                title: '⚠️ Mendekati Deadline',
                html: 'Target waktu untuk milestone <br><strong>{{ $activeMs->jenis_milestone }}</strong> tinggal <br><strong>{{ $daysLeft == 0 ? "Hari Ini!" : ($daysLeft . " hari lagi") }}</strong>.',
                icon: 'warning',
                confirmButtonText: 'Siap, Saya Segerakan',
                confirmButtonColor: '#d69e2e'
            });
        @endif
    });
</script>
@endpush

@endsection



@push('styles')
<style>

.card-custom {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.step-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    font-size: 14px;
    font-weight: 500;
    position: relative;
    z-index: 2; /* 🔥 ini penting */
    cursor: pointer;
}

.step-circle::after {
    content: attr(data-date);
    position: absolute;
    bottom: 125%; /* Memunculkan popup di atas bulatan */
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 400;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, transform 0.3s ease;
    pointer-events: none;
    z-index: 10;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.step-circle::before {
    content: '';
    position: absolute;
    bottom: 105%; /* Segitiga panah kecill ke bulatan */
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
    z-index: 10;
}

.step-circle:hover::after,
.step-circle:hover::before {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-5px); /* Efek melayang */
}

.step-circle.active {
    background: #4e73df;
    color: white;
}

.step-label {
    font-size: 13px;
    margin-top: 5px;
    line-height: 1.2;
}

.btn-primary {
    background-color: #4e73df;
    border: none;
    border-radius: 10px;
}

.btn-danger {
    border-radius: 10px;
}

.badge-warning {
    background-color: #f6c23e;
}
.page-wrapper {
    padding: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 20px;
}

/* CARD BOX */
.card-box {
    background: white;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 25px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}

/* HEADER */
.card-header {
    font-weight: 600;
    margin-bottom: 12px;
}
/* TABLE */

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.table-custom th {
    background: #f8f9fc;
    padding: 10px;
    font-size: 13px;
    text-align: center;
    color: #6c757d;
}

.table-custom td {
    padding: 10px;
    border-top: 1px solid #eee;
    font-size: 14px;
    word-wrap: break-word;
}

table th, table td {
    padding: 14px;
    text-align: center;
    font-size: 14px;
    vertical-align: middle;
}


td {
    word-break: break-word;
}

/* STATUS BADGE */
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.waiting {
    background: #fff4e5;
    color: #f6a500;
}

.approved {
    background: #e6f7ee;
    color: #1cc88a;
}

.verify {
    background: #e7f1ff;
    color: #4e73df;
}

.valid {
    background: #e6f7ee;
    color: #1cc88a;
}


/* BUTTON */
.btn-acc {
    background: #1cc88a;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-reject {
    background: #e74a3b;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-detail {
    background: #36b9cc;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-proof {
    background: #858796;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
}
/* badge status */
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

/* sudah = hijau */
.status-badge.done {
    background: #e6f7ee;
    color: #1cc88a;
}

/* belum = merah */
.status-badge.pending {
    background: #fde8e8;
    color: #e74a3b;
}

.btn-view-log {
    border: none;
    padding: 6px 14px;
    border-radius: 8px;
    background: #36b9cc;
    color: white;
    font-size: 13px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-view-log:hover {
    background: #2c9faf;
}


/* Warna */
.btn-view {
    background: #0dcaf0;
}

.btn-approve {
    background: #198754;
}

.btn-reject {
    background: #dc3545;
}

/* Hover */
.btn-icon:hover {
    transform: scale(1.05);
    opacity: 0.9;
}
.modal-log {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(4px);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-content-log {
    background: white;
    width: 540px;
    max-width: 100%;
    border-radius: 20px;
    padding: 24px;
    animation: fadeIn .25s ease;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    border: 1px solid #eef2f7;
    position: relative;
    z-index: 10001;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}

.modal-header h4 {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}

.close-modal {
    cursor: pointer;
    font-size: 18px;
}

.log-grid {
    display: grid;
    grid-template-columns: 140px 10px 1fr; /* Sedikit dipersempit labelnya */
    gap: 12px 14px;
    font-size: 14px;
    align-items: start;
}

.value {
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    color: #374151;
    line-height: 1.5;
}

.label {
    color: #6b7280;
    font-weight: 500;
}

.colon {
    text-align: center;
    color: #6b7280;
    font-weight: 600;
}

.modal-actions {
    text-align: right;
    margin-top: 20px;
}

@keyframes fadeIn {
    from {transform: scale(0.95); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}
.modal-footer-log {
    text-align: right;
    margin-top: 20px;
}

.btn-close-log {
    background: #858796;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 8px;
    cursor: pointer;
}
.btn-create {
    background: #02048d;
    color: white;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: 0.2s;
}

.btn-create:hover {
    background: #1a1bb8;
    color: white;
}

/* HEADER TABLE */
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.table-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

/* HEADER STATUS */
.status-header {
    display: flex;
    justify-content: space-between; /* kiri-kanan */
    align-items: center;            /* sejajar vertikal */
    flex-wrap: wrap;                /* biar responsif */
}

.status-actions {
    display: flex;
    gap: 10px;
}

.btn-status {
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    color: white;
    transition: 0.2s;
}

/* Upload */
.btn-status.upload {
    background: #4e73df;
}

.btn-status.upload:hover {
    background: #2e59d9;
}

/* Timeline */
.btn-status.timeline {
    background: #36b9cc;
}

.btn-status.timeline:hover {
    background: #2c9faf;
}
/* =========================
   PROGRESS LINE MILESTONE
========================= */

.milestone-wrapper {
    position: relative;
}

/* garis lurus */
.milestone-wrapper::before {
    content: "";
    position: absolute;
    top: 18px;              /* sejajar tengah circle */
    left: 4%;
    right: 4%;
    height: 3px;
    background: #dee2e6;    /* abu dulu */
    z-index: 1;
    border-radius: 10px;
}
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openModal(id) {
    var el = document.getElementById(id);
    if (el) {
        el.style.display = "flex";
    }
}

function closeModal(id) {
    var el = document.getElementById(id);
    if (el) {
        el.style.display = "none";
    }
}
/* Klik luar modal untuk close */
window.addEventListener('click', function(e) {
    document.querySelectorAll('.modal-log').forEach(function(modal) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});


const milestoneSelect = document.querySelector('select[name="milestone"]');
if (milestoneSelect) {
    milestoneSelect.addEventListener('change', function() {
        const val = this.value;
        const noteDiv = document.getElementById('milestone-note');
        const noteText = document.getElementById('note-text');
        const label1 = document.getElementById('main-file-label');
        const label2 = document.getElementById('secondary-file-label');
        const container2 = document.getElementById('secondary-file-container');

        const requirements = {
            'Penetapan Komisi Pembimbing': { note: 'SK Dosen', l1: 'SK Dosen', l2: '' },
            'Sidang Komisi 1': { note: 'Undangan + BAP', l1: 'Undangan', l2: 'BAP' },
            'Kolokium': { note: 'Undangan + BAP', l1: 'Undangan', l2: 'BAP' },
            'Proposal': { note: 'File Proposal PDF', l1: 'File Proposal PDF', l2: '' },
            'Sidang Komisi 2': { note: 'Undangan + BAP', l1: 'Undangan', l2: 'BAP' },
            'Seminar': { note: 'BAP + Undangan', l1: 'Undangan', l2: 'BAP' },
            'Publikasi Ilmiah': { note: 'Jurnal', l1: 'Jurnal', l2: '' },
            'Ujian Tesis': { note: 'Dokumen Seminar', l1: 'Dokumen Seminar', l2: '' },
            'SKL': { note: 'File SKL + IPK', l1: 'File SKL', l2: 'IPK' }
        };

        if (requirements[val]) {
            const req = requirements[val];
            noteDiv.classList.remove('d-none');
            noteText.innerText = 'Persyaratan: ' + req.note;
            label1.innerHTML = req.l1 + ' <span style="color:red">*</span>';
            
            if (req.l2) {
                container2.classList.remove('d-none');
                label2.innerText = req.l2 + ' (Opsional)';
            } else {
                container2.classList.add('d-none');
            }
        } else {
            noteDiv.classList.add('d-none');
            label1.innerHTML = 'Upload Bukti 1 <span style="color:red">*</span>';
            container2.classList.remove('d-none');
            label2.innerText = 'Upload Bukti 2 (Opsional)';
        }
    });
}

@if(isset($milestoneAlertTitle) && $milestoneAlertTitle)
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "{!! $milestoneAlertTitle !!}",
            text: "{!! $milestoneAlertText !!}",
            icon: "{{ $milestoneAlertType }}",
            confirmButtonText: "Tutup",
            confirmButtonColor: "#4361ee"
        });
    });
@endif
</script>
@endpush
