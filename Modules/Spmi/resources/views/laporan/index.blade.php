@extends('layouts.app')

@section('title', 'Laporan SPMI')
@section('page-title', 'Laporan SPMI')
@section('page-subtitle', 'Ringkasan dan laporan Sistem Penjaminan Mutu Internal')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Filter Periode --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-1">
            <div class="card-body p-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label text-slate-500 font-semibold small text-uppercase mb-2">Filter Periode</label>
                        <select name="periode_id" class="form-select border-slate-200 rounded-xl py-2 px-3 text-slate-700">
                            <option value="">Semua Periode</option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->tahun }} - {{ $p->semester ?? 'Semester ' . $p->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary rounded-xl px-4 py-2 font-bold text-sm">
                            <i class="bi bi-filter me-1.5"></i>Filter
                        </button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary rounded-xl px-4 py-2 font-bold text-sm border-slate-200">
                            <i class="bi bi-arrow-counterclockwise me-1.5"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Ringkasan Statistik (Luxury Stat Cards with glowing orbs & left colored border) --}}
    {{-- Card 1: Total Audit --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-blue-600">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">Total Audit</span>
                        <h3 class="fw-extrabold text-slate-800 mt-1 mb-0">{{ $ringkasan['audit']['total'] }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                        <i class="bi bi-clipboard-check fs-5"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">{{ $ringkasan['audit']['selesai'] }} selesai</span>
                    <a href="{{ route('laporan.audit') }}" class="text-xs font-bold text-blue-600 text-decoration-none hover:underline d-flex align-items-center gap-1">
                        Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Total Temuan --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-red-500">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-red-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">Total Temuan</span>
                        <h3 class="fw-extrabold text-slate-800 mt-1 mb-0">{{ $ringkasan['temuan']['total'] }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-red-50 text-red-500">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">{{ $ringkasan['temuan']['open'] }} open</span>
                    <a href="{{ route('laporan.audit') }}" class="text-xs font-bold text-red-500 text-decoration-none hover:underline d-flex align-items-center gap-1">
                        Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Total Dokumen --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-emerald-500">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">Total Dokumen</span>
                        <h3 class="fw-extrabold text-slate-800 mt-1 mb-0">{{ $ringkasan['dokumen']['total'] }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="bi bi-file-earmark-text fs-5"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">{{ $ringkasan['dokumen']['approved'] }} approved</span>
                    <a href="{{ route('laporan.dokumen') }}" class="text-xs font-bold text-emerald-600 text-decoration-none hover:underline d-flex align-items-center gap-1">
                        Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Monitoring --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-indigo-500">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-indigo-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex flex-column justify-content-between h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">Monitoring</span>
                        <h3 class="fw-extrabold text-slate-800 mt-1 mb-0">{{ $ringkasan['monitoring']['total'] }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">{{ $ringkasan['monitoring']['tercapai'] }} tercapai</span>
                    <a href="{{ route('laporan.monitoring') }}" class="text-xs font-bold text-indigo-600 text-decoration-none hover:underline d-flex align-items-center gap-1">
                        Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Laporan --}}
    <div class="col-12 mt-5">
        <h5 class="mb-1 text-slate-800 font-extrabold tracking-tight">Jenis Laporan Tersedia</h5>
        <p class="text-xs text-slate-400 mb-0">Silakan pilih format laporan penjaminan mutu yang ingin diakses atau diunduh</p>
    </div>

    {{-- Grid Laporan Jenis --}}
    {{-- Card Laporan Audit --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100/50">
            <div class="card-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3.5 rounded-2xl bg-blue-50/70 text-blue-600 mb-3.5">
                    <i class="bi bi-clipboard-check fs-3"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-2">Laporan Audit</h6>
                <p class="text-slate-400 text-xs mb-4">
                    Laporan hasil audit internal meliputi temuan, kategori, dan status tindak lanjut.
                </p>
                <a href="{{ route('laporan.audit') }}" class="btn btn-outline-primary rounded-xl text-xs font-bold px-4 py-2.5 border-slate-200 hover:border-primary">
                    <i class="bi bi-eye me-1.5"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    {{-- Card Laporan Dokumen --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100/50">
            <div class="card-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3.5 rounded-2xl bg-emerald-50/70 text-emerald-600 mb-3.5">
                    <i class="bi bi-file-earmark-text fs-3"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-2">Laporan Dokumen</h6>
                <p class="text-slate-400 text-xs mb-4">
                    Laporan dokumen mutu berdasarkan kategori, standar, dan status persetujuan.
                </p>
                <a href="{{ route('laporan.dokumen') }}" class="btn btn-outline-success rounded-xl text-xs font-bold px-4 py-2.5 border-slate-200 hover:border-success">
                    <i class="bi bi-eye me-1.5"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    {{-- Card Laporan Monitoring --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100/50">
            <div class="card-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3.5 rounded-2xl bg-cyan-50/70 text-cyan-600 mb-3.5">
                    <i class="bi bi-graph-up-arrow fs-3"></i>
                </div>
                <h6 class="font-bold text-slate-800 mb-2">Laporan Monitoring</h6>
                <p class="text-slate-400 text-xs mb-4">
                    Laporan pelaksanaan dan evaluasi capaian indikator kinerja.
                </p>
                <a href="{{ route('laporan.monitoring') }}" class="btn btn-outline-info rounded-xl text-xs font-bold px-4 py-2.5 border-slate-200 hover:border-info">
                    <i class="bi bi-eye me-1.5"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    {{-- Card Laporan EDOM --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100/50">
            <div class="card-body text-center p-4 d-flex flex-column justify-content-between h-100">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center p-3.5 rounded-2xl bg-amber-50/70 text-amber-600 mb-3.5">
                        <i class="bi bi-person-badge fs-3"></i>
                    </div>
                    <h6 class="font-bold text-slate-800 mb-2">Laporan EDOM</h6>
                    <p class="text-slate-400 text-xs mb-4">
                        Laporan hasil Evaluasi Dosen Oleh Mahasiswa (EDOM) dan kinerja dosen.
                    </p>
                </div>
                <div>
                    <a href="{{ route('laporan.export.pdf', ['type' => 'edom', 'periode_id' => request('periode_id')]) }}" class="btn btn-outline-dark rounded-xl text-xs font-bold px-4 py-2 w-100 mb-2 border-slate-200" target="_blank">
                        <i class="bi bi-file-pdf me-1.5 text-danger"></i>Cetak PDF Resmi
                    </a>
                    <a href="{{ route('kinerja-dosen.index') }}" class="text-xs font-bold text-primary text-decoration-none hover:underline d-inline-block">
                        Lihat Dashboard EDOM
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Buku Laporan AMI --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-gradient-to-br from-slate-900 via-[#1e293b] to-slate-900 text-white shadow-lg h-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-danger-500/10 blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
            <div class="card-body text-center p-4 relative z-10 d-flex flex-column justify-content-between h-100">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center p-3.5 rounded-2xl bg-danger-500/15 text-danger-400 mb-3.5 border border-danger-500/25">
                        <i class="bi bi-book fs-3"></i>
                    </div>
                    <h6 class="font-bold text-white mb-2">Buku Laporan AMI</h6>
                    <p class="text-slate-300 text-xs mb-4">
                        Kompilasi lengkap seluruh siklus PPEPP (Penetapan hingga Peningkatan) dalam 1 Dokumen PDF.
                    </p>
                </div>
                <a href="{{ route('laporan.export.pdf', ['type' => 'buku-ami', 'periode_id' => request('periode_id')]) }}" class="btn btn-danger rounded-xl text-xs font-bold w-100 py-2.5 shadow-lg shadow-danger/20 border-0" target="_blank">
                    <i class="bi bi-magic me-1.5"></i>Generate 1-Klik
                </a>
            </div>
        </div>
    </div>

    {{-- Section Khusus: Dokumen Resmi Institusi SPMI (Siap Cetak PDF / A4) --}}
    <div class="col-12 mt-2">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
                <h5 class="font-extrabold text-slate-800 mb-0">Dokumen Resmi Institusi POLKA (A4)</h5>
                <p class="text-slate-400 text-xs mb-0">Format laporan resmi standar institusi lengkap dengan cover, bab, matriks temuan, dan lembar pengesahan.</p>
            </div>
            <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-full font-bold text-xs">
                <i class="bi bi-shield-check me-1"></i>Standar SPMI POLKA
            </span>
        </div>
    </div>

    {{-- Card 1: Laporan AMI --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-emerald-500">
            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-2xl bg-emerald-50 text-emerald-600 mb-3">
                        <i class="bi bi-clipboard2-check-fill fs-4"></i>
                    </div>
                    <h6 class="font-bold text-slate-900 mb-1.5">Laporan AMI (Audit Mutu Internal)</h6>
                    <p class="text-slate-500 text-xs mb-3">
                        Laporan komprehensif 11 bagian: Identitas, 19 Unit, Dasar Hukum, Jadwal, Matriks 31 Standar Temuan, dan Rekomendasi.
                    </p>
                </div>
                <a href="{{ route('cetak.berita-acara-ami', 1) }}" target="_blank" class="btn btn-emerald rounded-xl text-xs font-bold w-100 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm border-0">
                    <i class="bi bi-file-earmark-pdf-fill me-1.5"></i>Cetak Laporan AMI (A4)
                </a>
            </div>
        </div>
    </div>

    {{-- Card 2: Laporan RTL --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-teal-500">
            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-2xl bg-teal-50 text-teal-600 mb-3">
                        <i class="bi bi-arrow-repeat fs-4"></i>
                    </div>
                    <h6 class="font-bold text-slate-900 mb-1.5">Laporan RTL (Rencana Tindak Lanjut)</h6>
                    <p class="text-slate-500 text-xs mb-3">
                        Rencana tindakan koreksi atas hasil temuan AMI untuk 31 Standar Mutu (Pendidikan, Penelitian, PkM, dan Standar Tambahan).
                    </p>
                </div>
                <a href="{{ route('cetak.laporan-rtl') }}" target="_blank" class="btn btn-teal rounded-xl text-xs font-bold w-100 py-2.5 bg-teal-600 text-white hover:bg-teal-700 shadow-sm border-0">
                    <i class="bi bi-file-earmark-pdf-fill me-1.5"></i>Cetak Laporan RTL (A4)
                </a>
            </div>
        </div>
    </div>

    {{-- Card 3: Laporan RTM --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-indigo-500">
            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-2xl bg-indigo-50 text-indigo-600 mb-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <h6 class="font-bold text-slate-900 mb-1.5">Laporan RTM (Tinjauan Manajemen)</h6>
                    <p class="text-slate-500 text-xs mb-3">
                        Risalah kebijakan mutu tertinggi institusi: Redistribusi tanggung jawab tindak lanjut, strategi deploymen, dan keputusan direksi.
                    </p>
                </div>
                <a href="{{ route('cetak.laporan-rtm') }}" target="_blank" class="btn btn-indigo rounded-xl text-xs font-bold w-100 py-2.5 bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm border-0">
                    <i class="bi bi-file-earmark-pdf-fill me-1.5"></i>Cetak Laporan RTM (A4)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection