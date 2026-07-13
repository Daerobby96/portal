@extends('layouts.app')

@section('title', 'Indikator Kinerja')
@section('page-title', 'Indikator Kinerja')
@section('page-subtitle', 'Kelola IKU (Kemendikbud) dan IKT (Institusional) sesuai SN-Dikti')

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel text-emerald-500"></i>
            <span>Import Excel</span>
        </button>
        <a href="{{ route('indikator-kinerja.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Indikator</span>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Indikator Kinerja</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-rose-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-rose-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-award-fill fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['IKU'] }}</h2>
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-1 d-block">IKU — Kinerja Utama</span>
                    <span class="text-[10px] font-medium text-slate-400 mt-0.5 d-block">Wajib Kemendikbudristek (KepMen 3/2021)</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-amber-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-amber-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-bar-chart-fill fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['IKT'] }}</h2>
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-1 d-block">IKT — Kinerja Tambahan</span>
                    <span class="text-[10px] font-medium text-slate-400 mt-0.5 d-block">Indikator Khusus Visi-Misi Institusi</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-slate-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-slate-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-sliders fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $summary['custom'] }}</h2>
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-1 d-block">Custom — Unit Kerja</span>
                    <span class="text-[10px] font-medium text-slate-400 mt-0.5 d-block">Didefinisikan mandiri oleh unit kerja</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <div class="p-4 py-3.5">
        <form method="GET" action="{{ route('indikator-kinerja.index') }}">
            <div class="row g-2.5 align-items-center">
                <div class="col-md-2">
                    <select name="tipe" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Tipe</option>
                        <option value="IKU" {{ request('tipe') == 'IKU' ? 'selected' : '' }}>🔴 IKU — Utama</option>
                        <option value="IKT" {{ request('tipe') == 'IKT' ? 'selected' : '' }}>🟡 IKT — Tambahan</option>
                        <option value="custom" {{ request('tipe') == 'custom' ? 'selected' : '' }}>⚫ Custom</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="standar_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Standar</option>
                        @foreach($standars as $s)
                            <option value="{{ $s->id }}" {{ request('standar_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->kode }} - {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="position-relative">
                        <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 ps-9 pe-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Cari kode atau nama indikator..."
                            value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute text-slate-400 text-sm" style="left: 12px; top: 12px;"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="per_page" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 baris</option>
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 baris</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 baris</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1.5">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-3 py-2.5 text-sm font-bold text-white hover:bg-primary-dark transition-colors border-0">
                        <i class="bi bi-search"></i>
                        <span>Cari</span>
                    </button>
                    @if(request()->hasAny(['tipe','standar_id','search','per_page']))
                        <a href="{{ route('indikator-kinerja.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors text-decoration-none" style="min-width: 40px;">
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
                        <th width="100" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kode</th>
                        <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tipe</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Nama Indikator</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Unit Kerja</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Standar Acuan</th>
                        <th class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Target</th>
                        <th width="80" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Bobot</th>
                        <th width="100" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status</th>
                        <th width="100" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0 modern-badge-container">
                    @forelse($indikators as $i)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                        <td class="py-3.5 text-xs font-mono font-bold text-primary">
                            {{ $i->kode }}
                        </td>
                        <td class="py-3.5">
                            @if($i->tipe == 'IKU')
                                <span class="inline-flex items-center rounded-full bg-rose-50 border border-rose-150 text-rose-600 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wide">🔴 IKU</span>
                            @elseif($i->tipe == 'IKT')
                                <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-150 text-amber-600 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wide">🟡 IKT</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wide">⚫ Custom</span>
                            @endif
                        </td>
                        <td class="py-3.5">
                            <span class="text-sm font-bold text-slate-800 d-block">{{ $i->nama }}</span>
                            @if($i->sumber)
                                <span class="text-[10px] font-semibold text-slate-400 d-block mt-0.5"><i class="bi bi-info-circle me-1"></i>Sumber: {{ $i->sumber }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-sm font-semibold text-slate-600">{{ $i->unit_kerja }}</td>
                        <td class="py-3.5">
                            @if($i->standar)
                                <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-0.5 text-xs font-bold">
                                    {{ $i->standar->kode }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="text-center py-3.5 text-sm">
                            @if($i->target_deskripsi)
                                <span class="font-bold text-slate-700 block">{{ $i->target_deskripsi }}</span>
                                @if($i->target_nilai)
                                    <small class="text-slate-400 text-xs block mt-0.5">({{ $i->target_nilai + 0 }} {{ $i->unit_pengukuran }})</small>
                                @endif
                            @else
                                <span class="font-bold text-slate-700">{{ $i->target_nilai + 0 }} {{ $i->unit_pengukuran }}</span>
                            @endif
                        </td>
                        <td class="text-center py-3.5">
                            <span class="inline-flex items-center justify-center rounded-lg bg-slate-50 border border-slate-150 text-slate-500 px-2 py-0.5 text-xs font-bold">
                                {{ $i->bobot }}%
                            </span>
                        </td>
                        <td class="text-center py-3.5">
                            @if($i->is_aktif)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Aktif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-dash-circle-fill"></i>
                                    <span>Nonaktif</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-end py-3.5 pe-4">
                            <div class="d-flex gap-1.5 justify-content-end">
                                <a href="{{ route('indikator-kinerja.edit', $i) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('indikator-kinerja.destroy', $i) }}" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Hapus indikator ini?')">
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
                        <td colspan="10" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-center py-5">
                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                    <i class="bi bi-bullseye fs-1"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Indikator Kinerja</h6>
                                <p class="text-xs font-medium text-slate-400 mb-0">Belum ada indikator kinerja yang didefinisikan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($indikators->hasPages())
    <div class="p-4 border-t border-slate-100 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-xs font-semibold text-slate-400">
            Menampilkan {{ $indikators->firstItem() }}-{{ $indikators->lastItem() }} dari {{ $indikators->total() }} data
        </div>
        <div class="modern-pagination">
            {{ $indikators->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content border-0 rounded-2xl shadow-xl overflow-hidden'>
            <form action='{{ route('indikator-kinerja.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-0 py-3.5 px-4'>
                    <div class="d-flex align-items-center gap-2">
                        <i class='bi bi-file-earmark-excel fs-5'></i>
                        <h6 class='modal-title font-bold text-white mb-0' id='importModalLabel'>Import Indikator Kinerja</h6>
                    </div>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold d-flex gap-2 mb-4'>
                        <i class='bi bi-info-circle-fill fs-5 text-blue-500'></i>
                        <span>Harap sesuaikan format kolom Excel Anda sebelum melakukan import data agar sinkronisasi indikator berjalan lancar.</span>
                    </div>
                    
                    <div class='mb-4 text-center'>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Struktur Kolom Excel</span>
                        <p class="text-[10px] font-semibold text-slate-700 bg-slate-100 p-2.5 rounded-xl border border-slate-200 leading-relaxed">
                            kode, nama, tipe (IKU/IKT/custom), bobot, unit_pengukuran, target_nilai, target_deskripsi, unit_kerja, kode_standar, sumber
                        </p>
                        <a href="{{ route('indikator-kinerja.template') }}" class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50/20 px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 text-decoration-none mt-3">
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
