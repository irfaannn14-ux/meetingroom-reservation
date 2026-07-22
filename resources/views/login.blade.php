<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengajuan Ruangan</title>
    <meta name="description" content="Login ke Sistem Pengajuan Peminjaman Ruangan Pemerintah Daerah Kab. Probolinggo">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --primary: #1D4ED8;
            --primary-dark: #1E3A8A;
            --primary-light: #3B82F6;
            --accent: #06B6D4;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #050d1a;
            overflow: hidden;
        }

        /* ══ LEFT PANEL ══ */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 80% at 20% 30%, rgba(29,78,216,0.45) 0%, transparent 60%),
                radial-gradient(ellipse 60% 60% at 80% 70%, rgba(6,182,212,0.3) 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 50% 10%, rgba(139,92,246,0.25) 0%, transparent 50%);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }

        @keyframes bgPulse {
            0%   { opacity: 0.8; }
            100% { opacity: 1; }
        }

        .left-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .left-content {
            position: relative;
            z-index: 2;
            max-width: 480px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 60px;
        }

        .brand-logo img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 0 20px rgba(59,130,246,0.5);
        }

        .brand-logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            line-height: 1.2;
        }

        .brand-logo-text small {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
        }

        .left-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .left-headline span {
            background: linear-gradient(90deg, #60A5FA, #06B6D4, #A78BFA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .left-description {
            font-size: 1rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.7;
            margin-bottom: 48px;
        }

        .feature-list { display: flex; flex-direction: column; gap: 16px; }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .fi-blue   { background: rgba(59,130,246,0.2);  color: #60A5FA; }
        .fi-cyan   { background: rgba(6,182,212,0.2);   color: #22D3EE; }
        .fi-purple { background: rgba(139,92,246,0.2);  color: #A78BFA; }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: orbFloat 12s ease-in-out infinite;
        }
        .orb-1 { width: 350px; height: 350px; background: rgba(59,130,246,0.25); top: -10%; right: -5%; animation-delay: 0s; }
        .orb-2 { width: 250px; height: 250px; background: rgba(6,182,212,0.2);   bottom: 5%; left: 5%;   animation-delay: -4s; }
        .orb-3 { width: 200px; height: 200px; background: rgba(139,92,246,0.2);  top: 50%; right: 10%; animation-delay: -8s; }

        @keyframes orbFloat {
            0%,100% { transform: translate(0,0); }
            33%      { transform: translate(20px,-30px); }
            66%      { transform: translate(-15px,20px); }
        }

        /* Divider */
        .panel-divider {
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.1) 20%, rgba(255,255,255,0.1) 80%, transparent);
            flex-shrink: 0;
        }

        /* ══ RIGHT PANEL ══ */
        .right-panel {
            width: 480px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: rgba(255,255,255,0.025);
            backdrop-filter: blur(30px);
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-header { margin-bottom: 36px; }

        .login-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .login-header p {
            color: rgba(255,255,255,0.45);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Error */
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: #FCA5A5;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .alert-error i { color: #F87171; flex-shrink: 0; margin-top: 2px; }

        /* Form */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            margin-bottom: 8px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.28);
            font-size: 0.88rem;
            pointer-events: none;
            z-index: 2;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 12px;
            padding: 14px 16px 14px 44px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: white;
            transition: all 0.25s ease;
            outline: none;
            -webkit-appearance: none;
        }

        .form-input::placeholder { color: rgba(255,255,255,0.22); }

        .form-input:focus {
            border-color: rgba(59,130,246,0.55);
            background: rgba(59,130,246,0.08);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        .input-wrapper:focus-within .input-icon { color: #60A5FA; }

        /* Password field has extra right padding for the toggle button */
        .form-input.has-toggle { padding-right: 48px; }

        /* Password toggle button */
        .pwd-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.3);
            font-size: 0.88rem;
            padding: 6px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .pwd-toggle:hover {
            color: rgba(255,255,255,0.75);
            background: rgba(255,255,255,0.07);
        }

        .pwd-toggle.visible { color: #60A5FA; }

        /* Forgot */
        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.38);
            text-decoration: none;
            margin-top: -12px;
            margin-bottom: 28px;
            transition: color 0.2s;
        }

        .forgot-link:hover { color: #60A5FA; }

        /* Submit */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1D4ED8 0%, #3B82F6 55%, #06B6D4 100%);
            background-size: 200% 200%;
            border: none;
            border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(29,78,216,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            animation: gradShift 6s ease infinite;
        }

        @keyframes gradShift {
            0%   { background-position: 0% 50%;   }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%;   }
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(29,78,216,0.55);
        }

        .btn-login:hover::after { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-content, .btn-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login.loading .btn-content { display: none; }
        .btn-login:not(.loading) .btn-loading { display: none; }

        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Footer */
        .login-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.06);
            text-align: center;
            color: rgba(255,255,255,0.25);
            font-size: 0.78rem;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .left-panel, .panel-divider { display: none; }
            .right-panel {
                width: 100%;
                background: transparent;
            }
            body {
                background:
                    radial-gradient(ellipse 100% 80% at 30% 20%, rgba(29,78,216,0.4) 0%, transparent 60%),
                    radial-gradient(ellipse 80% 60% at 80% 80%, rgba(6,182,212,0.25) 0%, transparent 55%),
                    #050d1a;
            }
        }

        @media (max-width: 480px) {
            .right-panel { padding: 24px; }
        }
    </style>
</head>
<body>

    <!-- ═══ LEFT PANEL ═══ -->
    <div class="left-panel">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="left-content">
            <div class="brand-logo">
                <img src="{{ asset('images/logoipsum.png') }}" alt="Logo">
                <div class="brand-logo-text">
                    SIAPRUANG
                    <small>Pemerintah Kab. Probolinggo</small>
                </div>
            </div>

            <h2 class="left-headline">
                Kelola Ruangan<br>
                dengan <span>Lebih Mudah</span>
            </h2>

            <p class="left-description">
                Sistem pengajuan peminjaman ruangan rapat yang terintegrasi dan efisien untuk seluruh perangkat daerah.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon fi-blue"><i class="fas fa-calendar-check"></i></div>
                    <span>Pengajuan &amp; persetujuan ruangan secara digital</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon fi-cyan"><i class="fas fa-chart-bar"></i></div>
                    <span>Dashboard statistik penggunaan ruangan real-time</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon fi-purple"><i class="fas fa-shield-alt"></i></div>
                    <span>Manajemen akses berbasis peran (RBAC)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="panel-divider"></div>

    <!-- ═══ RIGHT PANEL (FORM) ═══ -->
    <div class="right-panel">
        <div class="login-box">

            <div class="login-header">
                <h1>Masuk ke Akun</h1>
                <p>Gunakan email dan password Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-wrapper">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="nama@example.com"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        >
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input has-toggle"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button
                            type="button"
                            class="pwd-toggle"
                            id="pwdToggleBtn"
                            aria-label="Tampilkan atau sembunyikan password"
                            title="Tampilkan / sembunyikan password"
                        >
                            <i class="fas fa-eye" id="pwdToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <a href="#" class="forgot-link">Lupa password?</a>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-content">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk Sekarang
                    </span>
                    <span class="btn-loading">
                        <span class="spinner"></span>
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="login-footer">
                &copy; {{ date('Y') }} Diskominfo Kab. Probolinggo<br>
                Sistem Informasi Pengajuan Peminjaman Ruangan
            </div>
        </div>
    </div>

    <script>
        // Toggle show / hide password
        const pwdInput  = document.getElementById('password');
        const pwdBtn    = document.getElementById('pwdToggleBtn');
        const pwdIcon   = document.getElementById('pwdToggleIcon');

        pwdBtn.addEventListener('click', function () {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type  = isHidden ? 'text' : 'password';

            if (isHidden) {
                pwdIcon.classList.replace('fa-eye', 'fa-eye-slash');
                pwdBtn.classList.add('visible');
                pwdBtn.setAttribute('title', 'Sembunyikan password');
            } else {
                pwdIcon.classList.replace('fa-eye-slash', 'fa-eye');
                pwdBtn.classList.remove('visible');
                pwdBtn.setAttribute('title', 'Tampilkan password');
            }
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
</body>
</html>