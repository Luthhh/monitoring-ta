@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('page-content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h4 class="page-title">Profile Admin</h4>

    <div class="card-profile">

        <div class="profile-grid">
            <div class="label">Nama</div>
            <div class="value">{{ auth()->user()->name ?? 'Admin' }}</div>

            <div class="label">Email</div>
            <div class="value">{{ auth()->user()->email ?? '-' }}</div>

            <div class="label">Password</div>
            <div class="value">********</div>

        </div>

    </div>

    <div class="button-group d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-success btn-edit text-white border-0" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Edit
        </button>
        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="btn btn-danger btn-logout text-white border-0">
                Logout
            </button>
        </form>
    </div>

</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.profile.update') }}" method="POST" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileLabel">Edit Profil Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" required>
        </div>

        <hr>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
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

@push('styles')
<style>
.page-title {
    margin-bottom: 25px;
    font-weight: 600;
}

.card-profile {
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
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

@media(max-width: 768px){
    .profile-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush