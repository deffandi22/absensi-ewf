<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Sistem Absensi EWF</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --ewf-orange: #f97316;
            --ewf-orange-dark: #ea580c;
            --ewf-dark: #111827;
            --ewf-gray: #6b7280;
            --ewf-soft: #f8fafc;
            --ewf-border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.14), transparent 32%),
                linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
            color: var(--ewf-dark);
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
        }

        .login-card {
            width: 100%;
            max-width: 1080px;
            min-height: 620px;
            background: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid rgba(229, 231, 235, 0.9);
            box-shadow: 0 28px 80px rgba(15, 23, 42, 0.13);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
        }

        .brand-panel {
            position: relative;
            padding: 48px;
            background:
                linear-gradient(145deg, rgba(17, 24, 39, 0.96), rgba(31, 41, 55, 0.94)),
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.42), transparent 35%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.24);
            right: -80px;
            top: -70px;
            filter: blur(2px);
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            border: 34px solid rgba(255, 255, 255, 0.06);
            left: -55px;
            bottom: -55px;
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo-box {
            width: 76px;
            height: 76px;
            border-radius: 24px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.18);
            margin-bottom: 26px;
            overflow: hidden;
        }

        .brand-logo-box img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .brand-logo-box i {
            font-size: 34px;
            color: var(--ewf-orange);
        }

        .brand-title {
            font-size: 34px;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -0.7px;
            margin-bottom: 16px;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.78);
            font-size: 15.5px;
            line-height: 1.75;
            max-width: 470px;
            margin-bottom: 0;
        }

        .feature-list {
            position: relative;
            z-index: 2;
            display: grid;
            gap: 14px;
            margin-top: 38px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.88);
            font-size: 14px;
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fed7aa;
            flex-shrink: 0;
        }

        .brand-footer {
            position: relative;
            z-index: 2;
            color: rgba(255, 255, 255, 0.55);
            font-size: 13px;
            margin-top: 40px;
        }

        .form-panel {
            padding: 52px 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(180deg, #ffffff 0%, #ffffff 72%, #fff7ed 100%);
        }

        .form-inner {
            width: 100%;
            max-width: 430px;
        }

        .mobile-logo {
            display: none;
            width: 70px;
            height: 70px;
            border-radius: 22px;
            background: #ffffff;
            border: 1px solid var(--ewf-border);
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09);
            overflow: hidden;
        }

        .mobile-logo img {
            width: 54px;
            height: 54px;
            object-fit: contain;
        }

        .mobile-logo i {
            font-size: 32px;
            color: var(--ewf-orange);
        }

        .login-heading {
            font-size: 29px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            color: var(--ewf-dark);
        }

        .login-description {
            color: var(--ewf-gray);
            font-size: 15px;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .form-label {
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 17px;
            z-index: 3;
        }

        .form-control {
            height: 52px;
            border-radius: 17px;
            border: 1px solid var(--ewf-border);
            padding-left: 48px;
            font-size: 15px;
            color: var(--ewf-dark);
            background-color: #ffffff;
            transition: 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--ewf-orange);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
        }

        .btn-login {
            width: 100%;
            height: 52px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--ewf-orange), var(--ewf-orange-dark));
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 16px 30px rgba(249, 115, 22, 0.28);
            transition: 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            color: #ffffff;
            box-shadow: 0 18px 34px rgba(249, 115, 22, 0.36);
        }

        .forgot-link {
            color: var(--ewf-orange-dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .alert {
            font-size: 14px;
            border-radius: 17px;
        }

        .security-note {
            margin-top: 24px;
            padding: 16px;
            border-radius: 18px;
            background: #f9fafb;
            border: 1px solid var(--ewf-border);
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
        }

        .security-note i {
            color: var(--ewf-orange);
        }

        .back-login-wrapper {
            margin-top: 22px;
            text-align: center;
        }

        @media (max-width: 992px) {
            .login-card {
                grid-template-columns: 1fr;
                max-width: 540px;
                min-height: auto;
            }

            .brand-panel {
                display: none;
            }

            .form-panel {
                padding: 42px 28px;
            }

            .mobile-logo {
                display: flex;
            }

            .login-heading,
            .login-description {
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .login-wrapper {
                padding: 18px 12px;
            }

            .login-card {
                border-radius: 24px;
            }

            .form-panel {
                padding: 34px 20px;
            }

            .login-heading {
                font-size: 25px;
            }

            .login-description {
                font-size: 14px;
                margin-bottom: 24px;
            }

            .form-control,
            .btn-login {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    @php
        $logoExists = file_exists(public_path('images/logo-polos.png'));
    @endphp

    <main class="login-wrapper">
        <div class="login-card">
            <section class="brand-panel">
                <div class="brand-content">
                    <div class="brand-logo-box">
                        @if ($logoExists)
                            <img src="{{ asset('images/logo-polos.png') }}" alt="Logo EWF">
                        @else
                            <i class="bi bi-building-fill-check"></i>
                        @endif
                    </div>

                    <h1 class="brand-title">
                        Pemulihan Akses Akun
                    </h1>

                    <p class="brand-subtitle">
                        Halaman ini digunakan untuk membantu pengguna mendapatkan password sementara
                        apabila tidak dapat masuk ke sistem absensi PT. Equity World Futures Surabaya.
                    </p>

                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-envelope-check-fill"></i>
                            </div>
                            <span>Password sementara dikirim ke email terdaftar</span>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <span>Gunakan email resmi yang terdaftar pada sistem</span>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-key-fill"></i>
                            </div>
                            <span>Segera ganti password setelah berhasil login</span>
                        </div>
                    </div>
                </div>

                <div class="brand-footer">
                    © {{ date('Y') }} PT. Equity World Futures Surabaya
                </div>
            </section>

            <section class="form-panel">
                <div class="form-inner">
                    <div class="mobile-logo">
                        @if ($logoExists)
                            <img src="{{ asset('images/logo-polos.png') }}" alt="Logo EWF">
                        @else
                            <i class="bi bi-building-fill-check"></i>
                        @endif
                    </div>

                    <h2 class="login-heading">Lupa Password</h2>
                    <p class="login-description">
                        Masukkan email yang terdaftar. Sistem akan mengirimkan password sementara ke email tersebut.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning border-0 shadow-sm mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            <i class="bi bi-x-circle-fill me-1"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            <i class="bi bi-x-circle-fill me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Terdaftar</label>
                            <div class="input-group-custom">
                                <i class="bi bi-envelope-fill input-icon"></i>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="Masukkan email"
                                       autocomplete="email"
                                       required
                                       autofocus>
                            </div>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login">
                            <i class="bi bi-send-fill me-1"></i>
                            Kirim Password Sementara
                        </button>
                    </form>

                    <div class="security-note">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Pastikan email yang dimasukkan sesuai dengan akun yang terdaftar pada sistem.
                    </div>

                    <div class="back-login-wrapper">
                        <a href="{{ route('login') }}" class="forgot-link">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali ke Login
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>