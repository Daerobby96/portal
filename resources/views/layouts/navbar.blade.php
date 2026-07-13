<nav class="navbar-top sticky top-0 z-50 glass border-b border-slate-200/50 px-4 py-2">
    <div class="d-flex align-items-center gap-3">
        <!-- Toggle Sidebar -->
        @auth
        <button class="btn btn-icon sidebar-toggle-btn" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        @endauth

        <!-- Breadcrumb (desktop) -->
        <div class="d-none d-md-block">
            <h6 class="navbar-page-title mb-0">@yield('title', 'Dashboard')</h6>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Academic Year Switcher -->
        @auth
            @php
                $periode = $periodeData['aktif'] ?? null;
                $allPeriodes = $periodeData['all'] ?? collect();
            @endphp
            <div class="dropdown me-1 d-none d-md-block">
                <button class="btn btn-periode d-flex align-items-center gap-2 rounded-xl px-3.5 py-1.5 text-xs font-bold shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-calendar3"></i>
                    <span>T.A: {{ $periode?->nama ?? 'Pilih Periode' }}</span>
                    <i class="bi bi-chevron-down small text-slate-400 ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-slate-100 rounded-xl p-1" style="min-width: 220px;">
                    <div class="dropdown-header small text-slate-400 fw-bold px-3 py-2">Pilih Tahun Akademik</div>
                    @foreach($allPeriodes as $p)
                        <li class="mb-1 last:mb-0">
                            <form action="{{ route('set-periode') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <input type="hidden" name="periode_id" value="{{ $p->id }}">
                                <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between rounded-lg py-2 px-3 {{ $p->is_aktif ? 'active-periode' : 'text-slate-600' }}">
                                    <span class="small">{{ $p->nama }}</span>
                                    @if($p->is_aktif)
                                        <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                    @endif
                                </button>
                            </form>
                        </li>
                    @endforeach
                    @if($allPeriodes->isEmpty())
                        <li><span class="dropdown-item text-muted small px-3 py-2">Belum ada periode</span></li>
                    @endif
                </ul>
            </div>
        @endauth

        <!-- Portal Publik -->
        <a href="{{ route('home') }}" class="btn btn-icon text-muted me-1 d-md-flex align-items-center d-none" title="Portal Publik">
            <i class="bi bi-globe fs-5"></i>
        </a>

        @auth
        <!-- App Launcher -->
        <a href="{{ route('portal') }}" class="btn btn-icon text-primary me-1 d-md-flex align-items-center d-none" title="App Launcher">
            <i class="bi bi-grid-fill fs-5"></i>
        </a>

        <!-- Notifikasi -->
        @php
            $unreadNotifications = auth()->user()->unreadNotifications ?? collect();
            $totalNotif = $unreadNotifications->count();
        @endphp
        <div class="dropdown">
            <button class="btn btn-icon position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5"></i>
                @if($totalNotif > 0)
                    <span class="badge bg-danger notification-badge">{{ $totalNotif > 99 ? '99+' : $totalNotif }}</span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow p-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <div class="dropdown-header d-flex justify-content-between align-items-center bg-light border-bottom sticky-top">
                    <span class="fw-bold text-dark">Notifikasi</span>
                    @if($totalNotif > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-primary p-0 text-decoration-none">
                                Tandai sudah dibaca
                            </button>
                        </form>
                    @endif
                </div>
                
                @if($totalNotif > 0)
                    <div class="list-group list-group-flush">
                        @foreach($unreadNotifications->take(5) as $notif)
                            <a href="{{ route('notifications.read', $notif->id) }}" class="list-group-item list-group-item-action py-3 border-bottom">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="notif-icon bg-{{ $notif->data['color'] ?? 'primary' }}-subtle text-{{ $notif->data['color'] ?? 'primary' }} rounded-circle d-flex align-items-center justify-content-center mt-1" style="width: 32px; height: 32px; min-width: 32px;">
                                        <i class="bi {{ $notif->data['icon'] ?? 'bi-bell' }}"></i>
                                    </div>
                                    <div class="min-w-0 flex-grow-1">
                                        <div class="small fw-semibold text-truncate mb-1">{{ $notif->data['message'] ?? 'Pemberitahuan Baru' }}</div>
                                        <div class="text-muted" style="font-size:0.75rem">
                                            <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if($totalNotif > 5)
                        <div class="p-2 text-center border-top bg-light">
                            <a href="{{ route('notifications.index') }}" class="small text-decoration-none text-primary fw-medium">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    @endif
                @else
                    <div class="p-4 text-center text-muted">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-bell-slash text-muted fs-3"></i>
                        </div>
                        <p class="mb-0 small fw-medium">Tidak ada notifikasi baru</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Divider -->
        <div class="vr opacity-25"></div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 user-dropdown-btn hover:bg-slate-100/50 transition-colors duration-200" data-bs-toggle="dropdown">
                <div class="user-avatar-sm shadow-sm ring-2 ring-white/20">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start">
                    <div class="small fw-semibold lh-1">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    <div class="text-muted" style="font-size:.7rem">{{ Str::title(str_replace('_', ' ', auth()->user()->roles->first()?->name ?? 'User')) }}</div>
                </div>
                <i class="bi bi-chevron-down small text-muted group-hover:translate-y-0.5 transition-transform"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <div class="dropdown-item-text">
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                        <div class="text-muted small">{{ auth()->user()->unit_kerja }}</div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <i class="bi bi-person me-2"></i>Profil Saya
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </a>
        @endauth
    </div>
</nav>
