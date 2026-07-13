@auth
<nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

    @php
        $appName    = $sidebarSettings['appName'] ?? 'SPMI';
        $appTagline = 'Data Akademik';
        $logo       = $sidebarSettings['logo'] ?? null;
    @endphp

    {{-- Logo --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
            @else
                <i class="bi bi-mortarboard"></i>
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

    <ul class="sidebar-menu list-unstyled">
        <li>
            <div class="sidebar-heading">Data Akademik</div>
        </li>

        <li class="sidebar-item {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">
            <a href="{{ route('mahasiswa.index') }}" class="sidebar-link">
                <i class="bi bi-person"></i><span>Data Mahasiswa</span>
            </a>
        </li>
        
        <li class="sidebar-item {{ request()->routeIs('prestasi.*') ? 'active' : '' }}">
            <a href="{{ route('prestasi.index') }}" class="sidebar-link">
                <i class="bi bi-award"></i><span>Prestasi Mahasiswa</span>
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('alumni.*') ? 'active' : '' }}">
            <a href="{{ route('alumni.index') }}" class="sidebar-link">
                <i class="bi bi-mortarboard"></i><span>Data Alumni</span>
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
    document.getElementById('sidebarClose')?.addEventListener('click', function () {
        document.getElementById('wrapper').classList.remove('sidebar-open');
    });
</script>
@endauth
