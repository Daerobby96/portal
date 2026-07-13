@auth
<nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

    @php
        $appName    = $sidebarSettings['appName'] ?? 'SPMI';
        $appTagline = 'Manajemen Surat';
        $logo       = $sidebarSettings['logo'] ?? null;
    @endphp

    {{-- Logo --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
            @else
                <i class="bi bi-envelope-paper"></i>
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
            <div class="sidebar-heading">Dashboard</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('manajemen-surat.dashboard') ? 'active' : '' }}">
            <a href="{{ route('manajemen-surat.dashboard') }}" class="sidebar-link" data-title="Dashboard">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>
        </li>
        
        <li>
            <div class="sidebar-heading">Surat Keluar</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
            <a href="{{ route('surat-keluar.index') }}" class="sidebar-link" data-title="Daftar Surat Keluar">
                <i class="bi bi-box-arrow-up-right"></i><span>Daftar Surat Keluar</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('surat-keputusan.*') ? 'active' : '' }}">
            <a href="{{ route('surat-keputusan.index') }}" class="sidebar-link" data-title="Surat Keputusan">
                <i class="bi bi-file-earmark-text"></i><span>Surat Keputusan</span>
            </a>
        </li>
        
        <li>
            <div class="sidebar-heading">Surat Masuk</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
            <a href="{{ route('surat-masuk.index') }}" class="sidebar-link" data-title="Daftar Surat Masuk">
                <i class="bi bi-box-arrow-in-down-left"></i><span>Daftar Surat Masuk</span>
            </a>
        </li>
        
        <li>
            <div class="sidebar-heading">Disposisi</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('disposisi.my-disposisi') ? 'active' : '' }}">
            <a href="{{ route('disposisi.my-disposisi') }}" class="sidebar-link" data-title="Disposisi Saya">
                <i class="bi bi-person-badge"></i><span>Disposisi Saya</span>
                @if(auth()->user()->disposisiPending ?? false)
                    <span class="badge bg-danger rounded-pill ms-auto">{{ auth()->user()->disposisiPending }}</span>
                @endif
            </a>
        </li>
        
        <li>
            <div class="sidebar-heading">Pengaturan</div>
        </li>
        @if(auth()->user()->hasAnyRole(['super_admin', 'pimpinan']))
        <li class="sidebar-item {{ request()->routeIs('unit-pengelola.*') ? 'active' : '' }}">
            <a href="{{ route('unit-pengelola.index') }}" class="sidebar-link" data-title="Unit Pengelola">
                <i class="bi bi-building"></i><span>Unit Pengelola</span>
            </a>
        </li>
        @endif
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
