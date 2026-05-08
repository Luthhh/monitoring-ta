@extends('layouts.mahasiswa')

@section('title', 'Tambah Bimbingan')

@section('page-content')

<style>
body {
    background: #f5f6fa;
}

.page-title {
    font-weight: 600;
    margin-bottom: 20px;
}

.card-form {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.card-form h5 {
    margin-bottom: 20px;
    font-weight: 600;
}

.form-label {
    font-size: 14px;
    font-weight: 500;
}

textarea {
    resize: none;
}

.btn-group-custom {
    text-align: right;
}

/* Pastikan input date bisa diklik */
input[type="date"] {
    pointer-events: auto !important;
    cursor: pointer !important;
    position: relative;
    z-index: 1;
    -webkit-appearance: auto !important;
    appearance: auto !important;
    padding-right: 10px;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 1;
    position: absolute;
    right: 8px;
    width: 20px;
    height: 20px;
    z-index: 2;
}
</style>


<div class="container-fluid">

    <h4 class="page-title">Tambah Bimbingan</h4>

    <form action="{{ url('/mahasiswa/tambah-bimbingan') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Gambaran --}}
        <div class="card-form">
            <h5>Detail Bimbingan</h5>

            <div class="mb-3">
                <label class="form-label">Deskripsi Bimbingan *</label>
                <textarea name="deskripsi" class="form-control" rows="4" required placeholder="Jelaskan topik yang akan dibahas..."></textarea>
            </div>
        </div>

        {{-- Waktu --}}
        <div class="card-form">
            <h5>Waktu dan Tempat</h5>

            <div class="row">
                <div class="col-md-6 mb-3" style="position: relative; z-index: 5;">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="tanggal" class="form-control" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mb-3" style="position: relative; z-index: 5;">
                    <label class="form-label">Waktu *</label>
                    <input type="time" name="waktu" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tempat *</label>
                <input type="text" name="tempat" class="form-control" placeholder="Contoh: Lab, Zoom, Ruang Dosen" required>
            </div>
        </div>

        {{-- Pembimbing --}}
        <div class="card-form">
            <h5>Pembimbing Kegiatan</h5>

            @if($mahasiswa->pembimbing1 || $mahasiswa->pembimbing2)
                <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Dosen yang ditampilkan adalah dosen pembimbing Anda yang sudah dipilih.
                </div>
            @else
                <div class="alert alert-warning py-2 mb-3" style="font-size:13px;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Anda belum memilih dosen pembimbing. Silakan
                    <a href="{{ route('mahasiswa.profile') }}">pilih dosen pembimbing</a> terlebih dahulu,
                    atau pilih dari semua dosen di bawah.
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Pilih Dosen Pembimbing *</label>
                <select name="dosen_id" class="form-select" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->user->name ?? '-' }}{{ $dosen->nip ? ' (' . $dosen->nip . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="btn-group-custom">
            <a href="{{ url('/mahasiswa/dashboard') }}" class="btn btn-secondary text-white">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">Ajukan Bimbingan</button>
        </div>

    </form>

</div>

@endsection
