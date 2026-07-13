@extends('layouts.app')

@section('title', 'Dashboard Integrasi Data')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Integrasi Data</li>
@endsection
@section('page-title', 'Dashboard Integrasi Data')
@section('page-subtitle', 'Data terintegrasi dari semua modul sistem')

@section('content')
<div class="container-fluid px-4">

    <!-- Filter Periode -->
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="{{ route('spmi.integration.dashboard') }}" class="d-flex gap-2">
                <select name="periode_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Data --</option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->id }}" {{ $periode && $periode->id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @if($periode)
                <a href="{{ route('spmi.integration.dashboard') }}" class="btn btn-outline-secondary" title="Reset Filter">
                    <i class="bi bi-x"></i>
                </a>
                @endif
            </form>
        </div>
        <div class="col-md-8">
            @if($periode)
            <div class="alert alert-info mb-0">
                <i class="bi bi-funnel me-2"></i>
                Filter: <strong>{{ $periode->nama }}</strong> ({{ $periode->tanggal_mulai->locale('id')->translatedFormat('d M Y') }} - {{ $periode->tanggal_selesai->locale('id')->translatedFormat('d M Y') }})
            </div>
            @endif
        </div>
    </div>

    <!-- Summary Cards Top -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-primary bg-opacity-10 p-2">
                                <i class="bi bi-people-fill text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Mahasiswa</div>
                            <h4 class="mb-0">{{ number_format($integratedData['mahasiswa']['total']) }}</h4>
                            <small class="text-success"><i class="bi bi-check-circle"></i> {{ number_format($integratedData['mahasiswa']['aktif']) }} Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-success bg-opacity-10 p-2">
                                <i class="bi bi-person-badge text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Pegawai</div>
                            <h4 class="mb-0">{{ number_format($integratedData['pegawai']['total']) }}</h4>
                            <small class="text-muted">{{ number_format($integratedData['pegawai']['dosen']) }} Dosen · {{ number_format($integratedData['pegawai']['tendik']) }} Tendik</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-warning bg-opacity-10 p-2">
                                <i class="bi bi-mortarboard text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Program Studi</div>
                            <h4 class="mb-0">{{ count($integratedData['mahasiswa']['by_prodi']) }}</h4>
                            <small class="text-muted">{{ number_format($integratedData['prestasi']['total']) }} Prestasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #06b6d4 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-info bg-opacity-10 p-2">
                                <i class="bi bi-graph-up text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Alumni</div>
                            <h4 class="mb-0">{{ number_format($integratedData['tracer_study']['total']) }}</h4>
                            <small class="text-muted">Tracer Study</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Akademik -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">
                <i class="bi bi-mortarboard text-primary me-2"></i>
                Data Akademik
            </h5>
        </div>
        <div class="card-body">
            @if($integratedData['mahasiswa']['available'] || $integratedData['pegawai']['available'])
            <div class="row g-3">
                @if($integratedData['mahasiswa']['available'])
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <i class="bi bi-people fs-3 text-primary mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['mahasiswa']['total']) }}</h3>
                        <div class="text-muted small">Total Mahasiswa</div>
                        <div class="mt-2">
                            <span class="badge bg-success">{{ number_format($integratedData['mahasiswa']['aktif']) }} Aktif</span>
                        </div>
                    </div>
                </div>
                @endif

                @if($integratedData['pegawai']['available'])
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <i class="bi bi-person-workspace fs-3 text-success mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['pegawai']['dosen']) }}</h3>
                        <div class="text-muted small">Dosen</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded text-center">
                        <i class="bi bi-person-badge fs-3 text-info mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['pegawai']['tendik']) }}</h3>
                        <div class="text-muted small">Tenaga Kependidikan</div>
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                <p>Data akademik belum tersedia</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Tridharma -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">
                <i class="bi bi-book text-success me-2"></i>
                Tridharma Perguruan Tinggi
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-3 border rounded h-100">
                        <i class="bi bi-journal-richtext text-info fs-2 mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['penelitian']['total']) }}</h3>
                        <div class="text-muted small">Penelitian</div>
                        @if($integratedData['penelitian']['total'] == 0)
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> Belum ada data</small>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-3 border rounded h-100">
                        <i class="bi bi-people text-success fs-2 mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['pengabdian']['total']) }}</h3>
                        <div class="text-muted small">Pengabdian</div>
                        @if($integratedData['pengabdian']['total'] == 0)
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> Belum ada data</small>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-3 border rounded h-100">
                        <i class="bi bi-file-earmark-text text-primary fs-2 mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['publikasi']['total']) }}</h3>
                        <div class="text-muted small">Publikasi</div>
                        @if($integratedData['publikasi']['total'] == 0)
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> Belum ada data</small>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-3 border rounded h-100">
                        <i class="bi bi-shield-check text-warning fs-2 mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['hki']['total']) }}</h3>
                        <div class="text-muted small">HKI</div>
                        @if($integratedData['hki']['total'] == 0)
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> Belum ada data</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kerjasama & Tracer Study -->
    <div class="row g-4 mb-4">
        <!-- Kerjasama -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-briefcase text-info me-2"></i>
                        Kerjasama
                    </h5>
                </div>
                <div class="card-body">
                    @if($integratedData['kerjasama']['available'])
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 border rounded">
                                <div class="text-muted small mb-1">Total Kerjasama</div>
                                <h3 class="mb-0">{{ number_format($integratedData['kerjasama']['total']) }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-success bg-opacity-10">
                                <div class="text-muted small mb-1">Aktif</div>
                                <h3 class="mb-0 text-success">{{ number_format($integratedData['kerjasama']['aktif']) }}</h3>
                            </div>
                        </div>
                    </div>
                    @if(!empty($integratedData['kerjasama']['by_tingkat']))
                    <div class="mt-3">
                        <small class="text-muted d-block mb-2">Berdasarkan Tingkat:</small>
                        @foreach($integratedData['kerjasama']['by_tingkat'] as $tingkat => $count)
                        <span class="badge bg-secondary me-1">{{ ucfirst($tingkat) }}: {{ $count }}</span>
                        @endforeach
                    </div>
                    @endif
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Modul Kerjasama belum tersedia
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tracer Study -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        Tracer Study Alumni
                    </h5>
                    @if($integratedData['tracer_study']['available'] && $integratedData['tracer_study']['total'] > 0)
                    <a href="{{ route('tracer-study.index') }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-arrow-right-circle me-1"></i>
                        Detail
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($integratedData['tracer_study']['available'])
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 border rounded">
                                <div class="text-muted small mb-1">Total Responden</div>
                                <h3 class="mb-0">{{ number_format($integratedData['tracer_study']['total']) }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-success bg-opacity-10">
                                <div class="text-muted small mb-1">Bekerja</div>
                                <h3 class="mb-0 text-success">{{ number_format($integratedData['tracer_study']['bekerja']) }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-primary bg-opacity-10">
                                <div class="text-muted small mb-1">Wirausaha</div>
                                <h3 class="mb-0 text-primary">{{ number_format($integratedData['tracer_study']['wirausaha']) }}</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-info bg-opacity-10">
                                <div class="text-muted small mb-1">Studi Lanjut</div>
                                <h3 class="mb-0 text-info">{{ number_format($integratedData['tracer_study']['studi_lanjut']) }}</h3>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada data alumni</p>
                        <a href="{{ route('tracer-study.index') }}" class="btn btn-sm btn-outline-success mt-2">
                            <i class="bi bi-plus-circle me-1"></i>
                            Import Data
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Aset & Sarana Prasarana -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">
                <i class="bi bi-box-seam text-warning me-2"></i>
                Aset & Sarana Prasarana
            </h5>
        </div>
        <div class="card-body">
            @if($integratedData['aset']['available'])
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <i class="bi bi-box-seam fs-3 text-warning mb-2"></i>
                        <h3 class="mb-1">{{ number_format($integratedData['aset']['total']) }}</h3>
                        <div class="text-muted small">Total Aset</div>
                    </div>
                </div>
                <div class="col-md-8">
                    @if(!empty($integratedData['aset']['by_kondisi']))
                    <div class="text-muted small mb-2">Berdasarkan Kondisi:</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($integratedData['aset']['by_kondisi'] as $kondisi => $count)
                        <span class="badge {{ $kondisi == 'baik' ? 'bg-success' : ($kondisi == 'rusak' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($kondisi) }}: {{ $count }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-inbox fs-1 opacity-50"></i>
                        <p class="mb-0 small">Belum ada data aset</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Modul Manajemen Aset belum tersedia
            </div>
            @endif
        </div>
    </div>

</div>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
h3, h4 {
    font-weight: 700;
}
.border-rounded {
    border-radius: 0.5rem;
}
.opacity-50 {
    opacity: 0.5;
}
</style>
@endsection
