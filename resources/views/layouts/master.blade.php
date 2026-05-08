<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Monitoring TA')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            overflow: hidden; /* Mencegah seluruh halaman scroll */
            height: 100vh;
        }
        html {
            overflow: hidden;
        }
        a {
            text-decoration: none;
        }
        .main {
            flex: 1;
            padding: 25px;
            height: 100vh;
            overflow-y: auto; /* Hanya konten utama yang bisa scroll */
        }

        /* Pagination Polishing */
        .pagination {
            gap: 5px;
        }
        .pagination .page-item .page-link {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            color: #4361ee;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .pagination .page-item.active .page-link {
            background-color: #4361ee;
            border-color: #4361ee;
            color: #fff;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }
        .pagination .page-item .page-link:hover {
            background-color: #f0f3ff;
            color: #3a0ca3;
            transform: translateY(-1px);
        }
        .pagination .page-item.disabled .page-link {
            color: #999;
            background-color: #f8f9fa;
        }

    </style>

    @stack('styles')

    {{-- Welcome Popup Styles --}}
    <style>
        /* ===== WELCOME POPUP OVERLAY ===== */
        #welcomeOverlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 60, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            animation: overlayFadeIn 0.4s ease forwards;
        }

        @keyframes overlayFadeIn {
            to { opacity: 1; }
        }

        #welcomeCard {
            background: #ffffff;
            border-radius: 24px;
            padding: 44px 48px;
            max-width: 460px;
            width: 90%;
            text-align: center;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
            position: relative;
            transform: translateY(40px) scale(0.95);
            opacity: 0;
            animation: cardSlideIn 0.45s cubic-bezier(0.23, 1, 0.32, 1) 0.15s forwards;
        }

        @keyframes cardSlideIn {
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .wc-icon-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
        }

        .wc-icon-ring.admin   { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
        .wc-icon-ring.dosen   { background: linear-gradient(135deg, #2ec4b6, #0e7c7b); }
        .wc-icon-ring.mahasiswa { background: linear-gradient(135deg, #f8961e, #d62828); }

        .wc-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .wc-badge.admin    { background: #eef0ff; color: #4361ee; }
        .wc-badge.dosen    { background: #e0f7f6; color: #0e7c7b; }
        .wc-badge.mahasiswa { background: #fff3e0; color: #d62828; }

        #welcomeCard h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        #welcomeCard p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .wc-btn {
            display: inline-block;
            padding: 12px 36px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .wc-btn.admin    { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
        .wc-btn.dosen    { background: linear-gradient(135deg, #2ec4b6, #0e7c7b); }
        .wc-btn.mahasiswa { background: linear-gradient(135deg, #f8961e, #d62828); }

        .wc-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        }

        .wc-time {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 14px;
        }

        /* Confetti dots (decorative) */
        .wc-dot {
            position: absolute;
            border-radius: 50%;
            opacity: 0.15;
        }
        .wc-dot.d1 { width:90px; height:90px; background:#4361ee; top:-25px; left:-25px; }
        .wc-dot.d2 { width:60px; height:60px; background:#f8961e; bottom:-20px; right:-20px; }
        .wc-dot.d3 { width:40px; height:40px; background:#2ec4b6; bottom:10px; left:10px; }
    </style>
</head>

<body>

    {{-- ===== WELCOME POPUP ===== --}}
    @auth
    @php
        $user     = auth()->user();
        $userName = $user->name ?? 'Pengguna';
        $role     = $user->role->name ?? 'mahasiswa';

        $roleLabels = [
            'admin'     => 'Administrator',
            'dosen'     => 'Dosen Pembimbing',
            'mahasiswa' => 'Mahasiswa',
        ];
        $roleLabel = $roleLabels[$role] ?? ucfirst($role);

        $roleIcons = [
            'admin'     => '🛡️',
            'dosen'     => '🎓',
            'mahasiswa' => '📚',
        ];
        $roleIcon = $roleIcons[$role] ?? '👤';

        $roleMessages = [
            'admin'     => 'Selamat datang di Panel Admin. Pantau dan kelola seluruh aktivitas monitoring tugas akhir mahasiswa dengan mudah.',
            'dosen'     => 'Selamat datang kembali. Pantau progres bimbingan mahasiswa Anda dan kelola pengajuan bimbingan hari ini.',
            'mahasiswa' => 'Selamat datang di portal Anda. Catat progres tugas akhir, ajukan bimbingan, dan pantau milestone Anda.',
        ];
        $roleMessage = $roleMessages[$role] ?? 'Selamat datang di Sistem Monitoring Tugas Akhir.';
    @endphp

    <div id="welcomeOverlay">
        <div id="welcomeCard">
            {{-- Decorative dots --}}
            <div class="wc-dot d1"></div>
            <div class="wc-dot d2"></div>
            <div class="wc-dot d3"></div>

            {{-- Icon --}}
            <div class="wc-icon-ring {{ $role }}">{{ $roleIcon }}</div>

            {{-- Badge role --}}
            <div class="wc-badge {{ $role }}">{{ $roleLabel }}</div>

            {{-- Greeting — diisi JS agar sesuai jam lokal browser --}}
            <h2><span id="wc-greeting">Halo</span>, {{ $userName }}! 👋</h2>

            {{-- Message --}}
            <p>{{ $roleMessage }}</p>

            {{-- Button --}}
            <button class="wc-btn {{ $role }}" onclick="closeWelcome()">
                Mulai Sekarang &nbsp;→
            </button>

            {{-- Date — diisi JS agar sesuai waktu lokal browser --}}
            <div class="wc-time">📅 <span id="wc-tanggal"></span></div>
        </div>
    </div>

    {{-- Greeting & Tanggal: gunakan waktu lokal browser, bukan server --}}
    <script>
    (function () {
        var now  = new Date();
        var hour = now.getHours();

        var greeting =
            hour >= 5  && hour < 12 ? 'Selamat Pagi'  :
            hour >= 12 && hour < 15 ? 'Selamat Siang' :
            hour >= 15 && hour < 18 ? 'Selamat Sore'  :
                                      'Selamat Malam';

        var days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        var months = ['Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];
        var tanggal = days[now.getDay()] + ', ' +
                      now.getDate() + ' ' +
                      months[now.getMonth()] + ' ' +
                      now.getFullYear();

        var elGreeting = document.getElementById('wc-greeting');
        var elTanggal  = document.getElementById('wc-tanggal');
        if (elGreeting) elGreeting.textContent = greeting;
        if (elTanggal)  elTanggal.textContent  = tanggal;
    })();
    </script>
    @endauth

    @yield('content')

    {{-- Welcome Popup Script --}}
    @auth
    <script>
    // ⚠️ Harus di luar IIFE agar bisa dipanggil dari onclick HTML attribute
    var _welcomeKey    = 'welcome_shown_{{ auth()->id() }}';
    var _welcomeOverlay = null;

    function closeWelcome() {
        if (!_welcomeOverlay) _welcomeOverlay = document.getElementById('welcomeOverlay');
        if (window._welcomeTimer) clearTimeout(window._welcomeTimer);
        if (!_welcomeOverlay) return;
        _welcomeOverlay.style.transition = 'opacity 0.35s ease';
        _welcomeOverlay.style.opacity = '0';
        setTimeout(function() {
            if (_welcomeOverlay) _welcomeOverlay.style.display = 'none';
        }, 360);
        sessionStorage.setItem(_welcomeKey, '1');
    }

    document.addEventListener('DOMContentLoaded', function () {
        _welcomeOverlay = document.getElementById('welcomeOverlay');

        if (sessionStorage.getItem(_welcomeKey)) {
            // Sudah pernah ditampilkan di sesi ini → langsung hide
            if (_welcomeOverlay) _welcomeOverlay.style.display = 'none';
        } else {
            // Auto-close setelah 8 detik
            window._welcomeTimer = setTimeout(closeWelcome, 8000);

            // Tutup jika klik latar (luar card)
            if (_welcomeOverlay) {
                _welcomeOverlay.addEventListener('click', function (e) {
                    if (e.target === _welcomeOverlay) closeWelcome();
                });
            }
        }
    });
    </script>
    @endauth

    {{-- ===== FLOATING NOTIFICATION TOASTS ===== --}}
    @auth
    @php
        $notifRole   = auth()->user()->role->name ?? 'mahasiswa';
        $notifMarkUrl = match($notifRole) {
            'admin'  => route('admin.notif.mark-read'),
            'dosen'  => route('dosen.notif.mark-read'),
            default  => route('mahasiswa.notif.mark-read'),
        };
        $notifListUrl = match($notifRole) {
            'admin'  => route('admin.notifikasi'),
            'dosen'  => route('dosen.notifikasi'),
            default  => route('mahasiswa.notifikasi'),
        };
        $notifFetchUrl = match($notifRole) {
            'admin'  => route('admin.notif.unread'),
            'dosen'  => route('dosen.notif.unread'),
            default  => route('mahasiswa.notif.unread'),
        };
    @endphp

    {{-- Toast Container --}}
    <div id="notifToastContainer" style="
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        display: flex;
        flex-direction: column-reverse;
        gap: 12px;
        max-width: 380px;
        width: 100%;
    "></div>

    <style>
        .notif-toast {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.07);
            padding: 16px 18px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            border-left: 4px solid #4361ee;
            animation: toastSlideIn 0.4s cubic-bezier(0.23,1,0.32,1) forwards;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        .notif-toast.dosen-toast   { border-left-color: #2ec4b6; }
        .notif-toast.mahasiswa-toast { border-left-color: #f8961e; }

        .notif-toast::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 3px;
            background: currentColor;
            width: 100%;
            opacity: 0.1;
            animation: toastTimer 8s linear forwards;
        }
        @keyframes toastTimer { to { width: 0; } }

        @keyframes toastSlideIn {
            from { transform: translateX(120%); opacity:0; }
            to   { transform: translateX(0);   opacity:1; }
        }
        @keyframes toastSlideOut {
            from { transform: translateX(0);   opacity:1; }
            to   { transform: translateX(120%); opacity:0; height:0; padding:0; margin:0; }
        }
        .notif-toast-icon {
            font-size: 24px;
            flex-shrink: 0;
            line-height: 1;
        }
        .notif-toast-body {
            flex: 1;
            min-width: 0;
        }
        .notif-toast-title {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .notif-toast-msg {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .notif-toast-time {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 6px;
        }
        .notif-toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 16px;
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
            line-height: 1;
            transition: color 0.2s;
        }
        .notif-toast-close:hover { color: #374151; }
        .notif-toast-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
        .notif-toast-btn {
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }
        .notif-toast-btn.primary {
            background: #4361ee;
            color: #fff;
        }
        .notif-toast-btn.primary:hover { background: #3a0ca3; }
        .notif-toast-btn.secondary {
            background: #f3f4f6;
            color: #6b7280;
        }
        .notif-toast-btn.secondary:hover { background: #e5e7eb; }

        /* Unread dot on sidebar bell icon */
        .notif-bell-dot {
            position:absolute; top:-2px; right:-2px;
            width:8px; height:8px;
            background:#e74c3c;
            border-radius:50%;
            border:2px solid #fff;
        }
    </style>

    <script>
    (function() {
        const MARK_URL  = '{{ $notifMarkUrl }}';
        const LIST_URL  = '{{ $notifListUrl }}';
        const FETCH_URL = '{{ $notifFetchUrl }}';
        const CSRF      = '{{ csrf_token() }}';
        const ROLE      = '{{ $notifRole }}';

        const container = document.getElementById('notifToastContainer');
        const shownIds  = new Set(JSON.parse(sessionStorage.getItem('shown_notif_ids') || '[]'));

        function timeAgo(dateStr) {
            const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
            if (diff < 60)    return 'Baru saja';
            if (diff < 3600)  return Math.floor(diff/60) + ' menit yang lalu';
            if (diff < 86400) return Math.floor(diff/3600) + ' jam yang lalu';
            return Math.floor(diff/86400) + ' hari yang lalu';
        }

        function dismissToast(el, id) {
            el.style.animation = 'toastSlideOut 0.35s ease forwards';
            setTimeout(() => el.remove(), 360);
            if (id) {
                shownIds.add(id);
                sessionStorage.setItem('shown_notif_ids', JSON.stringify([...shownIds]));
            }
        }

        function markRead(ids) {
            fetch(MARK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({ ids }),
            });
        }

        function showToast(notif) {
            const el = document.createElement('div');
            el.className = 'notif-toast ' + ROLE + '-toast';
            el.dataset.id = notif.id;

            const icon = ROLE === 'admin' ? '🛡️' : ROLE === 'dosen' ? '🔔' : '📬';

            el.innerHTML = `
                <div class="notif-toast-icon">${icon}</div>
                <div class="notif-toast-body">
                    <div class="notif-toast-title">${notif.title || 'Notifikasi'}</div>
                    <div class="notif-toast-msg">${notif.message || ''}</div>
                    <div class="notif-toast-time">${timeAgo(notif.created_at)}</div>
                    <div class="notif-toast-actions">
                        <a href="${LIST_URL}" class="notif-toast-btn primary">Lihat Semua</a>
                        <button class="notif-toast-btn secondary" onclick="dismissNotif(this, ${notif.id})">Tutup</button>
                    </div>
                </div>
                <button class="notif-toast-close" onclick="dismissNotif(this, ${notif.id})">✕</button>
            `;

            container.prepend(el);

            // auto-dismiss setelah 8 detik
            setTimeout(() => {
                if (el.parentNode) dismissToast(el, notif.id);
            }, 8000);
        }

        window.dismissNotif = function(btn, id) {
            const toast = btn.closest('.notif-toast');
            dismissToast(toast, id);
            markRead([id]);
        };

        function fetchAndShowNotifs() {
            if (!FETCH_URL) return;
            fetch(FETCH_URL, {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(notifs => {
                if (!Array.isArray(notifs)) return;
                const newOnes = notifs.filter(n => !shownIds.has(n.id));
                // show max 3 at once
                newOnes.slice(0, 3).forEach((n, i) => {
                    setTimeout(() => showToast(n), i * 600);
                });
                newOnes.forEach(n => shownIds.add(n.id));
                sessionStorage.setItem('shown_notif_ids', JSON.stringify([...shownIds]));
            })
            .catch(() => {});
        }

        // First fetch on page load (with slight delay)
        setTimeout(fetchAndShowNotifs, 1500);

        // Poll every 30 seconds
        setInterval(fetchAndShowNotifs, 30000);

    })();
    </script>
    @endauth

    @stack('modals')
    @stack('scripts')
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmForms = document.querySelectorAll('.form-confirm');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const text = this.getAttribute('data-text') || 'Apakah Anda yakin ingin melanjutkan?';
                Swal.fire({
                    title: 'Konfirmasi',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#e74a3b',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
    </script>
</body>
</html>
