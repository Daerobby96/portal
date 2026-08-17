@extends('layouts.app')

@section('title', 'Manajemen Siklus Mutu SPMI')

@push('styles')
<style>
.siklus-card {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
}
.siklus-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}
.siklus-card .card-accent {
    height: 4px;
    width: 100%;
}
.progress-ring-wrap {
    width: 80px;
    height: 80px;
    position: relative;
    flex-shrink: 0;
}
.progress-ring-wrap svg {
    transform: rotate(-90deg);
}
.progress-ring-wrap .ring-text {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Siklus Mutu SPMI</h4>
            <p class="text-muted mb-0 small">Kelola siklus penjaminan mutu tahunan dan bandingkan progres PPEPP lintas siklus.</p>
        </div>
        <a href="{{ route('siklus-spmi.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Buat Siklus Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($sikluses->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-arrow-repeat fs-1 text-muted opacity-50 d-block mb-3"></i>
                <h5 class="fw-bold text-muted">Belum Ada Siklus Mutu</h5>
                <p class="text-muted">Buat siklus SPMI pertama untuk mulai melacak progres PPEPP.</p>
                <a href="{{ route('siklus-spmi.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Buat Siklus Pertama
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($sikluses as $siklus)
            @php
                $ppepp = $siklus->ppepp_aggregate;
                $overall = $ppepp['overall'];
                $circum = 2 * 3.14159 * 30; // r=30
                $dash = ($overall / 100) * $circum;
            @endphp
            <div class="col-xl-4 col-md-6">
                <div class="card siklus-card border-0 shadow-sm h-100">
                    <div class="card-accent" style="background: {{ $siklus->status_color }};"></div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1 me-3">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    {!! $siklus->status_badge !!}
                                    @if($siklus->is_aktif)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                            <i class="bi bi-lightning-fill me-1"></i>Aktif
                                        </span>
                                    @endif
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">{{ $siklus->nama }}</h5>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $siklus->tanggal_mulai->format('d M Y') }} –
                                    {{ $siklus->tanggal_selesai ? $siklus->tanggal_selesai->format('d M Y') : 'Sekarang' }}
                                </small>
                            </div>

                            {{-- Progress Ring --}}
                            <div class="progress-ring-wrap">
                                <svg width="80" height="80" viewBox="0 0 80 80">
                                    <circle cx="40" cy="40" r="30" fill="none" stroke="#f1f5f9" stroke-width="8"/>
                                    <circle cx="40" cy="40" r="30" fill="none"
                                        stroke="{{ $siklus->status_color }}"
                                        stroke-width="8"
                                        stroke-dasharray="{{ $dash }} {{ $circum - $dash }}"
                                        stroke-linecap="round"/>
                                </svg>
                                <div class="ring-text">
                                    <span class="fw-black" style="font-size: 1rem; color: {{ $siklus->status_color }};">{{ $overall }}%</span>
                                    <span style="font-size: 0.55rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">PPEPP</span>
                                </div>
                            </div>
                        </div>

                        {{-- Mini PPEPP Bar --}}
                        <div class="mb-3">
                            @php
                                $steps = [
                                    ['label' => 'P1', 'val' => $ppepp['penetapan']],
                                    ['label' => 'P2', 'val' => $ppepp['pelaksanaan']],
                                    ['label' => 'P3', 'val' => $ppepp['evaluasi']],
                                    ['label' => 'P4', 'val' => $ppepp['pengendalian']],
                                    ['label' => 'P5', 'val' => $ppepp['peningkatan']],
                                ];
                            @endphp
                            <div class="d-flex gap-1 align-items-end mb-1">
                                @foreach($steps as $step)
                                <div class="flex-grow-1 text-center">
                                    <div style="height: 4px; background: #f1f5f9; border-radius: 2px; overflow: hidden;">
                                        <div style="width: {{ $step['val'] }}%; height: 100%; background: {{ $siklus->status_color }}; border-radius: 2px;"></div>
                                    </div>
                                    <span style="font-size: 0.6rem; color: #94a3b8; font-weight: 700;">{{ $step['label'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="d-flex align-items-center gap-2 text-muted small mb-3">
                            <i class="bi bi-collection"></i>
                            <span>{{ $siklus->periodes->count() }} Periode</span>
                            @if($siklus->penanggungJawab)
                                <span class="mx-1">·</span>
                                <i class="bi bi-person"></i>
                                <span>{{ $siklus->penanggungJawab->name }}</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('siklus-spmi.show', $siklus) }}" class="btn btn-sm btn-primary rounded-pill flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                            <a href="{{ route('siklus-spmi.edit', $siklus) }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($siklus->status === 'persiapan')
                            <form action="{{ route('siklus-spmi.destroy', $siklus) }}" method="POST" onsubmit="return confirm('Hapus siklus ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
