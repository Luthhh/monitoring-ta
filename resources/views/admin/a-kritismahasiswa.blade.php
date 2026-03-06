@extends('layouts.admin')

@section('title','Detail Aktivitas Bimbingan')

@section('page-content')


<div class="page-wrapper">
    <h2 class="page-title">Daftar Mahasiswa Kritis</h2>

    {{--  Mahasiswa > 30 hari --}}
    <div class="card-box">
        <div class="card-header">
            <span>🔔 Mahasiswa Kritis</span>
            <span class="badge-danger">2</span>
        </div>
        <div class="table-tools">
            <input type="text" id="searchInput" placeholder="🔍 Cari nama mahasiswa...">
            <select id="sortTahun">
                <option value="">Semua Tahun</option>
                <option value="2020/2021">2020/2021</option>
                <option value="2021/2022">2021/2022</option>
                <option value="2022/2023">2022/2023</option>
                <option value="2023/2024">2023/2024</option>
            </select>
            <select id="sortSemester">
                <option value="">Semua Semester</option>
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3">Semester 3</option>
                <option value="4">Semester 4</option>
                <option value="5">Semester 5</option>
                <option value="6">Semester 6</option>
                <option value="7">Semester 7</option>
                <option value="8">Semester 8</option>
            </select>
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
                <tr onclick="window.location='{{ url('/admin/data-mahasiswa') }}'">
                    <td>J0403221149</td>
                    <td>Andi Saputra</td>
                    <td>2021/2022</td>
                    <td>7</td>
                    <td>Kolokium</td>
                    <td>3 Juli 2025</td>
                    <td>
                        <button class="btn-remind">Ingatkan</button>
                    </td>
                </tr>
                <tr onclick="window.location='{{ url('/admin/data-mahasiswa') }}'">
                    <td>J0403221149</td>
                    <td>Andi Saputra</td>
                    <td>2021/2022</td>
                    <td>7</td>
                    <td>Kolokium</td>
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
/* ================= WRAPPER ================= */
.page-wrapper {
    padding: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 20px;
}

/* ================= CARD ================= */
.card-box {
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    border: 1px solid #f1f1f1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    margin-bottom: 14px;
}

/* ================= BADGE ================= */
.badge-danger {
    background: #ffe5e5;
    color: #e74a3b;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 13px;
}

/* ================= TABLE TOOLS ================= */
.table-tools {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.table-tools input,
.table-tools select {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: 0.15s;
}

.table-tools input:focus,
.table-tools select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
}

/* ================= TABLE ================= */
.table-custom {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.table-custom thead {
    background: #f8fafc;
}

.table-custom th,
.table-custom td {
    padding: 12px 10px;
    font-size: 14px;
    vertical-align: middle;
    word-break: break-word;
}

.table-custom th {
    color: #6b7280;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
}

.table-custom td {
    border-top: 1px solid #eee;
    text-align: center;
}

/* nama rata kiri */
.table-custom td:nth-child(2) {
    text-align: left;
}

/* row hover */
.table-custom tbody tr {
    cursor: pointer;
    transition: 0.15s;
}

.table-custom tbody tr:hover {
    background: #f8fafc;
}

/* ================= BUTTON ================= */
.btn-remind {
    border: none;
    background: #e74a3b;
    color: white;
    padding: 6px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

.btn-remind:hover {
    background: #c0392b;
}

/* ================= OPTIONAL STATUS ================= */
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
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