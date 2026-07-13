<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $appName = $appSettings['appName'] ?? 'SPMI';
        $logo    = $appSettings['logo'] ?? null;
    @endphp
    <title>Portal Modul � {{ $appName }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(79, 70, 229, 0.05), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(6, 182, 212, 0.05), transparent 25%);
            min-height: 100vh;
            color: var(--text-main);
        }

        .portal-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 3rem 1rem;
        }

        /* Header */
        .portal-header {
            text-align: center;
            padding: 1rem 0 3rem;
        }

        .portal-logo {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--primary);
        }
        
        .portal-logo img {
            max-width: 60%;
            max-height: 60%;
            object-fit: contain;
        }

        .portal-greeting {
            font-size: 0.95rem;
            color: var(--primary);
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .portal-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .portal-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 500px;
            margin: 0 auto;
        }

        /* Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
            padding-bottom: 3rem;
        }

        /* Module Card */
        .module-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 2rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .module-card:hover {
            transform: translateY(-8px);
            border-color: rgba(79, 70, 229, 0.2);
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.15);
            text-decoration: none;
            color: inherit;
        }
        
        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color-start), var(--card-color-end));
            opacity: 0.8;
            transition: height 0.3s ease;
        }
        
        .module-card:hover::before {
            height: 6px;
        }

        .card-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.25rem;
            background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
            color: white;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .card-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.25rem;
            flex-grow: 1;
        }

        .card-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
        }

        .card-tag {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            background: #f1f5f9;
            color: #475569;
        }

        .card-arrow {
            margin-left: auto;
            color: #cbd5e1;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .module-card:hover .card-arrow {
            color: var(--card-color-start);
            transform: translateX(4px);
        }

        /* Divider */
        .section-divider {
            max-width: 1100px;
            margin: 0 auto 1.5rem;
        }

        .section-label {
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-label::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: rgba(203, 213, 225, 0.6);
        }

        /* Logout */
        .logout-btn {
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: #fff;
            color: var(--text-muted);
            border: 1px solid rgba(226,232,240,1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background: #fef2f2;
            color: var(--danger);
            border-color: #fca5a5;
            box-shadow: 0 4px 12px rgba(239,68,68,0.1);
        }

        /* Animation */
        .fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>
<body>

    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="portal-wrapper">
        <div class="container">
            
            <header class="portal-header fade-in-up">
                <div class="portal-logo">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ $appName }}">
                    @else
                        <i class="bi bi-shield-check"></i>
                    @endif
                </div>
                <div class="portal-greeting">Halo, {{ auth()->user()->name }}</div>
                <h1 class="portal-title">Selamat Datang di Portal</h1>
                <p class="portal-subtitle">Pilih modul aplikasi yang ingin Anda akses hari ini.</p>
            </header>

            {{-- -- SISTEM UTAMA -- --}}
            <div class="section-divider fade-in-up" style="animation-delay: 0.1s">
                <div class="section-label">Sistem Utama</div>
            </div>

            <div class="modules-grid mb-5">
                {{-- SPMI Core --}}
                <a href="{{ route('dashboard') }}" class="module-card fade-in-up" style="--card-color-start: #4f46e5; --card-color-end: #3b82f6; animation-delay: 0.15s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="card-title">Penjaminan Mutu (SPMI)</h3>
                        <p class="card-desc">Sistem inti Penjaminan Mutu Internal. Kelola Dokumen, Standar, Siklus PPEPP, Audit Mutu, dan Evaluasi.</p>
                        <div class="card-meta">
                            <span class="card-tag">Core System</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>

                {{-- SDM & Kepegawaian --}}
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
                <a href="{{ route('sdm.pegawai.index') }}" class="module-card fade-in-up" style="--card-color-start: #0ea5e9; --card-color-end: #0284c7; animation-delay: 0.2s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-database-check"></i>
                        </div>
                        <h3 class="card-title">Data Master</h3>
                        <p class="card-desc">Pusat kelola Program Studi, dan Manajemen Periode aktif.</p>
                        <div class="card-meta">
                            <span class="card-tag">Master Data</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif
            </div>

            {{-- -- MODUL PENDUKUNG -- --}}
            <div class="section-divider fade-in-up" style="animation-delay: 0.25s">
                <div class="section-label">Modul Pendukung</div>
            </div>

            <div class="modules-grid">
                
                @if(auth()->user()->canAccessModule('data_akademik'))
                {{-- Data Akademik --}}
                <a href="{{ route('mahasiswa.index') }}" class="module-card fade-in-up" style="--card-color-start: #2563eb; --card-color-end: #1d4ed8; animation-delay: 0.22s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-person-video2"></i>
                        </div>
                        <h3 class="card-title">Data Akademik</h3>
                        <p class="card-desc">Pusat kelola Data Mahasiswa, Prestasi, dan Data Alumni terintegrasi.</p>
                        <div class="card-meta">
                            <span class="card-tag">Akademik</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- System Admin --}}
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('settings.index') }}" class="module-card fade-in-up" style="--card-color-start: #374151; --card-color-end: #1f2937; animation-delay: 0.23s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h3 class="card-title">System Admin</h3>
                        <p class="card-desc">Pusat konfigurasi aplikasi, manajemen pengguna (users), hak akses, dan log sistem.</p>
                        <div class="card-meta">
                            <span class="card-tag">Administrasi</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Tracer Study --}}
                @if(auth()->user()->canAccessModule('tracer_study'))
                <a href="{{ route('tracer-study.index') }}" class="module-card fade-in-up" style="--card-color-start: #10b981; --card-color-end: #059669; animation-delay: 0.3s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <h3 class="card-title">Tracer Study</h3>
                        <p class="card-desc">Pelacakan jejak alumni, pengelolaan data lulusan, masa tunggu kerja, dan survei kepuasan.</p>
                        <div class="card-meta">
                            <span class="card-tag">Alumni</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Manajemen Rapat --}}
                @if(auth()->user()->canAccessModule('rapat'))
                <a href="{{ route('rapat.index') }}" class="module-card fade-in-up" style="--card-color-start: #f59e0b; --card-color-end: #d97706; animation-delay: 0.35s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <h3 class="card-title">Manajemen Rapat</h3>
                        <p class="card-desc">Kelola jadwal rapat, notulensi, absensi kehadiran, dan tindak lanjut hasil rapat penjaminan mutu.</p>
                        <div class="card-meta">
                            <span class="card-tag">Administrasi</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- SDM & Kepegawaian --}}
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
                <a href="{{ route('sdm.index') }}" class="module-card fade-in-up" style="--card-color-start: #0ea5e9; --card-color-end: #0284c7; animation-delay: 0.38s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="card-title">SDM & Kepegawaian</h3>
                        <p class="card-desc">Manajemen Pegawai, presensi, cuti, lembur, penilaian kinerja, dan surat tugas pegawai.</p>
                        <div class="card-meta">
                            <span class="card-tag">SDM</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Surat Keputusan --}}
                @if(auth()->user()->canAccessModule('system_admin'))
                <a href="{{ route('surat-keputusan.index') }}" class="module-card fade-in-up" style="--card-color-start: #8b5cf6; --card-color-end: #7c3aed; animation-delay: 0.4s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="card-title">Manajemen Surat</h3>
                        <p class="card-desc">Mengelola Surat Keluar dan Surat Masuk untuk berbagai keperluan administrasi perguruan tinggi.</p>
                        <div class="card-meta">
                            <span class="card-tag">Administrasi</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Kerjasama --}}
                @if(auth()->user()->canAccessModule('kerjasama'))
                <a href="{{ route('kerjasama.index') }}" class="module-card fade-in-up" style="--card-color-start: #ec4899; --card-color-end: #db2777; animation-delay: 0.45s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h3 class="card-title">Kerjasama & Mitra</h3>
                        <p class="card-desc">Pendataan MoU, MoA, dan IA dengan mitra dalam negeri maupun luar negeri.</p>
                        <div class="card-meta">
                            <span class="card-tag">Kemitraan</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Tridharma --}}
                @if(auth()->user()->canAccessModule('tridharma'))
                <a href="{{ route('penelitian.index') }}" class="module-card fade-in-up" style="--card-color-start: #f43f5e; --card-color-end: #e11d48; animation-delay: 0.5s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h3 class="card-title">Tridharma Dosen</h3>
                        <p class="card-desc">Sistem rekapitulasi data Penelitian, Pengabdian Masyarakat (PkM), Publikasi Ilmiah, dan HKI.</p>
                        <div class="card-meta">
                            <span class="card-tag">Dosen</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

                {{-- Manajemen Aset --}}
                @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
                <a href="{{ route('aset.index') }}" class="module-card fade-in-up" style="--card-color-start: #06b6d4; --card-color-end: #0891b2; animation-delay: 0.55s">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h3 class="card-title">Manajemen Aset</h3>
                        <p class="card-desc">Pengelolaan inventaris aset, pemeliharaan, peminjaman aset, dan booking ruangan institusi.</p>
                        <div class="card-meta">
                            <span class="card-tag">Sarana Prasarana</span>
                            <i class="bi bi-arrow-right-short card-arrow"></i>
                        </div>
                    </div>
                </a>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

