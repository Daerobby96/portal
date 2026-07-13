@extends('layouts.app')

@section('title', 'Monitoring Triwulan IKU')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item active">Monitoring Triwulan</li>
@endsection

@section('page-title', 'Monitoring Triwulan IKU')
@section('page-subtitle', 'Pelaporan IKU Per Triwulan untuk Kemdiktisaintek')

@section('page-actions')
<div class="d-flex gap-2">
    <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-calendar-event me-1"></i> Periode: 
            {{ $periodes->firstWhere('id', $periodeId)?->nama ?? 'Pilih Periode' }}
        </button>
        <ul class="dropdown-menu">
            @foreach($periodes as $p)
            <li>
                <a class="dropdown-item {{ $p->id == $periodeId ? 'active' : '' }}" 
                   href="?periode_id={{ $p->id }}&triwulan={{ $triwulan }}">
                    {{ $p->nama }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    
    <div class="dropdown">
        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-calendar3 me-1"></i> {{ $triwulanOptions[$triwulan] }}
        </button>
        <ul class="dropdown-menu">
            @foreach($triwulanOptions as $key => $label)
            @if($key !== 'TAHUNAN')
            <li>
                <a class="dropdown-item {{ $key == $triwulan ? 'active' : '' }}" 
                   href="?periode_id={{ $periodeId }}&triwulan={{ $key }}">
                    {{ $label }}
                </a>
            </li>
            @endif
            @endforeach
        </ul>
    </div>
    
    <a href="{{ route('iku-resmi.laporan-triwulan', ['periode_id' => $periodeId, 'triwulan' => $triwulan]) }}" 
       class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-pdf me-1"></i> Laporan Triwulan
    </a>
    
    <a href="{{ route('iku-resmi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <!-- Info Banner -->
    <div class="alert alert-primary border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex">
            <div>
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            </div>
            <div>
                <h6 class="alert-heading mb-1">Monitoring Triwulan {{ $triwulan }}</h6>
                <p class="mb-0 small">
                    Menampilkan data IKU untuk <strong>{{ $triwulanOptions[$triwulan] }}</strong> 
                    periode <strong>{{ $periodes->firstWhere('id', $periodeId)?->nama }}</strong>.
                    Data ini akan dilaporkan ke Kemdiktisaintek sesuai jadwal pelaporan.
                </p>
            </div>
        </div>
    </div>
    
    <!-- IKU List per Triwulan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-clipboard-data me-2"></i>
                Daftar IKU - {{ $triwulanOptions[$triwulan] }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">No. IKU</th>
                            <th>Nama Indikator</th>
                            <th class="text-center" style="width: 100px;">Target</th>
                            <th class="text-center" style="width: 100px;">Nilai</th>
                            <th class="text-center" style="width: 100px;">Capaian</th>
                            <th class="text-center" style="width: 120px;">Status</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ikuList as $iku)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">
                                    {{ $iku->nomor_iku }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $iku->nama }}</strong>
                                    <p class="text-muted small mb-0">
                                        {{ \Str::limit($iku->deskripsi, 100) }}
                                    </p>
                                </div>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($iku->target, 2, ',', '.') }}</strong>
                                <span class="text-muted small d-block">{{ $iku->satuan }}</span>
                            </td>
                            <td class="text-center">
                                <strong class="fs-5">{{ number_format($iku->nilai_hasil, 2, ',', '.') }}</strong>
                                <span class="text-muted small d-block">{{ $iku->satuan }}</span>
                            </td>
                            <td class="text-center">
                                @if($iku->target > 0)
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar 
                                            @if($iku->persentase_capaian >= 100) bg-success
                                            @elseif($iku->persentase_capaian >= 80) bg-info
                                            @elseif($iku->persentase_capaian >= 60) bg-warning
                                            @else bg-danger
                                            @endif" 
                                            role="progressbar" 
                                            style="width: {{ min($iku->persentase_capaian, 100) }}%"
                                            aria-valuenow="{{ $iku->persentase_capaian }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            <strong>{{ number_format($iku->persentase_capaian, 1) }}%</strong>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($iku->status_capaian == 'Tercapai')
                                    <span class="badge bg-success">Tercapai</span>
                                @elseif($iku->status_capaian == 'Dalam Progress')
                                    <span class="badge bg-warning">Dalam Progress</span>
                                @elseif($iku->status_capaian == 'Tidak Tercapai')
                                    <span class="badge bg-danger">Tidak Tercapai</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dihitung</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('iku-resmi.input', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId, 'triwulan' => $triwulan]) }}" 
                                       class="btn btn-outline-primary" title="Input Data">
                                        <i class="bi bi-pencil-square"></i> Input
                                    </a>
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="calculateIku({{ $iku->id }}, {{ $periodeId }}, '{{ $triwulan }}')"
                                            title="Hitung">
                                        <i class="bi bi-calculator"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2"></i>
                                <p class="mb-0">Belum ada data IKU untuk triwulan ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Comparison Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-secondary text-white">
            <h5 class="mb-0">
                <i class="bi bi-bar-chart-line me-2"></i>
                Perbandingan Nilai IKU Antar Triwulan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 80px;">No. IKU</th>
                            <th>Nama Indikator</th>
                            @foreach($triwulanOptions as $key => $label)
                            @if($key !== 'TAHUNAN')
                            <th class="text-center" style="width: 120px;">
                                {{ str_replace('Triwulan ', 'TW', explode(' (', $label)[0]) }}
                            </th>
                            @endif
                            @endforeach
                            <th class="text-center bg-warning bg-opacity-10" style="width: 100px;">Trend</th>
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
                            <td><strong>{{ $iku->nama }}</strong></td>
                            
                            @php
                                $values = [];
                            @endphp
                            
                            @foreach(['TW1', 'TW2', 'TW3', 'TW4'] as $tw)
                            @php
                                $nilai = $comparisonData[$tw][$iku->id]['nilai'] ?? 0;
                                $values[] = $nilai;
                                $status = $comparisonData[$tw][$iku->id]['status'] ?? 'Belum Dihitung';
                            @endphp
                            <td class="text-center {{ $tw == $triwulan ? 'bg-primary bg-opacity-10' : '' }}">
                                <div>
                                    <strong>{{ number_format($nilai, 2, ',', '.') }}</strong>
                                    @if($status == 'Tercapai')
                                        <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                    @elseif($status == 'Dalam Progress')
                                        <i class="bi bi-clock-fill text-warning ms-1"></i>
                                    @elseif($status == 'Tidak Tercapai')
                                        <i class="bi bi-x-circle-fill text-danger ms-1"></i>
                                    @endif
                                </div>
                            </td>
                            @endforeach
                            
                            <td class="text-center">
                                @php
                                    $nonZeroValues = array_filter($values);
                                    if (count($nonZeroValues) >= 2) {
                                        $firstValue = reset($nonZeroValues);
                                        $lastValue = end($nonZeroValues);
                                        $trend = $lastValue > $firstValue ? 'up' : ($lastValue < $firstValue ? 'down' : 'stable');
                                    } else {
                                        $trend = 'stable';
                                    }
                                @endphp
                                
                                @if($trend == 'up')
                                    <i class="bi bi-arrow-up-circle-fill text-success fs-5"></i>
                                @elseif($trend == 'down')
                                    <i class="bi bi-arrow-down-circle-fill text-danger fs-5"></i>
                                @else
                                    <i class="bi bi-dash-circle-fill text-secondary fs-5"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Keterangan Trend:</strong> 
            <i class="bi bi-arrow-up-circle-fill text-success"></i> Meningkat | 
            <i class="bi bi-arrow-down-circle-fill text-danger"></i> Menurun | 
            <i class="bi bi-dash-circle-fill text-secondary"></i> Stabil/Belum Ada Data
        </div>
    </div>
    
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-secondary {
    background: linear-gradient(135deg, #667eea 0%, #64b3f4 100%);
}
</style>
@endpush

@push('scripts')
<script>
function calculateIku(ikuId, periodeId, triwulan) {
    if (!confirm('Hitung ulang IKU ini berdasarkan data ' + triwulan + ' yang sudah diinput?')) {
        return;
    }
    
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    btn.disabled = true;
    
    fetch(`/iku-resmi/${ikuId}/calculate?periode_id=${periodeId}&triwulan=${triwulan}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'IKU berhasil dihitung!');
            location.reload();
        } else {
            alert(data.error || 'Gagal menghitung IKU');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
}
</script>
@endpush
