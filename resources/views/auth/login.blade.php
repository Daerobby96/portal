<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $appSettings['appName'] ?? 'SPMI' }}</title>
    @if(isset($appSettings['favicon']) && $appSettings['favicon'])
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $appSettings['favicon']) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-rgb: 79, 70, 229;
            --accent: #06b6d4;
            --accent-rgb: 6, 182, 212;
            --dark-950: #090d16;
            --dark-900: #0f172a;
            --dark-800: #1e293b;
            --glass-bg: rgba(15, 23, 42, 0.45);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-950);
            color: #f8fafc;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Animated Background Lights */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.18;
            animation: floatOrb 25s infinite alternate ease-in-out;
        }

        .orb-1 {
            top: -10%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: rgba(79, 70, 229, 0.6);
            animation-delay: 0s;
        }

        .orb-2 {
            bottom: -15%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: rgba(6, 182, 212, 0.5);
            animation-delay: -5s;
        }

        .orb-3 {
            top: 40%;
            right: 15%;
            width: 400px;
            height: 400px;
            background: rgba(124, 58, 237, 0.45);
            animation-delay: -10s;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(100px, 80px) scale(1.15); }
            100% { transform: translate(-50px, -60px) scale(0.9); }
        }

        /* SVG Grid Backdrop */
        .grid-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 24px 24px;
            z-index: 2;
            pointer-events: none;
        }

        /* Login Layout Container */
        .login-container {
            width: 100%;
            max-width: 1120px;
            min-height: 680px;
            display: flex;
            border-radius: 32px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            z-index: 10;
            overflow: hidden;
            margin: 1.5rem;
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntrance {
            0% { opacity: 0; transform: translateY(30px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Left Panel - Visual Hub */
        .panel-left {
            flex: 1.1;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(9, 13, 22, 0.9) 100%);
            border-right: 1px solid var(--glass-border);
            padding: 4.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 0% 0%, rgba(79, 70, 229, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .left-logo-area {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
        }

        .logo-img {
            max-height: 36px;
            object-fit: contain;
        }

        .left-mid-content h1 {
            font-size: 2.35rem;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 40%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.25rem;
        }

        .left-mid-content p {
            color: #94a3b8;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 420px;
        }

        /* Visual Widgets inside Left Panel */
        .feature-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(79, 70, 229, 0.25);
            transform: translateX(5px);
        }

        .feature-icon-wrapper {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(79, 70, 229, 0.15);
            color: #818cf8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .feature-card.accent-card .feature-icon-wrapper {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
        }

        .feature-card.success-card .feature-icon-wrapper {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .feature-title {
            font-weight: 700;
            color: #ffffff;
            font-size: 0.88rem;
            margin-bottom: 0.15rem;
        }

        .feature-desc {
            color: #64748b;
            font-size: 0.76rem;
            margin-bottom: 0;
        }

        .left-footer {
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Right Panel - Login Form Hub */
        .panel-right {
            flex: 0.9;
            padding: 4.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(15, 23, 42, 0.2);
        }

        .form-header h3 {
            font-weight: 800;
            letter-spacing: -0.01em;
            margin-bottom: 0.35rem;
            color: white;
        }

        .form-header p {
            color: #64748b;
            font-size: 0.88rem;
            margin-bottom: 2.25rem;
        }

        /* Elegant Inputs Custom styling */
        .input-wrapper {
            margin-bottom: 1.5rem;
        }

        .input-wrapper label {
            display: block;
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.82rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .custom-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-input-group i.input-icon {
            position: absolute;
            left: 1rem;
            color: #475569;
            font-size: 1.15rem;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .custom-input-group .form-control {
            background: rgba(15, 23, 42, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.07) !important;
            border-radius: 14px;
            color: #ffffff !important;
            padding: 0.85rem 1rem 0.85rem 3rem;
            font-size: 0.92rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: none !important;
        }

        .custom-input-group .form-control::placeholder {
            color: #475569;
        }

        .custom-input-group .form-control:focus {
            border-color: rgba(79, 70, 229, 0.55) !important;
            background: rgba(15, 23, 42, 0.6) !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15) !important;
        }

        .custom-input-group .form-control:focus + i.input-icon {
            color: #818cf8;
        }

        /* Eye toggle btn styling */
        .eye-toggle-btn {
            position: absolute;
            right: 0.75rem;
            background: transparent;
            border: none;
            color: #475569;
            padding: 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .eye-toggle-btn:hover {
            color: #94a3b8;
        }

        /* Checkbox styling */
        .form-check-input {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.15);
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 6px !important;
            margin-top: 0.15rem;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
            padding-left: 0.25rem;
        }

        /* Premium Glow Button */
        .btn-submit-glow {
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            border: none;
            border-radius: 14px;
            color: white;
            padding: 0.95rem 1.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-submit-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.45);
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
        }

        .btn-submit-glow:active {
            transform: translateY(0);
        }

        /* Alert Styling inside dark glass */
        .alert-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            border-radius: 14px;
            padding: 0.9rem 1.25rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-custom-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #a7f3d0;
            border-radius: 14px;
            padding: 0.9rem 1.25rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-custom button, .alert-custom-success button {
            background: transparent;
            border: none;
            color: inherit;
            margin-left: auto;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .alert-custom button:hover, .alert-custom-success button:hover {
            opacity: 1;
        }

        /* Responsive Layout Overrides */
        @media (max-width: 991.98px) {
            .login-container {
                flex-direction: column;
                min-height: auto;
                max-width: 460px;
                border-radius: 28px;
            }
            .panel-left {
                display: none !important; /* Hide left on mobile */
            }
            .panel-right {
                padding: 3rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .orb {
                filter: blur(80px); /* Lower blur to enhance performance on mobile browsers */
                opacity: 0.12;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem 0;
            }
            .login-container {
                margin: 0.75rem;
                border-radius: 24px;
                max-width: calc(100% - 1.5rem);
            }
            .panel-right {
                padding: 2.25rem 1.25rem;
            }
            .form-header h3 {
                font-size: 1.65rem;
            }
        }

        @media (max-width: 375px) {
            .login-container {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            .panel-right {
                padding: 1.75rem 1rem;
            }
            .form-header h3 {
                font-size: 1.4rem;
            }
            .form-header p {
                margin-bottom: 1.75rem;
            }
            .custom-input-group .form-control {
                padding: 0.75rem 1rem 0.75rem 2.75rem;
                font-size: 0.85rem;
            }
            .custom-input-group i.input-icon {
                left: 0.85rem;
                font-size: 1rem;
            }
            .btn-submit-glow {
                padding: 0.8rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- Background Ambient Orbs -->
<div class="ambient-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<!-- SVG Grid -->
<div class="grid-bg"></div>

<!-- Login Container -->
<div class="login-container">

    <!-- Left Panel (d-none d-lg-flex) -->
    <div class="panel-left d-none d-lg-flex">
        <div class="left-logo-area">
            <div class="logo-box">
                @if(isset($appSettings['logo']) && $appSettings['logo'])
                    <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="Logo" class="logo-img">
                @else
                    <i class="bi bi-shield-lock-fill"></i>
                @endif
            </div>
            <div>
                <span class="fw-800 fs-5 d-block text-white mb-0 lh-1">{{ $appSettings['appName'] ?? 'SPMI' }}</span>
                <span class="text-muted smaller fw-600" style="font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase;">Pusat Penjaminan Mutu</span>
            </div>
        </div>

        <div class="left-mid-content">
            <h1>Meningkatkan Mutu & <br><span class="text-gradient-cyan" style="background: linear-gradient(135deg, #a5b4fc 0%, #22d3ee 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Integritas Akademik</span></h1>
            <p>Sistem manajemen internal terpadu untuk monitoring kepatuhan standar mutu, kendali audit lapangan, dan evaluasi berkelanjutan.</p>

            <!-- Features list widgets -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="bi bi-clipboard2-check-fill"></i>
                </div>
                <div>
                    <h6 class="feature-title">Audit Mutu Internal (AMI)</h6>
                    <p class="feature-desc">Pelaksanaan siklus audit komprehensif berbasis kertas kerja digital.</p>
                </div>
            </div>

            <div class="feature-card accent-card">
                <div class="feature-icon-wrapper">
                    <i class="bi bi-collection-play-fill"></i>
                </div>
                <div>
                    <h6 class="feature-title">Siklus PPEPP Otomatis</h6>
                    <p class="feature-desc">Pelacak kemajuan alur penjaminan mutu secara instan dan visual.</p>
                </div>
            </div>

            <div class="feature-card success-card">
                <div class="feature-icon-wrapper">
                    <i class="bi bi-folder-fill"></i>
                </div>
                <div>
                    <h6 class="feature-title">E-Repositori Terpusat</h6>
                    <p class="feature-desc">Arsip tunggal yang aman untuk seluruh Dokumen Standar, SOP, dan SK.</p>
                </div>
            </div>
        </div>

        <div class="left-footer">
            <span>© {{ date('Y') }} Penjaminan Mutu Internal. Hak Cipta Dilindungi.</span>
        </div>
    </div>

    <!-- Right Panel (Form) -->
    <div class="panel-right">
        <!-- Logo for Mobile view -->
        <div class="d-lg-none text-center mb-4">
            <div class="logo-box mx-auto mb-2.5">
                @if(isset($appSettings['logo']) && $appSettings['logo'])
                    <img src="{{ asset('storage/' . $appSettings['logo']) }}" alt="Logo" class="logo-img">
                @else
                    <i class="bi bi-shield-lock-fill"></i>
                @endif
            </div>
            <h4 class="fw-800 text-white mb-1">{{ $appSettings['appName'] ?? 'SPMI' }}</h4>
            <span class="text-muted small">Sistem Penjaminan Mutu</span>
        </div>

        <div class="form-header">
            <h3>Selamat Datang</h3>
            <p>Masuk ke akun penjaminan mutu internal Anda</p>
        </div>

        <!-- Alert messages -->
        @if ($errors->any())
        <div class="alert-custom" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
            <span>{{ $errors->first() }}</span>
            <button type="button" onclick="this.parentElement.remove();"><i class="bi bi-x-lg"></i></button>
        </div>
        @endif

        @if (session('success'))
        <div class="alert-custom-success" role="alert">
            <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove();"><i class="bi bi-x-lg"></i></button>
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- Email Input -->
            <div class="input-wrapper">
                <label for="email">Alamat Email</label>
                <div class="custom-input-group">
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@institusi.ac.id"
                        autocomplete="email"
                        required
                    >
                    <i class="bi bi-envelope-fill input-icon"></i>
                </div>
            </div>

            <!-- Password Input -->
            <div class="input-wrapper">
                <label for="password">Kata Sandi</label>
                <div class="custom-input-group">
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Masukkan password Anda"
                        autocomplete="current-password"
                        required
                    >
                    <i class="bi bi-lock-fill input-icon"></i>
                    <button class="eye-toggle-btn" type="button" id="togglePassword">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Reset info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check p-0 d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-submit-glow">
                <i class="bi bi-box-arrow-in-right fs-5"></i>
                <span>Masuk ke Dashboard</span>
            </button>
        </form>

        <p class="text-center text-muted small mt-5 mb-0">
            Lupa kata sandi? Hubungi <strong class="text-white-50">Administrator TI</strong>
        </p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const pwdInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            eyeIcon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        } else {
            pwdInput.type = 'password';
            eyeIcon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        }
    });
</script>
</body>
</html>