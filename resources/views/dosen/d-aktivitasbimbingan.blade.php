@extends('layouts.dosen')

@section('title','Detail Aktivitas Bimbingan')

@section('page-content')

<!-- Modal Log Bimbingan -->
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

<div class="page-wrapper">
    <h2 class="page-title">Detail Aktivitas Bimbingan</h2>

    {{--  Mahasiswa > 30 hari --}}
    <div class="card-box">
        <div class="card-header">
            <span>🔔 Mahasiswa Tidak Bimbingan > 30 Hari</span>
            <span class="badge-danger">2</span>
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
                <tr onclick="window.location='{{ url('/dosen/data-mahasiswa') }}'">
                    <td>2021001</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Andi Saputra
                    </td>
                    <td>12 Des 2025</td>
                    <td>Kolokium</td>
                    <td>20 Feb 2025</td>
                    <td><span class="status-badge done">Sudah</span></td>
                    <td>
                        <button class="btn-remind">Ingatkan</button>
                    </td>
                </tr>
                <tr onclick="window.location='{{ url('/dosen/data-mahasiswa') }}'">
                    <td>2021003</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Rina Putri
                    </td>
                    <td>1 Des 2025</td>
                    <td>Seminar</td>
                    <td>20 Feb 2025</td>
                    <td><span class="status-badge pending">Belum</span></td>
                    <td>
                    <button class="btn-remind">Ingatkan</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Bimbingan bulan ini --}}
    <div class="card-box">
        <div class="card-header">
            <span>📅 Bimbingan Bulan Ini</span>
            <span class="badge-success">3</span>
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
                <tr onclick="window.location='{{ url('/dosen/data-mahasiswa') }}'">
                    <td>2021002</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Siti Rahma
                    </td>
                    <td>5 Feb 2026</td>
                    <td>Sidang Komisi 1</td>
                    <td>20 Feb 2025</td>
                    <td><span class="status-badge done">Sudah</span></td>
                    <td>
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
                <tr onclick="window.location='{{ url('/dosen/data-mahasiswa') }}'">
                    <td>2748328</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Anindya
                    </td>
                    <td>10 Feb 2026</td>
                    <td>Seminar</td>
                    <td>20 Feb 2025</td>
                    <td><span class="status-badge pending">Belum</span></td>
                    <td>
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
                <tr onclick="window.location='{{ url('/dosen/data-mahasiswa') }}'">
                    <td>2387472</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Yasmin
                    </td>
                    <td>14 Feb 2026</td>
                    <td>Kolokium</td>
                    <td>20 Feb 2025</td>
                    <td><span class="status-badge done">Sudah</span></td>
                    <td>
                        <button class="btn-view-log" onclick="openLogModal()">
                            👁 View Log
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('styles')
<style>
/* scope biar aman */
.page-wrapper {
    padding: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
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
}

.table-custom th {
    background: #f8f9fc;
    padding: 10px;
    text-align: left;
    font-size: 13px;
    color: #6c757d;
}

.table-custom td {
    padding: 10px;
    border-top: 1px solid #eee;
    font-size: 14px;
}
table tbody tr {
    cursor: pointer;
}

/* STATUS */
.status {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 500;
}

.status.warning {
    background: #fff3cd;
    color: #856404;
}

.status.success {
    background: #d1f2eb;
    color: #0c6b58;
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

/* tombol ingatkan */
.btn-remind {
    border: none;
    background: #ff0000;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
}

.btn-remind:hover {
    background: #ffd500;
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

/* ====== LOCK COLUMN WIDTH ====== */

.table-custom th:nth-child(1),
.table-custom td:nth-child(1) {
    width: 110px; /* NIM */
}

.table-custom th:nth-child(2),
.table-custom td:nth-child(2) {
    width: 200px; /* Nama */
}

.table-custom th:nth-child(3),
.table-custom td:nth-child(3) {
    width: 150px; /* Terakhir */
}

.table-custom th:nth-child(4),
.table-custom td:nth-child(4) {
    width: 160px; /* Milestone */
}

.table-custom th:nth-child(5),
.table-custom td:nth-child(5) {
    width: 150px; /* Target */
}

.table-custom th:nth-child(6),
.table-custom td:nth-child(6) {
    width: 120px; /* Status */
}

.table-custom th:nth-child(7),
.table-custom td:nth-child(7) {
    width: 130px; /* Aksi */
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

</style>
@endpush

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