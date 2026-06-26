<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Karyawan') | Sistem Absensi EWF</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --topbar-height: 76px;
            --mobile-navbar-height: 72px;
            --mobile-bottom-nav-height: 88px;
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-soft: #fff7ed;
            --primary-soft-2: #ffedd5;
            --primary-border: #fed7aa;
            --body-bg: #f6f7f9;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --border: #e5e7eb;
            --border-soft: #f1f5f9;
            --text: #111827;
            --muted: #64748b;
            --success: #16a34a;
            --warning: #ca8a04;
            --danger: #dc2626;
            --blue: #2563eb;
            --purple: #7c3aed;
        }

        * { box-sizing: border-box; }
        html, body { width: 100%; min-height: 100%; margin: 0; overflow-x: hidden; }
        body { background: var(--body-bg); font-family: 'Segoe UI', sans-serif; color: var(--text); }
        img { max-width: 100%; }
        a { text-decoration: none; }

        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .border-primary { border-color: var(--primary) !important; }

        .btn-primary, .btn-ewf, .btn-warning:not(.btn-danger):not(.btn-outline-danger), .btn-kembali-ewf {
            --bs-btn-bg: var(--primary);
            --bs-btn-border-color: var(--primary);
            --bs-btn-color: #ffffff;
            --bs-btn-hover-bg: var(--primary-dark);
            --bs-btn-hover-border-color: var(--primary-dark);
            --bs-btn-hover-color: #ffffff;
            --bs-btn-focus-shadow-rgb: 249, 115, 22;
            --bs-btn-active-bg: var(--primary-dark);
            --bs-btn-active-border-color: var(--primary-dark);
            --bs-btn-active-color: #ffffff;
            --bs-btn-disabled-bg: var(--primary);
            --bs-btn-disabled-border-color: var(--primary);
            --bs-btn-disabled-color: #ffffff;
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .btn-primary.show,
        .btn-ewf:hover, .btn-ewf:focus, .btn-ewf:active, .btn-ewf.active, .btn-ewf.show,
        .btn-warning:not(.btn-danger):not(.btn-outline-danger):hover,
        .btn-warning:not(.btn-danger):not(.btn-outline-danger):focus,
        .btn-warning:not(.btn-danger):not(.btn-outline-danger):active,
        .btn-kembali-ewf:hover, .btn-kembali-ewf:focus, .btn-kembali-ewf:active {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.18) !important;
        }

        .btn-check:checked + .btn-primary, .btn-check:active + .btn-primary,
        .btn-primary:first-child:active, :not(.btn-check) + .btn-primary:active,
        .btn-check:checked + .btn-ewf, .btn-check:active + .btn-ewf,
        .btn-ewf:first-child:active, :not(.btn-check) + .btn-ewf:active,
        .btn-check:checked + .btn-warning, .btn-check:active + .btn-warning,
        .btn-warning:first-child:active, :not(.btn-check) + .btn-warning:active {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            color: #ffffff !important;
        }

        .btn-outline-primary {
            --bs-btn-color: var(--primary);
            --bs-btn-border-color: var(--primary);
            --bs-btn-hover-bg: var(--primary);
            --bs-btn-hover-border-color: var(--primary);
            --bs-btn-hover-color: #ffffff;
            --bs-btn-active-bg: var(--primary-dark);
            --bs-btn-active-border-color: var(--primary-dark);
            --bs-btn-active-color: #ffffff;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.18) !important;
        }
        .btn-danger, .btn-outline-danger {
            --bs-btn-bg: var(--danger); --bs-btn-border-color: var(--danger); --bs-btn-color: #ffffff;
            --bs-btn-hover-bg: #b91c1c; --bs-btn-hover-border-color: #b91c1c; --bs-btn-hover-color: #ffffff;
            --bs-btn-active-bg: #991b1b; --bs-btn-active-border-color: #991b1b; --bs-btn-active-color: #ffffff;
            background: var(--danger) !important; border-color: var(--danger) !important; color: #ffffff !important;
        }

        .form-control, .form-select { border-color: #dbe3ef; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12); }

        .bg-gradient-primary, .bg-primary-gradient, .dashboard-hero, .hero-card, .welcome-card, .card.bg-primary, .card.text-bg-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 55%, #c2410c 100%) !important;
            color: #ffffff !important;
        }
        .card[style*="#1e40af"], .card[style*="#1d4ed8"], .card[style*="#2563eb"], .card[style*="#0f172a"], .card[style*="#16a34a"], .card[style*="#0f766e"], .card[style*="#7c3aed"], .card[style*="#5b21b6"] {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 55%, #c2410c 100%) !important;
            color: #ffffff !important;
        }

        .sidebar { width: var(--sidebar-width); height: 100vh; background: var(--surface); position: fixed; left: 0; top: 0; padding: 22px 16px; color: var(--text); z-index: 1000; overflow-y: auto; overflow-x: hidden; border-right: 1px solid var(--border); }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 999px; }
        .sidebar-profile { text-align: center; padding: 10px 10px 22px 10px; margin-bottom: 16px; border-bottom: 1px solid var(--border-soft); }
        .sidebar-profile-avatar, .profile-avatar-circle, .detail-avatar-circle { width: 72px; height: 72px; min-width: 72px; max-width: 72px; min-height: 72px; max-height: 72px; border-radius: 50%; background: var(--primary-soft); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; color: var(--primary-dark); font-size: 28px; font-weight: 800; border: 1px solid var(--primary-border); overflow: hidden; flex-shrink: 0; }
        .detail-avatar-circle { width: 110px; height: 110px; min-width: 110px; max-width: 110px; min-height: 110px; max-height: 110px; font-size: 42px; margin-bottom: 16px; }
        .sidebar-profile-avatar i, .profile-avatar-circle i, .detail-avatar-circle i { color: var(--primary-dark) !important; }
        .sidebar-profile-avatar img, .profile-avatar-circle img, .detail-avatar-circle img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block; padding: 0; }
        .sidebar-profile h6 { margin: 0; font-weight: 800; color: var(--text); font-size: 17px; word-break: break-word; }
        .sidebar-profile small { color: var(--muted); font-size: 13px; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin-bottom: 6px; }
        .sidebar-menu a, .sidebar-menu button { width: 100%; border: none; background: transparent; color: #475569; display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 14px; transition: 0.18s ease; font-size: 15px; text-align: left; font-weight: 600; }
        .sidebar-menu a:hover, .sidebar-menu button:hover { background: var(--surface-soft); color: var(--text); }
        .sidebar-menu a.active { background: var(--primary-soft); color: var(--primary-dark); }
        .sidebar-menu i { font-size: 18px; width: 22px; text-align: center; flex-shrink: 0; color: #94a3b8; }
        .sidebar-menu a.active i { color: var(--primary-dark); }
        .sidebar-menu .logout-button { color: var(--danger) !important; }
        .sidebar-menu .logout-button i { color: var(--danger) !important; }
        .sidebar-menu .logout-button:hover { color: var(--danger) !important; background: #fef2f2 !important; }

        .content { margin-left: var(--sidebar-width); min-height: 100vh; width: calc(100% - var(--sidebar-width)); padding-top: var(--topbar-height); }
        .topbar { min-height: var(--topbar-height); background: var(--surface); border-bottom: 1px solid var(--border); padding: 14px 30px; display: flex; align-items: center; justify-content: space-between; gap: 18px; position: fixed; top: 0; left: var(--sidebar-width); right: 0; z-index: 900; }
        .topbar-left { min-width: 0; }
        .topbar h5 { font-size: 20px; color: var(--text); }
        .topbar small, .topbar .text-muted { color: var(--muted) !important; }
        .main-content { padding: 30px; }
        .desktop-brand-right { display: flex; align-items: center; gap: 10px; text-align: left; flex-shrink: 0; }
        .company-logo { width: 58px; height: 58px; display: flex; align-items: center; justify-content: center; background: transparent; overflow: hidden; flex-shrink: 0; }
        .company-logo img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .desktop-brand-text h6 { margin: 0; font-size: 16px; font-weight: 800; color: var(--text); line-height: 1.2; white-space: nowrap; }
        .desktop-brand-text small { color: var(--muted); font-size: 13px; white-space: nowrap; }

        .mobile-navbar, .mobile-bottom-nav, .mobile-filter-trigger { display: none; }
        .mobile-brand-left { display: flex; align-items: center; gap: 10px; text-align: left; min-width: 0; }
        .mobile-company-logo { width: 56px; height: 56px; flex: 0 0 56px; display: flex; align-items: center; justify-content: center; background: transparent; overflow: hidden; }
        .mobile-company-logo img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .mobile-brand-text { min-width: 0; }
        .mobile-brand-text h6 { margin: 0; font-size: 16px; font-weight: 800; color: var(--text); line-height: 1.2; white-space: nowrap; }
        .mobile-brand-text small { color: var(--muted); font-size: 13px; white-space: nowrap; }
        .mobile-profile-button { width: 46px; height: 46px; min-width: 46px; border: 1px solid var(--primary-border); background: var(--primary-soft); border-radius: 50%; color: var(--primary-dark); display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 0; }
        .mobile-profile-button i { font-size: 20px; color: var(--primary-dark); }
        .mobile-profile-button img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .stat-card, .dashboard-card, .table-card { border: none; border-radius: 22px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); overflow: hidden; }
        .status-box { border-radius: 24px; background: linear-gradient(135deg, #f97316 0%, #ea580c 55%, #c2410c 100%); color: #fff; box-shadow: 0 18px 40px rgba(249, 115, 22, 0.18); }
        .stat-icon, .info-icon { width: 54px; height: 54px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0; }
        .info-icon { background: var(--primary-soft-2); color: var(--primary-dark); }
        .icon-orange { background: var(--primary-soft-2); color: var(--primary-dark); }
        .icon-green { background: #dcfce7; color: var(--success); }
        .icon-blue { background: #dbeafe; color: var(--blue); }
        .icon-yellow { background: #fef3c7; color: var(--warning); }
        .icon-red { background: #fee2e2; color: var(--danger); }
        .icon-purple { background: #ede9fe; color: var(--purple); }
        .stat-icon i, .info-icon i, .icon-orange i, .icon-green i, .icon-blue i, .icon-yellow i, .icon-red i, .icon-purple i { color: inherit !important; }
        .badge-soft-success { background: #dcfce7; color: #15803d; }
        .badge-soft-warning { background: #fef3c7; color: #b45309; }
        .badge-soft-primary, .badge-soft-orange { background: var(--primary-soft-2); color: var(--primary-dark); }
        .badge-soft-danger { background: #fee2e2; color: #b91c1c; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-responsive table { min-width: 820px; }
        .table { margin-bottom: 0; }
        .table thead th { background: #f8fafc; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: .04em; border-bottom: none; white-space: nowrap; padding: 14px 16px; vertical-align: middle; }
        .table td { vertical-align: middle; color: #334155; white-space: nowrap; padding: 14px 16px; }
        .table td.wrap-cell, .table th.wrap-cell { white-space: normal; min-width: 180px; }
        .table-danger td { background: #f8d7da !important; }
        .btn { white-space: nowrap; }

        .compact-filter-card { border: none; border-radius: 22px; background: #fff; box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05); }
        .compact-filter-title { font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 14px; }
        .compact-filter .form-control, .compact-filter .form-select { border-radius: 999px; font-size: 14px; padding: 10px 16px; border: 1px solid #dbe3ef; min-height: 44px; }
        .compact-filter .btn { border-radius: 999px; padding: 10px 18px; font-size: 14px; min-height: 44px; }
        .date-input-wrap { position: relative; }
        .date-input-label { display: flex; align-items: center; gap: 6px; color: #64748b; font-size: 12px; font-weight: 700; margin-bottom: 6px; }
        .date-input-label i { color: var(--primary-dark); }
        .date-range-group { display: grid; grid-template-columns: 1fr auto 1fr; align-items: end; gap: 8px; }
        .date-range-separator { padding-bottom: 11px; color: #64748b; font-weight: 800; white-space: nowrap; }

        .employee-history-mobile { display: none; }
        .history-mobile-item { border: 1px solid #e5e7eb; border-radius: 18px; padding: 14px; margin-bottom: 12px; background: #ffffff; }
        .history-mobile-item.history-late { background: #fee2e2; border-color: #fecaca; }
        .history-time-box { background: #f8fafc; border-radius: 14px; padding: 12px; }
        .history-time-box small { display: block; color: #64748b; margin-bottom: 4px; font-size: 12px; }
        .history-time-box strong { font-size: 14px; color: #0f172a; }

        @media (max-width: 991.98px) {
            .sidebar { display: none; }
            .content { margin-left: 0; width: 100%; padding-top: var(--mobile-navbar-height); padding-bottom: var(--mobile-bottom-nav-height); }
            .topbar { display: none; }
            .mobile-navbar { display: flex; min-height: var(--mobile-navbar-height); background: var(--surface); align-items: center; justify-content: space-between; padding: 0 18px; border-bottom: 1px solid var(--border); position: fixed; top: 0; left: 0; right: 0; z-index: 900; gap: 14px; }
            .mobile-bottom-nav { display: flex; align-items: center; gap: 8px; position: fixed; left: 14px; right: 14px; bottom: 12px; height: 68px; z-index: 920; background: #fff; border: 1px solid var(--border); border-radius: 28px; padding: 8px 10px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12); overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; scrollbar-width: none; scroll-behavior: auto; }
            .mobile-bottom-nav::-webkit-scrollbar { display: none; }
            .mobile-bottom-nav a { min-width: 88px; height: 52px; border: none; background: transparent; color: var(--muted); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 3px; border-radius: 999px; font-size: 10.5px; font-weight: 700; flex: 0 0 88px; white-space: nowrap; transition: background 0.22s ease, color 0.22s ease, transform 0.22s ease; }
            .mobile-bottom-nav i { font-size: 17px; color: var(--muted); transition: color 0.22s ease; }
            .mobile-bottom-nav span { line-height: 1; }
            .mobile-bottom-nav a.active { background: var(--primary); color: #fff; transform: translateY(-1px); }
            .mobile-bottom-nav a.active i { color: #fff; }
            .main-content { padding: 24px; }
            .stat-card, .dashboard-card, .table-card, .status-box { border-radius: 20px; }
            .mobile-filter-trigger { width: 100%; border: 1px solid var(--border); background: #fff; color: var(--text); border-radius: 18px; padding: 13px 15px; display: flex; align-items: center; justify-content: space-between; gap: 12px; font-weight: 800; margin-bottom: 14px; }
            .mobile-filter-trigger .filter-left { display: flex; align-items: center; gap: 10px; }
            .mobile-filter-trigger i { width: 34px; height: 34px; border-radius: 12px; background: var(--primary-soft); color: var(--primary-dark); display: flex; align-items: center; justify-content: center; }
            .mobile-filter-count { background: var(--primary-soft); color: var(--primary-dark); border-radius: 999px; padding: 5px 10px; font-size: 12px; font-weight: 800; white-space: nowrap; }
            .compact-filter-card.mobile-filter-panel { display: none; margin-bottom: 16px; border-radius: 20px; box-shadow: none; border: 1px solid var(--border); }
            .compact-filter-card.mobile-filter-panel.show { display: block; }
            .compact-filter-card.mobile-filter-panel .card-body, .compact-filter-card.mobile-filter-panel .p-4 { padding: 16px !important; }
            .compact-filter-card.mobile-filter-panel .row { row-gap: 12px; }
            .compact-filter-card.mobile-filter-panel .form-control, .compact-filter-card.mobile-filter-panel .form-select, .compact-filter-card.mobile-filter-panel .btn { border-radius: 14px; width: 100%; }
        }
        @media (max-width: 576px) {
            :root { --mobile-navbar-height: 70px; --mobile-bottom-nav-height: 88px; }
            .mobile-navbar { padding: 0 14px; }
            .mobile-company-logo { width: 52px; height: 52px; flex-basis: 52px; }
            .mobile-brand-text h6 { font-size: 15px; }
            .mobile-brand-text small { font-size: 12px; }
            .mobile-profile-button { width: 44px; height: 44px; min-width: 44px; }
            .main-content { padding: 16px; }
            .stat-card, .dashboard-card, .table-card, .status-box { border-radius: 18px; }
            .card-body { padding: 18px !important; }
            .compact-filter .btn, .compact-filter a.btn { width: 100%; }
            .compact-filter .d-flex { flex-wrap: wrap; }
            .table-responsive table { min-width: 760px; }
            .employee-history-table { display: none; }
            .employee-history-mobile { display: block; }
            .date-range-group { grid-template-columns: 1fr; gap: 10px; }
            .date-range-separator { display: none; }
        }
        @media (max-width: 420px) {
            .mobile-company-logo { width: 48px; height: 48px; flex-basis: 48px; }
            .mobile-brand-text h6 { font-size: 14px; }
            .mobile-brand-text small { font-size: 11px; }
            .main-content { padding: 14px; }
            .mobile-bottom-nav a { min-width: 82px; flex-basis: 82px; font-size: 10px; }
        }

        .custom-pagination-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }

        .custom-pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .page-btn {
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s ease;
        }

        .page-btn:hover {
            background: #f97316;
            border-color: #f97316;
            color: #ffffff;
        }

        .page-btn.active {
            background: #f97316;
            border-color: #f97316;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(249, 115, 22, 0.25);
        }

        .page-btn.disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .page-btn.dots {
            border: none;
            background: transparent;
            color: #9ca3af;
            cursor: default;
        }

        @media (max-width: 576px) {
            .custom-pagination {
                gap: 6px;
            }

            .page-btn {
                min-width: 34px;
                height: 34px;
                padding: 0 10px;
                font-size: 13px;
                border-radius: 8px;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $profilePhoto = $user && $user->profile_photo ? asset('storage/' . $user->profile_photo) : null;
    @endphp

    <aside class="sidebar">
        <div class="sidebar-profile">
            <div class="sidebar-profile-avatar">
                @if ($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="Foto Profil">
                @else
                    <i class="bi bi-person-fill"></i>
                @endif
            </div>
            <h6>{{ $user->name }}</h6>
            <small>Karyawan</small>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('employee.dashboard') }}" class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('employee.checkin.form') }}" class="{{ request()->routeIs('employee.checkin.*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Check-in</span>
                </a>
            </li>
            <li>
                <a href="{{ route('employee.checkout.form') }}" class="{{ request()->routeIs('employee.checkout.*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Check-out</span>
                </a>
            </li>
            <li>
                <a href="{{ route('employee.history.index') }}" class="{{ request()->routeIs('employee.history.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil</span>
                </a>
            </li>
            <li class="mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <div class="content">
        <nav class="mobile-navbar">
            <div class="mobile-brand-left">
                <div class="mobile-company-logo">
                    <img src="{{ asset('images/logo-polos.png') }}" alt="Logo Perusahaan">
                </div>
                <div class="mobile-brand-text">
                    <h6>Absensi EWF</h6>
                    <small>Karyawan</small>
                </div>
            </div>
            <a href="{{ route('profile.show') }}" class="mobile-profile-button" aria-label="Buka profil">
                @if ($profilePhoto)
                    <img src="{{ $profilePhoto }}" alt="Foto Profil">
                @else
                    <i class="bi bi-person-fill"></i>
                @endif
            </a>
        </nav>

        <div class="topbar">
            <div class="topbar-left">
                <h5 class="fw-bold mb-0">@yield('page-title', 'Dashboard Karyawan')</h5>
                <small class="text-muted">PT. Equity World Futures Surabaya</small>
            </div>
            <div class="desktop-brand-right">
                <div class="company-logo">
                    <img src="{{ asset('images/logo-polos.png') }}" alt="Logo Perusahaan">
                </div>
                <div class="desktop-brand-text">
                    <h6>Absensi EWF</h6>
                    <small>Karyawan</small>
                </div>
            </div>
        </div>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <nav class="mobile-bottom-nav">
        <a href="{{ route('employee.dashboard') }}" class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('employee.checkin.form') }}" class="{{ request()->routeIs('employee.checkin.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Check-in</span>
        </a>
        <a href="{{ route('employee.checkout.form') }}" class="{{ request()->routeIs('employee.checkout.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-right"></i>
            <span>Check-out</span>
        </a>
        <a href="{{ route('employee.history.index') }}" class="{{ request()->routeIs('employee.history.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            <span>Profil</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const backButtons = document.querySelectorAll('.main-content a.btn, .main-content button.btn');
            backButtons.forEach(function (button) {
                const text = button.textContent.trim().toLowerCase();
                if (text.includes('kembali')) {
                    button.classList.add('btn-kembali-ewf');
                }
            });

            const filterCards = document.querySelectorAll('.compact-filter-card');
            filterCards.forEach(function (card) {
                if (card.dataset.mobileFilterReady === 'true') return;
                card.dataset.mobileFilterReady = 'true';
                card.classList.add('mobile-filter-panel');

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'mobile-filter-trigger';
                button.setAttribute('aria-expanded', 'false');

                const countActiveFilters = function () {
                    const fields = card.querySelectorAll('input, select, textarea');
                    let count = 0;
                    fields.forEach(function (field) {
                        if (!field.name || field.type === 'hidden' || field.type === 'submit' || field.type === 'button') return;
                        if ((field.type === 'checkbox' || field.type === 'radio') && field.checked) { count++; return; }
                        if (field.tagName.toLowerCase() === 'select') {
                            const value = (field.value || '').trim();
                            if (value !== '' && value !== 'all' && value !== 'semua') count++;
                            return;
                        }
                        if ((field.value || '').trim() !== '') count++;
                    });
                    return count;
                };

                const renderButton = function () {
                    const count = countActiveFilters();
                    const countText = count > 0 ? count + ' aktif' : 'Buka';
                    button.innerHTML = `
                        <span class="filter-left">
                            <i class="bi bi-funnel-fill"></i>
                            <span>Filter Data</span>
                        </span>
                        <span class="mobile-filter-count">${countText}</span>
                    `;
                };

                renderButton();
                card.parentNode.insertBefore(button, card);

                button.addEventListener('click', function () {
                    const isOpen = card.classList.toggle('show');
                    button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    const badge = button.querySelector('.mobile-filter-count');
                    if (badge) badge.textContent = isOpen ? 'Tutup' : (countActiveFilters() > 0 ? countActiveFilters() + ' aktif' : 'Buka');
                });
                card.addEventListener('input', renderButton);
                card.addEventListener('change', renderButton);
            });

            const bottomNav = document.querySelector('.mobile-bottom-nav');
            const activeItem = document.querySelector('.mobile-bottom-nav .active');
            if (bottomNav && activeItem && window.innerWidth <= 991) {
                requestAnimationFrame(function () {
                    const navRect = bottomNav.getBoundingClientRect();
                    const activeRect = activeItem.getBoundingClientRect();
                    const targetScroll = bottomNav.scrollLeft + (activeRect.left - navRect.left) - 10;
                    bottomNav.scrollLeft = Math.max(targetScroll, 0);
                });
            }
        });
    </script>
</body>
</html>
