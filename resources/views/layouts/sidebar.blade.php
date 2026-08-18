@auth
    <nav id="sidebar-wrapper" class="sidebar-wrapper border-r border-white/5 shadow-2xl transition-all duration-300">

        @php
            $appName    = $sidebarSettings['appName'] ?? 'SPMI';
            $appTagline = $sidebarSettings['appTagline'] ?? 'Penjaminan Mutu';
            $logo       = $sidebarSettings['logo'] ?? null;
            $periode    = $periodeData['aktif'] ?? null;
            $allPeriodes = $periodeData['all'] ?? collect();

            // Tentukan grup mana yang aktif untuk auto-expand
            $grpDokumen  = request()->routeIs('dokumen.*','standar.*','kategori-dokumen.*','surat-keputusan.*');
            $grpMonitor  = request()->routeIs('indikator-kinerja.*','iku-resmi.*','monitoring.*','evaluasi.*','spmi.integration.*');
            $grpAudit    = request()->routeIs('audit.*','tindak-lanjut.*','rtm.*','rapat.*');
            $grpUmpan    = request()->routeIs('user-kuesioner.*','kinerja-dosen.*','tracer-study.*','laporan.*');
            $grpData     = request()->routeIs('mahasiswa.*','alumni.*','pegawai.*','prestasi.*');
            $grpKerja    = request()->routeIs('kerjasama.*');
            $grpTridharm = request()->routeIs('penelitian.*','pengabdian.*','publikasi.*','hki.*');
            $grpAdmin    = request()->routeIs('users.*','periode.*','program-studi.*','kuesioner.*','settings.*','activity-log.*');
        @endphp

        {{-- Logo --}}
        <div class="sidebar-brand">
            <div class="brand-logo">
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}" height="32">
                @else
                    <i class="bi bi-shield-check"></i>
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

        <style>
            /* ── Accordion Sidebar — Premium Style ── */

            /* Tombol grup (toggle) */
            .sb-toggle {
                display: flex;
                align-items: center;
                gap: .6rem;
                width: 100%;
                padding: .6rem 1.1rem .6rem 1rem;
                margin: .1rem 0;
                background: none;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                font-size: .72rem;
                font-weight: 700;
                letter-spacing: .065em;
                text-transform: uppercase;
                color: rgba(255,255,255,.38);
                transition: background .2s ease, color .2s ease;
                user-select: none;
                text-align: left;
            }
            .sb-toggle i:first-child {
                width: 18px;
                text-align: center;
                font-size: .95rem;
                flex-shrink: 0;
                opacity: .7;
                transition: opacity .2s ease;
            }
            .sb-toggle:hover {
                background: rgba(255,255,255,.06);
                color: rgba(255,255,255,.75);
            }
            .sb-toggle:hover i:first-child { opacity: 1; }
            .sb-toggle.open {
                color: rgba(255,255,255,.85);
                background: rgba(255,255,255,.07);
            }
            .sb-toggle.open i:first-child { opacity: 1; }

            /* Chevron */
            .sb-chev {
                margin-left: auto;
                font-size: .6rem;
                transition: transform .25s cubic-bezier(.4,0,.2,1);
                opacity: .55;
                flex-shrink: 0;
            }
            .sb-toggle.open .sb-chev {
                transform: rotate(180deg);
                opacity: 1;
            }

            /* Body (collapsible area) */
            .sb-body {
                max-height: 0;
                overflow: hidden;
                transition: max-height .32s cubic-bezier(.4,0,.2,1);
            }
            .sb-body.open { max-height: 900px; }

            /* Container sub-items */
            .sb-body ul {
                padding: .3rem .6rem .5rem 2.35rem;
                position: relative;
            }
            /* Garis vertikal kiri */
            .sb-body ul::before {
                content: '';
                position: absolute;
                left: 1.55rem;
                top: 4px;
                bottom: 8px;
                width: 1.5px;
                background: rgba(255,255,255,.1);
                border-radius: 2px;
            }

            /* Sub-item link */
            .sb-body .sidebar-link {
                display: flex;
                align-items: center;
                gap: .5rem;
                padding: .42rem .65rem .42rem .65rem !important;
                font-size: .85rem;
                font-weight: 450;
                color: rgba(255,255,255,.5);
                border-radius: 8px;
                text-decoration: none;
                transition: background .18s ease, color .18s ease, padding-left .18s ease;
                position: relative;
            }
            .sb-body .sidebar-link i {
                font-size: .9rem;
                width: 16px;
                text-align: center;
                flex-shrink: 0;
                opacity: .7;
                transition: opacity .18s;
            }
            .sb-body .sidebar-link:hover {
                background: rgba(255,255,255,.07);
                color: rgba(255,255,255,.9);
                padding-left: .9rem !important;
            }
            .sb-body .sidebar-link:hover i { opacity: 1; }

            /* Active sub-item */
            .sb-body .sidebar-item.active .sidebar-link {
                background: rgba(255,255,255,.1);
                color: #fff;
                font-weight: 600;
            }
            .sb-body .sidebar-item.active .sidebar-link::before {
                content: '';
                position: absolute;
                left: -1.1rem;
                top: 50%;
                transform: translateY(-50%);
                width: 5px;
                height: 5px;
                background: var(--primary-color, #6c7ae0);
                border-radius: 50%;
                box-shadow: 0 0 6px var(--primary-color, #6c7ae0);
            }
            .sb-body .sidebar-item.active .sidebar-link i { opacity: 1; }
        </style>

        {{-- Navigation Menu --}}
        <ul class="sidebar-menu list-unstyled">

            {{-- Dashboard --}}
            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="sidebar-link" data-title="Dashboard">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
            </li>

            {{-- Siklus PPEPP --}}
            <li class="sidebar-item {{ request()->routeIs('ppepp.*') || request()->path() === 'ppepp' ? 'active' : '' }}">
                <a href="{{ route('ppepp.index') }}" class="sidebar-link" data-title="Siklus PPEPP">
                    <i class="bi bi-arrow-repeat"></i><span>Siklus PPEPP</span>
                </a>
            </li>

            {{-- ── Dokumen & Standar ── --}}
            @if(auth()->user()->canManageDokumen() || auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
            <li>
                <button class="sb-toggle {{ $grpDokumen ? 'open' : '' }}" data-target="grp-dokumen">
                    <i class="bi bi-folder2-open"></i>
                    <span>Dokumen &amp; Standar</span>
                    <i class="bi bi-chevron-down sb-chev"></i>
                </button>
                <div class="sb-body {{ $grpDokumen ? 'open' : '' }}" id="grp-dokumen">
                    <ul class="list-unstyled mb-0">
                        @if(auth()->user()->canManageDokumen() || auth()->user()->isSuperAdmin())
                        <li class="sidebar-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                            <a href="{{ route('dokumen.index') }}" class="sidebar-link" data-title="Dokumen Mutu">
                                <i class="bi bi-folder2"></i><span>Dokumen Mutu</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('standar.*') ? 'active' : '' }}">
                            <a href="{{ route('standar.index') }}" class="sidebar-link" data-title="Standar Mutu">
                                <i class="bi bi-bookmark-check"></i><span>Standar Mutu</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('kategori-dokumen.*') ? 'active' : '' }}">
                            <a href="{{ route('kategori-dokumen.index') }}" class="sidebar-link" data-title="Kategori Dokumen">
                                <i class="bi bi-tags"></i><span>Kategori Dokumen</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            {{-- ── Monitoring & Evaluasi ── --}}
            <li>
                <button class="sb-toggle {{ $grpMonitor ? 'open' : '' }}" data-target="grp-monitor">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Monitoring &amp; Evaluasi</span>
                    <i class="bi bi-chevron-down sb-chev"></i>
                </button>
                <div class="sb-body {{ $grpMonitor ? 'open' : '' }}" id="grp-monitor">
                    <ul class="list-unstyled mb-0">
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
                        <li class="sidebar-item {{ request()->routeIs('indikator-kinerja.*') ? 'active' : '' }}">
                            <a href="{{ route('indikator-kinerja.index') }}" class="sidebar-link">
                                <i class="bi bi-bullseye"></i><span>Indikator SPMI (IKU/IKT)</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('iku-resmi.*') ? 'active' : '' }}">
                            <a href="{{ route('iku-resmi.index') }}" class="sidebar-link">
                                <i class="bi bi-award"></i><span>IKU Kemdiktisaintek</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                            <a href="{{ route('monitoring.index') }}" class="sidebar-link">
                                <i class="bi bi-bar-chart-line"></i><span>Monitoring IKU/IKT</span>
                            </a>
                        </li>
                        @if(auth()->user()->canManageAudit() || auth()->user()->isPimpinan())
                        <li class="sidebar-item {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">
                            <a href="{{ route('evaluasi.index') }}" class="sidebar-link">
                                <i class="bi bi-graph-up-arrow"></i><span>Evaluasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('spmi.integration.*') ? 'active' : '' }}">
                            <a href="{{ route('spmi.integration.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-diagram-3"></i><span>Integrasi Data Modul</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>

            {{-- ── Audit Mutu Internal ── --}}
            @if(auth()->user()->canManageAudit() || auth()->user()->isAuditee() || auth()->user()->isPimpinan())
            <li>
                <button class="sb-toggle {{ $grpAudit ? 'open' : '' }}" data-target="grp-audit">
                    <i class="bi bi-clipboard2-check"></i>
                    <span>Audit Mutu Internal</span>
                    <i class="bi bi-chevron-down sb-chev"></i>
                </button>
                <div class="sb-body {{ $grpAudit ? 'open' : '' }}" id="grp-audit">
                    <ul class="list-unstyled mb-0">
                        @if(auth()->user()->canManageAudit() || auth()->user()->isPimpinan())
                        <li class="sidebar-item {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                            <a href="{{ route('audit.index') }}" class="sidebar-link">
                                <i class="bi bi-clipboard2-check"></i><span>Pelaksanaan Audit</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item {{ request()->routeIs('tindak-lanjut.*') ? 'active' : '' }}">
                            <a href="{{ route('tindak-lanjut.index') }}" class="sidebar-link">
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Tindak Lanjut</span>
                                @if($openTemuanCount > 0)
                                    <span class="badge bg-danger ms-auto">{{ $openTemuanCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('rtm.*') ? 'active' : '' }}">
                            <a href="{{ route('rtm.index') }}" class="sidebar-link">
                                <i class="bi bi-people-fill"></i><span>Tinjauan Manajemen (RTM)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            {{-- ── Umpan Balik & Laporan ── --}}
            <li>
                <button class="sb-toggle {{ $grpUmpan ? 'open' : '' }}" data-target="grp-umpan">
                    <i class="bi bi-chat-square-text"></i>
                    <span>Umpan Balik &amp; Laporan</span>
                    <i class="bi bi-chevron-down sb-chev"></i>
                </button>
                <div class="sb-body {{ $grpUmpan ? 'open' : '' }}" id="grp-umpan">
                    <ul class="list-unstyled mb-0">
                        <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <a href="{{ route('laporan.index') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-bar-graph"></i><span>Laporan</span>
                            </a>
                        </li>

                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan() || auth()->user()->canManageAudit())
                        <li class="sidebar-item {{ request()->routeIs('kuesioner.*') ? 'active' : '' }}">
                            <a href="{{ route('kuesioner.index') }}" class="sidebar-link">
                                <i class="bi bi-ui-checks"></i><span>Manajemen Kuesioner</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item {{ request()->routeIs('kinerja-dosen.*') ? 'active' : '' }}">
                            <a href="{{ route('kinerja-dosen.index') }}" class="sidebar-link">
                                <i class="bi bi-person-badge"></i><span>Kinerja Dosen (EDOM)</span>
                            </a>
                        </li>
                    </ul>
                </div>
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
        document.querySelectorAll('.sb-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id     = this.getAttribute('data-target');
                var body   = document.getElementById(id);
                var isOpen = this.classList.contains('open');

                // Tutup semua terlebih dulu
                document.querySelectorAll('.sb-toggle').forEach(function(b) { b.classList.remove('open'); });
                document.querySelectorAll('.sb-body').forEach(function(b)   { b.classList.remove('open'); });

                // Buka hanya yang diklik (toggle: jika sudah open, biarkan tertutup)
                if (!isOpen) {
                    this.classList.add('open');
                    body.classList.add('open');
                }
            });
        });
    </script>
@endauth

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>
