<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Hotel Booking System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0a1628;
        }

        /* LEFT PANEL */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0a1628 0%, #1a3a5c 50%, #2e86de 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            top: -100px; right: -100px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -80px; left: -80px;
        }
        .left-content { position: relative; z-index: 1; text-align: center; color: #fff; max-width: 420px; }
        .hotel-icon {
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.12);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .left-content h1 {
            font-size: 32px; font-weight: 800;
            margin-bottom: 12px; line-height: 1.2;
        }
        .left-content p {
            font-size: 14px; color: #b8d4e8;
            line-height: 1.7; margin-bottom: 40px;
        }
        .features { display: flex; flex-direction: column; gap: 16px; text-align: left; }
        .feature-item {
            display: flex; align-items: center; gap: 14px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 14px 18px;
        }
        .feature-icon {
            width: 40px; height: 40px;
            background: rgba(46,134,222,0.3);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .feature-text h4 { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 2px; }
        .feature-text p  { font-size: 11px; color: #7fb3d3; }

        /* RIGHT PANEL */
        .right-panel {
            width: 480px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 48px;
        }
        .right-header { margin-bottom: 36px; }
        .right-header .logo {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 32px;
        }
        .right-header .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #2e86de, #1a6bbf);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .right-header .logo-text {
            font-size: 14px; font-weight: 700; color: #0a1628;
        }
        .right-header h2 {
            font-size: 26px; font-weight: 800;
            color: #0a1628; margin-bottom: 6px;
        }
        .right-header p { font-size: 13px; color: #888; }

        /* FORM */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 12px;
            font-weight: 700; color: #334155;
            margin-bottom: 7px; letter-spacing: 0.3px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            font-size: 16px; color: #aaa;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13.5px;
            color: #334155;
            outline: none;
            transition: all 0.2s;
            background: #fafbfc;
        }
        .form-input:focus {
            border-color: #2e86de;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(46,134,222,0.1);
        }
        .form-error { font-size: 12px; color: #e74c3c; margin-top: 5px; }

        /* Remember & Forgot */
        .form-extras {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember {
            display: flex; align-items: center; gap: 7px;
            font-size: 12.5px; color: #555; cursor: pointer;
        }
        .remember input { accent-color: #2e86de; width: 14px; height: 14px; }
        .forgot { font-size: 12.5px; color: #2e86de; text-decoration: none; font-weight: 600; }
        .forgot:hover { text-decoration: underline; }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2e86de, #1a6bbf);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(46,134,222,0.35);
        }
        .btn-login:active { transform: translateY(0); }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0; color: #ccc; font-size: 12px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: #e8edf2;
        }

        /* Register link */
        .register-link {
            text-align: center; font-size: 13px; color: #888;
        }
        .register-link a {
            color: #2e86de; font-weight: 700; text-decoration: none;
        }
        .register-link a:hover { text-decoration: underline; }

        /* Alert */
        .alert-error {
            background: #fff5f5; color: #821c1c;
            border: 1px solid #f5c6cb;
            border-left: 4px solid #e74c3c;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 12.5px;
            margin-bottom: 20px;
        }

        /* Bottom badge */
        .bottom-badge {
            margin-top: 32px;
            display: flex; align-items: center; gap: 8px;
            justify-content: center;
        }
        .role-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px; font-weight: 700;
            border: 1px solid;
        }
        .pill-admin    { background:#eef4fb; color:#1a5fa0; border-color:#b8d4e8; }
        .pill-ceo      { background:#fef9e7; color:#7d5a0a; border-color:#f0d080; }
        .pill-customer { background:#e8f8f1; color:#1a6b3a; border-color:#b7e4c7; }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 28px; }
        }
    </style>
</head>
<body>

    {{-- LEFT PANEL --}}
    <div class="left-panel">
        <div class="left-content">
            <div class="hotel-icon">🏨</div>
            <h1>Hotel Booking System</h1>
            <p>Platform manajemen hotel terpadu untuk memudahkan proses reservasi, check-in, check-out, dan pelaporan keuangan secara efisien.</p>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">📋</div>
                    <div class="feature-text">
                        <h4>Manajemen Booking</h4>
                        <p>Kelola reservasi kamar dengan mudah dan cepat</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💳</div>
                    <div class="feature-text">
                        <h4>Sistem Pembayaran</h4>
                        <p>Catat pembayaran DP maupun lunas secara otomatis</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">
                        <h4>Laporan & Dashboard</h4>
                        <p>Pantau pendapatan dan statistik hotel real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="right-panel">
        <div class="right-header">
            <div class="logo">
                <div class="logo-icon">🏨</div>
                <div class="logo-text">Hotel Booking System</div>
            </div>
            <h2>Selamat Datang!</h2>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- Error --}}
        @if($errors->any())
            <div class="alert-error">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                    <span class="input-icon">✉️</span>
                    <input type="email" name="email" class="form-input"
                           placeholder="email@contoh.com"
                           value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" id="passwordInput"
                           class="form-input" placeholder="Masukkan password" required>
                    <span onclick="togglePassword()"
                          style="position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:15px; color:#aaa" id="eyeIcon">
                        👁️
                    </span>
                </div>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-extras">
                <label class="remember">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                🚀 Masuk ke Sistem
            </button>
        </form>

        <div class="divider">atau</div>

        <div class="register-link">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>

        <div class="bottom-badge">
            <span style="font-size:11px; color:#ccc">Role tersedia:</span>
            <span class="role-pill pill-admin">Admin</span>
            <span class="role-pill pill-ceo">CEO</span>
            <span class="role-pill pill-customer">Customer</span>
        </div>
    </div>

    <script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = '🙈';
        } else {
            input.type = 'password';
            icon.textContent = '👁️';
        }
    }
    </script>
</body>
</html>