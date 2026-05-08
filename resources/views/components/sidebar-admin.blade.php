<div class="sidebar">

    <div class="sidebar-header" onclick="window.location.reload();" title="Refresh Data">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo">
        <span class="sidebar-title">Admin Panel</span>
    </div>

    <a href="{{ url('/admin/dashboard') }}" class="sidebar-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span class="sidebar-text">Dashboard</span>
    </a>

    <a href="{{ url('/admin/manajemen-mahasiswa') }}" class="sidebar-item {{ request()->is('admin/manajemen-mahasiswa*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i>
        <span class="sidebar-text">Manajemen Mhs</span>
    </a>

    <a href="{{ url('/admin/manajemen-dosen') }}" class="sidebar-item {{ request()->is('admin/manajemen-dosen*') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i>
        <span class="sidebar-text">Manajemen Dosen</span>
    </a>

    <a href="{{ route('admin.notifikasi') }}" class="sidebar-item {{ request()->is('admin/notifikasi*') ? 'active' : '' }}">
        @php $adminUnread = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
        <span style="position:relative; display:inline-flex; align-items:center; min-width:40px; justify-content:center;">
            <i class="fas fa-bell"></i>
            @if($adminUnread > 0)<span class="notif-bell-dot"></span>@endif
        </span>
        <span class="sidebar-text">
            Notifikasi
            @if($adminUnread > 0)
                <span class="badge bg-danger rounded-pill ms-1" style="font-size:10px;">{{ $adminUnread }}</span>
            @endif
        </span>
    </a>

    <a href="{{ url('/admin/profile') }}" class="sidebar-item {{ request()->is('admin/profile*') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span class="sidebar-text">Profile</span>
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
