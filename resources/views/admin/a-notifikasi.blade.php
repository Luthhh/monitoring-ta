@extends('layouts.admin')

@section('title', 'Notifikasi Admin')

@section('page-content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">🔔 Semua Notifikasi</h4>
    </div>

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
<style>
.page-title { font-weight: 700; font-size: 1.4rem; color: #1e1b4b; }
.date-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #6b7280; margin-top: 28px; margin-bottom: 10px; padding-left: 4px; }
.notif-item { background:#f8fafc; padding:16px 20px; border-radius:12px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:flex-start; border:1px solid #e2e8f0; transition:box-shadow 0.2s; }
.notif-item:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.06); }
.notif-item.unread { background:#eff6ff; border-color:#bfdbfe; }
.notif-left { display:flex; gap:14px; align-items:flex-start; }
.notif-icon { width:38px; height:38px; background:#4f46e5; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-size:16px; flex-shrink:0; }
.notif-item.unread .notif-icon { background:#2563eb; }
.notif-title { font-weight:700; font-size:0.9rem; color:#1e293b; margin-bottom:3px; }
.notif-text { font-size:0.875rem; color:#475569; line-height:1.5; }
.notif-time { font-size:0.78rem; color:#94a3b8; flex-shrink:0; padding-top:2px; }
.empty-state { text-align:center; padding:80px 20px; }
@media(max-width:768px){ .notif-item { flex-direction:column; gap:10px; } }
</style>
@endpush
