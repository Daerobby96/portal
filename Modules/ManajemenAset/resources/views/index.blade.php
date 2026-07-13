@extends('manajemenaset::layouts.master')

@section('title', 'Manajemen Aset & Sarana Prasarana')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard Aset</li>
@endsection

@section('content')
@include('manajemenaset::components.stat-card-styles')

<div class="container-fluid px-4 py-4">
    
    {{-- Header Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-[#131b2e] to-slate-900 p-6 text-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] mb-6 group border border-slate-800/40">
        <div class="absolute -right-6 -top-6 h-36 w-36 rounded-full bg-primary/10 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
        <div class="absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-emerald-500/5 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
        
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-400 mb-3.5">
                    <span class="relative d-flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Modul Aktif
                </div>
                <h5 class="text-2xl font-extrabold mb-1 tracking-tight">
                    Manajemen Aset & Sarana Prasarana
                </h5>
                <p class="text-slate-300 text-sm flex flex-wrap items-center gap-2 mt-2">
                    Sistem informasi pengelolaan aset dan fasilitas institusi
                </p>
            </div>
            @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
            <div class="flex gap-2">
                <a href="{{ route('aset.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary to-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/25 transition-all hover:shadow-xl hover:shadow-primary/35 hover:-translate-y-0.5 active:translate-y-0 border-0 text-decoration-none">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Aset Baru</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Aset</div>
                        <div class="stat-value">{{ $stats['total_aset'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Aset Aktif</div>
                        <div class="stat-value">{{ $stats['aktif'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Perbaikan</div>
                        <div class="stat-value">{{ $stats['perbaikan'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Rusak</div>
                        <div class="stat-value">{{ $stats['rusak'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
        <a href="{{ route('aset.create') }}" class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-t-primary text-decoration-none h-100">
            <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-primary/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="flex flex-col items-center justify-center text-center relative z-10 h-100">
                <div class="rounded-xl bg-primary/10 p-3 text-primary transition-colors group-hover:bg-primary group-hover:text-white mb-3">
                    <i class="bi bi-plus-lg text-2xl"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-1">Tambah Aset</h6>
                <span class="text-xs text-slate-400">Inventaris baru</span>
            </div>
        </a>
        @endif
        
        <a href="{{ route('peminjaman.create') }}" class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-t-info text-decoration-none h-100">
            <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-info/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="flex flex-col items-center justify-center text-center relative z-10 h-100">
                <div class="rounded-xl bg-info/10 p-3 text-info transition-colors group-hover:bg-info group-hover:text-white mb-3">
                    <i class="bi bi-arrow-left-right text-2xl"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-1">Pinjam Aset</h6>
                <span class="text-xs text-slate-400">Ajukan peminjaman</span>
            </div>
        </a>
        
        <a href="{{ route('booking-ruangan.create') }}" class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-t-success text-decoration-none h-100">
            <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-success/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="flex flex-col items-center justify-center text-center relative z-10 h-100">
                <div class="rounded-xl bg-success/10 p-3 text-success transition-colors group-hover:bg-success group-hover:text-white mb-3">
                    <i class="bi bi-calendar-check text-2xl"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-1">Booking Ruangan</h6>
                <span class="text-xs text-slate-400">Pesan ruangan</span>
            </div>
        </a>

        @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
        <a href="{{ route('ruangan.create') }}" class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all hover:-translate-y-1 hover:shadow-xl border-t-4 border-t-warning text-decoration-none h-100">
            <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-warning/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="flex flex-col items-center justify-center text-center relative z-10 h-100">
                <div class="rounded-xl bg-warning/10 p-3 text-warning transition-colors group-hover:bg-warning group-hover:text-white mb-3">
                    <i class="bi bi-door-open text-2xl"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-1">Tambah Ruangan</h6>
                <span class="text-xs text-slate-400">Data ruangan baru</span>
            </div>
        </a>
        @endif
    </div>

    <!-- Main Menu Grid -->
    <div class="row g-4 mb-4">
        @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
        <div class="col-md-4">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-xl bg-primary/10 p-3 me-3 text-primary">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-bold text-slate-800">Inventaris Aset</h5>
                            <small class="text-slate-400">Kelola data aset institusi</small>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">
                        Sistem pencatatan dan pengelolaan aset meliputi elektronik, peralatan, furniture, dan inventaris lainnya.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('aset.index') }}" class="btn btn-sm btn-primary rounded-xl px-3">
                            <i class="bi bi-box-seam me-1"></i>Lihat Aset
                        </a>
                        <a href="{{ route('kategori-aset.index') }}" class="btn btn-sm btn-outline-primary rounded-xl px-3 border-primary/20 hover:border-primary">
                            <i class="bi bi-tags me-1"></i>Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-xl bg-warning/10 p-3 me-3 text-warning">
                            <i class="bi bi-tools fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-bold text-slate-800">Pemeliharaan</h5>
                            <small class="text-slate-400">Perawatan & perbaikan aset</small>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">
                        Pencatatan riwayat pemeliharaan preventif, korektif, kalibrasi, dan inspeksi aset.
                    </p>
                    <a href="{{ route('pemeliharaan.index') }}" class="btn btn-sm btn-warning text-white rounded-xl px-3">
                        <i class="bi bi-tools me-1"></i>Lihat Pemeliharaan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-xl bg-success/10 p-3 me-3 text-success">
                            <i class="bi bi-door-open fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-bold text-slate-800">Data Ruangan</h5>
                            <small class="text-slate-400">Kelola ruangan & fasilitas</small>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">
                        Pengelolaan data ruangan meliputi kelas, lab, ruang rapat, dan fasilitas lainnya.
                    </p>
                    <a href="{{ route('ruangan.index') }}" class="btn btn-sm btn-success rounded-xl px-3">
                        <i class="bi bi-door-open me-1"></i>Lihat Ruangan
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-xl bg-info/10 p-3 me-3 text-info">
                            <i class="bi bi-arrow-left-right fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-bold text-slate-800">Peminjaman Aset</h5>
                            <small class="text-slate-400">Kelola peminjaman aset</small>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">
                        Sistem peminjaman aset dengan approval workflow untuk memastikan aset terkelola dengan baik.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-info text-white rounded-xl px-3">
                            <i class="bi bi-list-ul me-1"></i>Riwayat
                        </a>
                        <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-outline-info rounded-xl px-3 border-info/20 hover:border-info">
                            <i class="bi bi-plus-lg me-1"></i>Ajukan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-xl bg-purple-500/10 p-3 me-3 text-purple-600">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-bold text-slate-800">Booking Ruangan</h5>
                            <small class="text-slate-400">Pemesanan ruangan</small>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm mb-4">
                        Sistem booking ruangan dengan deteksi konflik jadwal otomatis dan approval workflow.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('booking-ruangan.index') }}" class="btn btn-sm bg-purple-600 text-white rounded-xl px-3 hover:bg-purple-700">
                            <i class="bi bi-calendar-event me-1"></i>Jadwal
                        </a>
                        <a href="{{ route('booking-ruangan.create') }}" class="btn btn-sm text-purple-600 border border-purple-200 rounded-xl px-3 hover:bg-purple-50">
                            <i class="bi bi-plus-lg me-1"></i>Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-primary overflow-hidden relative">
                <div class="absolute -right-6 -bottom-6 h-32 w-32 rounded-full bg-primary/5 blur-xl"></div>
                <div class="card-body p-5 relative z-10">
                    <h5 class="font-bold text-slate-800 mb-4">
                        <i class="bi bi-info-circle text-primary me-2"></i>Tentang Modul Manajemen Aset
                    </h5>
                    <p class="text-slate-500 mb-4">
                        Modul ini menyediakan sistem informasi terintegrasi untuk pengelolaan aset dan sarana prasarana institusi, meliputi:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2 mt-1"></i>
                            <div>
                                <strong class="text-slate-800">Inventarisasi Aset</strong>
                                <p class="text-xs text-slate-500 mt-1 mb-0">Pencatatan lengkap aset dengan QR Code</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2 mt-1"></i>
                            <div>
                                <strong class="text-slate-800">Pemeliharaan</strong>
                                <p class="text-xs text-slate-500 mt-1 mb-0">Riwayat perawatan dan perbaikan</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2 mt-1"></i>
                            <div>
                                <strong class="text-slate-800">Peminjaman</strong>
                                <p class="text-xs text-slate-500 mt-1 mb-0">Workflow approval otomatis</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-2 mt-1"></i>
                            <div>
                                <strong class="text-slate-800">Booking Ruangan</strong>
                                <p class="text-xs text-slate-500 mt-1 mb-0">Deteksi konflik jadwal otomatis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
}
</style>
@endpush
@endsection
