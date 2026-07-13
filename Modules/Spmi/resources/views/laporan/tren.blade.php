@extends('layouts.app')

@section('title', 'Laporan Tren Multi-Periode')
@section('page-title', 'Laporan Tren')
@section('page-subtitle', 'Analisis tren capaian IKU & IKT dari waktu ke waktu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Tren</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Tipe Indikator</label>
                        <select name="tipe" class="form-select" onchange="this.form.submit()">
                            <option value="IKU" {{ $tipeFilter == 'IKU' ? 'selected' : '' }}>IKU - Indikator Kinerja Utama</option>
                            <option value="IKT" {{ $tipeFilter == 'IKT' ? 'selected' : '' }}>IKT - Indikator Kinerja Tambahan</option>
                            <option value="Custom" {{ $tipeFilter == 'Custom' ? 'selected' : '' }}>Custom - Institusi</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Grafik Tren Capaian ({{ $tipeFilter }})</h5>
            </div>
            <div class="card-body">
                @if(count($trendData) > 0)
                    <div style="height: 400px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bar-chart fs-1 mb-3 d-block"></i>
                        <p>Belum ada data capaian untuk tipe indikator ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(count($trendData) > 0)
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Tabel Rincian Capaian per Periode</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Indikator Kinerja</th>
                                <th>Target Default</th>
                                <th>Bobot</th>
                                @foreach($labels as $label)
                                    <th class="text-center">{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trendData as $data)
                            <tr>
                                <td class="fw-medium">{{ $data['name'] }}</td>
                                <td class="text-center">{{ $data['target'] ?? '-' }}</td>
                                <td class="text-center">{{ $data['bobot'] }}%</td>
                                @foreach($data['data'] as $capaian)
                                    <td class="text-center">
                                        @if($capaian > 0)
                                            <span class="badge {{ $capaian >= ($data['target'] ?? 0) ? 'bg-success' : 'bg-warning' }}">
                                                {{ $capaian }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
@if(count($trendData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendChart').getContext('2d');
        
        const labels = {!! json_encode($labels) !!};
        const rawData = {!! json_encode($trendData) !!};
        
        // Generate random distinct colors
        const colors = [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
            '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997'
        ];
        
        const datasets = rawData.map((item, index) => {
            const color = colors[index % colors.length];
            return {
                label: item.name,
                data: item.data,
                borderColor: color,
                backgroundColor: color + '20', // Add transparency
                borderWidth: 2,
                tension: 0.3,
                fill: false,
                pointBackgroundColor: color,
                pointRadius: 4,
                pointHoverRadius: 6
            };
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nilai Capaian'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Periode Akademik'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    });
</script>
@endif
@endpush
@endsection
