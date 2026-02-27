@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-content')



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="topbar">
    <h1>Dashboard Admin</h1>
    <div>Admin User</div>
</div>

<!-- Statistik -->
<div class="cards">
    <a href="{{ url('/admin/total-mahasiswa') }}" class="card blue text-decoration-none">
        <h2>2</h2>
        <p>Mahasiswa Aktif</p>
    </a>
    <a href="{{ url('/admin/ahead-mahasiswa') }}" class="card green text-decoration-none">
        <h2>2</h2>
        <p>Ahead</p>
    </a>
    <a href="{{ url('/admin/ideal-mahasiswa') }}" class="card yellow text-decoration-none">
        <h2>0</h2>
        <p>Ideal</p>
    </a>
    <a href="{{ url('/admin/behind-mahasiswa') }}" class="card red text-decoration-none">
        <h2>0</h2>
        <p>Behind</p>
    </a>
</div>

<div class="highlight-bar">
    <a href="{{ url('/admin/aktivitas-bimbingan') }}" class="highlight-item warning clickable-card">
        <div class="highlight-text">
            🔔 Mahasiswa tidak bimbingan &gt; 30 hari
        </div>
        <div class="highlight-number">2</div>
    </a>
</div>

<!-- ================= CARD KRITIS ================= -->
<div class="kritis-wrapper">

    <!-- 🔴 KRITIS (lebih mencolok) -->
    <div class="kritis-card danger highlight">
        <div class="kritis-header">
            <span class="kritis-icon">🚨</span>
            <span class="kritis-title">Mahasiswa Kritis</span>
        </div>
        <div class="kritis-value">{{ $mhsKritis ?? 0 }}</div>
        <div class="kritis-desc">Butuh perhatian segera</div>
    </div>

    <!-- 🟠 Mendekati -->
    <div class="kritis-card warning">
        <div class="kritis-title">Mendekati Batas Studi</div>
        <div class="kritis-value">{{ $mhsMendekati ?? 0 }}</div>
        <div class="kritis-desc">Perlu monitoring</div>
    </div>

    <!-- 🟢 Tepat waktu -->
    <div class="kritis-card success">
        <div class="kritis-title">On Track Tepat Waktu</div>
        <div class="kritis-value">{{ $mhsTepatWaktu ?? 0 }}</div>
        <div class="kritis-desc">Kinerja baik</div>
    </div>

</div>

<div class="box">
    <div class="box-header">
        <h3>Status Masa Studi</h3>

        <select id="filterAngkatanPie">
            <option value="2021">Angkatan 2021</option>
            <option value="2022">Angkatan 2022</option>
            <option value="2023">Angkatan 2023</option>
        </select>
    </div>

    <canvas id="statusChart" height="120"></canvas>
</div>

<div class="box">
    <div class="box-header">
    <h3>Sebaran Mahasiswa</h3>

    <select id="filterTahun">
        <option value="2021">Angkatan 2021</option>
        <option value="2022">Angkatan 2022</option>
        <option value="2023">Angkatan 2023</option>
    </select>
    </div>

    <canvas id="barChart"></canvas>
</div>


@endsection

@push('scripts')
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
    padding: 20px;
    border-radius: 15px;
    color: white;
    box-shadow: 0 8px 15px rgba(0,0,0,0.08);
}

.blue { background: #02048d; }
.green { background: #00a806; }
.yellow { background: #f6c23e; color: #000; }
.red { background: #ff1500; }

.card h2 {
    font-size: 28px;
}

.box {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 15px rgba(0,0,0,0.05);
}

.box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

#filterTahun {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
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
.highlight-bar {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.highlight-item {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}

/* teks kiri */
.highlight-text {
    font-weight: 500;
    color: #555;
}

/* angka kanan */
.highlight-number {
    min-width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #eef2ff;
    color: #3b4cca;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* variant warning */
.warning .highlight-number {
    background: #fde8e8;
    color: #e74a3b;
}

.clickable-card {
    text-decoration: none; /* ⬅️ ini yang ngilangin garis bawah */
    color: inherit;
    cursor: pointer;
    transition: all .18s ease;
}

/* penting juga untuk state hover & visited */
.clickable-card:hover,
.clickable-card:focus,
.clickable-card:visited {
    text-decoration: none;
    color: inherit;
}
.kritis-wrapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.kritis-card {
    background: white;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    transition: 0.25s ease;
    position: relative;
}

.kritis-card:hover {
    transform: translateY(-4px);
}

/* 🔥 CARD KRITIS SUPER MENCOLOK */
.kritis-card.highlight {
    background: linear-gradient(135deg, #fff5f5, #ffeaea);
    border: 1px solid #ffd6d6;
    box-shadow: 0 8px 24px rgba(231, 76, 60, 0.15);
}

.kritis-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.kritis-icon {
    font-size: 18px;
}

.kritis-title {
    font-size: 14px;
    color: #666;
}

.kritis-value {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 6px;
    color: #222;
}

.kritis-desc {
    font-size: 13px;
    color: #888;
}

/* accent line */
.kritis-card.danger {
    border-left: 5px solid #e74c3c;
}

.kritis-card.warning {
    border-left: 5px solid #f39c12;
}

.kritis-card.success {
    border-left: 5px solid #27ae60;
}
#filterAngkatanPie {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const dataPerTahun = {
        2021: {
            belum: [30,25,20,15,10,8,5,3,2,1,0],
            sudah: [0,5,10,15,20,22,25,27,28,29,30]
        },
        2022: {
            belum: [40,30,25,18,12,10,6,4,3,2,1],
            sudah: [0,10,15,22,28,30,34,36,37,38,39]
        },
        2023: {
            belum: [50,45,35,25,15,12,8,5,3,2,1],
            sudah: [0,5,15,25,35,38,42,45,47,48,49]
        }
    };

    const ctx1 = document.getElementById('barChart');

    let chart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [
                'Penetapan Komisi',
                'Sidang Komisi 1',
                'Kolokium',
                'Proposal',
                'Penelitian',
                'Evaluasi',
                'Sidang Komisi 2',
                'Seminar',
                'Publikasi',
                'Ujian Tesis',
                'SKL'
            ],
            datasets: [
                {
                    label: 'Belum',
                    data: dataPerTahun[2021].belum,
                    backgroundColor: '#4e73df'
                },
                {
                    label: 'Sudah',
                    data: dataPerTahun[2021].sudah,
                    backgroundColor: '#f6c23e'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true },
                y: { stacked: true }
            }
        }
    });

    document.getElementById('filterTahun')
    .addEventListener('change', function () {

        let tahun = this.value;

        chart.data.datasets[0].data = dataPerTahun[tahun].belum;
        chart.data.datasets[1].data = dataPerTahun[tahun].sudah;

        chart.update();
    });

    const ctx2 = document.getElementById('lineChart');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Bimbingan',
                data: [5,18,25,6,17,26,13,7,18,6,18,25],
                backgroundColor: '#9b59b6'
            }]
        }
    });

});

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

// pie chart
document.addEventListener("DOMContentLoaded", function() {

    // 🔥 dummy data per angkatan
    const statusPerAngkatan = {
        2021: [40, 10, 25, 5],
        2022: [30, 15, 35, 8],
        2023: [20, 12, 45, 10]
    };

    const ctxStatus = document.getElementById('statusChart');

    let statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: [
                'Tepat Waktu',
                'Tidak Tepat Waktu',
                'Dalam Proses',
                'Terancam DO'
            ],
            datasets: [{
                data: statusPerAngkatan[2021],
                backgroundColor: [
                    '#00a806',
                    '#ff1500',
                    '#f6c23e',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '65%'
        }
    });

    // 🔥 filter dropdown
    document
        .getElementById('filterAngkatanPie')
        .addEventListener('change', function () {

            let tahun = this.value;

            statusChart.data.datasets[0].data =
                statusPerAngkatan[tahun];

            statusChart.update();
        });

});
</script>
@endpush

