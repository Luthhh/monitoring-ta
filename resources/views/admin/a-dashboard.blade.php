@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-content')

<style>
.main {
    background: #f4f6fb;
    padding: 30px;
}

.topbar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}


.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.card h3 {
    font-size: 14px;
    margin-bottom: 10px;
    color: #888;
}

.card p {
    font-size: 22px;
    font-weight: 600;
}

.chart-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px;
    font-size: 14px;
}

table thead {
    background: #f1f2f6;
}

table tbody tr {
    border-bottom: 1px solid #eee;
}

.aksi button {
    border: none;
    padding: 8px 10px;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 5px;
}

.btn-view {
    background: #4b7bec;
    color: white;
}

.btn-alert {
    background: #f39c12;
    color: white;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="topbar">
    <h1>Dashboard Admin</h1>
    <div>Admin User</div>
</div>

<!-- Statistik -->
<div class="cards">

        <a href="{{ url('/mahasiswa/card') }}" class="card blue text-decoration-none">
            <h2>2</h2>
            <p>Mahasiswa Aktif</p>
        </a>

        <a href="{{ url('/mahasiswa/card/ahead') }}" class="card green text-decoration-none">
            <h2>2</h2>
            <p>Ahead</p>
        </a>

        <a href="{{ url('/mahasiswa/card/ideal') }}" class="card yellow text-decoration-none">
            <h2>0</h2>
            <p>Ideal</p>
        </a>

        <a href="{{ url('/mahasiswa/card/behind') }}" class="card red text-decoration-none">
            <h2>0</h2>
            <p>Behind</p>
        </a>

    </div>

<div class="chart-box">
    <h3>Statistik Tahapan TA</h3>
    <canvas id="adminChart"></canvas>
</div>

<div class="chart-box" style="margin-top:20px;">
    <h3>Pengajuan Terbaru</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun Masuk</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Status Progress</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>2020/2021</td>
                <td>Luthfi Dika Chandra</td>
                <td>J0403221143</td>
                <td>Penetapan Komisi Pembimbing</td>
                <td class="aksi">
                    <button class="btn-view">👁</button>
                    <button class="btn-alert">🔔</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
const ctx = document.getElementById('adminChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pengajuan','Proposal','Seminar','Sidang','Lulus'],
        datasets: [{
            label: 'Jumlah Mahasiswa',
            data: [120, 90, 70, 50, 30],
            backgroundColor: '#4b7bec'
        }]
    }
});
</script>

@endsection
