@extends('layouts.admin')

@section('title', 'Total Mahasiswa')

@section('page-content')

<div class="main">

    <h1>Daftar Mahasiswa</h1>

    <!-- Statistik -->
    <div class="stat-card stat-blue">
        <h2>30</h2>
        <p>Mahasiswa Bimbingan Aktif</p>
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
        <select id="sortPeran">
            <option value="">Peran Semua</option>
            <option value="P1">Pembimbing 1</option>
            <option value="P2">Pembimbing 2</option>
        </select>
    </div>

    <!-- Tabel -->
    <div class="card">
        <table id="tabelMahasiswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Masuk</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Milestone Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr onclick="window.location='{{ url('/admin/data-mahasiswa') }}'">
                    <td>1</td>
                    <td>2020/2021</td>
                    <td>J0403221143</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Luthfi Dika Chandra
                    </td>
                    <td>3</td>
                    <td>
                        <span class="badge badge-blue">
                            Penetapan Komisi Pembimbing
                        </span>
                    </td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr onclick="window.location='{{ url('/admin/data-mahasiswa') }}'">
                    <td>2</td>
                    <td>2020/2021</td>
                    <td>J0403221143</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Dini Nurul Azizah
                    </td>
                    <td>4</td>
                    <td>
                        <span class="badge badge-blue">
                            Kolokium
                        </span>
                    </td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>

.main {
    padding: 30px;
}

.stat-card {
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.stat-red {
    background: #e74a3b;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table th, table td {
    padding: 14px;
    text-align: center;
    font-size: 14px;
    vertical-align: middle;
}

table thead {
    background: #f1f2f6;
}

table tbody tr {
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

td:nth-child(4) {
    max-width: 200px;
    word-break: break-word;
}

/* Kolom milestone */
td:nth-child(6) {
    max-width: 220px;
}

table td:nth-child(4)  {
    text-align: left;
}


td {
    word-break: break-word;
}

.badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-block;
    color: white;
}

.stat-blue {
    background: #02048d;
    color: white;
}

.badge-blue {
    background: #02048d;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    border: none;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    cursor: pointer;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: 0.2s;
}

/* Warna */
.btn-view {
    background: #0dcaf0;
}

.table-tools {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
}

.table-tools input,
.table-tools select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
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

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("searchInput").addEventListener("keyup", filterTable);
    document.getElementById("sortSemester").addEventListener("change", filterTable);
    document.getElementById("sortTahun").addEventListener("change", filterTable);
    document.getElementById("sortPeran").addEventListener("change", filterTable);

    function filterTable() {

        let search = document.getElementById("searchInput").value.toLowerCase();
        let semester = document.getElementById("sortSemester").value;
        let tahun = document.getElementById("sortTahun").value;
        let peran = document.getElementById("sortPeran").value;

        let rows = document.querySelectorAll("#tabelMahasiswa tbody tr");

        rows.forEach(row => {

            let nama = row.cells[3].innerText.toLowerCase();
            let rowSemester = row.cells[4].innerText.trim();
            let rowTahun = row.cells[1].innerText.trim();

            // 🔥 ambil dari badge
            let badge = row.querySelector(".role-badge");
            let rowPeran = badge ? badge.innerText.trim() : "";

            let show = true;

            if (search && !nama.includes(search)) show = false;
            if (semester && rowSemester !== semester) show = false;
            if (tahun && rowTahun !== tahun) show = false;
            if (peran && rowPeran !== peran) show = false;

            row.style.display = show ? "" : "none";
        });
    }

});
</script>
@endpush