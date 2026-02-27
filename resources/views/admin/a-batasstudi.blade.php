@extends('layouts.admin')

@section('title','Detail Aktivitas Bimbingan')

@section('page-content')


<div class="page-wrapper">
    <h2 class="page-title">Daftar Mahasiswa Mendekati Batas Studi</h2>

    {{--  Mahasiswa > 30 hari --}}
    <div class="card-box">
        <div class="card-header">
            <span>🔔 Mahasiswa yang Menedekati Batas Studi</span>
            <span class="badge-danger">2</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tahun Masuk</th>
                    <th>Semester</th>
                    <th>Milestone Terakhir</th>
                    <th>Bimbingan Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>J0403221149</td>
                    <td>Andi Saputra</td>
                    <td>2021/2022</td>
                    <td>7</td>
                    <td>Seminar</td>
                    <td>3 Juli 2025</td>
                    <td>
                        <button class="btn-remind">Ingatkan</button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221149</td>
                    <td>Andi Saputra</td>
                    <td>2021/2022</td>
                    <td>7</td>
                    <td>Seminar</td>
                    <td>3 Juli 2025</td>
                    <td>
                        <button class="btn-remind">Ingatkan</button>
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

table td:nth-child(2)  {
    text-align: left;
}


td {
    word-break: break-word;
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