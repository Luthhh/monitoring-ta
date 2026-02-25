@extends('layouts.dosen')

@section('title','Dashboard Dosen')

@section('page-content')


<div class="main">

    <div class="topbar">
        <h2>Monitoring Tugas Akhir</h2>
        <div class="topbar-right">
            <a href="/dosen/notifikasi" class="notif-icon">
                <i class="fas fa-bell"></i>
            </a>
            <img src="https://i.pravatar.cc/100" alt="">
            <span>Dini S.Kom M.Kom</span>
        </div>
    </div>

    <div class="cards">
        <a href="{{ url('/dosen/total-mahasiswa') }}" class="card blue text-decoration-none">
            <h2>2</h2>
            <p>Mahasiswa Aktif</p>
        </a>
        <a href="{{ url('/dosen/ahead-mahasiswa') }}" class="card green text-decoration-none">
            <h2>2</h2>
            <p>Ahead</p>
        </a>
        <a href="{{ url('/dosen/ideal-mahasiswa') }}" class="card yellow text-decoration-none">
            <h2>0</h2>
            <p>Ideal</p>
        </a>
        <a href="{{ url('/dosen/behind-mahasiswa') }}" class="card red text-decoration-none">
            <h2>0</h2>
            <p>Behind</p>
        </a>
    </div>

    <div class="highlight-bar">
        <a href="{{ url('/dosen/aktivitas-bimbingan') }}" class="highlight-item warning clickable-card">
            <div class="highlight-text">
                🔔 Mahasiswa tidak bimbingan &gt; 30 hari
            </div>
            <div class="highlight-number">2</div>
        </a>
        <a href="{{ url('/dosen/aktivitas-bimbingan') }}" class="highlight-item warning clickable-card">
            <div class="highlight-text">
                📅 Bimbingan bulan ini
            </div>
            <div class="highlight-number">3</div>
        </a>
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

</div>

<div class="page-wrapper">
    <h2 class="page-title">Pengajuan & Verifikasi Bimbingan</h2>

    <!-- CARD SUMMARY -->
    <div class="summary-wrapper">
        <div class="summary-card">
            <div class="summary-text">
                📝 Pengajuan Menunggu
            </div>
            <div class="summary-number">5</div>
        </div>
        <div class="summary-card">
            <div class="summary-text">
                📄 Bukti Menunggu Verifikasi
            </div>
            <div class="summary-number">3</div>
        </div>
    </div>

    {{-- TABLE PENGAJUAN BIMBINGAN --}}
    <div class="card-box">
        <div class="card-header">
            <span>Pengajuan Bimbingan</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tgl Pengajuan</th>
                    <th>Rencana Bimbingan</th>
                    <th>Topik</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>J0403221149</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Andi Saputra
                    </td>
                    <td>5 Feb 2026</td>
                    <td>10 Feb 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('pengajuanModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221150</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Anindya
                    </td>
                    <td>10 Feb 2026</td>
                    <td>13 Maret 2026</td>
                    <td>Pengajuan Judul</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('pengajuanModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221151</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Yasmin
                    </td>
                    <td>14 Feb 2026</td>
                    <td>2 April 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('pengajuanModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221152</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Rahdi
                    </td>
                    <td>14 Feb 2026</td>
                    <td>2 April 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('pengajuanModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221153</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Dinda
                    </td>
                    <td>14 Feb 2026</td>
                    <td>2 April 2026</td>
                    <td>Revisi Bimbingan</td>
                    <td><span class="status-badge waiting">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('pengajuanModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- TABLE SRUJUI BUKTI BIMBINGAN--}}
    <div class="card-box">
        <div class="card-header">
            <span>Verifikasi Bukti Bimbingan</span>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tgl Bimbingan</th>
                    <th>Topik</th>
                    <th>Milestone</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>J0403221149</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Siti Rahma
                    </td>
                    <td>5 Feb 2026</td>
                    <td>Sidang Komisi 1</td>
                    <td><button class="btn-proof">📄 Lihat Bukti</button></td>
                    <td><span class="status-badge verify">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('verifikasiModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221150</td>
                    <td>
                        <span class="role-badge p2">P2</span>
                        Anindya
                    </td>
                    <td>10 Feb 2026</td>
                    <td>Seminar</td>
                    <td><button class="btn-proof">📄 Lihat Bukti</button></td>
                    <td><span class="status-badge verify">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('verifikasiModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>J0403221151</td>
                    <td>
                        <span class="role-badge p1">P1</span>
                        Yasmin
                    </td>
                    <td>14 Feb 2026</td>
                    <td>Kolokium</td>
                    <td><button class="btn-proof">📄 Lihat Bukti</button></td>
                    <td><span class="status-badge verify">Menunggu</span></td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <button class="btn-icon btn-view" onclick="openModal('verifikasiModal')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <!-- Setujui -->
                        <button class="btn-icon btn-approve" onclick="openModal('approveModal')">
                            <i class="fas fa-check"></i>
                        </button>
                        <!-- Tolak -->
                        <button class="btn-icon btn-reject" onclick="openModal('rejectModal')">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


{{-- Modal Pengajuan Bimbingan--}}
<div id="pengajuanModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Detail Pengajuan Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('pengajuanModal')">✖</span>
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

                <div class="label">Catatan</div>
                <div class="colon">:</div>
                <div class="value">Perlu berdiskusi terkait metode</div>

                <div class="label">Status Verifikasi</div>
                <div class="colon">:</div>
                <div class="value">
                    <td><span class="status-badge waiting">Menunggu</span></td>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Verifikasi --}}
<div id="verifikasiModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Detail Verifikasi Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('verifikasiModal')">✖</span>
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

{{-- Modal Setujui --}}
<div id="approveModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Setujui Bimbingan</h4>
            <span class="close-modal" onclick="closeModal('approveModal')">✖</span>
        </div>

        <div class="modal-body">

            <div class="mb-2">Catatan :</div>
            <textarea style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;"></textarea>

        </div>

        <div class="modal-footer-log">
            <button class="btn-close-log" onclick="closeModal('approveModal')">Batal</button>
            <button class="btn-acc">Setujui</button>
        </div>
    </div>
</div>


{{-- Modal Tolak --}}
<div id="rejectModal" class="modal-log">
    <div class="modal-content-log">

        <div class="modal-header">
            <h4>Tolak Pengajuan</h4>
            <span class="close-modal" onclick="closeModal('rejectModal')">✖</span>
        </div>

        <div class="modal-body">

            <div class="mb-2">Catatan:</div>
            <textarea style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;"></textarea>

        </div>

        <div class="modal-footer-log">
            <button class="btn-close-log" onclick="closeModal('rejectModal')">Batal</button>
            <button class="btn-reject">Tolak</button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>

.main {
    flex: 1;
    padding: 25px;
    background: #f4f6f9;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.topbar-right img {
    width: 35px;
    border-radius: 50%;
}

/* Cards */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

/* Chart Box */
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

.page-wrapper {
    padding: 24px;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 20px;
}

/* SUMMARY */
.summary-wrapper {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: 260px;   /* diperbesar */
    gap: 15px;          /* jarak antar isi */
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border: 1px solid #eef1f6;
}

.summary-text {
    font-size: 14px;
    color: #6c757d;
    flex: 1;            /* biar text ambil ruang */
}

.summary-number {
    background: #4e73df;
    color: white;
    border-radius: 8px;
    padding: 6px 12px;
    font-weight: 600;
    min-width: 32px;
    text-align: center;
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

table td:nth-child(2)  {
    text-align: left;
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

</script>
@endpush
