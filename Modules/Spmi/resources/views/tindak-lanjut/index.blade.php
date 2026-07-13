@extends('layouts.app')

@section('title', 'Tindak Lanjut Temuan')
@section('page-title', 'Tindak Lanjut Temuan')
@section('page-subtitle', 'Kelola tindak lanjut temuan audit')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tindak Lanjut Temuan</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-rose-500/5 border-l-4 border-l-rose-500 group">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500 transition-colors group-hover:bg-rose-500 group-hover:text-white">
                    <i class="bi bi-exclamation-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['open'] }}</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Open</div>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-rose-500/5 blur-xl transition-all group-hover:scale-150"></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/5 border-l-4 border-l-amber-500 group">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                    <i class="bi bi-clock fs-4"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['in_progress'] }}</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">In Progress</div>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-amber-500/5 blur-xl transition-all group-hover:scale-150"></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5 border-l-4 border-l-emerald-500 group">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['closed'] }}</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Closed</div>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-emerald-500/5 blur-xl transition-all group-hover:scale-150"></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-rose-600/5 border-l-4 border-l-rose-600 group">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100/50 text-rose-600 transition-colors group-hover:bg-rose-600 group-hover:text-white">
                    <i class="bi bi-calendar-x fs-4"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold tracking-tight text-slate-800">{{ $stats['overdue'] }}</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Overdue</div>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-rose-600/5 blur-xl transition-all group-hover:scale-150"></div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <form method="GET" class="m-0">
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="kategori" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                    <option value="">Semua Kategori</option>
                    <option value="KTS_Mayor" {{ request('kategori') === 'KTS_Mayor' ? 'selected' : '' }}>KTS Mayor</option>
                    <option value="KTS_Minor" {{ request('kategori') === 'KTS_Minor' ? 'selected' : '' }}>KTS Minor</option>
                    <option value="OB" {{ request('kategori') === 'OB' ? 'selected' : '' }}>Observasi</option>
                    <option value="Rekomendasi" {{ request('kategori') === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="deadline" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                    <option value="">Semua Deadline</option>
                    <option value="7" {{ request('deadline') === '7' ? 'selected' : '' }}>≤ 7 hari</option>
                    <option value="14" {{ request('deadline') === '14' ? 'selected' : '' }}>≤ 14 hari</option>
                    <option value="30" {{ request('deadline') === '30' ? 'selected' : '' }}>≤ 30 hari</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-9 pr-4 py-2 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                        placeholder="Cari temuan..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col text-end d-flex gap-2 justify-content-end">
                <button class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                    <i class="bi bi-search"></i>
                    <span>Cari</span>
                </button>
                <a href="{{ route('tindak-lanjut.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0">
                    <span>Reset</span>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
    <div class="table-responsive">
        <table class="w-full text-left border-collapse table-custom mb-0">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                    <th class="py-3 px-4 text-center">#</th>
                    <th class="py-3 px-3">Kode</th>
                    <th class="py-3 px-3">Kategori</th>
                    <th class="py-3 px-3" style="min-width: 250px;">Uraian Temuan</th>
                    <th class="py-3 px-3">Unit Diaudit</th>
                    <th class="py-3 px-3">Batas TL</th>
                    <th class="py-3 px-3 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100/70">
                @forelse($temuans as $temuan)
                <tr class="transition-colors hover:bg-slate-50/50 align-middle">
                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium text-xs">{{ $loop->iteration }}</td>
                    <td class="py-3.5 px-3">
                        <span class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-0.5 text-xs font-mono font-bold text-blue-600 border border-blue-100">
                            {{ $temuan->kode_temuan }}
                        </span>
                    </td>
                    <td class="py-3.5 px-3 modern-badge-container">{!! $temuan->kategori_badge !!}</td>
                    <td class="py-3.5 px-3">
                        <div class="fw-semibold text-slate-800 leading-snug" style="font-size: 0.85rem;">
                            {{ Str::limit($temuan->uraian_temuan, 75) }}
                        </div>
                    </td>
                    <td class="py-3.5 px-3 text-slate-600 text-xs font-medium">{{ $temuan->audit->unit_yang_diaudit ?? '-' }}</td>
                    <td class="py-3.5 px-3">
                        @if($temuan->batas_tindak_lanjut)
                            @php $isOverdue = $temuan->batas_tindak_lanjut->isPast(); @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-lg py-1 px-2 text-xs font-semibold {{ $isOverdue ? 'bg-rose-50 text-rose-600 border border-rose-100 animate-pulse' : 'bg-slate-50 text-slate-600 border border-slate-100' }}">
                                <i class="bi {{ $isOverdue ? 'bi-exclamation-triangle-fill' : 'bi-calendar3' }}"></i>
                                {{ $temuan->batas_tindak_lanjut->translatedFormat('d M Y') }}
                            </span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-3 text-center">
                        @if($temuan->status === 'open')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-600 border border-rose-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                Open
                            </span>
                        @elseif($temuan->status === 'in_progress')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 border border-amber-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                In Progress
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600 border border-emerald-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Closed
                            </span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            @if($temuan->tindakLanjuts->count() === 0)
                            <a href="{{ route('tindak-lanjut.create', ['temuan_id' => $temuan->id]) }}"
                               class="inline-flex h-8 w-8 items-center justify-content-center rounded-lg bg-blue-50 text-blue-600 shadow-sm transition-all hover:bg-blue-600 hover:text-white hover:-translate-y-0.5 hover:shadow-md hover:shadow-blue-500/20 active:translate-y-0" title="Buat Tindak Lanjut">
                                <i class="bi bi-plus-lg text-sm"></i>
                            </a>
                            @else
                            <a href="{{ route('tindak-lanjut.show', $temuan->tindakLanjuts->first()) }}"
                               class="inline-flex h-8 w-8 items-center justify-content-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-700 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0" title="Lihat TL">
                                <i class="bi bi-eye text-sm"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-slate-400 py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="h-14 w-14 rounded-full bg-emerald-50 text-emerald-500 d-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-check2-circle fs-2"></i>
                            </div>
                            <span class="fw-bold text-slate-700">Luar Biasa!</span>
                            <span class="text-xs text-slate-400 mt-1">Tidak ada temuan yang memerlukan tindak lanjut</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($temuans->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-100 bg-slate-50/50 px-4 py-3">
        <div class="text-xs font-semibold text-slate-400">
            Menampilkan <span class="text-slate-700 font-bold">{{ $temuans->firstItem() }}</span>–<span class="text-slate-700 font-bold">{{ $temuans->lastItem() }}</span> dari <span class="text-slate-700 font-bold">{{ $temuans->total() }}</span> data
        </div>
        <div class="pagination-links-custom">
            {{ $temuans->links() }}
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Override bootstrap badge di dalam kategori_badge agar serasi dengan konsep pastel modern */
    .modern-badge-container .badge {
        font-weight: 700 !important;
        font-size: 0.72rem !important;
        padding: 4px 10px !important;
        border-radius: 9999px !important;
        box-shadow: none !important;
        letter-spacing: 0.01em !important;
    }
    .modern-badge-container .badge.bg-danger {
        background-color: #fef2f2 !important;
        color: #ef4444 !important;
        border: 1px solid #fee2e2 !important;
    }
    .modern-badge-container .badge.bg-warning {
        background-color: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fef3c7 !important;
    }
    .modern-badge-container .badge.bg-info {
        background-color: #f0f9ff !important;
        color: #0284c7 !important;
        border: 1px solid #e0f2fe !important;
    }
    .modern-badge-container .badge.bg-success {
        background-color: #f0fdf4 !important;
        color: #16a34a !important;
        border: 1px solid #dcfce7 !important;
    }
</style>
@endpush
@endsection