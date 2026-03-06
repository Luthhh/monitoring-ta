@extends('layouts.admin')

@section('title','Detail Aktivitas Bimbingan')

@section('page-content')


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
                <tr onclick="window.location='{{ url('/admin/data-mahasiswa') }}'">
                    <td>2021001</td>
                    <td>Andi Saputra</td>
                    <td>12 Des 2025</td>
                    <td>Kolokium</td>
                    <td>20 Feb 2025</td>
                    <td><span class="status-badge done">Sudah</span></td>
                    <td>
                        <button class="btn-remind">Ingatkan</button>
                    </td>
                </tr>
                <tr onclick="window.location='{{ url('/admin/data-mahasiswa') }}'">
                    <td>2021003</td>
                    <td>Rina Putri</td>
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
</div>
@endsection

@push('styles')
<style>
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

/* TABLE */
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
    text-align: center;
    cursor: pointer;
}

/* kolom nama rata kiri */
.table-custom td:nth-child(2) {
    text-align: left;
}

/* badge status */
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.status-badge.done {
    background: #e6f7ee;
    color: #1cc88a;
}

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