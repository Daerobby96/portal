@extends('layouts.app')

@section('title', 'Analisa Kinerja IKU')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item active">Analisa Kinerja</li>
@endsection

@section('page-title', 'Analisa Kinerja IKU')
@section('page-subtitle', 'Evaluasi Capaian vs Target per Periode')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('iku-resmi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <a href="{{ route('iku-resmi.set-target', ['periode_id' => $periodeId]) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-bullseye me-1"></i> Set Target
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <!-- Periode Filter -->
    <div class="mb-4">
        <div class="dropdown d-inline-block">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-calendar-event me-1"></i> Periode: 
                {{ $periodes->firstWhere('id', $periodeId)?->nama ?? 'Pilih Periode' }}
            </button>
            <ul class="dropdown-menu">
                @foreach($periodes as $p)
                <li>
                    <a class="dropdown-item {{ $p->id == $periodeId ? 'active' : '' }}" 
                       href="?periode_id={{ $p->id }}">
                        {{ $p->nama }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-0">IKU Tercapai</p>
                            <h2 class="mb-0 fw-bold text-success">{{ $summary['tercapai'] }}</h2>
                            <small class="text-muted">dari {{ $summary['total'] }} IKU</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-0">Dalam Progress</p>
                            <h2 class="mb-0 fw-bold text-warning">{{ $summary['progress'] }}</h2>
                            <small class="text-muted">≥80% target</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="bi bi-x-circle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-0">Tidak Tercapai</p>
                            <h2 class="mb-0 fw-bold text-danger">{{ $summary['tidak_tercapai'] }}</h2>
                            <small class="text-muted"><80% target</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-percent text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-0">Rata-rata Capaian</p>
                            <h2 class="mb-0 fw-bold text-primary">{{ number_format($summary['rata_capaian'], 1) }}%</h2>
                            <small class="text-muted">dari target</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Detail Capaian IKU vs Target</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">No. IKU</th>
                            <th>Nama Indikator</th>
                            <th class="text-center" style="width: 100px;">Sifat</th>
                            <th class="text-center" style="width: 120px;">Target</th>
                            <th class="text-center" style="width: 120px;">Capaian</th>
                            <th class="text-center" style="width: 100px;">% Capaian</th>
                            <th class="text-center" style="width: 120px;">Gap</th>
                            <th class="text-center" style="width: 140px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ikuList as $iku)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">
                                    {{ $iku->nomor_iku }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $iku->nama }}</strong>
                            </td>
                            <td class="text-center">
                                {!! $iku->sifat_badge !!}
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($iku->target, 2, ',', '.') }}</strong>
                                <small class="text-muted d-block">{{ $iku->satuan }}</small>
                            </td>
                            <td class="text-center">
                                <strong class="fs-6">{{ number_format($iku->nilai_hasil, 2, ',', '.') }}</strong>
                                <small class="text-muted d-block">{{ $iku->satuan }}</small>
                            </td>
                            <td class="text-center">
                                @if($iku->persentase_capaian > 0)
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-{{ $iku->persentase_capaian >= 100 ? 'success' : ($iku->persentase_capaian >= 80 ? 'info' : ($iku->persentase_capaian >= 60 ? 'warning' : 'danger')) }}" 
                                         style="width: {{ min($iku->persentase_capaian, 100) }}%">
                                        <strong>{{ number_format($iku->persentase_capaian, 1) }}%</strong>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($iku->gap != 0)
                                    @if($iku->gap > 0)
                                        <span class="text-success fw-bold">+{{ number_format($iku->gap, 2) }}</span>
                                        <i class="bi bi-arrow-up-circle text-success"></i>
                                    @else
                                        <span class="text-danger fw-bold">{{ number_format($iku->gap, 2) }}</span>
                                        <i class="bi bi-arrow-down-circle text-danger"></i>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($iku->status_capaian == 'Tercapai')
                                    <span class="badge bg-success">✓ Tercapai</span>
                                @elseif($iku->status_capaian == 'Dalam Progress')
                                    <span class="badge bg-warning">⟳ Progress</span>
                                @elseif($iku->status_capaian == 'Tidak Tercapai')
                                    <span class="badge bg-danger">✗ Tidak Tercapai</span>
                                @else
                                    <span class="badge bg-secondary">- Belum Dihitung</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Rekomendasi -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-warning bg-opacity-10 border-0">
            <h6 class="mb-0 text-warning">
                <i class="bi bi-lightbulb me-2"></i>Rekomendasi Tindak Lanjut
            </h6>
        </div>
        <div class="card-body">
            @if($summary['tidak_tercapai'] > 0)
            <div class="alert alert-danger mb-3">
                <h6 class="alert-heading">🚨 IKU Tidak Tercapai: {{ $summary['tidak_tercapai'] }} IKU</h6>
                <ul class="mb-0 small">
                    @foreach($ikuList->where('status_capaian', 'Tidak Tercapai') as $iku)
                    <li><strong>{{ $iku->nomor_iku }}</strong>: {{ $iku->nama }} ({{ number_format($iku->persentase_capaian, 1) }}% dari target)</li>
                    @endforeach
                </ul>
                <p class="mb-0 mt-2"><strong>Rekomendasi:</strong> Review strategi, alokasi sumber daya, dan action plan untuk IKU yang tidak tercapai.</p>
            </div>
            @endif
            
            @if($summary['progress'] > 0)
            <div class="alert alert-warning mb-3">
                <h6 class="alert-heading">⏳ IKU Dalam Progress: {{ $summary['progress'] }} IKU</h6>
                <p class="mb-0 small"><strong>Rekomendasi:</strong> Monitor secara berkala dan berikan dukungan agar dapat mencapai target 100%.</p>
            </div>
            @endif
            
            @if($summary['tercapai'] == $summary['total'])
            <div class="alert alert-success mb-0">
                <h6 class="alert-heading">🎉 Semua IKU Tercapai!</h6>
                <p class="mb-0 small">Pertahankan kinerja dan tingkatkan target untuk periode berikutnya.</p>
            </div>
            @endif
        </div>
    </div>
    
</div>
@endsection
