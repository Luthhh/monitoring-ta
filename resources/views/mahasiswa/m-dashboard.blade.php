@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('page-content')

<h4 class="mb-4 fw-semibold">Dashboard Tugas Akhir</h4>

<!-- Status Verifikasi -->
<div class="card card-custom p-4 mb-4">
    <h6 class="fw-semibold mb-3">Status Verifikasi</h6>

    <div class="row text-center">
    
        <div class="col">
            <div class="step-circle">1</div>
            <div class="step-label">
                Penetapan<br>
                Komisi<br>
                Pembimbing
            </div>
        </div>
        <div class="col">
            <div class="step-circle">2</div>
            <div class="step-label">
                Sidang<br>
                Komisi<br>
                1
            </div>
        </div>
        <div class="col">
            <div class="step-circle">3</div>
            <div class="step-label">Kolokium</div>
        </div>
        <div class="col">
            <div class="step-circle">4</div>
            <div class="step-label">Proposal</div>
        </div>
        <div class="col">
            <div class="step-circle">5</div>
            <div class="step-label">
                Penelitian<br>
                dan<br>
                Bimbingan
            </div>
        </div>
        <div class="col">
            <div class="step-circle">6</div>
            <div class="step-label">
                Evaluasi<br>
                dan<br>
                Monitoring
            </div>
        </div>
        <div class="col">
            <div class="step-circle">7</div>
            <div class="step-label">
                Sidang<br>
                Komisi<br>
                2
            </div>
        </div>
        <div class="col">
            <div class="step-circle">8</div>
            <div class="step-label">Seminar</div>
        </div>
        <div class="col">
            <div class="step-circle">9</div>
            <div class="step-label">
                Publikasi<br>
                Ilmiah
            </div>
        </div>
        <div class="col">
            <div class="step-circle">10</div>
            <div class="step-label">
                Ujian<br>
                Tesis
            </div>
        </div>
        <div class="col">
            <div class="step-circle">11</div>
            <div class="step-label">SKL</div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="card card-custom p-4 mb-4">
    <h6 class="fw-semibold mb-3">Progress Tahapan</h6>
    <canvas id="progressChart"></canvas>
</div>

<!-- Riwayat -->
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-semibold mb-0">Riwayat Pengajuan</h6>
            <a href="/mahasiswa/tambah-bimbingan" class="btn btn-primary btn-sm">
                + Ajukan Bimbingan
            </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tahun Semester</th>
                    <th>Dosen Pembimbing</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>2024/2025 Ganjil</td>
                    <td>Dr. Lina</td>
                    <td><span class="badge badge-warning text-dark">Diproses</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm">Batal</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection


@push('styles')
<style>

.card-custom {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.step-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    font-size: 14px;
    font-weight: 500;
}

.step-circle.active {
    background: #4e73df;
    color: white;
}

.step-label {
    font-size: 13px;
    margin-top: 5px;
    line-height: 1.2;
}

.btn-primary {
    background-color: #4e73df;
    border: none;
    border-radius: 10px;
}

.btn-danger {
    border-radius: 10px;
}

.badge-warning {
    background-color: #f6c23e;
}

</style>
@endpush


@push('scripts')
<script>
const ctx = document.getElementById('progressChart');

new Chart(ctx, {
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
                data: [30, 25, 20, 15, 10, 8, 5, 3, 2, 1, 0],
                backgroundColor: '#4e73df'
            },
            {
                label: 'Sudah',
                data: [0, 5, 10, 15, 20, 22, 25, 27, 28, 29, 30],
                backgroundColor: '#f6c23e'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true
            }
        }
    }
});

</script>
@endpush
