@extends('layouts.admin')

@section('title', 'Manajemen Mahasiswa')

@section('page-content')



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Alert section -->
@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Statistik -->
<div class="cards mt-3">
    <a href="{{ url('/admin/total-mahasiswa') }}" class="card blue text-decoration-none">
        <h2>{{ $mahasiswas->count() }}</h2>
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

<form action="{{ route('admin.manajemen-mahasiswa') }}" method="GET" class="table-tools" id="filterForm">
    <input type="text" name="search" id="searchInput" placeholder="🔍 Cari nama atau NIM..." value="{{ request('search') }}">
    <select name="tahun" id="sortTahun">
        <option value="">Semua Tahun</option>
        @foreach($tahunMasukList as $tahun)
            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
        @endforeach
    </select>
    <select name="semester" id="sortSemester">
        <option value="">Semua Semester</option>
        @for($i=1; $i<=8; $i++)
            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
        @endfor
    </select>
    <select name="dosen" id="sortDosen">
        <option value="">Semua Dosen</option>
        @foreach($dosens as $d)
            <option value="{{ $d->user->name }}" {{ request('dosen') == $d->user->name ? 'selected' : '' }}>{{ $d->user->name }}</option>
        @endforeach
    </select>
</form>

<div class="table-mahasiswa">
    <div class="table-header">
        <h3>Tabel Mahasiswa</h3>
            <div class="d-flex gap-2 flex-wrap">
            {{-- Import --}}
            <button class="btn-import" data-bs-toggle="modal" data-bs-target="#importModal">
                📥 Import Excel/CSV
            </button>
            {{-- Export --}}
            <div class="dropdown">
                <button class="btn-export dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                    📤 Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownExport">
                    <li><a class="dropdown-item" href="{{ route('admin.mahasiswa.export-excel') }}">📊 Export Excel (.xlsx)</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.mahasiswa.export-csv') }}">📄 Export CSV</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.mahasiswa.template') }}">📋 Download Template Import</a></li>
                </ul>
            </div>
            <button class="btn-create border-0 cursor-pointer" data-bs-toggle="modal" data-bs-target="#tambahMahasiswaModal">
                + Tambah Mahasiswa
            </button>
        </div>
    </div>
        <table id="tabelMahasiswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tahun Masuk</th>
                    <th>Semester</th>
                    <th>Prodi</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $index => $mhs)
                <tr class="align-middle" 
                    data-tahun="{{ $mhs->tahun_masuk }}" 
                    data-semester="{{ $mhs->semester }}"
                    data-d1="{{ optional($mhs->pembimbing1)->user->name ?? '' }}" 
                    data-d2="{{ optional($mhs->pembimbing2)->user->name ?? '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->user->name }}</td>
                    <td>{{ $mhs->angkatan_formatted }}</td>
                    <td>{{ $mhs->semester }}</td>
                    <td>
                        <span class="badge badge-blue">
                            {{ $mhs->prodi }}
                        </span>
                    </td>
                    <td class="action-buttons">
                        <!-- Lihat -->
                        <a href="{{ route('admin.detail-mahasiswa', $mhs->id) }}" class="btn-icon btn-view text-decoration-none" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#editMahasiswaModal-{{ $mhs->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="d-inline form-confirm" data-text="Yakin ingin menghapus mahasiswa ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($mahasiswas->isEmpty())
                <tr class="empty-row">
                    <td colspan="7" class="text-center">Belum ada data mahasiswa</td>
                </tr>
                @else
                <tr class="empty-row" style="display: none;">
                    <td colspan="7" class="text-center">Tidak ada mahasiswa yang sesuai dengan filter</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $mahasiswas->links() }}
    </div>
</div>

@foreach($mahasiswas as $mhs)
<!-- Modal Edit Mahasiswa -->
<div class="modal fade" id="editMahasiswaModal-{{ $mhs->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.mahasiswa.update', $mhs->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $mhs->user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" name="nim" class="form-control" value="{{ $mhs->nim }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $mhs->user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prodi</label>
                    <input type="text" name="prodi" class="form-control" value="{{ $mhs->prodi }}" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control" value="{{ $mhs->tahun_masuk }}" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control" value="{{ $mhs->semester }}" required>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">Password Baru (Kosongkan Jika Tidak Diubah)</label>
                    <div class="input-group" style="display: flex;">
                        <input type="password" name="password" class="form-control password-input" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                        <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; border-radius: 0 10px 10px 0;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Tambah Mahasiswa -->
<div class="modal fade" id="tambahMahasiswaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" name="nim" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prodi</label>
                    <input type="text" name="prodi" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control" value="1" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group" style="display: flex;">
                        <input type="password" name="password" class="form-control password-input" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                        <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; border-radius: 0 10px 10px 0;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Import --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">📥 Import Mahasiswa dari Excel/CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <div class="alert" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px;font-size:13px;">
                    <strong>📋 Format kolom yang diperlukan:</strong><br>
                    <code>nim, nama, email, prodi, tahun_masuk, semester, password</code><br><br>
                    Password bersifat opsional. Jika kosong, password default = NIM mahasiswa.<br>
                    Baris yang duplikat (NIM/email sudah ada) akan dilewati otomatis.
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label fw-semibold">Pilih File</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    <div class="form-text">Format: .xlsx, .xls, atau .csv. Maks 5MB.</div>
                </div>
                <a href="{{ route('admin.mahasiswa.template') }}" class="btn btn-outline-secondary btn-sm">
                    📋 Download Template CSV
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/a-manajemenmahasiswa.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin/a-manajemenmahasiswa.js') }}"></script>
@endpush
