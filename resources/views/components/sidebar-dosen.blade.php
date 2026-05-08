<div class="sidebar">

    <div class="sidebar-header" onclick="window.location.reload();" title="Refresh Data" style="cursor: pointer;">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo">
        <span class="sidebar-title">Dosen Panel</span>
    </div>

    <a href="{{ url('/dosen/dashboard') }}" class="sidebar-item {{ request()->is('dosen/dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span class="sidebar-text">Dashboard</span>
    </a>

    <a href="{{ url('/dosen/total-mahasiswa') }}" class="sidebar-item {{ request()->is('dosen/total-mahasiswa*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span class="sidebar-text">Mahasiswa</span>
    </a>

    <a href="{{ route('dosen.notifikasi') }}" class="sidebar-item {{ request()->is('dosen/notifikasi*') ? 'active' : '' }}">
        @php $dosenUnread = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
        <span style="position:relative; display:inline-flex; align-items:center; min-width:40px; justify-content:center;">
            <i class="fas fa-bell"></i>
            @if($dosenUnread > 0)<span class="notif-bell-dot"></span>@endif
        </span>
        <span class="sidebar-text">
            Notifikasi
            @if($dosenUnread > 0)
                <span class="badge bg-danger rounded-pill ms-1" style="font-size:10px;">{{ $dosenUnread }}</span>
            @endif
        </span>
    </a>

    <a href="{{ url('/dosen/profile') }}" class="sidebar-item {{ request()->is('dosen/profile*') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span class="sidebar-text">Profil</span>
    </a>

</div>

<style>
.sidebar {
    width: 70px;
    background: #ffffff;
    min-height: 100vh;
    height: 100vh;
    padding-top: 20px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    transition: width 0.3s ease;
    overflow-x: hidden;
    white-space: nowrap;
    z-index: 999;
}

.sidebar:hover {
    width: 250px;
}

.sidebar-header {
    display: flex;
    align-items: center;
    padding: 0;
    margin: 5px 15px 30px 15px;
    color: #4e73df;
    font-weight: bold;
    font-size: 18px;
}

.sidebar-header img.sidebar-logo {
    width: 32px;
    height: 32px;
    margin-left: 4px;
}

.sidebar-title {
    opacity: 0;
    transition: opacity 0.3s;
    margin-left: 5px;
}

.sidebar:hover .sidebar-title {
    opacity: 1;
}

.sidebar-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    margin: 5px 15px;
    border-radius: 12px;
    text-decoration: none;
    color: #6c757d;
    transition: 0.2s;
}

.sidebar-item i {
    min-width: 40px;
    text-align: center;
    font-size: 18px;
}

.sidebar-text {
    opacity: 0;
    transition: opacity 0.3s;
    font-weight: 500;
    margin-left: 5px;
}

.sidebar:hover .sidebar-text {
    opacity: 1;
}

.sidebar-item:hover {
    background: #eef2ff;
    color: #4e73df;
}

.sidebar-item.active {
    background: #4e73df;
    color: white;
}

.sidebar-item.active i, .sidebar-item.active .sidebar-text {
    color: white;
}
</style>

