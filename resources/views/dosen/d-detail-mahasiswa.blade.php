@extends('layouts.dosen')

@section('title', 'Detail Mahasiswa - ' . ($mahasiswa->user->name ?? ''))

@section('page-content')

<div class="page-wrapper">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dosen.data_mahasiswa') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h2 class="mb-0">Detail Mahasiswa</h2>
    </div>

    @if(session('success'))
        <div class="alert-success mb-3">✅ {{ session('success') }}</div>
    @endif

    {{-- Informasi Mahasiswa --}}
    <div class="card mb-4">
        <div class="card-title-flex">
            <h3 class="section-title">👤 Informasi Mahasiswa</h3>
        </div>
        <div class="d-flex flex-column align-items-center mb-4">
            <div class="profile-photo-wrapper">
                @if($mahasiswa->foto)
                    <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Profil" class="student-profile-photo">
                @else
                    <div class="student-profile-photo-placeholder">
                        <i class="bi bi-person-fill fs-1 text-muted"></i>
                    </div>
                @endif
            </div>
        </div>
        <div class="info-grid">
            <div><span class="lbl">NIM</span><span class="colon">:</span><span class="val">{{ $mahasiswa->nim }}</span></div>
            <div><span class="lbl">Nama</span><span class="colon">:</span><span class="val">{{ $mahasiswa->user->name ?? '-' }}</span></div>
            <div><span class="lbl">Email</span><span class="colon">:</span><span class="val">{{ $mahasiswa->user->email ?? '-' }}</span></div>
            <div><span class="lbl">Prodi</span><span class="colon">:</span><span class="val">{{ $mahasiswa->prodi ?? '-' }}</span></div>
            <div><span class="lbl">Semester</span><span class="colon">:</span><span class="val">{{ $mahasiswa->semester ?? '-' }}</span></div>
            <div><span class="lbl">Tahun Masuk</span><span class="colon">:</span><span class="val">{{ $mahasiswa->angkatan_formatted }}</span></div>
            <div><span class="lbl">Pembimbing 1</span><span class="colon">:</span><span class="val">{{ optional($mahasiswa->pembimbing1)->user->name ?? '-' }}</span></div>
            <div><span class="lbl">Pembimbing 2</span><span class="colon">:</span><span class="val">{{ optional($mahasiswa->pembimbing2)->user->name ?? '-' }}</span></div>
            <div><span class="lbl">SK Pembimbing</span><span class="colon">:</span><span class="val">
                @if($mahasiswa->sk_pembimbing)
                    <a href="{{ asset('storage/' . $mahasiswa->sk_pembimbing) }}" target="_blank" class="btn btn-outline-primary btn-sm" style="font-size: 11px; padding: 2px 8px;">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Lihat SK PDF
                    </a>
                @else
                    <span class="text-muted">Belum ada dokumen SK</span>
                @endif
            </span></div>
        </div>
    </div>

    {{-- Status Tugas Akhir --}}
    @if($tugasAkhir)
    <div class="card mb-4">
        <h3 class="section-title">📊 Status Tugas Akhir</h3>
        <div class="info-grid">
            <div><span class="lbl">Judul</span><span class="colon">:</span><span class="val">{{ $tugasAkhir->judul }}</span></div>
            <div><span class="lbl">Status</span><span class="colon">:</span><span class="val"><span class="status-badge {{ strtolower($tugasAkhir->status) }}">{{ $tugasAkhir->status }}</span></span></div>
        </div>

        @php
            $milestoneList = [
                'Penetapan Komisi Pembimbing','Sidang Komisi 1','Kolokium','Proposal',
                'Penelitian dan Bimbingan','Evaluasi dan Monitoring','Sidang Komisi 2',
                'Seminar','Publikasi Ilmiah','Ujian Tesis','SKL'
            ];
            $approvedCount = $milestones->where('status','disetujui')->count();
            $progress = round(($approvedCount / 11) * 100);
        @endphp

        <div class="mt-3">
            <div class="d-flex justify-content-between mb-1" style="font-size:13px; color:#64748b;">
                <span>Progress Milestone</span>
                <span>{{ $approvedCount }}/11 ({{ $progress }}%)</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $progress }}%"></div>
            </div>
        </div>

        <div class="milestone-grid mt-3">
            @foreach($milestoneList as $i => $jenis)
                @php $ms = $milestones->get($jenis); @endphp
                <div class="milestone-item {{ $ms && $ms->status ? $ms->status : 'pending' }}">
                    <div class="milestone-num">{{ $i + 1 }}</div>
                    <div class="milestone-name">{{ $jenis }}</div>
                    <div class="ms-deadline" style="font-size: 11px; color: #64748b; font-weight: 500;">
                        Target: {{ $ms && $ms->deadline ? \Carbon\Carbon::parse($ms->deadline)->format('d M Y') : '-' }}
                    </div>
                    <div class="ms-status" style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                        <div>
                            @if(!$ms || $ms->status === 'pending') <span class="ms-badge pending">Belum Ada</span>
                            @elseif($ms->status === 'menunggu_verifikasi') <span class="ms-badge waiting">Menunggu</span>
                            @elseif($ms->status === 'disetujui') <span class="ms-badge done">✓ Selesai</span>
                            @elseif($ms->status === 'ditolak') <span class="ms-badge rejected">Ditolak</span>
                            @endif
                        </div>
                        @if($ms && $ms->file_path)
                            @if(is_array($ms->file_path))
                                @foreach($ms->file_path as $key => $path)
                                    <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $path) }}')" title="Lihat Bukti Dokumen" style="color: #4e73df; font-size: 14px; text-decoration: none; margin-right: 5px;">
                                        <i class="fas fa-file-pdf"></i> {{ is_numeric($key) ? 'Bukti ' . ($key + 1) : ucfirst(str_replace('_', ' ', $key)) }}
                                    </a>
                                @endforeach
                            @else
                                <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $ms->file_path) }}')" title="Lihat Bukti Dokumen" style="color: #4e73df; font-size: 14px; text-decoration: none;">
                                    <i class="fas fa-file-pdf"></i> Bukti
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Verifikasi Milestone --}}
    @if($tugasAkhir)
    <div class="card mb-4">
        <h3 class="section-title">🏆 Milestone & Dokumen</h3>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Milestone</th>
                    <th>Target Deadline</th>
                    <th>Tgl Upload</th>
                    <th>Dokumen</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milestoneList as $jenis)
                @php $m = $milestones->get($jenis); @endphp
                @if($m && $m->status !== 'pending')
                <tr>
                    <td class="fw-semibold">{{ $m->jenis_milestone }}</td>
                    <td class="text-primary fw-bold">{{ $m->deadline ? \Carbon\Carbon::parse($m->deadline)->format('d M Y') : '-' }}</td>
                    <td>{{ $m->tanggal_upload ? \Carbon\Carbon::parse($m->tanggal_upload)->format('d M Y') : '-' }}</td>
                    <td>
                        @if($m->file_path)
                            @if(is_array($m->file_path))
                                @foreach($m->file_path as $key => $path)
                                    <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $path) }}')" class="badge bg-secondary text-decoration-none d-block mb-1" style="color:white; padding: 4px 8px;">
                                        📄 {{ is_numeric($key) ? 'Bukti ' . ($key + 1) : ucfirst(str_replace('_', ' ', $key)) }}
                                    </a>
                                @endforeach
                            @else
                                <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $m->file_path) }}')" class="badge bg-secondary text-decoration-none" style="color:white; padding: 4px 8px;">
                                    📄 Bukti
                                </a>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($m->status == 'disetujui')
                            <span class="status-badge disetujui">Disetujui</span>
                        @elseif($m->status == 'menunggu_verifikasi')
                            <span class="status-badge menunggu">Menunggu Verifikasi</span>
                        @elseif($m->status == 'ditolak')
                            <span class="status-badge ditolak">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Log Bimbingan --}}
    <div class="card mb-4">
        <h3 class="section-title">📅 Log Bimbingan</h3>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Jadwal Bimbingan</th>
                    <th>Tempat</th>
                    <th>Catatan Mahasiswa</th>
                    <th>Catatan Dosen (Hasil)</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingans as $b)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}{{ $b->waktu ? ' (' . \Carbon\Carbon::parse($b->waktu)->format('H:i') . ')' : '' }}</td>
                    <td>{{ $b->tempat ?? '-' }}</td>
                    <td>{{ Str::limit($b->catatan_mahasiswa ?? $b->deskripsi, 30) }}</td>
                    <td>{{ Str::limit($b->catatan, 30) }}</td>
                    <td>
                        @if($b->status === 'pending') <span class="status-badge menunggu">Menunggu</span>
                        @elseif($b->status === 'disetujui') <span class="status-badge disetujui">Disetujui</span>
                        @elseif($b->status === 'ditolak') <span class="status-badge ditolak">Ditolak</span>
                        @elseif($b->status === 'selesai') <span class="status-badge disetujui">Selesai</span>
                        @else <span class="status-badge menunggu">{{ ucfirst($b->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn-icon btn-view" style="background:#0dcaf0; color:white; border:none; border-radius:6px; width:34px; height:34px; cursor:pointer;"
                                onclick="openBimbinganDetailModal(
                                    this.getAttribute('data-nama'),
                                    '{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}',
                                    '{{ $b->waktu }}',
                                    this.getAttribute('data-tempat'),
                                    this.getAttribute('data-deskripsi'),
                                    this.getAttribute('data-catatan-dosen'),
                                    this.getAttribute('data-catatan-mhs'),
                                    '{{ $b->file_dokumen ? asset('storage/'.$b->file_dokumen) : '' }}',
                                    this.getAttribute('data-dosen')
                                )"
                                data-nama="{{ $mahasiswa->user->name }}"
                                data-dosen="{{ $b->dosen->user->name ?? '-' }}"
                                data-tempat="{{ $b->tempat ?? '-' }}"
                                data-deskripsi="{{ $b->deskripsi ?? '' }}"
                                data-catatan-dosen="{{ $b->catatan ?? '' }}"
                                data-catatan-mhs="{{ $b->catatan_mahasiswa ?? '' }}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color:#aaa; padding:20px;">Belum ada bimbingan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.page-wrapper { padding: 28px; }
.btn-back { background:#e7f1ff; color:#4e73df; border:1px solid #d0e2ff; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-back:hover { background:#4e73df; color:white; }
.alert-success { background:#e6f7ee; color:#1a7f4b; border:1px solid #b7e4c7; border-radius:10px; padding:12px 18px; font-size:14px; }
.card { background:white; border-radius:14px; padding:22px; margin-bottom:20px; box-shadow:0 4px 14px rgba(0,0,0,0.06); border:1px solid #f1f1f1; }
.card-title-flex { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
.section-title { font-weight:700; font-size:16px; color:#1f2937; margin:0 0 16px; }

.profile-photo-wrapper {
    width: 100px;
    height: 100px;
    margin-bottom: 15px;
}

.student-profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #f1f5f9;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.student-profile-photo-placeholder {
    width: 100%;
    height: 100%;
    background: #f8fafc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #cbd5e1;
}

.info-grid { display:grid; gap:8px; }
.info-grid > div { display:grid; grid-template-columns:160px 14px 1fr; padding:8px 12px; border-radius:8px; align-items:start; }
.lbl { font-weight:600; color:#475569; font-size:13px; }
.colon { text-align:center; color:#94a3b8; }
.val { color:#0f172a; font-size:14px; }
.progress-bar { width:100%; height:10px; background:#e5e7eb; border-radius:999px; overflow:hidden; }
.progress-fill { height:100%; background:linear-gradient(90deg,#02048d,#4e73df); border-radius:999px; transition:width 0.4s; }
.milestone-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:10px; margin-top:12px; }
.milestone-item { border-radius:10px; padding:12px; border:1px solid #e2e8f0; display:flex; flex-direction:column; gap:6px; }
.milestone-item.disetujui { background:#f0fdf4; border-color:#bbf7d0; }
.milestone-item.menunggu_verifikasi { background:#fffbeb; border-color:#fde68a; }
.milestone-item.ditolak { background:#fff1f2; border-color:#fecdd3; }
.milestone-item.pending { background:#f8fafc; }
.milestone-num { width:24px; height:24px; background:#e2e8f0; border-radius:50%; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; color:#64748b; }
.milestone-name { font-size:12px; font-weight:600; color:#1e293b; }
.ms-badge { font-size:11px; padding:2px 8px; border-radius:12px; font-weight:600; }
.ms-badge.pending { background:#f1f5f9; color:#64748b; }
.ms-badge.waiting { background:#fff4e5; color:#f6a500; }
.ms-badge.done { background:#dcfce7; color:#16a34a; }
.ms-badge.rejected { background:#fee2e2; color:#dc2626; }
.table-custom { width:100%; border-collapse:collapse; }
.table-custom th { background:#f8f9fc; padding:12px; font-size:13px; color:#6c757d; font-weight:600; text-align:center; }
.table-custom td { padding:12px; border-top:1px solid #eee; font-size:13px; text-align:center; word-break:break-word; overflow-wrap: anywhere; }
.status-badge { padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }
.status-badge.menunggu { background:#fff4e5; color:#f6a500; }
.status-badge.disetujui { background:#e6f7ee; color:#1cc88a; }
.status-badge.ditolak { background:#ffe5e5; color:#e74a3b; }

.modal-log {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1050;
    align-items: center;
    justify-content: center;
}
.modal-content-log {
    background: white;
    width: 90%;
    max-width: 600px;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    animation: fadeIn 0.3s ease;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.modal-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}
.close-modal {
    cursor: pointer;
    font-size: 18px;
}
.log-grid {
    display: grid;
    grid-template-columns: 140px 10px 1fr;
    gap: 12px 14px;
    font-size: 14px;
    align-items: start;
}
.log-grid .value {
    word-break: break-word;
    overflow-wrap: anywhere;
    color: #374151;
    line-height: 1.5;
}
.log-grid .label {
    color: #6b7280;
    font-weight: 500;
}
@keyframes fadeIn {
    from {transform: scale(0.95); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}
.btn-cancel {
    background: #f1f2f6;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
}
</style>



<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

window.addEventListener('click', function(e) {
    document.querySelectorAll('.modal-log').forEach(function(modal) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});

function openBimbinganDetailModal(mhsNama, tgl, waktu, tempat, deskripsi, hasil, catatanMhs, filePath, dosenNama) {
    document.getElementById('bd-nama').textContent = mhsNama || '-';
    document.getElementById('bd-dosen').textContent = dosenNama || '-';
    document.getElementById('bd-tgl').textContent = tgl || '-';
    document.getElementById('bd-waktu').textContent = waktu ? waktu : '-';
    document.getElementById('bd-tempat').textContent = tempat || '-';
    document.getElementById('bd-deskripsi').textContent = deskripsi || '-';
    document.getElementById('bd-hasil').textContent = hasil || '-';
    document.getElementById('bd-catatan-mhs').textContent = catatanMhs || '-';
    
    const fileArea = document.getElementById('bd-file-area');
    if (filePath) {
        fileArea.innerHTML = `<a href="${filePath}" target="_blank" class="btn-proof text-decoration-none" style="background:#eef2ff; color:#4e73df; padding:6px 12px; border-radius:6px; font-weight:600; display:inline-block;"><i class="fas fa-file-download pe-1"></i> Lihat Dokumen</a>`;
    } else {
        fileArea.innerHTML = '-';
    }
    
    openModal('bimbinganModal-Dynamic');
}

function previewFile(url) {
    const previewFrame = document.getElementById('documentPreviewFrame');
    const downloadBtn = document.getElementById('previewDownloadBtn');
    previewFrame.src = url;
    downloadBtn.href = url;
    
    // Create new bootstrap modal instance and show it
    const modalElement = document.getElementById('previewModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

// Clear iframe src when modal is hidden to free up memory and stop audio/video
document.addEventListener('DOMContentLoaded', function () {
    const previewModal = document.getElementById('previewModal');
    if(previewModal) {
        previewModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('documentPreviewFrame').src = '';
        });
    }
});
</script>

@push('modals')
<!-- BIMBINGAN DETAIL MODAL (CUSTOM LOG GRID) -->
<div id="bimbinganModal-Dynamic" class="modal-log">
    <div class="modal-content-log">
        <div class="modal-header">
            <h4>Detail Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('bimbinganModal-Dynamic')">✖</span>
        </div>
        <div class="modal-body">
            <div class="log-grid">
                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value" id="bd-nama"></div>

                <div class="label">Dosen Pembimbing</div>
                <div class="colon">:</div>
                <div class="value" id="bd-dosen"></div>

                <div class="label">Jadwal Bimbingan</div>
                <div class="colon">:</div>
                <div class="value"><span id="bd-tgl"></span> <span id="bd-waktu"></span></div>

                <div class="label">Tempat</div>
                <div class="colon">:</div>
                <div class="value" id="bd-tempat"></div>

                <div class="label">Deskripsi</div>
                <div class="colon">:</div>
                <div class="value" id="bd-deskripsi"></div>

                <div class="label">Catatan Dosen</div>
                <div class="colon">:</div>
                <div class="value" id="bd-hasil"></div>

                <div class="label">Catatan Mahasiswa</div>
                <div class="colon">:</div>
                <div class="value" id="bd-catatan-mhs"></div>

                <div class="label">Dokumen Bukti</div>
                <div class="colon">:</div>
                <div class="value" id="bd-file-area"></div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f1f2f6; padding-top: 15px; margin-top: 15px; text-align: right;">
                <button class="btn-cancel" onclick="closeModal('bimbinganModal-Dynamic')">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 85vh; border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #f8f9fc; border-bottom: 1px solid #eef2f7;">
                <h5 class="modal-title fw-bold" id="previewModalLabel" style="color: #1f2937;">
                    <i class="fas fa-file-alt me-2 text-primary"></i> Preview Dokumen
                </h5>
                <a href="#" id="previewDownloadBtn" class="btn btn-sm text-white ms-3 fw-semibold px-3" style="background: #4361ee; border-radius: 6px;" target="_blank" download>
                    <i class="fas fa-download me-1"></i> Unduh Asli
                </a>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #e2e8f0;">
                <iframe id="documentPreviewFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endpush

@endsection
