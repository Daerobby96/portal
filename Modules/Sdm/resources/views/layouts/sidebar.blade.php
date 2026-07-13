@auth
<nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

    @php
        $appName    = $sidebarSettings['appName'] ?? 'SPMI';
        $appTagline = 'SDM & Kepegawaian';
        $logo       = $sidebarSettings['logo'] ?? null;
    @endphp

    {{-- Logo --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
            @else
                <i class="bi bi-people-fill"></i>
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
        {{-- Dashboard --}}
        <li>
            <div class="sidebar-heading">SDM</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.index') ? 'active' : '' }}">
            <a href="{{ route('sdm.index') }}" class="sidebar-link" data-title="Dashboard SDM">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </a>
        </li>

        {{-- Presensi --}}
        <li>
            <div class="sidebar-heading">Kehadiran</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.presensi.index') && !request()->routeIs('sdm.presensi.rekap') ? 'active' : '' }}">
            <a href="{{ route('sdm.presensi.index') }}" class="sidebar-link" data-title="Presensi">
                <i class="bi bi-calendar-check"></i><span>Data Presensi</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.presensi.rekap') ? 'active' : '' }}">
            <a href="{{ route('sdm.presensi.rekap') }}" class="sidebar-link" data-title="Rekap Presensi">
                <i class="bi bi-table"></i><span>Rekap Presensi</span>
            </a>
        </li>

        {{-- Cuti & Lembur --}}
        <li>
            <div class="sidebar-heading">Izin & Lembur</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.cuti.*') ? 'active' : '' }}">
            <a href="{{ route('sdm.cuti.index') }}" class="sidebar-link" data-title="Cuti">
                <i class="bi bi-calendar-x"></i><span>Manajemen Cuti</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.lembur.*') ? 'active' : '' }}">
            <a href="{{ route('sdm.lembur.index') }}" class="sidebar-link" data-title="Lembur">
                <i class="bi bi-clock-history"></i><span>Manajemen Lembur</span>
            </a>
        </li>

        {{-- Kinerja --}}
        <li>
            <div class="sidebar-heading">Penilaian</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.penilaian-kinerja.*') ? 'active' : '' }}">
            <a href="{{ route('sdm.penilaian-kinerja.index') }}" class="sidebar-link" data-title="Penilaian Kinerja">
                <i class="bi bi-star"></i><span>Penilaian Kinerja</span>
            </a>
        </li>

        {{-- Administrasi --}}
        <li>
            <div class="sidebar-heading">Administrasi</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.surat-tugas.*') ? 'active' : '' }}">
            <a href="{{ route('sdm.surat-tugas.index') }}" class="sidebar-link" data-title="Surat Tugas">
                <i class="bi bi-file-earmark-text"></i><span>Surat Tugas</span>
            </a>
        </li>

        {{-- Data Master --}}
        <li>
            <div class="sidebar-heading">Data</div>
        </li>
        <li class="sidebar-item {{ request()->routeIs('sdm.pegawai.*') ? 'active' : '' }}">
            <a href="{{ route('sdm.pegawai.index') }}" class="sidebar-link" data-title="Data Pegawai">
                <i class="bi bi-person-badge"></i><span>Data Pegawai</span>
            </a>
        </li>

        {{-- Back to Main --}}
        <li>
            <div class="sidebar-heading">Navigasi</div>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('dashboard') }}" class="sidebar-link" data-title="Kembali ke Dashboard Utama">
                <i class="bi bi-arrow-left-circle"></i><span>Dashboard Utama</span>
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
