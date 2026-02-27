@extends('layouts.admin')

@section('title', 'Manajemen Dosen')

@section('page-content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



    <div class="table-tools">
        <input type="text" id="searchInput" placeholder="🔍 Cari nama dosen...">
    </div>

    <div class="table-dosen">
        <div class="table-header">
            <h3>Tabel Dosen</h3>
            <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createDosenModal">
                + Tambah Dosen
            </button>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table id="tabelDosen">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dosens as $index => $dosen)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $dosen->nip }}</td>
                        <td>{{ $dosen->user->name }}</td>
                        <td>{{ $dosen->prodi }}</td>
                        <td class="action-buttons">

                            <!-- VIEW -->
                            <button class="btn-icon btn-view" data-bs-toggle="modal"
                                data-bs-target="#viewModal{{ $dosen->id }}">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- EDIT -->
                            <button class="btn-icon btn-edit" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $dosen->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" style="display:inline"
                                onsubmit="return confirm('Yakin hapus dosen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            Data dosen belum ada
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- MODAL TAMBAH DOSEN -->
    <div class="modal fade" id="createDosenModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('admin.dosen.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Program Studi</label>
                            <input type="text" name="prodi" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-success">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- VIEW MODAL -->
    <div class="modal fade" id="viewModal{{ $dosen->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><strong>NIP:</strong> {{ $dosen->nip }}</p>
                    <p><strong>Nama:</strong> {{ $dosen->user->name }}</p>
                    <p><strong>Email:</strong> {{ $dosen->user->email }}</p>
                    <p><strong>Prodi:</strong> {{ $dosen->prodi }}</p>
                </div>

            </div>
        </div>
    </div>
    <!-- EDIT MODAL -->
    <div class="modal fade" id="editModal{{ $dosen->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="text" name="nip" value="{{ $dosen->nip }}" class="form-control mb-2" required>

                        <input type="text" name="name" value="{{ $dosen->user->name }}" class="form-control mb-2" required>

                        <input type="text" name="prodi" value="{{ $dosen->prodi }}" class="form-control" required>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
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
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        }


        .card h2 {
            font-size: 28px;
        }

        .table-dosen {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
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
        }

        td:nth-child(3) {
            max-width: 200px;
            word-break: break-word;
        }

        table td:nth-child(3) {
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
            justify-content: center;
            /* 🔥 ini bikin center */
            align-items: center;
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

        .btn-edit {
            background: #ffc107;
        }

        .btn-delete {
            background: #dc3545;
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

        /* HEADER TABLE */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .table-header h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        /* BUTTON CREATE */
        .btn-create {
            background: #02048d;
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-create:hover {
            background: #1a1bb8;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const dataPerTahun = {
                2021: {
                    belum: [30, 25, 20, 15, 10, 8, 5, 3, 2, 1, 0],
                    sudah: [0, 5, 10, 15, 20, 22, 25, 27, 28, 29, 30]
                },
                2022: {
                    belum: [40, 30, 25, 18, 12, 10, 6, 4, 3, 2, 1],
                    sudah: [0, 10, 15, 22, 28, 30, 34, 36, 37, 38, 39]
                },
                2023: {
                    belum: [50, 45, 35, 25, 15, 12, 8, 5, 3, 2, 1],
                    sudah: [0, 5, 15, 25, 35, 38, 42, 45, 47, 48, 49]
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
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Bimbingan',
                        data: [5, 18, 25, 6, 17, 26, 13, 7, 18, 6, 18, 25],
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
        window.addEventListener('click', function (e) {
            const modal = document.getElementById('logModal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

    </script>
@endpush