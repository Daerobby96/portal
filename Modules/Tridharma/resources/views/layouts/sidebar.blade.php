@auth
<nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

    @php
        $appName    = $sidebarSettings['appName'] ?? 'SPMI';
        $appTagline = 'Tridharma PT';
        $logo       = $sidebarSettings['logo'] ?? null;
    @endphp

    {{-- Logo --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
            @else
                <i class="bi bi-book"></i>
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
            <div class="sidebar-heading">Tridharma</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('penelitian.*') ? 'active' : '' }}">
            <a href="{{ route('penelitian.index') }}" class="sidebar-link" data-title="Penelitian">
                <i class="bi bi-journal-richtext"></i><span>Penelitian</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('pengabdian.*') ? 'active' : '' }}">
            <a href="{{ route('pengabdian.index') }}" class="sidebar-link" data-title="Pengabdian">
                <i class="bi bi-people"></i><span>Pengabdian</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('publikasi.*') ? 'active' : '' }}">
            <a href="{{ route('publikasi.index') }}" class="sidebar-link" data-title="Publikasi">
                <i class="bi bi-file-earmark-text"></i><span>Publikasi</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('hki.*') ? 'active' : '' }}">
            <a href="{{ route('hki.index') }}" class="sidebar-link" data-title="HKI">
                <i class="bi bi-shield-check"></i><span>HKI</span>
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
