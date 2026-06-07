 {{-- Layout utama: sidebar navigasi Hotel Booking System --}}
 <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hotel Booking') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0a1628;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        /* Logo */
        .sidebar-logo {
            padding: 20px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #2e86de, #1a6bbf);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(46,134,222,0.3);
        }
        .logo-text h2 {
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .logo-text span {
            color: #4a7fa5;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-section {
            margin-bottom: 4px;
        }
        .nav-label {
            padding: 12px 18px 4px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #2d5070;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            color: #6b8fa8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.15s;
            position: relative;
        }
        .nav-item:hover {
            color: #c8dff0;
            background: rgba(255,255,255,0.04);
            border-left-color: rgba(46,134,222,0.4);
        }
        .nav-item.active {
            color: #fff;
            background: rgba(46,134,222,0.12);
            border-left-color: #2e86de;
            font-weight: 600;
        }
        .nav-item.active .nav-icon { color: #2e86de; }
        .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .nav-item:hover .nav-icon { color: #7fb3d3; }

        /* Divider */
        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.05);
            margin: 8px 18px;
        }

        /* User Footer */
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 14px 18px;
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(255,255,255,0.04);
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #2e86de, #1a5fa0);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }
        .user-info h4 {
            color: #e0eef8;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
        }
        .user-info .role-badge {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .role-admin   { background: rgba(46,134,222,0.2); color: #7fb3d3; }
        .role-ceo     { background: rgba(243,156,18,0.2); color: #f3c26b; }
        .role-customer{ background: rgba(39,174,96,0.2);  color: #6fcf97; }

        .btn-logout {
            width: 100%;
            padding: 8px;
            background: transparent;
            color: #4a7fa5;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-logout:hover {
            background: rgba(231,76,60,0.15);
            color: #e07070;
            border-color: rgba(231,76,60,0.3);
        }

        /* ===== TOPBAR ===== */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            padding: 0 28px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 0 #eef2f7;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-left h1 {
            font-size: 16px;
            font-weight: 700;
            color: #0d2137;
        }
        .topbar-left .breadcrumb {
            font-size: 11px;
            color: #aab4be;
            margin-top: 1px;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-date {
            background: #f0f7ff;
            color: #2e86de;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #d0e8f7;
        }

        /* ===== PAGE ===== */
        .page-body {
            padding: 24px 28px;
            flex: 1;
        }
        .alert-success {
            background: #f0faf4;
            color: #1a6b3a;
            border: 1px solid #b7e4c7;
            border-left: 4px solid #27ae60;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 500;
        }
        .alert-error {
            background: #fff5f5;
            color: #821c1c;
            border: 1px solid #f5c6cb;
            border-left: 4px solid #e74c3c;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 500;
        }

        /* ===== CARD ===== */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid #f0f4f8;
        }
        .card-header {
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f0f4f8;
            background: #fafbfc;
        }
        .card-header h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0d2137;
        }
        .card-body { padding: 22px; }

        /* ===== TABLE ===== */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead tr { background: #0d2137; }
        thead th { padding: 12px 16px; color: #fff; font-weight: 600; text-align: left; font-size: 12px; letter-spacing: 0.3px; }
        tbody tr { border-bottom: 1px solid #f5f7fa; transition: background 0.1s; }
        tbody tr:hover { background: #f7faff; }
        tbody td { padding: 11px 16px; color: #334155; }
        tbody tr:last-child { border-bottom: none; }

        /* ===== BUTTONS ===== */
        .btn { padding: 7px 16px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: all 0.15s; }
        .btn-primary   { background: #2e86de; color: #fff; }
        .btn-primary:hover   { background: #1a6bbf; }
        .btn-warning   { background: #f39c12; color: #fff; }
        .btn-warning:hover   { background: #d68910; }
        .btn-danger    { background: #e74c3c; color: #fff; }
        .btn-danger:hover    { background: #c0392b; }
        .btn-secondary { background: #e8edf2; color: #4a5568; }
        .btn-secondary:hover { background: #d8dfe6; }
        .btn-success   { background: #27ae60; color: #fff; }
        .btn-success:hover   { background: #1e8449; }
        .btn-sm { padding: 4px 11px; font-size: 11.5px; border-radius: 6px; }

        /* ===== FORM ===== */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 9px 13px; border: 1.5px solid #dde3ed; border-radius: 8px; font-size: 13px; color: #334155; transition: border 0.2s; outline: none; background: #fff; }
        .form-control:focus { border-color: #2e86de; box-shadow: 0 0 0 3px rgba(46,134,222,0.1); }
        .form-error { color: #e74c3c; font-size: 11.5px; margin-top: 4px; }

        /* ===== BADGES ===== */
        .badge { padding: 3px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 700; display: inline-block; }
        .badge-success { background: #e8f8f1; color: #1a6b3a; }
        .badge-warning { background: #fef9e7; color: #7d5a0a; }
        .badge-danger  { background: #fdf0ef; color: #821c1c; }
        .badge-info    { background: #eaf4fd; color: #1a5276; }
        .badge-dark    { background: #eaecee; color: #2c3e50; }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-icon">🏨</div>
        <div class="logo-text">
            <h2>Hotel Booking</h2>
            <span>Management System</span>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">

        <div class="nav-section">
            <div class="nav-label">Overview</div>
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
        </div>

        <div class="nav-divider"></div>

        <div class="nav-section">
            <div class="nav-label">Master Data</div>
            @if(in_array(Auth::user()->role, ['ceo', 'admin']))
            <a href="{{ route('tipe-kamar.index') }}"
               class="nav-item {{ request()->routeIs('tipe-kamar.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span> Tipe Kamar
            </a>
            @endif
            <a href="{{ route('kamar.index') }}"
               class="nav-item {{ request()->routeIs('kamar.*') ? 'active' : '' }}">
                <span class="nav-icon">🛏️</span> Data Kamar
            </a>
            @if(in_array(Auth::user()->role, ['ceo', 'admin']))
            <a href="{{ route('tamu.index') }}"
               class="nav-item {{ request()->routeIs('tamu.*') ? 'active' : '' }}">
                <span class="nav-icon">👤</span> Data Tamu
            </a>
            @endif
        </div>

        <div class="nav-divider"></div>

        <div class="nav-section">
            <div class="nav-label">Transaksi</div>
            <a href="{{ route('booking.index') }}"
               class="nav-item {{ request()->routeIs('booking.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Booking
            </a>
            @if(in_array(Auth::user()->role, ['ceo', 'admin']))
            <a href="{{ route('pembayaran.index') }}"
               class="nav-item {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                <span class="nav-icon">💳</span> Pembayaran
            </a>
            @endif
        </div>

        @if(in_array(Auth::user()->role, ['ceo', 'admin']))
        <div class="nav-divider"></div>

        <div class="nav-section">
            <div class="nav-label">Laporan</div>
            <a href="{{ route('laporan.booking') }}"
               class="nav-item {{ request()->routeIs('laporan.booking') ? 'active' : '' }}">
                <span class="nav-icon">📄</span> Laporan Booking
            </a>
            <a href="{{ route('laporan.pembayaran') }}"
               class="nav-item {{ request()->routeIs('laporan.pembayaran') ? 'active' : '' }}">
                <span class="nav-icon">📄</span> Laporan Pembayaran
            </a>
            @if(Auth::user()->role === 'ceo')
            <a href="{{ route('laporan.pendapatan') }}"
               class="nav-item {{ request()->routeIs('laporan.pendapatan') ? 'active' : '' }}">
                <span class="nav-icon">💰</span> Laporan Pendapatan
            </a>
            @endif
        </div>
        @endif

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <h4>{{ Auth::user()->name }}</h4>
                <span class="role-badge role-{{ Auth::user()->role }}">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                🚪 Keluar dari Sistem
            </button>
        </form>
    </div>

</aside>

{{-- MAIN --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-left">
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="breadcrumb">Hotel Booking System › @yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">📅 {{ now()->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    {{-- Content --}}
    <div class="page-body">
        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">❌ {{ session('error') }}</div>
        @endif

        {{ $slot }}
    </div>

</div>

</body>
</html>
</body>
</html>
