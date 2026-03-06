@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('page-content')

<div class="page-wrapper">
    <h4 class="mb-4 fw-semibold">Dashboard Tugas Akhir</h4>

    <!-- Status Verifikasi -->
    <div class="card card-custom p-4 mb-4">
        <div class="status-header mb-3">
            <h6 class="fw-semibold m-0">Status Verifikasi</h6>

            <div class="status-actions">
                <a href="#" onclick="openModal('uploadModal')"
                class="btn-status upload">
                    Upload Verifikasi
                </a>

                <a href="#" onclick="openModal('timelineModal')"
                class="btn-status timeline">
                    Ubah Timeline
                </a>
            </div>
        </div>

        <div class="row text-center milestone-wrapper">
        
            <div class="col">
                <div class="step-circle">1</div>
                <div class="step-label">
                    Penetapan<br>
                    Komisi<br>
                    Pembimbing
                </div>
            </div>
            <div class="col">
                <div class="step-circle">2</div>
                <div class="step-label">
                    Sidang<br>
                    Komisi<br>
                    1
                </div>
            </div>
            <div class="col">
                <div class="step-circle">3</div>
                <div class="step-label">Kolokium</div>
            </div>
            <div class="col">
                <div class="step-circle">4</div>
                <div class="step-label">Proposal</div>
            </div>
            <div class="col">
                <div class="step-circle">5</div>
                <div class="step-label">
                    Penelitian<br>
                    dan<br>
                    Bimbingan
                </div>
            </div>
            <div class="col">
                <div class="step-circle">6</div>
                <div class="step-label">
                    Evaluasi<br>
                    dan<br>
                    Monitoring
                </div>
            </div>
            <div class="col">
                <div class="step-circle">7</div>
                <div class="step-label">
                    Sidang<br>
                    Komisi<br>
                    2
                </div>
            </div>
            <div class="col">
                <div class="step-circle">8</div>
                <div class="step-label">Seminar</div>
            </div>
            <div class="col">
                <div class="step-circle">9</div>
                <div class="step-label">
                    Publikasi<br>
                    Ilmiah
                </div>
            </div>
            <div class="col">
                <div class="step-circle">10</div>
                <div class="step-label">
                    Ujian<br>
                    Tesis
                </div>
            </div>
            <div class="col">
                <div class="step-circle">11</div>
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
                    <th>Tgl Pengajuan</th>
                    <th>Rencana Bimbingan</th>
                    <th>Topik</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>5 Feb 2026</td>
                    <td>10 Feb 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <button class="btn-icon btn-acc" onclick="openModal('bimbinganModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>10 Feb 2026</td>
                    <td>13 Maret 2026</td>
                    <td>Pengajuan Judul</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <button class="btn-icon btn-acc" onclick="openModal('bimbinganModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>14 Feb 2026</td>
                    <td>2 April 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-acc" onclick="openModal('bimbinganModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>14 Feb 2026</td>
                    <td>2 April 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <button class="btn-icon btn-acc" onclick="openModal('bimbinganModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>14 Feb 2026</td>
                    <td>2 April 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <button class="btn-icon btn-acc" onclick="openModal('bimbinganModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Detail Bimbingan --}}
<div id="bimbinganModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Detail Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('bimbinganModal')">✖</span>
        </div>
        <div class="modal-body">

            <div class="log-grid">
                <div class="label">NIM</div>
                <div class="colon">:</div>
                <div class="value">J0403221234</div>

                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value">Diandra Puteri</div>

                <div class="label">Tanggal Pengajuan</div>
                <div class="colon">:</div>
                <div class="value">10 Feb 2025</div>

                <div class="label">Rencana Bimbingan</div>
                <div class="colon">:</div>
                <div class="value">15 Feb 2025</div>

                <div class="label">Tempat</div>
                <div class="colon">:</div>
                <div class="value">Ruang Dosen</div>

                <div class="label">Topik</div>
                <div class="colon">:</div>
                <div class="value">Revisi Proposal</div>

                <div class="label">Hasil Bimbingan</div>
                <div class="colon">:</div>
                <div class="value">Bimbingan hari ini berdiskusi terkait metode yang digunakan</div>

                <div class="label">Bukti Bimbingan</div>
                <div class="colon">:</div>
                <div class="value">
                    <button class="btn-proof">📄 Lihat Bukti</button>
                </div>

                <div class="label">Status Verifikasi</div>
                <div class="colon">:</div>
                <div class="value">
                    <td><span class="status-badge verify">Menunggu</span></td>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Upload Verifikasi--}}
<div id="uploadModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Upload Bukti Milestone</h4>
            <span class="close-modal" onclick="closeModal('uploadModal')">✖</span>
        </div>

        <form action="{{ url('/mahasiswa/upload-verifikasi') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="modal-body">

                {{-- ✅ UPLOAD FILE --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Upload Bukti <span style="color:red">*</span>
                    </label>

                    <input type="file"
                           name="bukti_file"
                           class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png"
                           required>

                    <small style="color:#6b7280;">
                        Format: PDF/JPG/PNG (max 2MB)
                    </small>
                </div>

                {{-- ✅ CATATAN --}}
                <div class="mb-2">Catatan :</div>
                <textarea name="catatan"
                          style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;"
                          rows="3"
                          placeholder="Tambahkan catatan (opsional)"></textarea>

            </div>

            <div class="modal-footer-log">
                <button type="button"
                        class="btn-close-log"
                        onclick="closeModal('uploadModal')">
                    Batal
                </button>

                <button type="submit" class="btn-acc">
                    Upload
                </button>
            </div>

        </form>
    </div>
</div>
{{-- Modal Ubah Timeline --}}
<div id="timelineModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Ubah Timeline Milestone</h4>
            <span class="close-modal" onclick="closeModal('timelineModal')">✖</span>
        </div>

        <form action="{{ url('/mahasiswa/update-timeline') }}"
              method="POST">
            @csrf

            <div class="modal-body">

                {{-- ✅ DROPDOWN MILESTONE --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Pilih Milestone <span style="color:red">*</span>
                    </label>

                    <select name="milestone"
                            class="form-control"
                            required>
                        <option value="">-- Pilih Milestone --</option>
                        <option value="1">Penetapan Komisi Pembimbing</option>
                        <option value="2">Sidang Komisi 1</option>
                        <option value="3">Kolokium</option>
                        <option value="4">Proposal</option>
                        <option value="5">Penelitian dan Bimbingan</option>
                        <option value="6">Evaluasi dan Monitoring</option>
                        <option value="7">Sidang Komisi 2</option>
                        <option value="8">Seminar</option>
                        <option value="9">Publikasi Ilmiah</option>
                        <option value="10">Ujian Tesis</option>
                        <option value="11">SKL</option>
                    </select>
                </div>

                {{-- ✅ INPUT TANGGAL --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Tanggal Milestone <span style="color:red">*</span>
                    </label>

                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           required>
                </div>

            </div>

            <div class="modal-footer-log">
                <button type="button"
                        class="btn-close-log"
                        onclick="closeModal('timelineModal')">
                    Batal
                </button>

                <button type="submit" class="btn-acc">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
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
    z-index: 999;
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
    grid-template-columns: 180px 10px 1fr;
    gap: 12px 14px;
    font-size: 14px;
    align-items: start;
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
<script>
function openModal(id) {
    document.getElementById(id).style.display = "flex";
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

/* optional: klik luar modal untuk close */
window.addEventListener('click', function(e) {
    const modal = document.getElementById('logModal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
window.addEventListener('click', function(e) {
    document.querySelectorAll('.modal-log').forEach(modal => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>
@endpush
