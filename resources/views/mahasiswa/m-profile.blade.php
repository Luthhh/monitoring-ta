@extends('layouts.mahasiswa')

@section('title', 'Profil Mahasiswa')

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


<div class="container-fluid">

    <h4 class="page-title">Biodata Mahasiswa</h4>

    <div class="card-profile">

        <div class="profile-grid">

            <div class="label">NIM</div>
            <div class="value">J0403221149</div>

            <div class="label">Nama</div>
            <div class="value">DINI NURUL AZIZAH</div>

            <div class="label">Tahun Masuk</div>
            <div class="value">2022/2023</div>

            <div class="label">Judul Penelitian</div>
            <div class="value">Penelitian sapi vegan go vegan!!!</div>

            <div class="label">Dosen Pembimbing</div>
            <div class="value">Ibu Popi</div>

            <div class="label">Status Progres</div>
            <div class="value">Sidang</div>

        </div>

    </div>

    <div class="button-group">
        <a href="#" class="btn btn-success">
            Edit
        </a>
        <a href="#" class="btn btn-danger">
            Logout
        </a>
    </div>

</div>

@endsection
