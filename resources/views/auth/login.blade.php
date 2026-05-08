<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Student Portal Monitoring TA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

    <div class="left">
        <img src="{{ asset('images/logo.png') }}" alt="IPB University Logo" class="left-logo">
        <p style="margin-top: 20px; font-weight: 300; opacity: 0.8; max-width: 400px; line-height: 1.6; z-index: 1;">
            Sistem Monitoring Tugas Akhir Mahasiswa IPB University. Masuk untuk mengelola progres riset dan bimbingan Anda.
        </p>
    </div>

    <div class="right">
        <div class="login-box">
            <div class="d-flex align-items-center mb-4 d-lg-none">
                <img src="{{ asset('images/logo.png') }}" alt="IPB University Logo" style="width: 50px; margin-right: 15px;">
                <h4 class="mb-0 fw-bold">Monitoring TA</h4>
            </div>
            
            <h2>Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px; font-size: 14px;">
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Enter your password" required>
                        <button class="btn-toggle" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted" for="remember" style="font-size: 13px; font-weight: 400; margin-bottom: 0;">
                            Ingat saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size: 13px; color: var(--primary-color);">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">
                    Masuk ke Akun
                </button>

                <div class="footer-info">
                    &copy; 2026 IPB University • Versi 2026.1.0
                </div>
            </form>
        </div>
    </div>

    <!-- External JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>

</html>