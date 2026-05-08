@extends('layouts.dosen')

@section('title', 'Profil Dosen')

@section('page-content')

    <style>
        .page-title {
            margin-bottom: 25px;
            font-weight: 600;
        }

        .card-profile {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            row-gap: 20px;
            column-gap: 40px;
        }

        .label {
            font-weight: 600;
            color: #555;
        }

        .value {
            color: #333;
        }

        .button-group {
            text-align: right;
        }

        .btn-edit {
            background: #4CAF50;
            color: white;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
        }

        @media(max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h4 class="page-title">Profil Dosen</h4>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <a href="{{ url('/dosen/total-mahasiswa') }}?pembimbing=1" class="text-decoration-none">
                    <div class="card-profile" style="padding: 20px; border-left: 5px solid #02048d; margin-bottom: 0;">
                        <div style="font-size: 14px; color: #666; font-weight: 600;">Mahasiswa Pembimbing 1</div>
                        <div style="font-size: 32px; font-weight: 800; color: #02048d;">{{ $jmlPembimbing1 }}</div>
                        <div style="font-size: 12px; color: #4e73df;">Lihat daftar mahasiswa <i class="bi bi-arrow-right"></i></div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <a href="{{ url('/dosen/total-mahasiswa') }}?pembimbing=2" class="text-decoration-none">
                    <div class="card-profile" style="padding: 20px; border-left: 5px solid #1cc88a; margin-bottom: 0;">
                        <div style="font-size: 14px; color: #666; font-weight: 600;">Mahasiswa Pembimbing 2</div>
                        <div style="font-size: 32px; font-weight: 800; color: #1cc88a;">{{ $jmlPembimbing2 }}</div>
                        <div style="font-size: 12px; color: #16a34a;">Lihat daftar mahasiswa <i class="bi bi-arrow-right"></i></div>
                    </div>
                </a>
            </div>
        </div>

        <h5 class="mb-3 fw-bold">Biodata Dosen</h5>

        <div class="card-profile">

            <div class="profile-grid">

                <div class="label">NIP</div>
                <div class="value">{{ $dosen->nip ?? '-' }}</div>

                <div class="label">Nama</div>
                <div class="value">{{ $user->name }}</div>

                <div class="label">Program Studi</div>
                <div class="value">{{ $dosen->prodi ?? '-' }}</div>

                <div class="label">Email</div>
                <div class="value">{{ $user->email }}</div>

                <div class="label">Jabatan</div>
                <div class="value">Dosen Pembimbing</div>

            </div>

        </div>

        <div class="button-group">
            <a href="#" class="btn btn-success btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                Edit
            </a>
            <a href="#" class="btn btn-danger btn-logout"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

        <!-- Modal Edit Profile -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form action="{{ route('dosen.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Biodata</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label>NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $dosen->nip }}">
                            </div>

                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                            </div>

                            <div class="mb-3">
                                <label>Program Studi</label>
                                <input type="text" name="prodi" class="form-control" value="{{ $dosen->prodi }}">
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                            </div>

                            <hr>
                            
                            <div class="mb-3">
                                <label>Password Baru</label>
                                <div class="input-group" style="display: flex;">
                                    <input type="password" name="password" class="form-control password-input" placeholder="Kosongkan jika tidak ingin diubah" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; border-radius: 0 10px 10px 0;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimal 8 karakter & kombinasi huruf/angka</small>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
@endsection