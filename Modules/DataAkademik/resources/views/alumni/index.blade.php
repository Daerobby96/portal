@extends('dataakademik::layouts.master')

@section('title', 'Data Lulusan & Alumni')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Lulusan / Alumni</li>
@endsection
@section('page-title', 'Data Lulusan & Alumni')

@section('content')
<div class="container-fluid px-4">

@push('styles')
<style>
    .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
        border-radius: 4px 0 0 4px;
        transition: all 0.3s ease;
    }
    .premium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06);
    }
    .premium-card:hover::before {
        width: 6px;
    }
    .premium-icon-container {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: var(--bg-color);
        color: var(--accent-color);
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
</style>
@endpush

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php $cards = [
            [
                'label'=>'Total Lulusan',
                'value'=>$stats['total'],
                'icon'=>'bi-mortarboard-fill',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Lulus Tepat Waktu (≤4 Thn)',
                'value'=>$stats['tepat_waktu'],
                'icon'=>'bi-clock-history',
                'accent'=>'#10b981',
                'bg'=>'rgba(16, 185, 129, 0.08)',
                'shadow'=>'rgba(16, 185, 129, 0.15)'
            ],
            [
                'label'=>'Rata-rata IPK',
                'value'=>number_format($stats['avg_ipk'], 2),
                'icon'=>'bi-star-fill',
                'accent'=>'#f59e0b',
                'bg'=>'rgba(245, 158, 11, 0.08)',
                'shadow'=>'rgba(245, 158, 11, 0.15)'
            ],
            [
                'label'=>'Rata-rata Masa Studi (Bln)',
                'value'=>round($stats['avg_studi']),
                'icon'=>'bi-calendar-check',
                'accent'=>'#0ea5e9',
                'bg'=>'rgba(14, 165, 233, 0.08)',
                'shadow'=>'rgba(14, 165, 233, 0.15)'
            ],
        ]; @endphp
        @foreach($cards as $c)
        <div class="col-6 col-md-3">
            <div class="card premium-card" style="--accent-color: {{ $c['accent'] }};">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="premium-icon-container" style="--bg-color: {{ $c['bg'] }}; --accent-color: {{ $c['accent'] }}; --shadow-color: {{ $c['shadow'] }};">
                        <i class="bi {{ $c['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ $c['label'] }}</p>
                        <h3 class="mb-0 fw-bold text-dark mt-1" style="font-size: 1.8rem; line-height: 1.2;">{{ $c['value'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama alumni..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <select name="prodi" class="form-select form-select-sm">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ request('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="tahun_lulus" class="form-select form-select-sm">
                        <option value="">Semua Tahun Lulus</option>
                        @foreach($tahunLulus as $thn)
                        <option value="{{ $thn }}" {{ request('tahun_lulus') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('alumni.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>NIM / Nama Alumni</th>
                            <th>Prodi / Angkatan</th>
                            <th class="text-center">Tahun Lulus</th>
                            <th class="text-center">Masa Studi</th>
                            <th class="text-center">IPK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumnis as $m)
                        <tr>
                            <td class="text-muted small">{{ $alumnis->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold small"><a href="{{ route('mahasiswa.show', $m) }}" class="text-decoration-none text-dark">{{ $m->nama }}</a></div>
                                <div class="text-muted font-monospace" style="font-size:.73rem">{{ $m->nim }}</div>
                            </td>
                            <td>
                                <div class="small">{{ $m->prodi?->nama ?? '-' }}</div>
                                <div class="text-muted" style="font-size:.73rem">Angkatan {{ $m->angkatan ?? '-' }}</div>
                            </td>
                            <td class="text-center fw-bold">{{ $m->tanggal_lulus ? $m->tanggal_lulus->format('Y') : '-' }}</td>
                            <td class="text-center font-monospace small">
                                @if($m->masa_studi_bulan)
                                    <span class="{{ $m->masa_studi_bulan <= 48 ? 'text-success fw-bold' : 'text-danger' }}">
                                        {{ $m->masa_studi_bulan }} bln
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center font-monospace small">
                                @if($m->ipk)
                                    <span class="{{ $m->ipk >= 3.5 ? 'text-success fw-bold' : '' }}">{{ $m->ipk }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada data lulusan / alumni.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($alumnis->hasPages())
        <div class="card-footer bg-white">{{ $alumnis->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
