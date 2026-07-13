@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Sambutan --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-[#131b2e] to-slate-900 p-6 text-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] mb-6 group border border-slate-800/40">
    <!-- Floating dynamic background lights -->
    <div class="absolute -right-6 -top-6 h-36 w-36 rounded-full bg-primary/10 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
    <div class="absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-emerald-500/5 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
    
    <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-400 mb-3.5">
                <span class="relative d-flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Sistem Terkoneksi & Aktif
            </div>
            <h5 class="text-2xl font-extrabold mb-1 tracking-tight">
                Selamat datang kembali, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">{{ auth()->user()->name }}</span>!
            </h5>
            @php
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tanggal = $hari[now()->dayOfWeek] . ', ' . now()->day . ' ' . $bulan[now()->month] . ' ' . now()->year;
            @endphp
            <p class="text-slate-300 text-sm flex flex-wrap items-center gap-2 mt-2">
                <span class="inline-flex items-center gap-1.5 bg-slate-800/60 px-3 py-1.5 rounded-xl border border-slate-700/50">
                    <i class="bi bi-calendar3 text-primary"></i>
                    {{ $tanggal }}
                </span>
                @if($periode)
                    <span class="inline-flex items-center gap-1.5 bg-slate-800/60 px-3 py-1.5 rounded-xl border border-slate-700/50">
                        <i class="bi bi-bookmark-star text-amber-400"></i>
                        Periode Aktif: <strong class="text-white">{{ $periode->nama }}</strong>
                    </span>
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('scan.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary to-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/25 transition-all hover:shadow-xl hover:shadow-primary/35 hover:-translate-y-0.5 active:translate-y-0 border-0">
                <i class="bi bi-qr-code-scan"></i>
                <span>Scan QR Lapangan</span>
            </a>
        </div>
    </div>
</div>

@if($ppeppDetails['is_loop_closed'])
{{-- Celebratory Loop-Closed Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-500 p-6 text-white shadow-[0_10px_30px_rgba(16,185,129,0.15)] mb-6 group border border-emerald-500/30 animate-fade-in">
    <div class="absolute -right-6 -top-6 h-36 w-36 rounded-full bg-white/10 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
    <div class="relative z-10 d-flex flex-wrap items-center justify-between gap-4">
        <div class="d-flex align-items-center gap-4">
            <div class="p-3 rounded-2xl bg-white/20 text-white fs-3 shadow-inner d-none d-md-block animate-bounce" style="animation-duration: 3s;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div>
                <h5 class="text-xl font-extrabold mb-1 tracking-tight">🏆 Siklus PPEPP Selesai & Ditutup Sempurna!</h5>
                <p class="text-emerald-50 text-sm mb-0">
                    Sistem Penjaminan Mutu Internal (SPMI) untuk periode <strong>{{ $periode->nama }}</strong> telah sukses diselesaikan 100% (Full Loop Closed). Seluruh dokumen, audit, dan tindak lanjut telah diverifikasi oleh tim penjaminan mutu.
                </p>
            </div>
        </div>
        <div class="d-flex gap-2">
            @php
                $rtm = \App\Models\RTM::where('periode_id', $periode->id)->where('status', 'selesai')->latest()->first();
            @endphp
            @if($rtm)
            <a href="{{ route('rtm.export-pdf', $rtm) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-emerald-700 shadow-md transition-all hover:bg-emerald-50 hover:-translate-y-0.5 border-0">
                <i class="bi bi-download"></i>
                <span>Unduh Laporan Mutu</span>
            </a>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── Status Siklus PPEPP ── --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-6 overflow-hidden">
    <div class="p-4 py-3.5 border-b border-slate-100 d-flex justify-content-between align-items-center bg-white">
        <div>
            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-arrow-repeat me-2 text-primary fs-5"></i>Status Siklus PPEPP Periode Berjalan</h6>
            <span class="text-xs text-slate-400">Pantauan otomatis alur penjaminan mutu nasional</span>
        </div>
        <span class="badge bg-primary/10 text-primary rounded-xl px-3 py-2 text-xs font-bold border-0">
            <span class="relative d-inline-flex h-2 w-2 me-1.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
            </span>
            Real-time
        </span>
    </div>
    <div class="card-body p-4 p-lg-5">
        <div class="position-relative d-flex justify-content-between align-items-start w-100 ppepp-tracker">
            <!-- Line background -->
            <div class="ppepp-line position-absolute start-0 w-100" style="height: 4px; background: #f1f5f9; z-index: 1; top: 25px;"></div>
            
            @php
                $steps = [
                    ['id' => 'penetapan', 'label' => 'Penetapan', 'icon' => 'bi-file-earmark-check', 'desc' => 'Standar & IKU'],
                    ['id' => 'pelaksanaan', 'label' => 'Pelaksanaan', 'icon' => 'bi-play-circle', 'desc' => 'Monitoring'],
                    ['id' => 'evaluasi', 'label' => 'Evaluasi', 'icon' => 'bi-search', 'desc' => 'Audit Mutu'],
                    ['id' => 'pengendalian', 'label' => 'Pengendalian', 'icon' => 'bi-shield-check', 'desc' => 'Tindak Lanjut'],
                    ['id' => 'peningkatan', 'label' => 'Peningkatan', 'icon' => 'bi-graph-up-arrow', 'desc' => 'RTM']
                ];
            @endphp

            @foreach($steps as $index => $step)
                @php
                    $progress = $ppeppDetails[$step['id']] ?? 0;
                    $isCompleted = $progress >= 100;
                    $isActive = !$isCompleted && $progress > 0;
                    $isPending = $progress == 0;
                    
                    $statusClass = $isCompleted ? 'completed' : ($isActive ? 'active' : 'pending');
                    $colorClass = $isCompleted ? 'bg-emerald-500 shadow-emerald-500/20 text-white' : ($isActive ? 'bg-primary shadow-primary/20 text-white' : 'bg-slate-100 text-slate-400');
                    $textColor = $isCompleted || $isActive ? 'text-white' : 'text-slate-400';
                @endphp
                
                <div class="ppepp-step text-center position-relative {{ $statusClass }}" style="z-index: 2; flex: 1; min-width: 80px;">
                    <div class="step-icon-wrapper mx-auto mb-3 d-flex align-items-center justify-content-center shadow-lg {{ $colorClass }} {{ $textColor }} transition-all duration-300 animate-none" 
                          style="width: 52px; height: 52px; border-radius: 16px; border: 4px solid #fff;">
                        <i class="bi {{ $step['icon'] }} fs-5"></i>
                    </div>
                    <div class="step-label font-bold {{ $isCompleted || $isActive ? 'text-slate-800' : 'text-slate-400' }}" style="font-size: 0.88rem;">{{ $step['label'] }}</div>
                    <div class="step-desc text-slate-400 mt-1 d-none d-md-block" style="font-size: 0.74rem;">{{ $step['desc'] }}</div>
                    
                    {{-- Dynamic Progress Badges --}}
                    <div class="mt-2.5">
                        @if($isCompleted)
                            <span class="badge bg-emerald-50 text-emerald-600 border border-emerald-200/50 rounded-pill font-bold px-2 py-1.5 text-[10px] d-inline-flex align-items-center gap-1 shadow-none">
                                <i class="bi bi-check-circle-fill text-emerald-500"></i>100% Selesai
                            </span>
                        @elseif($isActive)
                            <span class="badge bg-blue-50 text-blue-600 border border-blue-200/50 rounded-pill font-bold px-2 py-1.5 text-[10px] d-inline-flex align-items-center gap-1 shadow-none">
                                <span class="relative d-flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-500"></span>
                                </span>
                                {{ $progress }}% Proses
                            </span>
                        @else
                            <span class="badge bg-slate-50 text-slate-400 border border-slate-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px]">
                                Menunggu
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Audit -->
    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-l-4 border-l-primary h-100">
        <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-primary/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="rounded-xl bg-primary/5 p-2.5 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="bi bi-clipboard2-check text-xl"></i>
            </div>
            <div class="text-3xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_audit'] }}</div>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 relative z-10">Total Audit</div>
        <div class="text-xs text-slate-400 relative z-10">{{ $stats['audit_aktif'] }} sedang berjalan</div>
    </div>

    <!-- Card 2: Total Temuan -->
    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-l-4 border-l-orange-500 h-100">
        <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-orange-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="rounded-xl bg-orange-50 p-2.5 text-orange-600 transition-colors group-hover:bg-orange-500 group-hover:text-white">
                <i class="bi bi-exclamation-triangle text-xl"></i>
            </div>
            <div class="text-3xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_temuan'] }}</div>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 relative z-10">Total Temuan</div>
        <div class="text-xs text-slate-400 relative z-10">{{ $stats['temuan_open'] }} belum ditangani</div>
    </div>

    <!-- Card 3: Dokumen Aktif -->
    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-l-4 border-l-emerald-500 h-100">
        <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-emerald-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                <i class="bi bi-folder2-open text-xl"></i>
            </div>
            <div class="text-3xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_dokumen'] }}</div>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 relative z-10">Dokumen Aktif</div>
        <div class="text-xs text-slate-400 relative z-10">{{ $stats['dokumen_kadaluarsa'] }} akan kadaluarsa</div>
    </div>

    <!-- Card 4: Data Monitoring -->
    <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-l-4 border-l-purple-500 h-100">
        <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-purple-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="rounded-xl bg-purple-50 p-2.5 text-purple-600 transition-colors group-hover:bg-purple-500 group-hover:text-white">
                <i class="bi bi-bar-chart-line text-xl"></i>
            </div>
            <div class="text-3xl font-extrabold tracking-tight text-slate-800">{{ $stats['total_monitoring'] }}</div>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 relative z-10">Data Monitoring</div>
        <div class="text-xs text-slate-400 relative z-10">{{ $stats['total_user'] }} pengguna aktif</div>
    </div>
</div>

{{-- ── Premium Charts ── --}}
<div class="row g-3 mb-4">
    {{-- Radar Chart: Keseimbangan Capaian --}}
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100">
            <div class="p-4 py-3 border-b border-slate-100 bg-white">
                <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-bullseye me-2 text-primary"></i>Keseimbangan Capaian</h6>
                <span class="text-xs text-slate-400">Rata-rata % capaian per standar</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <div style="width: 100%; height: 300px;">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Line Chart: Tren Performa Capaian --}}
    <div class="col-lg-8 col-md-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100">
            <div class="p-4 py-3 border-b border-slate-100 bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Tren Performa Capaian</h6>
                    <span class="text-xs text-slate-400">Perkembangan % rata-rata indikator</span>
                </div>
                <span class="badge bg-success-subtle text-success rounded-xl px-3 py-2 text-xs font-bold border-0">
                    <i class="bi bi-chevron-double-up me-1"></i>High Performance
                </span>
            </div>
            <div class="card-body p-4">
                <div style="width: 100%; height: 280px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Kelengkapan Dokumen (Full-Width Grid) ── --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <div class="p-4 py-3 border-b border-slate-100 bg-white">
        <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-files me-2 text-blue-600 fs-5"></i>Kelengkapan Dokumen</h6>
        <span class="text-xs text-slate-400">% Dokumen yang sudah Approved per Standar</span>
    </div>
    <div class="card-body p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($standarProgress as $sp)
            <div class="p-3.5 rounded-xl bg-slate-50/50 border border-slate-100/30 hover:bg-slate-50 hover:border-slate-100 transition-all">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small font-bold text-slate-700 text-truncate" style="max-width: 80%;" title="{{ $sp['nama'] }}">
                        {{ $sp['kode'] }} - {{ $sp['nama'] }}
                    </span>
                    <span class="small text-slate-400 font-semibold">{{ $sp['approved'] }}/{{ $sp['total'] }}</span>
                </div>
                <div class="progress mb-1.5" style="height: 6px; border-radius: 10px; background-color: #e2e8f0;">
                    <div class="progress-bar bg-blue-600 shadow-sm" role="progressbar" 
                         style="width: {{ $sp['percent'] }}%; border-radius: 10px;" 
                         aria-valuenow="{{ $sp['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="text-end">
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $sp['percent'] }}% Approved</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Temuan Kategori & Tren Row (Side-by-Side) ── --}}
<div class="row g-3 mb-4">
    {{-- Chart Temuan per Kategori --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100">
            <div class="p-4 py-3 border-b border-slate-100 bg-white">
                <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-pie-chart me-2 text-primary"></i>Temuan per Kategori</h6>
                <span class="text-xs text-slate-400">Pembagian temuan berdasarkan keparahan</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <div style="width: 100%; height: 260px;">
                    <canvas id="temuanChart" style="max-height:260px"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Tren Temuan --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100">
            <div class="p-4 py-3 border-b border-slate-100 bg-white">
                <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-bug me-2 text-danger"></i>Tren Temuan (Kualitas)</h6>
                <span class="text-xs text-slate-400">Total akumulasi temuan dari waktu ke waktu</span>
            </div>
            <div class="card-body p-4">
                <div style="width: 100%; height: 240px;">
                    <canvas id="trenChart" style="max-height:240px"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Temuan Deadline Terdekat --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden">
            <div class="p-4 py-3 border-b border-slate-100 bg-white d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-clock-history me-2 text-warning"></i>Temuan Deadline Terdekat</h6>
                    <span class="text-xs text-slate-400">Segera tindak lanjuti temuan yang mendekati tenggat</span>
                </div>
                <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-sm btn-outline-primary px-3 rounded-xl border-1 text-xs font-bold text-decoration-none">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($temuanDeadline as $t)
                <div class="deadline-item p-3 border-bottom hover:bg-slate-50/50 transition-colors">
                    <div class="d-flex align-items-start gap-3">
                        <div class="deadline-badge">
                            {!! $t->kategori_badge !!}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 small font-bold text-slate-700 text-truncate">{{ $t->uraian_temuan }}</p>
                            <p class="mb-0 text-slate-400" style="font-size:.75rem">
                                <i class="bi bi-clipboard2 me-1"></i>{{ $t->audit->nama_audit }}
                            </p>
                        </div>
                        <div class="text-end flex-shrink-0">
                            @php $daysLeft = now()->diffInDays($t->batas_tindak_lanjut, false); @endphp
                            <span class="badge rounded-xl px-2.5 py-1.5 text-xs font-bold border-0 {{ $daysLeft < 0 ? 'bg-danger/10 text-danger' : ($daysLeft <= 3 ? 'bg-warning/10 text-amber-600' : 'bg-slate-100 text-slate-500') }}">
                                {{ $daysLeft < 0 ? 'Terlambat ' . abs($daysLeft) . ' hari' : $daysLeft . ' hari lagi' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 py-5">
                    <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                    Tidak ada temuan yang mendekati deadline
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Dokumen Akan Kadaluarsa --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden">
            <div class="p-4 py-3 border-b border-slate-100 bg-white d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-file-earmark-diff me-2 text-danger"></i>Dokumen Akan Kadaluarsa</h6>
                    <span class="text-xs text-slate-400">Monitoring dokumen untuk perpanjangan masa berlaku</span>
                </div>
                <a href="{{ route('dokumen.index') }}" class="btn btn-sm btn-outline-primary px-3 rounded-xl border-1 text-xs font-bold text-decoration-none">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($listDokumenKadaluarsa as $doc)
                <div class="deadline-item p-3 border-bottom hover:bg-slate-50/50 transition-colors">
                    <div class="d-flex align-items-start gap-3">
                        <div class="d-flex h-10 w-10 items-center justify-center rounded-xl bg-danger/5 text-danger shadow-inner flex-shrink-0">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-1 small font-bold text-slate-700 text-truncate">{{ $doc->judul }}</p>
                            <p class="mb-0 text-slate-400" style="font-size:.75rem">
                                <i class="bi bi-building me-1"></i>{{ $doc->unit_pemilik }}
                            </p>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge rounded-xl px-2.5 py-1.5 text-xs font-bold border-0 {{ $doc->tanggal_kadaluarsa <= now() ? 'bg-danger/10 text-danger' : 'bg-warning/10 text-amber-600' }}">
                                {{ $doc->tanggal_kadaluarsa->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 py-5">
                    <i class="bi bi-shield-check fs-2 text-success d-block mb-2"></i>
                    Semua dokumen masih berlaku
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Audit Terbaru ── --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
    <div class="p-4 py-3 border-b border-slate-100 bg-white d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-clipboard2-check me-2 text-primary"></i>Audit Terbaru</h6>
            <span class="text-xs text-slate-400">Riwayat audit mutu internal terbaru</span>
        </div>
        <a href="{{ route('audit.index') }}" class="btn btn-sm btn-outline-primary px-3 rounded-xl border-1 text-xs font-bold text-decoration-none">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100">
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Kode Audit</th>
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Nama Audit</th>
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Unit Diaudit</th>
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Ketua Auditor</th>
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Tgl Audit</th>
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-slate-500 font-bold px-4 py-3 text-xs uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditTerbaru as $audit)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                        <td class="px-4 py-3"><code class="text-primary font-bold bg-primary/5 px-2.5 py-1 rounded-lg text-xs">{{ $audit->kode_audit }}</code></td>
                        <td class="px-4 py-3 text-slate-700 font-semibold small">{{ $audit->nama_audit }}</td>
                        <td class="px-4 py-3 text-slate-600 small">{{ $audit->unit_yang_diaudit }}</td>
                        <td class="px-4 py-3 text-slate-600 small">{{ $audit->ketuaAuditor->name }}</td>
                        <td class="px-4 py-3 text-slate-500 small">{{ $audit->tanggal_audit->translatedFormat('d F Y') }}</td>
                        <td class="px-4 py-3">{!! $audit->status_badge !!}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('audit.show', $audit) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-primary/10 hover:text-primary transition-colors text-decoration-none">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 py-5">
                            Belum ada data audit
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .progress-bar {
        transition: width 1.5s cubic-bezier(0.1, 0, 0.2, 1);
    }
    .bg-indigo { background-color: #2563eb !important; }
    .text-indigo { color: #2563eb !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; color: #16a34a !important; }
    canvas {
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.02));
    }
    
    /* PPEPP Tracker Styles */
    .ppepp-step .step-icon-wrapper {
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    }
    .ppepp-step.active .step-icon-wrapper {
        transform: scale(1.15);
        box-shadow: 0 0 0 4px rgba(var(--primary-color-rgb), 0.2) !important;
        animation: pulse-primary 2s infinite;
    }
    .ppepp-step.completed .step-icon-wrapper {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2) !important;
    }
    @keyframes pulse-primary {
        0% { box-shadow: 0 0 0 0 rgba(var(--primary-color-rgb), 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(var(--primary-color-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--primary-color-rgb), 0); }
    }
    @media (max-width: 768px) {
        .ppepp-tracker {
            flex-direction: column;
            gap: 30px;
            align-items: flex-start !important;
            padding-left: 20px;
        }
        .ppepp-line {
            width: 4px !important;
            height: 100% !important;
            left: 45px !important;
            top: 0 !important;
        }
        .ppepp-step { 
            width: 100% !important; 
            text-align: left !important;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .ppepp-step .step-icon-wrapper {
            margin-bottom: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .ppepp-step .step-label {
            margin-bottom: 0 !important;
        }
        .ppepp-step .badge {
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            transform: none !important;
            margin-top: 0 !important;
            margin-left: 10px !important;
        }
    }
</style>
@endpush

@endsection

@push('scripts')
<script>
    // Common configurations
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // Retrieve global brand colors dynamically from CSS custom properties!
    const themePrimary = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || '#2563eb';
    const themePrimaryRgb = getComputedStyle(document.documentElement).getPropertyValue('--primary-color-rgb').trim() || '37, 99, 235';

    // 1. Radar Chart: Keseimbangan Capaian
    const ctxRadar = document.getElementById('radarChart').getContext('2d');
    new Chart(ctxRadar, {
        type: 'radar',
        data: {
            labels: {!! json_encode($radarLabels) !!},
            datasets: [{
                label: 'Capaian %',
                data: {!! json_encode($radarData) !!},
                backgroundColor: `rgba(${themePrimaryRgb}, 0.15)`,
                borderColor: themePrimary,
                borderWidth: 2,
                pointBackgroundColor: themePrimary,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: themePrimary
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { display: true, color: 'rgba(226, 232, 240, 0.6)' },
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: { backdropColor: 'transparent', stepSize: 20, font: { size: 10 } }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Performance Trend Chart
    const ctxPerf = document.getElementById('performanceChart').getContext('2d');
    const perfGradient = ctxPerf.createLinearGradient(0, 0, 0, 300);
    perfGradient.addColorStop(0, 'rgba(16, 185, 129, 0.15)'); // soft green tint
    perfGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctxPerf, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{
                label: 'Rata-rata Capaian (%)',
                data: {!! json_encode($perfTrendData) !!},
                borderColor: '#10b981',
                backgroundColor: perfGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(226, 232, 240, 0.5)' },
                    ticks: { callback: value => value + '%', font: { size: 11 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold', family: "'Inter', sans-serif" },
                    bodyFont: { size: 12, family: "'Inter', sans-serif" },
                    cornerRadius: 12,
                    displayColors: false
                }
            }
        }
    });

    // 3. Temuan Chart (Doughnut)
    const ctxTemuan = document.getElementById('temuanChart').getContext('2d');
    new Chart(ctxTemuan, {
        type: 'doughnut',
        data: {
            labels: ['KTS Mayor', 'KTS Minor', 'Observasi', 'Rekomendasi'],
            datasets: [{
                data: [
                    {{ $temuanPerKategori['KTS_Mayor'] ?? 0 }},
                    {{ $temuanPerKategori['KTS_Minor'] ?? 0 }},
                    {{ $temuanPerKategori['OB'] ?? 0 }},
                    {{ $temuanPerKategori['Rekomendasi'] ?? 0 }},
                ],
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
                hoverOffset: 12,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true, boxWidth: 8, font: { size: 11 } }
                }
            }
        }
    });

    // 4. Tren Temuan Chart (Line)
    const ctxTren = document.getElementById('trenChart').getContext('2d');
    const trenGradient = ctxTren.createLinearGradient(0, 0, 0, 240);
    trenGradient.addColorStop(0, 'rgba(239, 68, 68, 0.12)');
    trenGradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

    new Chart(ctxTren, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{
                label: 'Jumlah Temuan',
                data: {!! json_encode($trenData) !!},
                borderColor: '#ef4444',
                backgroundColor: trenGradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#ef4444',
                pointBorderColor: '#fff',
                pointBorderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(226, 232, 240, 0.5)' },
                    ticks: { stepSize: 1, font: { size: 10 } } 
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush