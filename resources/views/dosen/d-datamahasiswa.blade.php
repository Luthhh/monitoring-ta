@extends('layouts.dosen')

@section('title', 'Data Mahasiswa')

@section('page-content')

<div id="logModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Detail Log Bimbingan</h4>
            <span class="close-modal" onclick="closeLogModal()">✖</span>
        </div>

        <div class="modal-body">

            <div class="log-grid">
                <div class="label">NIM</div>
                <div class="colon">:</div>
                <div class="value">J0403221234</div>

                <div class="label">Nama</div>
                <div class="colon">:</div>
                <div class="value">
                    Diandra Puteri
                    <span class="role-badge p1">P1</span>
                </div>

                <div class="label">Tanggal Pengajuan</div>
                <div class="colon">:</div>
                <div class="value">10 Feb 2025</div>

                <div class="label">Tanggal Bimbingan</div>
                <div class="colon">:</div>
                <div class="value">15 Feb 2025</div>

                <div class="label">Tempat</div>
                <div class="colon">:</div>
                <div class="value">Ruang Dosen</div>

                <div class="label">Topik</div>
                <div class="colon">:</div>
                <div class="value">Revisi Proposal</div>

                <div class="label">Bukti Bimbingan</div>
                <div class="colon">:</div>
                <div class="value">
                    <button class="btn-proof">📄 Lihat Bukti</button>
                </div>

                <div class="label">Status Verifikasi</div>
                <div class="colon">:</div>
                <div class="value">
                    <span class="status-badge done">Disetujui</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main">
    <h2>Data Mahasiswa</h2>

    <!-- ================= INFO MAHASISWA ================= -->
    <div class="card">
        <div class="card-title-flex">
            <h3 class="section-title">👤 Informasi Mahasiswa</h3>
        </div>

        <div class="info-grid">
            <div>
                <span class="label">NIM</span>
                <span class="colon">:</span>
                <span class="value">J0403221143</span>
            </div>
            <div>
                <span class="label">Nama</span>
                <span class="colon">:</span>
                <span class="value">Dini Nurul Azizah</span>
            </div>
            <div>
                <span class="label">Tahun Masuk</span>
                <span class="colon">:</span>
                <span class="value">2020/2021</span>
            </div>
            <div>
                <span class="label">Semester</span>
                <span class="colon">:</span>
                <span class="value">4</span>
            </div>
            <div>
                <span class="label">Pembimbing 1</span>
                <span class="colon">:</span>
                <span class="value">Ibu Dini Nurul Azizah</span>
            </div>
            <div>
                <span class="label">Pembimbing 2</span>
                <span class="colon">:</span>
                <span class="value">Ibu Nurul Azizah</span>
            </div>
        </div>
    </div>


    <!-- ================= STATUS TA ================= -->
    <div class="card">
        <h3 class="section-title">📊 Status Tugas Akhir</h3>

        <div class="info-grid">
            <div>
                <span class="label">Judul</span>
                <span class="colon">:</span>
                <span class="value">Sistem Absensi Otomatis Menggunakan Face Recognition Berbasis CNN</span>
            </div>
            <div>
                <span class="label">Milestone Terakhir</span>
                <span class="colon">:</span>
                <span class="value">Sidang Komisi 1</span>
            </div>
            <div>
                <span class="label">Milestone Saat Ini</span>
                <span class="colon">:</span>
                <span class="value">Kolokium</span>
            </div>
            <div>
                <span class="label">Status</span>
                <span class="colon">:</span>
                <span class="status-badge ahead">Ahead</span>
            </div>
        </div>
    </div>


    <!-- ================= LOG BIMBINGAN ================= -->
    <div class="card-box">
        <div class="card-header">
            <span>📅 Log Bimbingan</span>
            <span class="badge-success">3</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th class="col-date">Tgl Pengajuan</th>
                    <th class="col-date">Tgl Bimbingan</th>
                    <th class="col-topic">Topik</th>
                    <th class="col-milestone">Milestone</th>
                    <th class="col-status">Status</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-date">1 Januari 2026</td>
                    <td class="col-date">4 Januari 2026</td>
                    <td>Revisi Proposal yang sangat panjang banget biar kita test wrapping</td>
                    <td class="col-milestone">Sidang Komisi 1</td>
                    <td class="col-status">
                        <span class="status-badge menunggu">Menunggu</span>
                    </td>
                    <td class="col-action">
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="col-date">1 Januari 2026</td>
                    <td class="col-date">4 Januari 2026</td>
                    <td>Revisi Proposal yang sangat panjang banget biar kita test wrapping</td>
                    <td class="col-milestone">Sidang Komisi 1</td>
                    <td class="col-status">
                        <span class="status-badge menunggu">Menunggu</span>
                    </td>
                    <td class="col-action">
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="col-date">1 Januari 2026</td>
                    <td class="col-date">4 Januari 2026</td>
                    <td>Revisi Proposal yang sangat panjang banget biar kita test wrapping</td>
                    <td class="col-milestone">Sidang Komisi 1</td>
                    <td class="col-status">
                        <span class="status-badge disetujui">Disetujui</span>
                    </td>
                    <td class="col-action">
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="col-date">1 Januari 2026</td>
                    <td class="col-date">4 Januari 2026</td>
                    <td>Revisi Proposal yang sangat panjang banget biar kita test wrapping</td>
                    <td class="col-milestone">Sidang Komisi 1</td>
                    <td class="col-status">
                        <span class="status-badge diverifikasi">Diverifikasi</span>
                    </td>
                    <td class="col-action">
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>


<style>

.main {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden; /* cegah dorong sidebar */
}

/* ================= TITLE ================= */
.section-title {
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 16px;
    color: #1f2937;
}

/* ================= CARD ================= */
.card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    border: 1px solid #f1f1f1;
}

/* ================= INFO GRID ================= */
.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
}

.info-grid > div {
    display: grid;
    grid-template-columns: 180px 16px 1fr;
    align-items: start;
    padding: 10px 14px;
    border-radius: 10px;
    transition: 0.15s;
}


.label {
    font-weight: 600;
    color: #475569;
    font-size: 13px;
}

.colon {
    text-align: center;
    font-weight: bold;
    color: #94a3b8;
}

.value {
    color: #0f172a;
    font-weight: 500;
}

/* ================= STATUS BOX ================= */
.status-box {
    max-width: 460px;
    background: #f8fafc;
    padding: 20px;
    border-radius: 14px;
    border: 1px solid #eef2f7;
}


.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    width: fit-content;
    white-space: nowrap;
}

.status-badge.success {
    background: #e6f7ee;
    color: #1cc88a;
}

/* 🟢 Ahead */
.status-badge.ahead {
    background: #e6f7ee;
    color: #1cc88a;
}

/* 🟡 Ideal */
.status-badge.ideal {
    background: #fff4e5;
    color: #f6a500;
}

/* 🔴 Behind */
.status-badge.behind {
    background: #ffe5e5;
    color: #e74a3b;
}

/* progress */
.progress-bar {
    width: 100%;
    height: 12px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b4cca, #6366f1);
    border-radius: 999px;
    transition: width 0.4s ease;
}

.progress-text {
    font-size: 13px;
    color: #64748b;
}

/* CARD */
.card-box {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    border: 1px solid #f1f1f1;
}

/* HEADER */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    margin-bottom: 14px;
}

/* BADGE */
.badge-danger {
    background: #ffe5e5;
    color: #e74a3b;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 600;
}

.badge-success {
    background: #e6f4ea;
    color: #1cc88a;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 600;
}

/* TABLE */
.table-custom {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* 🔥 WAJIB biar ga loncat */
}

.table-custom th {
    background: #f8f9fc;
    font-size: 13px;
    color: #6c757d;
    font-weight: 600;
    text-align: center;
}

.table-custom td {
    padding: 10px;
    border-top: 1px solid #eee;
    font-size: 14px;
    word-wrap: break-word;
}

.col-date {
    width: 140px;
    text-align: center;
}

.col-topic {
    width: 220px;
}

.col-milestone {
    width: 130px;
    text-align: center;
}

.col-status {
    width: 130px;
    text-align: center;
}

.col-action {
    width: 130px;
    text-align: center;
}

/* STATUS */
.status {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 500;
}

/* ================= STATUS BADGE ================= */

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

/* 🟡 menunggu */
.status-badge.menunggu {
    background: #fff4e5;
    color: #f6a500;
}

/* 🟢 disetujui */
.status-badge.disetujui {
    background: #e6f7ee;
    color: #1cc88a;
}

/* 🟣 diverifikasi */
.status-badge.diverifikasi {
    background: #e7f1ff;
    color: #4e73df;
}

/* 🔴 ditolak */
.status-badge.ditolak {
    background: #ffe5e5;
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

.table-custom {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* ⭐⭐⭐ INI KUNCI UTAMA */
}

.table-custom th,
.table-custom td {
    padding: 10px;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal; /* ⭐ penting */
    vertical-align: top;
}

.role-badge {
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 6px;
    margin-left: 6px;
    font-weight: 600;
}

.role-badge.p1 {
    background: #e8f0ff;
    color: #3b4cca;
}

.role-badge.p2 {
    background: #e6f4ea;
    color: #1cc88a;
}
/* FLEX TITLE + BUTTON */
.card-title-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* ACTION BUTTON WRAPPER */
.card-actions {
    display: flex;
    gap: 10px;
}

/* EDIT BUTTON */
.btn-edit {
    background: #e7f1ff;
    color: #4e73df;
    border: 1px solid #d0e2ff;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-edit:hover {
    background: #4e73df;
    color: white;
}

/* DELETE BUTTON */
.btn-delete {
    background: #ffe5e5;
    color: #e74a3b;
    border: 1px solid #ffd6d6;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-delete:hover {
    background: #e74a3b;
    color: white;
}


</style>
@endsection

@push('scripts')
<script>
function openLogModal() {
    document.getElementById('logModal').style.display = 'flex';
}

function closeLogModal() {
    document.getElementById('logModal').style.display = 'none';
}

/* optional: klik luar modal untuk close */
window.addEventListener('click', function(e) {
    const modal = document.getElementById('logModal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
@endpush