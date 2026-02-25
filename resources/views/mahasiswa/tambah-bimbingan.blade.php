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
</style>


<div class="container-fluid">

    <h4 class="page-title">Tambah Bimbingan</h4>

    <form>

        {{-- Gambaran --}}
        <div class="card-form">
            <h5>Gambaran Kegiatan</h5>

            <div class="mb-3">
                <label class="form-label">Tahun Semester *</label>
                <select class="form-select">
                    <option>-- Pilih --</option>
                    <option>2024/2025 Ganjil</option>
                    <option>2024/2025 Genap</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Kegiatan *</label>
                <input type="text" class="form-control" placeholder="Judul kegiatan">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Kegiatan *</label>
                <textarea class="form-control" rows="4"></textarea>
            </div>
        </div>


        {{-- Waktu --}}
        <div class="card-form">
            <h5>Waktu dan Tempat</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai *</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Selesai *</label>
                    <input type="date" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Durasi Jam *</label>
                <input type="number" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Tipe Penyelenggaraan *</label>
                <select class="form-select">
                    <option>Hybrid</option>
                    <option>Online</option>
                    <option>Offline</option>
                </select>
            </div>
        </div>


        {{-- Pembimbing --}}
        <div class="card-form">
            <h5>Pembimbing Kegiatan</h5>

            <div class="mb-3">
                <label class="form-label">Pembimbing IPB *</label>
                <select class="form-select">
                    <option>-- Pilih Dosen --</option>
                    <option>Dr. Lina</option>
                    <option>Dr. Andi</option>
                </select>
            </div>
        </div>


        {{-- Dokumen --}}
        <div class="card-form">
            <h5>Dokumen Pendukung</h5>

            <div class="mb-3">
                <label class="form-label">Nama *</label>
                <input type="text" class="form-control" placeholder="Sertifikat, LOA, dll">
            </div>

            <div class="mb-3">
                <label class="form-label">File *</label>
                <input type="file" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Link *</label>
                <input type="text" class="form-control" placeholder="URL kegiatan">
            </div>

            <small class="text-muted">Maksimum upload 10MB</small>
        </div>


        <div class="btn-group-custom">
            <a href="/mahasiswa/dashboard" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

    </form>

</div>

@endsection
