@extends('manajemenrapat::layouts.master')

@section('title', 'Manajemen Rapat')
@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen Rapat</li>
@endsection
@section('page-title', 'Manajemen Rapat')
@section('page-subtitle', 'Kelola seluruh siklus rapat institusi')

@section('page-actions')
@if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
<a href="{{ route('rapat.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i> Buat Rapat
</a>
@endif
@endsection

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
    .premium-card:hover .premium-icon-container {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 15px var(--shadow-color);
    }
</style>
@endpush

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        @php
        $cards = [
            [
                'label'=>'Total Rapat',
                'value'=>$stats['total'],
                'icon'=>'bi-calendar-event',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Terjadwal',
                'value'=>$stats['terjadwal'],
                'icon'=>'bi-calendar-check',
                'accent'=>'#0ea5e9',
                'bg'=>'rgba(14, 165, 233, 0.08)',
                'shadow'=>'rgba(14, 165, 233, 0.15)'
            ],
            [
                'label'=>'Berlangsung',
                'value'=>$stats['berlangsung'],
                'icon'=>'bi-broadcast',
                'accent'=>'#f59e0b',
                'bg'=>'rgba(245, 158, 11, 0.08)',
                'shadow'=>'rgba(245, 158, 11, 0.15)'
            ],
            [
                'label'=>'Selesai',
                'value'=>$stats['selesai'],
                'icon'=>'bi-check2-circle',
                'accent'=>'#10b981',
                'bg'=>'rgba(16, 185, 129, 0.08)',
                'shadow'=>'rgba(16, 185, 129, 0.15)'
            ],
        ];
        @endphp
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

    <div class="row g-4">
        {{-- Kolom Kiri: Filter + Daftar --}}
        <div class="col-lg-8">

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="row g-2 align-items-end">
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <div class="col-auto">
                            <select name="jenis" class="form-select form-select-sm">
                                <option value="">Semua Jenis</option>
                                @foreach(\Modules\ManajemenRapat\Models\Rapat::jenisOptions() as $k => $v)
                                <option value="{{ $k }}" {{ request('jenis')==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                @foreach(\Modules\ManajemenRapat\Models\Rapat::statusOptions() as $k => $v)
                                <option value="{{ $k }}" {{ request('status')==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}" placeholder="Dari">
                        </div>
                        <div class="col-auto">
                            <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}" placeholder="Sampai">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('rapat.index', ['periode_id'=>$periodeId]) }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Rapat --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Daftar Rapat</h6>
                    <small class="text-muted">{{ $rapats->count() }} rapat ditemukan</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Judul</th>
                                    <th class="text-center">Jenis</th>
                                    <th>Tanggal & Waktu</th>
                                    <th class="text-center">Peserta</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rapats as $r)
                                <tr>
                                    <td>
                                        <a href="{{ route('rapat.show', $r) }}" class="fw-semibold text-decoration-none text-dark">
                                            {{ $r->judul }}
                                        </a>
                                        <div class="text-muted small">{{ $r->tempat }}</div>
                                    </td>
                                    <td class="text-center">{!! $r->jenis_badge !!}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $r->tanggal->format('d M Y') }}</div>
                                        <div class="text-muted small">{{ substr($r->waktu_mulai,0,5) }} – {{ substr($r->waktu_selesai,0,5) }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">{{ $r->jumlah_peserta }}</span>
                                    </td>
                                    <td class="text-center">{!! $r->status_badge !!}</td>
                                    <td class="text-center">
                                        <a href="{{ route('rapat.show', $r) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
                                        @unless($r->isLocked())
                                        <a href="{{ route('rapat.edit', $r) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endunless
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                        Tidak ada rapat ditemukan untuk filter yang dipilih
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Mendatang + Overdue --}}
        <div class="col-lg-4">

            {{-- Rapat Mendatang --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="bi bi-calendar-event text-primary me-2"></i>Rapat Mendatang (30 hari)</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($mendatang as $m)
                    <a href="{{ route('rapat.show', $m) }}" class="list-group-item list-group-item-action px-3 py-2">
                        <div class="d-flex gap-2 align-items-start">
                            <div class="bg-primary text-white rounded text-center px-2 py-1" style="min-width:40px;font-size:.75rem">
                                <div class="fw-bold">{{ $m->tanggal->format('d') }}</div>
                                <div>{{ $m->tanggal->format('M') }}</div>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ Str::limit($m->judul, 40) }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ substr($m->waktu_mulai,0,5) }} · {{ $m->tempat }}</div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="list-group-item text-center text-muted py-3 small">
                        Tidak ada rapat mendatang
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Tindak Lanjut Overdue --}}
            @if($overdueActions->isNotEmpty())
            <div class="card border-0 shadow-sm border-danger border-opacity-25">
                <div class="card-header bg-danger bg-opacity-10 border-bottom border-danger border-opacity-25">
                    <h6 class="mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Tindak Lanjut Terlambat ({{ $overdueActions->count() }})</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($overdueActions->take(5) as $tl)
                    <a href="{{ route('rapat.show', $tl->rapat) }}" class="list-group-item list-group-item-action px-3 py-2">
                        <div class="small fw-semibold text-danger">{{ Str::limit($tl->deskripsi, 50) }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            PIC: {{ $tl->pic->name ?? '-' }} · Deadline: {{ $tl->deadline->format('d M Y') }}
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartRapat');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Jumlah Rapat',
                data: @json($chartData['values']),
                backgroundColor: 'rgba(13, 110, 253, 0.6)',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { title: { display: true, text: 'Bulan' } }
            },
            plugins: { legend: { display: false } }
        }
    });
}
</script>
@endpush


