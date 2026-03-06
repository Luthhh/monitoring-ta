<div class="sidebar-admin">
    <h2>Admin Panel</h2>
    <ul>
        <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ url('/admin/dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="{{ request()->is('admin/manajemen-mahasiswa*') ? 'active' : '' }}">
            <a href="{{ url('/admin/manajemen-mahasiswa') }}">
                <i class="fas fa-user-graduate"></i>
                <span>Manajemen Mahasiswa</span>
            </a>
        </li>
        <li class="{{ request()->is('admin/manajemen-dosen*') ? 'active' : '' }}">
            <a href="{{ url('/admin/manajemen-dosen') }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Manajemen Dosen</span>
            </a>
        </li>
        <li class="{{ request()->is('admin/profile*') ? 'active' : '' }}">
            <a href="{{ url('/admin/profile') }}">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
</div>


<style>
.sidebar-admin {
    width: 230px;
    min-height: 100vh;
    background: #1e1e2f;
    color: white;
    padding: 20px;
}

.sidebar-admin h2 {
    margin-bottom: 30px;
    font-size: 20px;
}

.sidebar-admin ul {
    list-style: none;
    padding: 0;
}

.sidebar-admin ul li {
    padding: 12px 10px;
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: 0.2s;
}

.sidebar-admin ul li:hover {
    background: #2f2f45;
}

.sidebar-admin ul li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    transition: 0.2s;
}

.sidebar-admin ul li.active a {
    background: #3b4cca;
}
</style>

