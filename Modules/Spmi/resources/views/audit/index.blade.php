@extends('layouts.app')

@section('title', 'Pelaksanaan Audit')
@section('page-title', 'Pelaksanaan Audit')
@section('page-subtitle', 'Kelola audit mutu internal')

@section('page-actions')
    <a href="{{ route('audit.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
        <i class="bi bi-plus-lg"></i>
        <span>Buat Audit Baru</span>
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Pelaksanaan Audit</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-blue-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-blue-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-clipboard2-check fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['total'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Total Audit</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-slate-400 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-slate-400/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-file-earmark fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['draft'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Draft</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-amber-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-amber-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-play-circle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['aktif'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Sedang Berjalan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-emerald-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-emerald-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['selesai'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Selesai</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <div class="p-4 py-3">
        <form method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="periode_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ request('status') === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 ps-9 pe-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Cari nama, kode, atau unit..."
                            value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute text-slate-400 text-sm" style="left: 12px; top: 12px;"></i>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-1.5">
                    <button class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-3 py-2.5 text-sm font-bold text-white hover:bg-primary-dark border-0">
                        <i class="bi bi-search"></i>
                        <span>Cari</span>
                    </button>
                    @if(request()->hasAny(['periode_id','status','search']))
                        <a href="{{ route('audit.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors text-decoration-none" style="min-width: 40px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100">
                        <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                        <th width="140" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kode Audit</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Nama Program Audit</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Unit Diaudit</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Ketua Auditor</th>
                        <th width="140" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tgl Audit</th>
                        <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Temuan</th>
                        <th width="130" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status</th>
                        <th width="140" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0 modern-badge-container">
                    @forelse($audits as $audit)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                        <td class="py-3.5">
                            <span class="text-xs font-mono font-bold text-primary bg-primary-light px-2.5 py-1 rounded-lg">
                                {{ $audit->kode_audit }}
                            </span>
                        </td>
                        <td class="py-3.5">
                            <span class="text-sm font-bold text-slate-800 d-block leading-snug">{{ $audit->nama_audit }}</span>
                        </td>
                        <td class="py-3.5 text-sm font-semibold text-slate-600">{{ $audit->unit_yang_diaudit }}</td>
                        <td class="py-3.5 text-xs font-bold text-slate-500 d-flex align-items-center gap-1.5">
                            <i class="bi bi-person-badge text-slate-400 fs-6"></i>
                            <span>{{ $audit->ketuaAuditor->name ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 text-xs font-bold text-slate-500">
                            {{ $audit->tanggal_audit->translatedFormat('d M Y') }}
                        </td>
                        <td class="text-center py-3.5">
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-xs font-bold">
                                {{ $audit->temuans()->count() }} temuan
                            </span>
                        </td>
                        <td class="text-center py-3.5">{!! $audit->status_badge !!}</td>
                        <td class="text-end py-3.5 pe-4">
                            <div class="d-flex gap-1.5 justify-content-end">
                                <a href="{{ route('audit.show', $audit) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('audit.edit', $audit) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('audit.destroy', $audit) }}" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Hapus audit ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-center py-5">
                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                    <i class="bi bi-clipboard2-x fs-1"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Program Audit</h6>
                                <p class="text-xs font-medium text-slate-400 mb-0">Silakan buat program audit mutu internal baru menggunakan tombol diatas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($audits->hasPages())
    <div class="p-4 border-t border-slate-100 d-flex justify-content-between align-items-center">
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
            Menampilkan {{ $audits->firstItem() }}–{{ $audits->lastItem() }} dari {{ $audits->total() }} data
        </div>
        <div class="pagination-wrapper m-0">
            {{ $audits->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.modern-badge-container .badge {
    border-radius: 9999px !important;
    padding: 0.25rem 0.75rem !important;
    font-size: 0.75rem !important;
    font-weight: 700 !important;
    text-transform: capitalize !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    border: 1px solid transparent !important;
}
.modern-badge-container .badge.bg-success {
    background-color: #ecfdf5 !important;
    color: #059669 !important;
    border-color: #d1fae5 !important;
}
.modern-badge-container .badge.bg-warning {
    background-color: #fffbeb !important;
    color: #d97706 !important;
    border-color: #fef3c7 !important;
}
.modern-badge-container .badge.bg-primary {
    background-color: #eff6ff !important;
    color: #2563eb !important;
    border-color: #dbeafe !important;
}
.modern-badge-container .badge.bg-secondary {
    background-color: #f8fafc !important;
    color: #64748b !important;
    border-color: #e2e8f0 !important;
}
.modern-badge-container .badge.bg-danger {
    background-color: #fef2f2 !important;
    color: #dc2626 !important;
    border-color: #fee2e2 !important;
}
</style>
@endpush