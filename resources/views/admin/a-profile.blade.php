@extends('layouts.admin')

@section('title', 'Profil Dosen')

@section('page-content')

<div class="container-fluid">

    <h4 class="page-title">Profile Admin</h4>

    <div class="card-profile">

        <div class="profile-grid">
            <div class="label">Email</div>
            <div class="value">andi@kampus.ac.id</div>

            <div class="label">Password</div>
            <div class="value">****</div>

        </div>

    </div>

    <div class="button-group">
        <a href="#" class="btn btn-success btn-edit">
            Edit
        </a>
        <a href="#" class="btn btn-danger btn-logout">
            Logout
        </a>
    </div>

</div>

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