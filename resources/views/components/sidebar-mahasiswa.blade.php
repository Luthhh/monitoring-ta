<div class="sidebar">

    <a href="/mahasiswa/dashboard">
        <i class="fas fa-home {{ request()->is('mahasiswa/dashboard') ? 'active' : '' }}"></i>
    </a>

    <a href="/mahasiswa/notifikasi">
        <i class="fas fa-bell {{ request()->is('mahasiswa/notifikasi') ? 'active' : '' }}"></i>
    </a>

    <a href="/mahasiswa/profile">
        <i class="fas fa-user {{ request()->is('mahasiswa/profile') ? 'active' : '' }}"></i>
    </a>

</div>



<style>
.sidebar {
    width: 70px;
    background: #ffffff;
    min-height: 100vh;   /* penting */
    height: 100vh;
    padding-top: 20px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    position: sticky; /* penting */
    top: 0; /* penting */
}

.sidebar a {
    text-decoration: none;
}

.sidebar i {
    font-size: 18px;
    margin: 20px 0;
    color: #6c757d;
    cursor: pointer;
    transition: 0.2s;
}

.sidebar i:hover {
    background: #eef2ff;
    color: #4e73df;
    padding: 10px;
    border-radius: 12px;
}

.sidebar i.active {
    background: #4e73df;
    color: white;
    padding: 10px;
    border-radius: 12px;
}
</style>
