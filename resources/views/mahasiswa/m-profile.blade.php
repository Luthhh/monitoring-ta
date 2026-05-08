@extends('layouts.mahasiswa')

@section('title', 'Profil Mahasiswa')

@section('page-content')

<div class="container-fluid">

    <h4 class="page-title">Profile Mahasiswa</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Informasi Pribadi --}}
    <div class="section-card">
        <div class="section-header">
            <i class="bi bi-person-fill me-2"></i> Informasi Pribadi
        </div>
        <div class="d-flex flex-column align-items-center mb-4">
            <div class="profile-photo-container">
                @if($mahasiswa->foto)
                    <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Profil" class="profile-photo">
                @else
                    <div class="profile-photo-placeholder">
                        <i class="bi bi-person-fill fs-1 text-muted"></i>
                    </div>
                @endif
                <button class="btn btn-sm btn-primary profile-photo-edit-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-camera-fill"></i>
                </button>
            </div>
            <div class="mt-3 text-center">
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="text-muted small">{{ $mahasiswa->nim }}</span>
            </div>
        </div>
        <div class="profile-grid">
            <div class="label">Email</div>
            <div class="value">{{ $user->email }}</div>

            <div class="label">Tahun Masuk</div>
            <div class="value">{{ $mahasiswa->angkatan_formatted }}</div>

            <div class="label">Semester</div>
            <div class="value">{{ $mahasiswa->semester }}</div>

            <div class="label">Program Studi</div>
            <div class="value">{{ $mahasiswa->prodi ?? '-' }}</div>

            <div class="label">Judul Penelitian</div>
            <div class="value">{{ $tugasAkhir?->judul ?? 'Belum ada judul' }}</div>

            <div class="label">Status Progres</div>
            <div class="value">
                <span class="badge bg-info text-dark">{{ $tugasAkhir?->status ?? 'Proses' }}</span>
            </div>

            <div class="label">SK Pembimbing</div>
            <div class="value">
                @if($mahasiswa->sk_pembimbing)
                    <a href="{{ asset('storage/' . $mahasiswa->sk_pembimbing) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Lihat SK Pembimbing
                    </a>
                @else
                    <span class="text-muted">Belum ada dokumen SK</span>
                @endif
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-fill me-1"></i> Edit Profil
            </button>
        </div>
    </div>

    {{-- Dosen Pembimbing --}}
    <div class="section-card">
        <div class="section-header">
            <i class="bi bi-person-badge-fill me-2"></i> Dosen Pembimbing
        </div>

        <div class="row g-4">
            {{-- Pembimbing 1 --}}
            <div class="col-md-6">
                <div class="pembimbing-card {{ $mahasiswa->pembimbing1 ? 'has-pembimbing' : 'no-pembimbing' }}">
                    <div class="pembimbing-label">Pembimbing 1</div>
                    @if($mahasiswa->pembimbing1)
                        <div class="pembimbing-name">{{ $mahasiswa->pembimbing1->user->name ?? '-' }}</div>
                        <div class="pembimbing-nip text-muted">NIP: {{ $mahasiswa->pembimbing1->nip ?? '-' }}</div>
                        <div class="pembimbing-prodi text-muted">{{ $mahasiswa->pembimbing1->prodi ?? '-' }}</div>
                    @else
                        <div class="pembimbing-empty">
                            <i class="bi bi-person-x-fill fs-2 mb-2 d-block text-muted"></i>
                            <span class="text-muted">Belum ada pembimbing 1</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pembimbing 2 --}}
            <div class="col-md-6">
                <div class="pembimbing-card {{ $mahasiswa->pembimbing2 ? 'has-pembimbing' : 'no-pembimbing' }}">
                    <div class="pembimbing-label">Pembimbing 2</div>
                    @if($mahasiswa->pembimbing2)
                        <div class="pembimbing-name">{{ $mahasiswa->pembimbing2->user->name ?? '-' }}</div>
                        <div class="pembimbing-nip text-muted">NIP: {{ $mahasiswa->pembimbing2->nip ?? '-' }}</div>
                        <div class="pembimbing-prodi text-muted">{{ $mahasiswa->pembimbing2->prodi ?? '-' }}</div>
                    @else
                        <div class="pembimbing-empty">
                            <i class="bi bi-person-x-fill fs-2 mb-2 d-block text-muted"></i>
                            <span class="text-muted">Belum ada pembimbing 2</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pilihPembimbingModal">
                <i class="bi bi-person-check-fill me-1"></i>
                {{ ($mahasiswa->pembimbing1 || $mahasiswa->pembimbing2) ? 'Ubah Dosen Pembimbing' : 'Pilih Dosen Pembimbing' }}
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>

</div>

{{-- Modal Edit Profil --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <p class="text-muted small">Lengkapi data profil Anda untuk memudahkan koordinasi bimbingan.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">NIM (Tidak dapat diubah)</label>
                        <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <div class="input-group" style="display: flex;">
                            <input type="password" name="password" class="form-control password-input" placeholder="Kosongkan jika tidak ingin mengubah password" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; border-radius: 0 10px 10px 0;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Minimal 8 karakter & kombinasi huruf/angka</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SK Pembimbing (PDF)</label>
                        <input type="file" name="sk_pembimbing" class="form-control" accept=".pdf">
                        <small class="text-muted">Format PDF, Maksimal 2MB. @if($mahasiswa->sk_pembimbing) <span class="text-success">Sudah ada dokumen.</span> @endif</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Profil (JPG/PNG)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Format JPG/PNG, Maksimal 1MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Pilih Dosen Pembimbing --}}
<div class="modal fade" id="pilihPembimbingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('mahasiswa.profile.update-pembimbing') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;">
                    <h5 class="modal-title">
                        <i class="bi bi-person-check-fill me-2"></i>Pilih Dosen Pembimbing
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Pilih dosen pembimbing utama (Pembimbing 1) dan dosen pembimbing pendamping (Pembimbing 2). Pembimbing 1 dan 2 tidak boleh sama.</p>

                    <div class="row g-4">
                        {{-- Pembimbing 1 --}}
                        <div class="col-md-6">
                            <label for="pembimbing1_id" class="form-label fw-semibold">
                                <span class="badge bg-primary me-1">1</span> Pembimbing Utama
                            </label>
                            <select name="pembimbing1_id" id="pembimbing1_id" class="form-select select-dosen">
                                <option value="">-- Tidak ada / Hapus --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}"
                                        {{ $mahasiswa->pembimbing1_id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->user->name ?? 'Dosen #'.$dosen->id }}
                                        @if($dosen->nip) ({{ $dosen->nip }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pembimbing 2 --}}
                        <div class="col-md-6">
                            <label for="pembimbing2_id" class="form-label fw-semibold">
                                <span class="badge bg-secondary me-1">2</span> Pembimbing Pendamping
                            </label>
                            <select name="pembimbing2_id" id="pembimbing2_id" class="form-select select-dosen">
                                <option value="">-- Tidak ada / Hapus --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}"
                                        {{ $mahasiswa->pembimbing2_id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->user->name ?? 'Dosen #'.$dosen->id }}
                                        @if($dosen->nip) ({{ $dosen->nip }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="pembimbing-warning" class="alert alert-warning mt-3 d-none">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Pembimbing 1 dan Pembimbing 2 tidak boleh sama!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpanPembimbing" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
.page-title {
    margin-bottom: 25px;
    font-weight: 700;
    font-size: 1.4rem;
    color: #1e1b4b;
}

.section-card {
    background: white;
    padding: 30px 35px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 24px;
}

.section-header {
    font-weight: 700;
    font-size: 1rem;
    color: #4f46e5;
    border-bottom: 2px solid #ede9fe;
    padding-bottom: 12px;
    margin-bottom: 22px;
    letter-spacing: 0.3px;
}

.profile-photo-container {
    position: relative;
    width: 120px;
    height: 120px;
}

.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #ede9fe;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.profile-photo-placeholder {
    width: 100%;
    height: 100%;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px dashed #cbd5e1;
}

.profile-photo-edit-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border: 2px solid white;
}

.profile-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    row-gap: 16px;
    column-gap: 40px;
}

.label {
    font-weight: 600;
    color: #555;
    border-bottom: 1px dashed #eee;
    padding-bottom: 8px;
}

.value {
    color: #333;
    font-weight: 500;
    border-bottom: 1px dashed #eee;
    padding-bottom: 8px;
}

/* Pembimbing Cards */
.pembimbing-card {
    border-radius: 14px;
    padding: 24px;
    min-height: 130px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.pembimbing-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.pembimbing-card.has-pembimbing {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1.5px solid #bbf7d0;
}

.pembimbing-card.no-pembimbing {
    background: #f8fafc;
    border: 1.5px dashed #cbd5e1;
}

.pembimbing-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #6b7280;
    margin-bottom: 10px;
}

.pembimbing-name {
    font-weight: 700;
    font-size: 1.05rem;
    color: #065f46;
    margin-bottom: 4px;
}

.pembimbing-nip, .pembimbing-prodi {
    font-size: 0.85rem;
    line-height: 1.6;
}

.pembimbing-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding-top: 10px;
    opacity: 0.6;
    font-size: 0.9rem;
}

.select-dosen {
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    padding: 10px;
    font-size: 0.95rem;
    transition: border-color 0.2s;
}

.select-dosen:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
}

@media(max-width: 768px){
    .profile-grid {
        grid-template-columns: 1fr;
    }
    .section-card {
        padding: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pb1 = document.getElementById('pembimbing1_id');
    const pb2 = document.getElementById('pembimbing2_id');
    const warning = document.getElementById('pembimbing-warning');
    const btnSimpan = document.getElementById('btnSimpanPembimbing');

    function checkSame() {
        const v1 = pb1.value;
        const v2 = pb2.value;
        const isSame = v1 && v2 && v1 === v2;
        warning.classList.toggle('d-none', !isSame);
        btnSimpan.disabled = isSame;
    }

    pb1.addEventListener('change', checkSame);
    pb2.addEventListener('change', checkSame);

    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.password-input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
});
</script>
@endpush
