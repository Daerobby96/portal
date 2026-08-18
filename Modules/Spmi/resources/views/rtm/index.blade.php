@extends('layouts.app')

@section('title', 'Rapat Tinjauan Manajemen (RTM)')
@section('page-title', 'Rapat Tinjauan Manajemen (RTM)')
@section('page-subtitle', 'Monitoring hasil audit dan keputusan manajemen')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('cetak.laporan-rtm') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-indigo-700 hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-600/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <span>Cetak Laporan RTM (A4)</span>
        </a>
        <a href="{{ route('rtm.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-plus-lg"></i>
            <span>Buat RTM Baru</span>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">RTM</li>
@endsection

@section('content')
{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    {{-- Card 1: Total RTM --}}
    <div class="col-lg-3">
        <div class="card border-0 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-[0_4px_20px_-4px_rgba(79,70,229,0.15)] h-100 overflow-hidden relative group">
            <div class="absolute -right-6 -bottom-6 h-28 w-28 rounded-full bg-white/5 blur-xl group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex flex-column justify-content-between relative z-10">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-white/70">Rapat Tinjauan (RTM)</span>
                        <h2 class="mb-0 mt-1 font-extrabold tracking-tight text-white fs-1">{{ $rtms->count() }}</h2>
                    </div>
                    <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white shadow-inner">
                        <i class="bi bi-calendar-event fs-3"></i>
                    </div>
                </div>
                <div class="text-white/60 text-xs font-semibold d-flex align-items-center gap-1">
                    <span>Total agenda rapat periode ini</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: KTS Mayor --}}
    <div class="col-lg-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-rose-500 h-100 overflow-hidden relative group">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-rose-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex flex-column justify-content-between relative z-10">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">KTS Mayor</span>
                        <h2 class="mb-0 mt-1 font-extrabold tracking-tight text-slate-800 fs-1">{{ $stats['kts_mayor'] }}</h2>
                    </div>
                    <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500">
                        <i class="bi bi-exclamation-octagon fs-3 {{ $stats['kts_mayor'] > 0 ? 'animate-bounce' : '' }}"></i>
                    </div>
                </div>
                <div class="text-slate-400 text-xs font-semibold d-flex align-items-center gap-1">
                    <span>Kategori temuan kritis/fatal</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: KTS Minor & OB --}}
    <div class="col-lg-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-amber-500 h-100 overflow-hidden relative group">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-amber-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex flex-column justify-content-between relative z-10">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">KTS Minor & OB</span>
                        <h2 class="mb-0 mt-1 font-extrabold tracking-tight text-slate-800 fs-1">{{ $stats['kts_minor'] + $stats['observasi'] }}</h2>
                    </div>
                    <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                </div>
                <div class="text-slate-400 text-xs font-semibold d-flex align-items-center gap-1">
                    <span>Temuan minor & saran observasi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Capaian Indikator --}}
    <div class="col-lg-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-emerald-500 h-100 overflow-hidden relative group">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-emerald-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex flex-column justify-content-between relative z-10">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Capaian Kinerja</span>
                        <h2 class="mb-0 mt-1 font-extrabold tracking-tight text-slate-800 fs-1">
                            {{ $stats['indikator_tercapai'] }}<span class="text-slate-400 text-lg font-bold">/{{ $stats['indikator_total'] }}</span>
                        </h2>
                    </div>
                    <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500">
                        <i class="bi bi-graph-up-arrow fs-3"></i>
                    </div>
                </div>
                <div class="text-slate-400 text-xs font-semibold d-flex align-items-center gap-1">
                    <span>Indikator tercapai periode ini</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100">
                        <th width="60" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Judul Rapat</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tanggal</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status</th>
                        <th width="150" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0">
                    @forelse($rtms as $rtm)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                        <td class="py-3.5">
                            <div class="font-bold text-slate-800 hover:text-primary transition-colors text-sm">
                                <a href="{{ route('rtm.show', $rtm) }}" class="text-decoration-none text-inherit">{{ $rtm->judul_rapat }}</a>
                            </div>
                            @if($rtm->agenda)
                            <div class="text-xs font-medium text-slate-400 mt-1 max-w-md truncate">{{ Str::limit($rtm->agenda, 80) }}</div>
                            @endif
                        </td>
                        <td class="py-3.5 text-sm font-semibold text-slate-600">
                            {{ $rtm->tanggal_rapat->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3.5">
                            @if($rtm->status === 'draft')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-600 border border-amber-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>Draft</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600 border border-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Selesai</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-end py-3.5 pe-4">
                            <div class="d-flex gap-1.5 justify-content-end">
                                <a href="{{ route('rtm.pdf', $rtm) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white" title="Ekspor PDF" target="_blank">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('rtm.show', $rtm) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail Rapat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('rtm.edit', $rtm) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Edit RTM">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('rtm.destroy', $rtm) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus RTM ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-center py-4">
                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                    <i class="bi bi-calendar-x fs-1"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Agenda RTM</h6>
                                <p class="text-xs font-medium text-slate-400 mb-3 text-center max-w-xs">Belum ada agenda rapat tinjauan manajemen yang diinputkan untuk periode monitoring ini.</p>
                                <a href="{{ route('rtm.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-primary-dark">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Buat Agenda Sekarang</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
