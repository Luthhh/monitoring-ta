@extends('layouts.dosen')

@section('title', 'Notifikasi Dosen')

@section('page-content')

<div class="container-fluid">

    <h4 class="page-title">Semua Notifikasi</h4>

    @if($notifications->isEmpty())
        <div class="empty-state">
            <i class="bi bi-bell-slash fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">Belum ada notifikasi.</p>
        </div>
    @else
        @foreach($notifications as $date => $items)
            <div class="date-title">{{ $date }}</div>

            @foreach($items as $notif)
                <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                    <div class="notif-left">
                        <div class="notif-icon">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div>
                            @if($notif->title)
                                <div class="notif-title">{{ $notif->title }}</div>
                            @endif
                            <div class="notif-text">{{ $notif->message }}</div>
                        </div>
                    </div>
                    <div class="notif-time">
                        {{ \Carbon\Carbon::parse($notif->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }}
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif

</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dosen/d-notifikasi.css') }}">
@endpush
