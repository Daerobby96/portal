@extends('layouts.app')

@section('title', 'Standar Mutu')
@section('page-title', 'Standar Mutu')
@section('page-subtitle', 'Kelola 24 standar wajib SN-Dikti dan standar institusional')

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel text-emerald-500"></i>
            <span>Import Excel</span>
        </button>
        <a href="{{ route('standar.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Standar</span>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Standar Mutu</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row g-4 mb-4">
    {{-- Card 1: Pendidikan --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-blue-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-blue-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-500 shadow-inner">
                    <i class="bi bi-book fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['pendidikan'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Pendidikan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Penelitian --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-emerald-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-emerald-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 shadow-inner">
                    <i class="bi bi-search fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['penelitian'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Penelitian</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: PkM --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-cyan-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-cyan-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-500 shadow-inner">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['pkm'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Pengabdian (PkM)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Institusional --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-purple-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-purple-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-500 shadow-inner">
                    <i class="bi bi-building fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['institusional'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Institusional</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Panel --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <div class="p-4 py-3.5">
        <form method="GET" action="{{ route('standar.index') }}">
            <div class="row g-2.5 align-items-center">
                <div class="col-md-3">
                    <select name="bidang" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Bidang</option>
                        @foreach($bidangOptions as $val => $label)
                            <option value="{{ $val }}" {{ request('bidang') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 ps-9 pe-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Cari nama atau kode standar..."
                            value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute text-slate-400 text-sm" style="left: 12px; top: 12px;"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="per_page" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 baris</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1.5">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-3 py-2.5 text-sm font-bold text-white hover:bg-primary-dark transition-colors border-0">
                        <i class="bi bi-search"></i>
                        <span>Cari</span>
                    </button>
                    @if(request()->hasAny(['search','bidang','per_page']))
                        <a href="{{ route('standar.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors text-decoration-none" style="min-width: 40px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
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
                        <th width="150" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Bidang</th>
                        <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kode</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Nama Standar</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Deskripsi</th>
                        <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Dokumen</th>
                        <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status</th>
                        <th width="140" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0 modern-badge-container">
                    @forelse($standars as $s)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $s->nomor ?? $loop->iteration }}</td>
                        <td class="py-3.5 text-sm font-bold">
                            {!! $s->bidang_badge !!}
                        </td>
                        <td class="py-3.5">
                            <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-xs font-extrabold text-indigo-600">
                                {{ $s->kode }}
                            </span>
                        </td>
                        <td class="py-3.5 text-sm font-bold text-slate-800">
                            <div class="d-flex align-items-center gap-1.5">
                                <a href="{{ route('standar.show', $s) }}" class="text-inherit text-decoration-none hover:text-primary transition-colors">{{ $s->nama }}</a>
                                @if($s->jenis == 'inti')
                                    <i class="bi bi-patch-check-fill text-blue-500 fs-6" title="Standar Inti (SN-Dikti)"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 text-xs font-medium text-slate-400 max-w-xs truncate">
                            {{ $s->deskripsi ?? '-' }}
                        </td>
                        <td class="text-center py-3.5">
                            <a href="{{ route('standar.show', $s) }}"
                               class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-2.5 py-0.5 text-xs font-bold text-decoration-none hover:bg-blue-100">
                                <span>{{ $s->dokumens_count }} dokumen</span>
                            </a>
                        </td>
                        <td class="text-center py-3.5">
                            @if($s->is_aktif)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                    <span>Aktif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                    <span>Nonaktif</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-end py-3.5 pe-4">
                            <div class="d-flex gap-1.5 justify-content-end">
                                <a href="{{ route('standar.show', $s) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('standar.edit', $s) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('standar.destroy', $s) }}" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Hapus standar ini?')">
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
                        <td colspan="8" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-center py-4">
                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                    <i class="bi bi-bookmark-x fs-1"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Standar Mutu</h6>
                                <p class="text-xs font-medium text-slate-400 mb-0">Belum ada standar wajib atau standar institusional yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($standars->hasPages())
    <div class="p-4 border-t border-slate-100 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-xs font-semibold text-slate-400">
            Menampilkan {{ $standars->firstItem() }}-{{ $standars->lastItem() }} dari {{ $standars->total() }} data
        </div>
        <div class="modern-pagination">
            {{ $standars->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content border-0 rounded-2xl shadow-xl overflow-hidden'>
            <form action='{{ route('standar.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-0 py-3.5 px-4'>
                    <div class="d-flex align-items-center gap-2">
                        <i class='bi bi-file-earmark-excel fs-5'></i>
                        <h6 class='modal-title font-bold text-white mb-0' id='importModalLabel'>Import Standar Mutu</h6>
                    </div>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold d-flex gap-2 mb-4'>
                        <i class='bi bi-info-circle-fill fs-5 text-blue-500'></i>
                        <span>Pastikan file Excel memiliki heading wajib: <strong class="text-blue-900">kode, nama, bidang, jenis, nomor, deskripsi</strong>.</span>
                    </div>
                    
                    <div class='mb-4 text-center'>
                        <a href="{{ route('standar.template') }}" class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50/20 px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 text-decoration-none">
                            <i class="bi bi-download"></i>
                            <span>Download Template Excel</span>
                        </a>
                    </div>
                    
                    <div>
                        <label class='text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2'>Pilih File (.xlsx / .xls / .csv)</label>
                        <div class="p-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/30 d-flex flex-column gap-2">
                            <input type='file' name='file' class='w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100' accept='.xlsx,.xls,.csv' required>
                        </div>
                    </div>
                </div>
                <div class='modal-footer bg-slate-50 border-0 p-3 px-4 d-flex justify-content-end gap-2'>
                    <button type='button' class='inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 px-4 py-2 text-sm font-bold text-slate-500 hover:bg-slate-50' data-bs-modal='modal' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' class='inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white hover:bg-primary-dark shadow-sm'>Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* CSS Badge Overrides to match standard beautiful styling */
.modern-badge-container .badge {
    text-transform: uppercase;
    font-size: 10px !important;
    font-weight: 800 !important;
    letter-spacing: 0.05em;
    padding: 4px 8px !important;
    border-radius: 9999px !important;
    display: inline-flex !important;
    align-items: center !important;
}
.modern-badge-container .bg-primary {
    background-color: rgba(59, 130, 246, 0.1) !important;
    color: #3b82f6 !important;
    border: 1px solid rgba(59, 130, 246, 0.2) !important;
}
.modern-badge-container .bg-success {
    background-color: rgba(16, 185, 129, 0.1) !important;
    color: #10b981 !important;
    border: 1px solid rgba(16, 185, 129, 0.2) !important;
}
.modern-badge-container .bg-info {
    background-color: rgba(6, 182, 212, 0.1) !important;
    color: #06b6d4 !important;
    border: 1px solid rgba(6, 182, 212, 0.2) !important;
}
.modern-badge-container .bg-secondary {
    background-color: rgba(139, 92, 246, 0.1) !important;
    color: #8b5cf6 !important;
    border: 1px solid rgba(139, 92, 246, 0.2) !important;
}
</style>
@endpush
