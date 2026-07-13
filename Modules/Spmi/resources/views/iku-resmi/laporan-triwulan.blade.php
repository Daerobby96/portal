@extends('layouts.app')

@section('title', 'Laporan Triwulan IKU')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.monitoring-triwulan') }}">Monitoring Triwulan</a></li>
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('page-title', 'Laporan Triwulan IKU')
@section('page-subtitle', 'Laporan Format Kemdiktisaintek')

@section('page-actions')
<div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
    
    <a href="?periode_id={{ request('periode_id') }}&triwulan={{ request('triwulan') }}&format=pdf" 
       class="btn btn-danger btn-sm">
        <i class="bi bi-file-pdf me-1"></i> Export PDF
    </a>
    
    <a href="{{ route('iku-resmi.monitoring-triwulan', ['periode_id' => request('periode_id'), 'triwulan' => request('triwulan')]) }}" 
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <!-- Report Header -->
    <div class="card border-0 shadow-sm mb-4" id="report-header">
        <div class="card-body text-center py-4">
            <h4 class="fw-bold mb-2">LAPORAN INDIKATOR KINERJA UTAMA (IKU)</h4>
            <h5 class="fw-bold mb-3">KEPMENDIKTI 358/M/KEP/2025</h5>
            <hr class="my-3">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td width="150"><strong>Institusi</strong></td>
                            <td width="20">:</td>
                            <td>{{ config('app.institution_name', 'Nama Institusi') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Periode</strong></td>
                            <td>:</td>
                            <td>{{ $periode->nama }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 text-md-start">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td width="150"><strong>Triwulan</strong></td>
                            <td width="20">:</td>
                            <td>{{ $triwulanOptions[$triwulan] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Laporan</strong></td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Summary Statistics -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Ringkasan Capaian IKU</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-primary fw-bold mb-0">{{ $ikuList->count() }}</h3>
                        <small class="text-muted">Total IKU</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-success fw-bold mb-0">
                            {{ $ikuList->where('status_capaian', 'Tercapai')->count() }}
                        </h3>
                        <small class="text-muted">Tercapai</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-warning fw-bold mb-0">
                            {{ $ikuList->where('status_capaian', 'Dalam Progress')->count() }}
                        </h3>
                        <small class="text-muted">Dalam Progress</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3">
                        <h3 class="text-danger fw-bold mb-0">
                            {{ $ikuList->where('status_capaian', 'Tidak Tercapai')->count() }}
                        </h3>
                        <small class="text-muted">Tidak Tercapai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detailed Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0"><i class="bi bi-table me-2"></i>Rincian Capaian IKU</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th class="text-center" width="100">Kode IKU</th>
                            <th>Nama Indikator Kinerja Utama</th>
                            <th class="text-center" width="100">Sifat</th>
                            <th class="text-center" width="100">Target</th>
                            <th class="text-center" width="100">Capaian</th>
                            <th class="text-center" width="100">%</th>
                            <th class="text-center" width="120">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ikuList as $index => $iku)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center fw-bold">{{ $iku->nomor_iku }}</td>
                            <td>
                                <strong>{{ $iku->nama }}</strong>
                                <p class="text-muted small mb-0">{{ $iku->deskripsi }}</p>
                            </td>
                            <td class="text-center">
                                @if(in_array($iku->sifat, ['WAJIB', 'WAJIB PTN-BH']))
                                    <span class="badge bg-danger">WAJIB</span>
                                @else
                                    <span class="badge bg-info">PILIHAN</span>
                                @endif
                            </td>
                            <td class="text-end">
                                {{ number_format($iku->target, 2, ',', '.') }}
                                <small class="text-muted d-block">{{ $iku->satuan }}</small>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($iku->nilai_hasil, 2, ',', '.') }}</strong>
                                <small class="text-muted d-block">{{ $iku->satuan }}</small>
                            </td>
                            <td class="text-center">
                                @if($iku->target > 0)
                                    <strong class="
                                        @if($iku->persentase_capaian >= 100) text-success
                                        @elseif($iku->persentase_capaian >= 80) text-info
                                        @elseif($iku->persentase_capaian >= 60) text-warning
                                        @else text-danger
                                        @endif
                                    ">
                                        {{ number_format($iku->persentase_capaian, 1, ',', '.') }}%
                                    </strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($iku->status_capaian == 'Tercapai')
                                    <span class="badge bg-success">Tercapai</span>
                                @elseif($iku->status_capaian == 'Dalam Progress')
                                    <span class="badge bg-warning text-dark">Dalam Progress</span>
                                @elseif($iku->status_capaian == 'Tidak Tercapai')
                                    <span class="badge bg-danger">Tidak Tercapai</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dihitung</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Rata-rata Capaian:</strong></td>
                            <td colspan="3" class="text-center">
                                <strong class="fs-5 text-primary">
                                    {{ number_format($ikuList->where('persentase_capaian', '>', 0)->avg('persentase_capaian'), 2, ',', '.') }}%
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Notes Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Catatan & Keterangan</h6>
        </div>
        <div class="card-body">
            <h6 class="fw-bold">Keterangan Status Capaian:</h6>
            <ul class="mb-3">
                <li><span class="badge bg-success">Tercapai</span> : Capaian ≥ 100% dari target</li>
                <li><span class="badge bg-warning text-dark">Dalam Progress</span> : Capaian 80% - 99% dari target</li>
                <li><span class="badge bg-danger">Tidak Tercapai</span> : Capaian < 80% dari target</li>
            </ul>
            
            <h6 class="fw-bold mt-4">Catatan Tambahan:</h6>
            <div class="border rounded p-3 bg-light" style="min-height: 100px;">
                <p class="text-muted mb-0">
                    <em>Catatan tambahan dapat ditambahkan di sini...</em>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Signature Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="text-center">
                        <p class="mb-0">Mengetahui,</p>
                        <p class="mb-0 fw-bold">Ketua SPMI</p>
                        <div style="height: 80px;"></div>
                        <p class="mb-0">_____________________</p>
                        <p class="mb-0 small">NIP/NIDN:</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <p class="mb-0">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p class="mb-0 fw-bold">Penyusun Laporan</p>
                        <div style="height: 80px;"></div>
                        <p class="mb-0">_____________________</p>
                        <p class="mb-0 small">NIP/NIDN:</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('styles')
<style>
@media print {
    .navbar, .breadcrumb, .page-actions, .btn, footer {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .table {
        font-size: 11px;
    }
    
    body {
        background: white !important;
    }
}
</style>
@endpush
