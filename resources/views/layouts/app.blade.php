<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        // Gunakan data dari View Composer (cached)
        $appName = $appSettings['appName'] ?? 'SPMI';
        $themePrimary = $appSettings['themePrimary'] ?? '#4e73df';
        $themeSidebar = $appSettings['themeSidebar'] ?? 'dark';
        $logo = $appSettings['logo'] ?? null;
        $favicon = $appSettings['favicon'] ?? null;
    @endphp
    <title>@yield('title', 'Dashboard') — {{ $appName }}</title>

    <!-- Favicon -->
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite (Tailwind 4 & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS (Legacy) -->
    <link href="{{ asset('css/spmi.css') }}" rel="stylesheet">

    <!-- Dynamic Theme -->
    <style>
        :root {
            --primary-color: {{ $themePrimary }};
            --primary-color-rgb: {{ implode(',', sscanf($themePrimary, "#%02x%02x%02x")) }};
        }
        
        /* Modern Minimalist Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
            border: 2px solid #f8fafc;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Beautiful Rounded Buttons & Elements */
        button, .btn, .rounded-xl {
            border-radius: 12px !important;
        }
        .rounded-2xl {
            border-radius: 16px !important;
        }
        .btn {
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
            font-weight: 700 !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .btn-sm {
            border-radius: 8px !important;
            padding-left: 0.85rem !important;
            padding-right: 0.85rem !important;
        }
        .btn-lg {
            border-radius: 16px !important;
            padding-left: 1.75rem !important;
            padding-right: 1.75rem !important;
        }

        /* Override Bootstrap primary colors */
        .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus {
            background-color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
        }
        .btn-primary:hover, .btn-primary:active {
            filter: brightness(0.9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.25) !important;
        }
        .btn-outline-primary {
            color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:active {
            background-color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
            color: #fff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.15) !important;
        }
        .text-primary { color: {{ $themePrimary }} !important; }
        .bg-primary { background-color: {{ $themePrimary }} !important; }
        .border-primary { border-color: {{ $themePrimary }} !important; }
        .badge.bg-primary { background-color: {{ $themePrimary }} !important; }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: {{ $themePrimary }} !important;
        }
        .page-link.active, .active > .page-link {
            background-color: {{ $themePrimary }} !important;
            border-color: {{ $themePrimary }} !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: {{ $themePrimary }} !important;
            box-shadow: 0 0 0 0.25rem rgba(var(--primary-color-rgb), 0.15) !important;
        }
        .card-header-custom {
            border-left: 3px solid {{ $themePrimary }};
        }
        /* Automatically fix Bootstrap badge text colors when using custom light backgrounds */
        .badge.bg-emerald-50, .badge.bg-success-subtle {
            color: #059669 !important;
            background-color: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
        .badge.bg-blue-50, .badge.bg-primary-subtle {
            color: #2563eb !important;
            background-color: rgba(37, 99, 235, 0.1) !important;
            border-color: rgba(37, 99, 235, 0.2) !important;
        }
        .badge.bg-red-50, .badge.bg-danger-subtle {
            color: #dc2626 !important;
            background-color: rgba(239, 68, 68, 0.1) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }
        .badge.bg-amber-50, .badge.bg-warning-subtle {
            color: #d97706 !important;
            background-color: rgba(245, 158, 11, 0.1) !important;
            border-color: rgba(245, 158, 11, 0.2) !important;
        }
        .badge.bg-cyan-50, .badge.bg-info-subtle {
            color: #0891b2 !important;
            background-color: rgba(6, 182, 212, 0.1) !important;
            border-color: rgba(6, 182, 212, 0.2) !important;
        }
        .badge.bg-indigo-50 {
            color: #4f46e5 !important;
            background-color: rgba(99, 102, 241, 0.1) !important;
            border-color: rgba(99, 102, 241, 0.2) !important;
        }
        .badge.bg-slate-50, .badge.bg-slate-100, .badge.bg-secondary-subtle {
            color: #475569 !important;
            background-color: rgba(100, 116, 139, 0.08) !important;
            border-color: rgba(100, 116, 139, 0.15) !important;
        }
        /* Frosted Glass Top Navbar Header */
        .navbar-top {
            background: rgba(255, 255, 255, 0.72) !important;
            backdrop-filter: blur(16px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.015) !important;
            transition: all 0.3s ease !important;
        }
        .navbar-top:hover {
            background: rgba(255, 255, 255, 0.82) !important;
        }

        /* Topbar Icon Buttons */
        .btn-icon {
            border-radius: 12px !important;
            width: 40px !important;
            height: 40px !important;
            background: rgba(241, 245, 249, 0.6) !important;
            border: 1px solid rgba(226, 232, 240, 0.7) !important;
            color: #64748b !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .btn-icon:hover {
            background: rgba(var(--primary-color-rgb), 0.08) !important;
            border-color: rgba(var(--primary-color-rgb), 0.2) !important;
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }
        .notification-badge {
            top: 2px !important;
            right: 2px !important;
            background-color: #ef4444 !important;
            border: 2px solid #fff !important;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2) !important;
        }

        /* Academic Year Switcher Button in Navbar */
        .btn-periode {
            height: 40px !important;
            border-radius: 12px !important;
            background: rgba(var(--primary-color-rgb), 0.06) !important;
            border: 1px solid rgba(var(--primary-color-rgb), 0.16) !important;
            color: var(--primary-color) !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .btn-periode:hover {
            background: rgba(var(--primary-color-rgb), 0.1) !important;
            border-color: rgba(var(--primary-color-rgb), 0.28) !important;
            color: var(--primary-color) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.08) !important;
        }
        .btn-periode i {
            color: var(--primary-color) !important;
            font-size: 1rem !important;
        }
        
        /* Custom Dropdown Items for Year Switcher */
        .dropdown-menu .dropdown-item {
            color: #475569 !important;
            font-weight: 500 !important;
            transition: all 0.15s ease !important;
            background: transparent !important;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: rgba(var(--primary-color-rgb), 0.04) !important;
            color: var(--primary-color) !important;
        }
        .dropdown-menu .dropdown-item.active-periode {
            background-color: rgba(var(--primary-color-rgb), 0.08) !important;
            color: var(--primary-color) !important;
            font-weight: 700 !important;
        }

        /* User Avatar and Profile Dropdown */
        .user-dropdown-btn {
            border-radius: 12px !important;
            border: 1px solid rgba(226, 232, 240, 0.7) !important;
            background: rgba(241, 245, 249, 0.4) !important;
            padding: 0.4rem 0.85rem !important;
            transition: all 0.2s ease !important;
        }
        .user-dropdown-btn:hover {
            background: rgba(241, 245, 249, 0.8) !important;
            border-color: rgba(203, 213, 225, 0.8) !important;
            transform: translateY(-1px);
        }
        .user-avatar-sm {
            border-radius: 10px !important;
            background: linear-gradient(135deg, var(--primary-color), #60a5fa) !important;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2) !important;
        }

        /* ================================================================
           PREMIUM SIDEBAR DESIGN
           ================================================================ */
        .sidebar-wrapper {
            background: #0d111c !important; /* Premium rich deep navy/slate-900 */
            border-right: 1px solid rgba(255, 255, 255, 0.04) !important;
        }
        .sidebar-brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            padding: 1.5rem 1.5rem 1.25rem !important;
        }
        .sidebar-brand .brand-name {
            color: #f8fafc !important;
            font-weight: 800 !important;
            letter-spacing: 0.02em !important;
        }
        .sidebar-brand .brand-sub {
            color: #475569 !important;
            font-weight: 600 !important;
        }
        
        /* Sidebar Navigation Items as Rounded Pills */
        .sidebar-item {
            margin: 0.25rem 0.85rem !important;
            position: relative;
        }
        .sidebar-link {
            border-radius: 12px !important;
            border-left: none !important; 
            padding: 0.65rem 1rem !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 0.82rem !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .sidebar-link:hover {
            color: #f8fafc !important;
            background: rgba(255, 255, 255, 0.04) !important;
            transform: translateX(4px);
        }
        .sidebar-link i {
            font-size: 1.1rem !important;
            color: #475569 !important;
            transition: all 0.2s ease !important;
        }
        .sidebar-link:hover i {
            color: var(--primary-color) !important;
            transform: scale(1.1);
        }
        
        /* Elegant Glowing Active Pill & Neon Indicator */
        .sidebar-item.active > .sidebar-link {
            background: linear-gradient(90deg, rgba(var(--primary-color-rgb), 0.12) 0%, rgba(var(--primary-color-rgb), 0.02) 100%) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .sidebar-item.active > .sidebar-link i {
            color: var(--primary-color) !important;
            text-shadow: 0 0 10px rgba(var(--primary-color-rgb), 0.5) !important;
        }
        .sidebar-item.active::before {
            content: "";
            position: absolute;
            left: -8px;
            top: 22%;
            height: 56%;
            width: 4px;
            background-color: var(--primary-color);
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(var(--primary-color-rgb), 0.8) !important;
        }
        
        /* Sidebar Headings */
        .sidebar-heading {
            padding: 1.25rem 1.85rem 0.5rem !important;
            font-size: 0.68rem !important;
            color: #334155 !important;
            font-weight: 800 !important;
            letter-spacing: 0.12em !important;
        }
        
        /* Sidebar Footer and User Mini */
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.03) !important;
            padding: 1.25rem 1.25rem !important;
            background: transparent !important;
        }
        .user-mini-avatar {
            border-radius: 10px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        .user-mini-name {
            color: #f1f5f9 !important;
            font-weight: 600 !important;
        }
        .user-mini-role {
            color: #475569 !important;
            font-weight: 500 !important;
        }

        /* ── Sidebar Theme - Light Upgrade ── */
        .sidebar-theme-light .sidebar-wrapper {
            background: #f8fafc !important;
            border-right: 1px solid rgba(226, 232, 240, 0.7) !important;
        }
        .sidebar-theme-light .sidebar-brand {
            background: #ffffff !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.7) !important;
        }
        .sidebar-theme-light .sidebar-brand .brand-name {
            color: #0f172a !important;
        }
        .sidebar-theme-light .sidebar-brand .brand-sub {
            color: #64748b !important;
        }
        .sidebar-theme-light .sidebar-brand .brand-logo {
            color: var(--primary-color) !important;
        }
        .sidebar-theme-light .sidebar-link {
            color: #64748b !important;
        }
        .sidebar-theme-light .sidebar-link:hover {
            background: rgba(15, 23, 42, 0.03) !important;
            color: #0f172a !important;
        }
        .sidebar-theme-light .sidebar-item.active > .sidebar-link {
            background: rgba(var(--primary-color-rgb), 0.08) !important;
            color: var(--primary-color) !important;
            font-weight: 700 !important;
        }
        .sidebar-theme-light .sidebar-item.active > .sidebar-link i {
            color: var(--primary-color) !important;
        }
        .sidebar-theme-light .sidebar-item.active::before {
            content: "";
            position: absolute;
            left: -8px;
            top: 22%;
            height: 56%;
            width: 4px;
            background-color: var(--primary-color);
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(var(--primary-color-rgb), 0.5) !important;
        }
        .sidebar-theme-light .sidebar-footer {
            background: #ffffff !important;
            border-top: 1px solid rgba(226, 232, 240, 0.7) !important;
        }
        .sidebar-theme-light .user-mini-name {
            color: #0f172a !important;
        }
        .sidebar-theme-light .user-mini-role {
            color: #64748b !important;
        }
        .sidebar-theme-light .dropdown-menu-dark {
            background: #ffffff !important;
            border: 1px solid rgba(226, 232, 240, 0.7) !important;
        }
        .sidebar-theme-light .dropdown-menu-dark .dropdown-item {
            color: #334155 !important;
        }
        .sidebar-theme-light .dropdown-menu-dark .dropdown-item:hover {
            background: #f1f5f9 !important;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ── Wrapper ── -->
<div class="d-flex {{ $themeSidebar === 'light' ? 'sidebar-theme-light' : '' }}" id="wrapper">

    <!-- ── Sidebar ── -->
    @hasSection('custom_sidebar')
        @yield('custom_sidebar')
    @else
        @include('layouts.sidebar')
    @endif

    <!-- ── Page Content ── -->
    <div id="page-content-wrapper">

        <!-- ── Navbar ── -->
        @include('layouts.navbar')

        <!-- ── Main Content ── -->
        <main class="main-content min-h-[calc(100vh-var(--topbar-height))]">

            {{-- Breadcrumb --}}
            @hasSection('breadcrumb')
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif

            {{-- Page Header --}}
            @hasSection('page-title')
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="page-title mb-0">@yield('page-title')</h4>
                    @hasSection('page-subtitle')
                    <p class="text-muted small mb-0 mt-1">@yield('page-subtitle')</p>
                    @endif
                </div>
                <div class="page-actions">
                    @yield('page-actions')
                </div>
            </div>
            @endif

            {{-- Flash Messages --}}
            @if (session('success'))
                <x-ui.alert variant="success" dismissible class="mb-4">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </x-ui.alert>
            @endif
            @if (session('error'))
                <x-ui.alert variant="danger" dismissible class="mb-4">
                    <strong>Error!</strong> {{ session('error') }}
                </x-ui.alert>
            @endif
            @if (session('warning'))
                <x-ui.alert variant="warning" dismissible class="mb-4">
                    <strong>Perhatian!</strong> {{ session('warning') }}
                </x-ui.alert>
            @endif
            @if (session('info'))
                <x-ui.alert variant="info" dismissible class="mb-4">
                    <strong>Info:</strong> {{ session('info') }}
                </x-ui.alert>
            @endif

            {{-- Content --}}
            @yield('content')
        </main>

        <!-- ── Footer ── -->
        <footer class="main-footer text-center text-muted small py-3">
            &copy; {{ date('Y') }} SPMI — Sistem Penjaminan Mutu Internal. All rights reserved.
        </footer>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Toggle sidebar - works differently on mobile vs desktop
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        const wrapper = document.getElementById('wrapper');
        const isMobile = window.innerWidth < 992;
        
        if (isMobile) {
            // Mobile: toggle sidebar open/close
            wrapper.classList.toggle('sidebar-open');
        } else {
            // Desktop: toggle sidebar collapsed/expanded
            wrapper.classList.toggle('sidebar-collapsed');
            // Save preference
            localStorage.setItem('sidebarCollapsed', wrapper.classList.contains('sidebar-collapsed'));
        }
    });

    // Close sidebar when clicking overlay (mobile only)
    document.querySelector('.sidebar-overlay')?.addEventListener('click', function () {
        document.getElementById('wrapper').classList.remove('sidebar-open');
    });

    // Close sidebar when clicking close button (mobile only)
    document.getElementById('sidebarClose')?.addEventListener('click', function () {
        document.getElementById('wrapper').classList.remove('sidebar-open');
    });

    // Restore sidebar state on page load (desktop only)
    if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
        document.getElementById('wrapper').classList.add('sidebar-collapsed');
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert.alert-success, .alert.alert-info').forEach(el => {
            new bootstrap.Alert(el).close();
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>