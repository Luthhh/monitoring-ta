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

        <h4 class="page-title">Biodata Dosen</h4>

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

@endsection