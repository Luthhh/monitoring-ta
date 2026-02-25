@extends('layouts.mahasiswa')

@section('title', 'Notifikasi Mahasiswa')

@section('page-content')

<style>
.page-title {
    margin-bottom: 30px;
    font-weight: 600;
}

.date-title {
    font-size: 18px;
    font-weight: 600;
    margin-top: 25px;
    margin-bottom: 10px;
    color: #444;
}

.notif-item {
    background: #f1f3f7;
    padding: 18px 25px;
    border-radius: 10px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notif-text {
    font-size: 14px;
    color: #333;
}

.notif-time {
    font-size: 13px;
    color: #666;
}

@media(max-width: 768px){
    .notif-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>


<div class="container-fluid">

    <h4 class="page-title">Semua Notifikasi</h4>

    <div class="date-title">4 Januari 2025</div>
    <div class="notif-item">
        <div class="notif-text">Pembimbing telah memverifikasi milestone</div>
        <div class="notif-time">02.00</div>
    </div>

    <div class="date-title">3 Januari 2025</div>
    <div class="notif-item">
        <div class="notif-text">Milestone berhasil diunggah</div>
        <div class="notif-time">04.10</div>
    </div>

    <div class="date-title">2 Januari 2025</div>
    <div class="notif-item">
        <div class="notif-text">Pembimbing menyetujui pengajuan bimbingan</div>
        <div class="notif-time">01.45</div>
    </div>

    <div class="date-title">1 Januari 2025</div>
    <div class="notif-item">
        <div class="notif-text">Bimbingan telah berhasil diajukan</div>
        <div class="notif-time">04.36</div>
    </div>

</div>

@endsection
