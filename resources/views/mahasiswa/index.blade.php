@extends('layouts.dosen')

@section('title', 'Data Mahasiswa')

@section('page-content')

<div class="main">

    <h1>{{ $judul }}</h1>

    <!-- Statistik -->
    <div class="stat-card" style="background: {{ $warna }}">
        <div class="stat-content">
            <i class="fas {{ $icon }}"></i>

            <div>
                <h2>{{ $total }}</h2>
                <p>{{ $judul }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card">
        <h3>Daftar Mahasiswa</h3>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Masuk</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Topik Tugas Akhir</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($dataMahasiswa as $index => $mhs)

            @php
                $badgeColor = '#3b4cca';
                if ($mhs['status'] == 'ahead') $badgeColor = '#4CAF50';
                elseif ($mhs['status'] == 'ideal') $badgeColor = '#f6c23e';
                elseif ($mhs['status'] == 'behind') $badgeColor = '#e74a3b';
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mhs['tahun'] }}</td>
                <td>{{ $mhs['nama'] }}</td>
                <td>{{ $mhs['nim'] }}</td>
                <td>{{ $mhs['topik'] }}</td>

                <td>{{ $mhs['progress'] }}</td>

                <td>
                    <span class="badge" style="background: {{ $badgeColor }}">
                        {{ ucfirst($mhs['status']) }}
                    </span>
                </td>

                <td class="aksi">
                    <button class="btn-view">👁</button>
                    <button class="btn-alert">🔔</button>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" style="text-align:center">
                    Tidak ada data mahasiswa
                </td>
            </tr>
            @endforelse

            </tbody>
        </table>
    </div>

</div>


<style>

.main {
    padding: 30px;
}

h1 {
    margin-bottom: 20px;
}

/* Statistik */
.stat-card {
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.stat-card h2 {
    font-size: 32px;
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-content i {
    font-size: 35px;
    opacity: 0.9;
}


/* Card tabel */
.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table th, table td {
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

table thead {
    background: #f1f2f6;
}

table tbody tr {
    border-bottom: 1px solid #eee;
}

/* Badge */
.badge {
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-block;
}

/* Button */
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

@endsection
