@extends('layouts.app')

@section('title', 'Laporan IKU')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('page-title', 'Laporan IKU Kemdiktisaintek 358/2025')
@section('page-subtitle', 'Periode: ' . $periode->nama)

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('iku-resmi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <!-- Summary Report -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary bg-opacity-10 border-0">
            <h5 class="mb-0 text-primary">
                <i class="bi bi-graph-up me-2"></i>Ringkasan Capaian IKU
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h6 class="text-muted small mb-2">Total IKU</h6>
                        <h2 class="mb-0 fw-bold">{{ $ikuList->count() }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h6 class="text-muted small mb-2">IKU Wajib</h6>
                        <h2 class="mb-0 fw-bold text-danger">
                            {{ $ikuList->whereIn('sifat', ['WAJIB', 'WAJIB PTN-BH'])->count() }}
                        </h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h6 class="text-muted small mb-2">IKU Pilihan</h6>
                        <h2 class="mb-0 fw-bold text-info">
                            {{ $ikuList->whereIn('sifat', ['PILIHAN', 'PILIHAN PTN'])->count() }}
                        </h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3 border rounded">
                        <h6 class="text-muted small mb-2">Rata-rata Capaian</h6>
                        <h2 class="mb-0 fw-bold text-success">
                            {{ number_format($ikuList->where('nilai_hasil', '>', 0)->avg('nilai_hasil'), 2) }}%
                        </h2>
                    </div>
                </div>
            </div>
            
            <!-- Chart Placeholder -->
            <div class="row">
                <div class="col-md-12">
                    <canvas id="ikuChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detailed Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Detail Capaian Per IKU</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">No. IKU</th>
                            <th>Nama Indikator</th>
                            <th class="text-center" style="width: 120px;">Sifat</th>
                            <th class="text-center" style="width: 120px;">Target</th>
                            <th class="text-center" style="width: 120px;">Capaian</th>
                            <th class="text-center" style="width: 100px;">%</th>
                            <th class="text-center" style="width: 120px;">Status</th>
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
                                <span class="text-muted">-</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($iku->nilai_hasil, 2, ',', '.') }}</strong>
                                <small class="text-muted">{{ $iku->satuan }}</small>
                            </td>
                            <td class="text-center">
                                @php
                                    $percentage = $iku->nilai_hasil;
                                    $color = $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                                @endphp
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $color }}" 
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($iku->status_capaian == 'Tercapai')
                                    <span class="badge bg-success">Tercapai</span>
                                @elseif($iku->status_capaian == 'Dalam Progress')
                                    <span class="badge bg-warning">Progress</span>
                                @elseif($iku->status_capaian == 'Tidak Tercapai')
                                    <span class="badge bg-danger">Tidak Tercapai</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dihitung</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Footer Info -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Referensi:</strong> Kepmendikti 358/M/KEP/2025</p>
                    <p class="mb-0"><strong>Periode:</strong> {{ $periode->nama }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1"><strong>Tanggal Laporan:</strong> {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
                    <p class="mb-0"><strong>Dicetak oleh:</strong> {{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('styles')
<style>
@media print {
    .sidebar-wrapper,
    .navbar-top,
    .page-actions,
    .btn,
    .breadcrumb {
        display: none !important;
    }
    .main-content {
        margin: 0 !important;
        padding: 20px !important;
    }
    .card {
        page-break-inside: avoid;
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Chart untuk visualisasi IKU
const ctx = document.getElementById('ikuChart');
const ikuData = @json($ikuList);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ikuData.map(iku => iku.nomor_iku),
        datasets: [{
            label: 'Capaian IKU (%)',
            data: ikuData.map(iku => parseFloat(iku.nilai_hasil)),
            backgroundColor: 'rgba(78, 115, 223, 0.8)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Grafik Capaian IKU Kemdiktisaintek 358/2025'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});
</script>
@endpush
