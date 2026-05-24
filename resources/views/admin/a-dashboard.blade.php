@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-content')

<div class="topbar">
    <h1>Dashboard Admin</h1>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.export-mahasiswa') }}" class="btn btn-success btn-sm" style="background:#27ae60; color:white; border:none; padding:8px 15px; border-radius:8px; text-decoration:none;">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
        <div>{{ auth()->user()->name ?? 'Admin' }}</div>
    </div>
</div>

<!-- Statistik -->
<div class="cards">
    <a href="{{ url('/admin/total-mahasiswa') }}" class="card blue text-decoration-none">
        <h2>{{ $totalMahasiswa }}</h2>
        <p>Mahasiswa Aktif</p>
    </a>
    <a href="{{ url('/admin/ahead-mahasiswa') }}" class="card green text-decoration-none">
        <h2>{{ $ahead }}</h2>
        <p>Ahead</p>
    </a>
    <a href="{{ url('/admin/ideal-mahasiswa') }}" class="card yellow text-decoration-none">
        <h2>{{ $ideal }}</h2>
        <p>Ideal</p>
    </a>
    <a href="{{ url('/admin/behind-mahasiswa') }}" class="card red text-decoration-none">
        <h2>{{ $behind }}</h2>
        <p>Behind</p>
    </a>
</div>

{{-- Ringkasan Bimbingan Bulanan (Global) --}}
<h3 style="margin-bottom: 15px; font-size: 17px; font-weight: 700; color: #1e293b;">📅 Ringkasan Aktivitas Bimbingan (Bulan Ini)</h3>
<div class="cards" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); margin-bottom: 30px;">
    <div class="card" style="background: white; border: 1px solid #e2e8f0; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: flex-start; gap: 4px; padding: 18px;">
        <span style="font-size: 13px; color: #64748b; font-weight: 600;">Total Rencana Bimbingan</span>
        <span style="font-size: 26px; font-weight: 800; color: #4e73df;">{{ $rencanaBimbinganBulanIni }}</span>
    </div>
    <div class="card" style="background: white; border: 1px solid #e2e8f0; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: flex-start; gap: 4px; padding: 18px;">
        <span style="font-size: 13px; color: #64748b; font-weight: 600;">Total Bimbingan Terlaksana</span>
        <span style="font-size: 26px; font-weight: 800; color: #1cc88a;">{{ $terlaksanaBulanIni }}</span>
    </div>
    <div class="card" style="background: white; border: 1px solid #e2e8f0; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: flex-start; gap: 4px; padding: 18px;">
        <span style="font-size: 13px; color: #64748b; font-weight: 600;">Bimbingan Sedang Berjalan (Aktif)</span>
        <span style="font-size: 26px; font-weight: 800; color: #f6c23e;">{{ $jumlahBimbinganAktif }}</span>
    </div>
</div>



<!-- ================= CARD KRITIS ================= -->
<div class="kritis-wrapper">

    <!-- 🔵 Tidak Bimbingan > 30 Hari -->
    <a href="{{ route('admin.aktivitas-bimbingan') }}" class="kritis-card danger top-row text-decoration-none">
        <div class="d-flex flex-column gap-1">
            <div class="kritis-header">
                <span class="kritis-title">Tidak Bimbingan > 30 Hari</span>
                <i class="fas fa-clock-rotate-left" style="color: #f6a500; font-size: 18px;"></i>
            </div>
            <div class="kritis-desc">Butuh pengingat bimbingan</div>
        </div>
        <div class="kritis-value" style="color: #f6a500;">{{ $tidakBimbingan30 }}</div>
    </a>

    <!-- 📄 Pengingat BAP -->
    <a href="#tabelUploadBap" class="kritis-card info top-row text-decoration-none">
        <div class="d-flex flex-column gap-1">
            <div class="kritis-header">
                <span class="kritis-title">Pengingat BAP</span>
                <i class="fas fa-file-signature" style="color: #0ea5e9; font-size: 18px;"></i>
            </div>
            <div class="kritis-desc">Seminar/Ujian Belum BAP</div>
        </div>
        <div class="kritis-value" style="color: #0ea5e9;">{{ $bapPendingCount }}</div>
    </a>

    <!-- 🟠 Mendekati -->
    <a href="{{ url('/admin/mendekati-batas-studi') }}" class="kritis-card warning bottom-row text-decoration-none">
        <div class="kritis-header">
            <span class="kritis-title">Mendekati Batas Studi</span>
            <i class="fas fa-hourglass-half" style="color: #f59e0b; font-size: 18px;"></i>
        </div>
        <div class="kritis-value" style="color: #f59e0b;">{{ $mhsMendekati }}</div>
        <div class="kritis-desc text-warning">Perlu monitoring</div>
    </a>

    <!-- 🟢 Tepat waktu -->
    <a href="{{ url('/admin/ontrack-mahasiswa') }}" class="kritis-card success bottom-row text-decoration-none">
        <div class="kritis-header">
            <span class="kritis-title">Lulus Tepat Waktu</span>
            <i class="fas fa-user-check" style="color: #10b981; font-size: 18px;"></i>
        </div>
        <div class="kritis-value" style="color: #10b981;">{{ $mhsTepatWaktu }}</div>
        <div class="kritis-desc text-success">Kinerja baik</div>
    </a>

    <!-- 🔴 KRITIS (lebih mencolok) -->
    <a href="{{ url('/admin/kritis-mahasiswa') }}" class="kritis-card danger highlight bottom-row text-decoration-none">
        <div class="kritis-header">
            <span class="kritis-title">Mahasiswa Kritis</span>
            <i class="fas fa-triangle-exclamation" style="color: #ef4444; font-size: 18px;"></i>
        </div>
        <div class="kritis-value" style="color: #ef4444;">{{ $mhsKritis }}</div>
        <div class="kritis-desc text-danger">Butuh perhatian segera</div>
    </a>
</div>

<div class="box">
    <div class="box-header">
        <h3>Status Masa Studi</h3>

        <select id="filterAngkatanPie">
            <option value="all">Semua Angkatan</option>
            @foreach($all_years as $tahun)
                <option value="{{ $tahun }}">{{ $tahun }}</option>
            @endforeach
        </select>
    </div>

    <div class="chart-container-pie">
        <canvas id="statusChart"></canvas>
    </div>
</div>

{{-- GRAFIK BIMBINGAN TREND (NEW) --}}
<div class="box">
    <div class="box-header">
        <h3>📊 Tren Aktivitas Bimbingan (6 Bulan Terakhir)</h3>
    </div>
    <div class="chart-container-bar" style="height: 300px;">
        <canvas id="bimbinganTrendChart"></canvas>
    </div>
</div>

<div class="box">
    <div class="box-header">
    <h3>Sebaran Mahasiswa</h3>

    <select id="filterTahun">
        <option value="all">Semua Angkatan</option>
        @foreach($all_years as $tahun)
            <option value="{{ $tahun }}">{{ $tahun }}</option>
        @endforeach
    </select>
    </div>

    <div class="chart-container-bar">
        <canvas id="barChart"></canvas>
    </div>
</div>

{{-- TABEL UPLOAD BAP (NEW) --}}
<div class="box mt-4" id="tabelUploadBap">
    <div class="box-header">
        <h3>📄 Upload BAP Milestone</h3>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background: #f6c23e; color: black;">{{ $bapPendingCount }} Belum Diunggah</span>
            <a href="{{ route('admin.verifikasi.bap') }}" class="btn btn-outline-warning btn-sm" style="font-size: 11px; border-radius: 8px; color: #856404; border-color: #f6c23e;">Lihat Semua</a>
        </div>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jenis Milestone</th>
                <th>Tgl Disetujui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bapPending as $m)
            <tr>
                <td>{{ $m->tugasAkhir->mahasiswa->nim }}</td>
                <td style="text-align: left;">{{ $m->tugasAkhir->mahasiswa->user->name }}</td>
                <td>{{ $m->jenis_milestone }}</td>
                <td>{{ \Carbon\Carbon::parse($m->tanggal_disetujui)->format('d M Y') }}</td>
                <td>
                    <button class="btn-icon btn-acc" style="background:#198754;" title="Upload BAP" 
                        onclick="openUploadBapModal({{ $m->id }}, '{{ $m->jenis_milestone }}', '{{ addslashes($m->tugasAkhir->mahasiswa->user->name) }}')">
                        <i class="fas fa-upload"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center; color: #999; padding: 20px;">Semua BAP sudah terunggah atau belum ada milestone yang disetujui.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('modals')
{{-- Modal Upload BAP --}}
<div class="modal fade" id="uploadBapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" id="formUploadBap" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload BAP Milestone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p>Mahasiswa: <strong id="bap-mhs-name"></strong></p>
                        <p>Milestone: <strong id="bap-milestone-name"></strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File BAP (PDF)</label>
                        <input type="file" name="file_bap" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Maksimal 4MB. Format: .pdf</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan & Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
function openUploadBapModal(id, milestone, name) {
    const form = document.getElementById('formUploadBap');
    form.action = "{{ url('/admin/upload-bap') }}/" + id;
    document.getElementById('bap-mhs-name').innerText = name;
    document.getElementById('bap-milestone-name').innerText = milestone;
    
    const modal = new bootstrap.Modal(document.getElementById('uploadBapModal'));
    modal.show();
}
</script>
@endpush

@endsection

@push('scripts')
<style>

.topbar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
.red { background: #e91603; }

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
.warning .highlight-number,
.warning-num {
    background: #fde8e8;
    color: #e74a3b;
}

.info .highlight-number,
.info-num {
    background: #e0f2fe;
    color: #0369a1;
}

.warning {
    border-left: 3px solid #e74a3b;
}

.info {
    border-left: 3px solid #0369a1;
}

.danger-alert {
    border-left: 3px solid #dc3545;
}

.danger-num {
    background: #fee2e2;
    color: #dc3545;
}

.badge-proof {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f1f5f9;
    color: #475569;
    padding: 5px 10px;
    border-radius: 8px;
    text-decoration: none !important;
    font-size: 11px;
    font-weight: 500;
    transition: all 0.2s;
    margin: 2px;
    border: 1px solid #e2e8f0;
}

.badge-proof:hover {
    background: #e2e8f0;
    color: #1e293b;
    transform: translateY(-1px);
}

.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 10px;
    transition: all 0.2s;
    text-decoration: none !important;
}

.btn-icon:hover {
    transform: scale(1.1);
}

.btn-view {
    background: #eef2ff;
    color: #4f46e5;
}

.btn-view:hover {
    background: #4f46e5;
    color: white;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table-custom th {
    background: #f8f9fc;
    padding: 12px;
    font-size: 13px;
    color: #6c757d;
    font-weight: 600;
    text-align: center;
}

.table-custom td {
    padding: 12px;
    border-top: 1px solid #eee;
    font-size: 13px;
    text-align: center;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.status-badge.waiting {
    background: #fffbeb;
    color: #b45309;
}

.clickable-card {
    text-decoration: none;
    color: inherit;
    cursor: pointer;
    transition: all .18s ease;
}

.clickable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.1) !important;
}

.clickable-card:focus,
.clickable-card:visited {
    text-decoration: none;
    color: inherit;
}
.kritis-wrapper {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.kritis-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 28px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    border: 1px solid rgba(226, 232, 240, 0.8);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    text-decoration: none !important;
}

.kritis-card.top-row {
    grid-column: span 3;
    padding: 20px 28px;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.kritis-card.top-row .kritis-value {
    margin-bottom: 0;
    font-size: 36px;
}

.kritis-card.top-row .kritis-header {
    margin-bottom: 0;
    flex-direction: row;
    gap: 12px;
}

.kritis-card.bottom-row {
    grid-column: span 2;
}

.kritis-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: rgba(203, 213, 225, 1);
}

.kritis-card.highlight {
    background: linear-gradient(180deg, #ffffff 0%, #fffafa 100%);
    border: 1px solid #fee2e2;
}

.kritis-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.kritis-title {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kritis-value {
    font-size: 42px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 12px;
    color: #1e293b;
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.kritis-value::after {
    content: 'mahasiswa';
    font-size: 13px;
    font-weight: 500;
    color: #94a3b8;
    text-transform: lowercase;
}

.kritis-desc {
    font-size: 13px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 8px;
    display: inline-block;
    width: fit-content;
}

.kritis-card.danger .kritis-desc { background: #fef2f2; color: #dc2626; }
.kritis-card.warning .kritis-desc { background: #fffbeb; color: #d97706; }
.kritis-card.success .kritis-desc { background: #ecfdf5; color: #059669; }
.kritis-card.info .kritis-desc { background: #f0f9ff; color: #0284c7; }

/* Decorative Circle */
.kritis-card::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    background: currentColor;
    border-radius: 50%;
    bottom: -40px;
    right: -40px;
    opacity: 0.03;
}

#filterAngkatanPie {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.chart-container-pie {
    position: relative;
    height: 300px;
    width: 100%;
}

.chart-container-bar {
    position: relative;
    height: 400px;
    width: 100%;
}

.table-custom tbody tr {
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data from Controller
    const statsByYear = @json($statsByYear);
    const globalMilestoneStats = @json($milestoneStats);
    const globalAhead = {{ $ahead }};
    const globalIdeal = {{ $ideal }};
    const globalBehind = {{ $behind }};

    // --- BAR CHART (Sebaran Milestone) ---
    const ctxBar = document.getElementById('barChart');
    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: Object.keys(globalMilestoneStats),
            datasets: [
                { 
                    label: 'Belum', 
                    data: Object.values(globalMilestoneStats).map(s => s.belum), 
                    backgroundColor: '#f3f4f6', // Light gray/slate
                    borderColor: '#94a3b8',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: '#e5e7eb'
                },
                { 
                    label: 'Sudah', 
                    data: Object.values(globalMilestoneStats).map(s => s.sudah), 
                    backgroundColor: '#10b981', // Emerald
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: '#059669'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: "'Inter', sans-serif", size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: { 
                x: { 
                    grid: { display: false },
                    ticks: { font: { family: "'Inter', sans-serif", weight: '500' } }
                }, 
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { 
                        stepSize: 1,
                        font: { family: "'Inter', sans-serif" }
                    }
                } 
            },
            interaction: {
                intersect: false,
                mode: 'index',
            }
        }
    });

    // --- PIE CHART (Status Masa Studi) ---
    const ctxPie = document.getElementById('statusChart');
    const statusChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: [
                'Tepat Waktu',
                'Tidak Tepat Waktu'
            ],
            datasets: [{
                data: [{{ $groupTepatWaktuCount }}, {{ $groupTidakTepatWaktuCount }}],
                backgroundColor: ['#1cc88a', '#e74a3b'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                            const pct = total ? Math.round(ctx.parsed/total*100) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} mahasiswa (${pct}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // --- BIMBINGAN TREND CHART (Grouped Bar Chart) ---
    const ctxTrend = document.getElementById('bimbinganTrendChart');
    new Chart(ctxTrend, {
        type: 'bar',
        data: {
            labels: @json(collect($chartBimbingan)->pluck('month')),
            datasets: [
                {
                    label: 'Rencana',
                    data: @json(collect($chartBimbingan)->pluck('planned')),
                    backgroundColor: '#4e73df',
                    borderColor: '#2e59d9',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: '#2e59d9'
                },
                {
                    label: 'Terlaksana',
                    data: @json(collect($chartBimbingan)->pluck('completed')),
                    backgroundColor: '#1cc88a',
                    borderColor: '#17a673',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: '#17a673'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: "'Inter', sans-serif", size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: "'Inter', sans-serif", weight: '500' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        stepSize: 1,
                        font: { family: "'Inter', sans-serif" }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            }
        }
    });

    // --- FILTER LOGIC ---
    document.getElementById('filterAngkatanPie').addEventListener('change', function() {
        const year = this.value;
        let data = [{{ $groupTepatWaktuCount }}, {{ $groupTidakTepatWaktuCount }}];
        
        if (year !== 'all' && statsByYear[year]) {
            data = [
                statsByYear[year].tepat_waktu,
                statsByYear[year].tidak_tepat_waktu
            ];
        }
        
        statusChart.data.datasets[0].data = data;
        statusChart.update();
    });

    document.getElementById('filterTahun').addEventListener('change', function() {
        const year = this.value;
        let pLabels = Object.keys(globalMilestoneStats);
        let pSudah = Object.values(globalMilestoneStats).map(s => s.sudah);
        let pBelum = Object.values(globalMilestoneStats).map(s => s.belum);

        if (year !== 'all' && statsByYear[year]) {
            const mData = statsByYear[year].milestones;
            pSudah = pLabels.map(lbl => mData[lbl] ? mData[lbl].sudah : 0);
            pBelum = pLabels.map(lbl => mData[lbl] ? mData[lbl].belum : 0);
        }

        barChart.data.datasets[0].data = pBelum;
        barChart.data.datasets[1].data = pSudah;
        barChart.update();
    });
});


function openModal(id) {
    document.getElementById(id).style.display = "flex";
}
function closeModal(id) {
    document.getElementById(id).style.display = "none";
}
</script>
@endpush

