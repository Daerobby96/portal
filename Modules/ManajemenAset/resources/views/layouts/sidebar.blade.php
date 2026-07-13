@auth
    <nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

        @php
            $appName    = $sidebarSettings['appName'] ?? 'SPMI';
            $appTagline = 'Manajemen Aset & Sarana Prasarana';
            $logo       = $sidebarSettings['logo'] ?? null;
        @endphp

        {{-- Logo --}}
        <div class="sidebar-brand">
            <div class="brand-logo">
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
                @else
                    <i class="bi bi-box-seam"></i>
                @endif
            </div>
            <div class="brand-text">
                <span class="brand-name">{{ $appName }}</span>
                <span class="brand-sub">{{ $appTagline }}</span>
            </div>
            <button class="sidebar-close-btn d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Navigation Menu --}}
        <ul class="sidebar-menu list-unstyled">

            {{-- Section: Aset & Inventaris --}}
            <li>
                <div class="sidebar-heading">Aset &amp; Inventaris</div>
            </li>

            @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
            <li class="sidebar-item {{ request()->routeIs('aset.*') ? 'active' : '' }}">
                <a href="{{ route('aset.index') }}" class="sidebar-link" data-title="Inventaris Aset">
                    <i class="bi bi-box-seam"></i><span>Inventaris Aset</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('kategori-aset.*') ? 'active' : '' }}">
                <a href="{{ route('kategori-aset.index') }}" class="sidebar-link" data-title="Kategori Aset">
                    <i class="bi bi-tags"></i><span>Kategori Aset</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pemeliharaan.*') ? 'active' : '' }}">
                <a href="{{ route('pemeliharaan.index') }}" class="sidebar-link" data-title="Pemeliharaan">
                    <i class="bi bi-tools"></i><span>Pemeliharaan</span>
                </a>
            </li>
            @endif

            <li class="sidebar-item {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                <a href="{{ route('peminjaman.index') }}" class="sidebar-link" data-title="Peminjaman Aset">
                    <i class="bi bi-arrow-left-right"></i><span>Peminjaman Aset</span>
                </a>
            </li>

            {{-- Section: Ruangan --}}
            <li>
                <div class="sidebar-heading">Sarana Prasarana</div>
            </li>

            @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
            <li class="sidebar-item {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                <a href="{{ route('ruangan.index') }}" class="sidebar-link" data-title="Data Ruangan">
                    <i class="bi bi-door-open"></i><span>Data Ruangan</span>
                </a>
            </li>
            @endif

            <li class="sidebar-item {{ request()->routeIs('booking-ruangan.*') ? 'active' : '' }}">
                <a href="{{ route('booking-ruangan.index') }}" class="sidebar-link" data-title="Booking Ruangan">
                    <i class="bi bi-calendar-check"></i><span>Booking Ruangan</span>
                </a>
            </li>

        </ul>

        {{-- Sidebar Footer --}}
        <div class="sidebar-footer">
            <div class="user-mini">
                <div class="user-mini-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-mini-info">
                    <span class="user-mini-name">{{ auth()->user()->name }}</span>
                    <span class="user-mini-role">{{ Str::title(str_replace("_", " ", auth()->user()->roles->first()?->name ?? "User")) }}</span>
                </div>
            </div>
        </div>

    </nav>

    <script>
        // Close sidebar when clicking close button (mobile only)
        document.getElementById('sidebarClose')?.addEventListener('click', function () {
            document.getElementById('wrapper').classList.remove('sidebar-open');
        });
    </script>
@endauth
