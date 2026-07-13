@extends('layouts.app')

@section('title', 'IKU Kemdiktisaintek 358/2025')

@section('breadcrumb')
<li class="breadcrumb-item active">IKU Kemdiktisaintek</li>
@endsection

@section('page-title', 'IKU Kemdiktisaintek 358/2025')
@section('page-subtitle', 'Indikator Kinerja Utama Perguruan Tinggi')

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
            <i class="bi bi-calendar3 me-1"></i> {{ $triwulanOptions[$triwulan] ?? 'TAHUNAN' }}
        </button>
        <ul class="dropdown-menu">
            @foreach($triwulanOptions as $key => $label)
            <li>
                <a class="dropdown-item {{ $key == $triwulan ? 'active' : '' }}" 
                   href="?periode_id={{ $periodeId }}&triwulan={{ $key }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    
    <a href="{{ route('iku-resmi.set-target', ['periode_id' => $periodeId]) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-bullseye me-1"></i> Set Target
    </a>
    
    <a href="{{ route('iku-resmi.monitoring-triwulan', ['periode_id' => $periodeId, 'triwulan' => $triwulan]) }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-calendar3 me-1"></i> Monitoring Triwulan
    </a>
    
    <a href="{{ route('iku-resmi.analytics', ['periode_id' => $periodeId]) }}" class="btn btn-info btn-sm">
        <i class="bi bi-graph-up me-1"></i> Analisa Kinerja
    </a>
    
    <form method="POST" action="{{ route('iku-resmi.calculate-all') }}" class="d-inline">
        @csrf
        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-calculator me-1"></i> Hitung Semua IKU
        </button>
    </form>
    
    <a href="{{ route('iku-resmi.report', ['periode_id' => $periodeId]) }}" class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-pdf me-1"></i> Laporan
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">

        {{-- Card 1: Total IKU --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4e73df !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
                         style="width:52px;height:52px;background:rgba(78,115,223,0.12);">
                        <i class="bi bi-clipboard-data-fill fs-4" style="color:#4e73df;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:.06em;">Total IKU</p>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fw-bold" style="font-size:2rem;line-height:1;color:#1e2a4a;">{{ $summary['total'] }}</span>
                            <span class="text-muted small">indikator</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:#6c757d;">
                            Kepmendikti 358/2025
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: IKU Wajib --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #e74a3b !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
                         style="width:52px;height:52px;background:rgba(231,74,59,0.12);">
                        <i class="bi bi-patch-exclamation-fill fs-4" style="color:#e74a3b;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:.06em;">IKU Wajib</p>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fw-bold" style="font-size:2rem;line-height:1;color:#1e2a4a;">{{ $summary['wajib'] }}</span>
                            <span class="text-muted small">indikator</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:#6c757d;">
                            Harus dipenuhi semua PT
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: IKU Pilihan --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #36b9cc !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
                         style="width:52px;height:52px;background:rgba(54,185,204,0.12);">
                        <i class="bi bi-ui-checks-grid fs-4" style="color:#36b9cc;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:.06em;">IKU Pilihan</p>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fw-bold" style="font-size:2rem;line-height:1;color:#1e2a4a;">{{ $summary['pilihan'] }}</span>
                            <span class="text-muted small">indikator</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:#6c757d;">
                            Sesuai profil institusi
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Tercapai / Progress --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #1cc88a !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
                         style="width:52px;height:52px;background:rgba(28,200,138,0.12);">
                        <i class="bi bi-graph-up-arrow fs-4" style="color:#1cc88a;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:.7rem;letter-spacing:.06em;">Tercapai</p>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fw-bold" style="font-size:2rem;line-height:1;color:#1e2a4a;">{{ $summary['tercapai'] ?? 0 }}</span>
                            <span class="text-muted small">/ {{ $summary['total'] }}</span>
                        </div>
                        @php
                            $pct = $summary['total'] > 0 ? round((($summary['tercapai'] ?? 0) / $summary['total']) * 100) : 0;
                        @endphp
                        <div class="mt-2">
                            <div class="progress" style="height:5px;border-radius:99px;">
                                <div class="progress-bar bg-success" style="width:{{ $pct }}%;border-radius:99px;"></div>
                            </div>
                            <p class="mb-0 mt-1" style="font-size:.72rem;color:#6c757d;">
                                {{ $pct }}% dari total IKU
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <!-- IKU List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Daftar IKU Kepmendikti 358/M/KEP/2025</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">No. IKU</th>
                            <th>Nama Indikator</th>
                            <th class="text-center" style="width: 140px;">Sifat</th>
                            <th class="text-center" style="width: 120px;">Nilai</th>
                            <th class="text-center" style="width: 140px;">Status</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ikuList as $iku)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-primary text-white fw-bold">
                                    {{ $iku->nomor_iku }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $iku->nama }}</strong>
                                    <p class="text-muted small mb-0">{{ $iku->deskripsi }}</p>
                                </div>
                            </td>
                            <td class="text-center">
                                {!! $iku->sifat_badge !!}
                            </td>
                            <td class="text-center">
                                <strong class="fs-5">
                                    {{ number_format($iku->nilai_hasil, 2, ',', '.') }}
                                </strong>
                                <span class="text-muted small">{{ $iku->satuan }}</span>
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
                                    <a href="{{ route('iku-resmi.show', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId, 'triwulan' => $triwulan]) }}" 
                                       class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('iku-resmi.input', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId, 'triwulan' => $triwulan]) }}" 
                                       class="btn btn-outline-primary" title="Input Data">
                                        <i class="bi bi-pencil-square"></i>
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
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2"></i>
                                <p class="mb-0">Belum ada data IKU</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Info Box -->
    <div class="alert alert-info mt-4" role="alert">
        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
        <p class="mb-0 small">
            Data IKU diambil dari <strong>Kepmendikti 358/M/KEP/2025</strong>. 
            Untuk mengisi data IKU, klik tombol <strong>Input Data</strong> pada setiap IKU. 
            Setelah data diisi, klik tombol <strong>Hitung</strong> untuk menghitung nilai IKU secara otomatis.
        </p>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
function calculateIku(ikuId, periodeId, triwulan) {
    if (!confirm('Hitung ulang IKU ini berdasarkan data yang sudah diinput?')) {
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
