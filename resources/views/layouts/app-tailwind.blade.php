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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite (Tailwind 4 & JS with Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 transform bg-sidebar transition-transform duration-200 lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            @click.away="sidebarOpen = false"
        >
            @include('layouts.sidebar')
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur-sm">
                @include('layouts.navbar')
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <!-- Page Header -->
                    @if(View::hasSection('page-title'))
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
                                    @yield('page-title')
                                </h1>
                                @if(View::hasSection('page-subtitle'))
                                <p class="mt-1 text-sm text-slate-500">
                                    @yield('page-subtitle')
                                </p>
                                @endif
                            </div>
                            @if(View::hasSection('page-actions'))
                            <div class="flex gap-2">
                                @yield('page-actions')
                            </div>
                            @endif
                        </div>

                        <!-- Breadcrumb -->
                        @if(View::hasSection('breadcrumb'))
                        <nav class="mt-4 flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 text-sm text-slate-500">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-primary">
                                        <i class="bi bi-house-door me-1"></i>
                                        Dashboard
                                    </a>
                                </li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                        @endif
                    </div>
                    @endif

                    <!-- Flash Messages -->
                    @if(session('success'))
                    <x-ui.alert variant="success" dismissible class="mb-6">
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </x-ui.alert>
                    @endif

                    @if(session('error'))
                    <x-ui.alert variant="danger" dismissible class="mb-6">
                        <strong>Error!</strong> {{ session('error') }}
                    </x-ui.alert>
                    @endif

                    @if($errors->any())
                    <x-ui.alert variant="danger" dismissible class="mb-6">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-inside list-disc">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-200 bg-white px-4 py-4">
                <div class="mx-auto max-w-7xl">
                    <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-slate-500">
                        <p>
                            &copy; {{ date('Y') }} <strong class="text-slate-700">{{ $appName }}</strong>. 
                            All rights reserved.
                        </p>
                        <p>
                            Powered by 
                            <a href="https://laravel.com" target="_blank" class="font-medium text-primary hover:underline">
                                Laravel
                            </a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div 
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
        style="display: none;"
    ></div>

    @stack('scripts')
</body>
</html>
