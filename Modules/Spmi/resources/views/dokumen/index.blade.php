@extends('layouts.app')

@section('title', 'Dokumen Mutu')

@section('page-title', 'Dokumen Mutu')
@section('page-subtitle', 'Kelola semua dokumen standar mutu internal')

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel text-emerald-500"></i>
            <span>Import Metadata</span>
        </button>
        <a href="{{ route('dokumen.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Dokumen</span>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dokumen Mutu</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    {{-- Card 1: Total Dokumen --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-blue-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-blue-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-500 shadow-inner">
                    <i class="bi bi-folder2-open fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['total'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total Dokumen</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Approved --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-emerald-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-emerald-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 shadow-inner">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['approved'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Approved</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Sedang Review --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-amber-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-amber-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500 shadow-inner">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['review'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Sedang Review</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Kadaluarsa --}}
    <div class="col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-rose-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-rose-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500 shadow-inner">
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['kadaluarsa'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Kadaluarsa</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Panel --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <div class="p-4 py-3.5">
        <form method="GET" action="{{ route('dokumen.index') }}">
            <div class="row g-2.5 align-items-center">
                <div class="col-md-3">
                    <div class="position-relative">
                        <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 ps-9 pe-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Cari judul, kode, atau unit..."
                            value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute text-slate-400 text-sm" style="left: 12px; top: 12px;"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="kategori_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Status</option>
                        <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                        <option value="review"   {{ request('status') == 'review'   ? 'selected' : '' }}>Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="obsolete" {{ request('status') == 'obsolete' ? 'selected' : '' }}>Obsolete</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="standar_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Standar</option>
                        @foreach($standars as $s)
                            <option value="{{ $s->id }}" {{ request('standar_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->kode }} - {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="per_page" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-2 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 text-center" title="Tampilkan per halaman">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1.5">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-3 py-2.5 text-sm font-bold text-white hover:bg-primary-dark transition-colors border-0">
                        <i class="bi bi-search"></i>
                        <span>Cari</span>
                    </button>
                    @if(request()->hasAny(['search','kategori_id','status','standar_id','per_page']))
                        <a href="{{ route('dokumen.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors text-decoration-none" style="min-width: 40px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
@if($dokumens->count())
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100">
                        <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                        <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kode</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Judul Dokumen</th>
                        <th width="100" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kategori</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Unit Pemilik</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Standar Acuan</th>
                        <th width="80" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Versi</th>
                        <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tgl Terbit</th>
                        <th width="100" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status</th>
                        <th width="100" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Visibilitas</th>
                        <th width="150" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0 modern-badge-container">
                    @foreach($dokumens as $dok)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $dokumens->firstItem() + $loop->index }}</td>
                        <td class="py-3.5 text-xs font-mono font-bold text-primary">
                            {{ $dok->kode_dokumen }}
                        </td>
                        <td class="py-3.5">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $iconColorMap = [
                                        'pdf' => 'text-rose-500 bg-rose-50 border-rose-100',
                                        'docx' => 'text-blue-500 bg-blue-50 border-blue-100',
                                        'doc' => 'text-blue-500 bg-blue-50 border-blue-100',
                                        'xlsx' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
                                        'xls' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
                                        'pptx' => 'text-orange-500 bg-orange-50 border-orange-100',
                                        'ppt' => 'text-orange-500 bg-orange-50 border-orange-100',
                                    ];
                                    $styleClass = $iconColorMap[$dok->file_type] ?? 'text-slate-500 bg-slate-50 border-slate-100';
                                @endphp
                                <div class="d-flex h-9 w-9 items-center justify-center rounded-xl border shadow-inner {{ $styleClass }}" style="min-width: 36px;">
                                    @switch($dok->file_type)
                                        @case('pdf')  <i class="bi bi-file-earmark-pdf-fill fs-5"></i>  @break
                                        @case('docx')
                                        @case('doc')  <i class="bi bi-file-earmark-word-fill fs-5"></i> @break
                                        @case('xlsx')
                                        @case('xls')  <i class="bi bi-file-earmark-excel-fill fs-5"></i>@break
                                        @case('pptx')
                                        @case('ppt')  <i class="bi bi-file-earmark-slides-fill fs-5"></i>@break
                                        @default       <i class="bi bi-file-earmark-fill fs-5"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 d-block">{{ $dok->judul }}</span>
                                    @if($dok->tanggal_kadaluarsa && $dok->tanggal_kadaluarsa <= now())
                                        <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-rose-500 mt-0.5 uppercase tracking-wide">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Kadaluarsa
                                        </span>
                                    @elseif($dok->tanggal_kadaluarsa && $dok->tanggal_kadaluarsa <= now()->addDays(30))
                                        <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-amber-500 mt-0.5 uppercase tracking-wide">
                                            <i class="bi bi-clock-fill"></i> Kadaluarsa {{ $dok->tanggal_kadaluarsa->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5">
                            @if($dok->kategori)
                                <span class="inline-flex items-center rounded-lg bg-{{ $dok->kategori->warna ?? 'secondary' }}-50 border border-{{ $dok->kategori->warna ?? 'secondary' }}-200 text-{{ $dok->kategori->warna ?? 'secondary' }}-600 px-2.5 py-1 text-xs font-extrabold">
                                    {{ $dok->kategori->kode }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-sm font-semibold text-slate-600">{{ $dok->unit_pemilik }}</td>
                        <td class="py-3.5">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($dok->standars as $standar)
                                    <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 px-2 py-0.5 text-[10px] font-extrabold text-indigo-600">
                                        {{ $standar->kode }}
                                    </span>
                                @endforeach
                                @if($dok->standars->isEmpty())
                                    <span class="text-slate-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center py-3.5">
                            <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 text-slate-500 px-2.5 py-0.5 text-xs font-bold">
                                v{{ $dok->versi }}
                            </span>
                        </td>
                        <td class="py-3.5 text-xs font-bold text-slate-500">{{ $dok->tanggal_terbit->translatedFormat('d M Y') }}</td>
                        <td class="text-center py-3.5">
                            @php
                                $statusStyleMap = [
                                    'draft' => 'bg-slate-50 border-slate-200 text-slate-500',
                                    'review' => 'bg-amber-50 border-amber-200 text-amber-600',
                                    'approved' => 'bg-emerald-50 border-emerald-200 text-emerald-600',
                                    'obsolete' => 'bg-dark border-dark text-white',
                                ];
                                $labelMap = [
                                    'draft' => 'Draft',
                                    'review' => 'Review',
                                    'approved' => 'Approved',
                                    'obsolete' => 'Obsolete',
                                ];
                                $curStyle = $statusStyleMap[$dok->status] ?? 'bg-slate-50 border-slate-200 text-slate-500';
                                $curLabel = $labelMap[$dok->status] ?? $dok->status;
                            @endphp
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $curStyle }}">
                                {{ $curLabel }}
                            </span>
                        </td>
                        <td class="text-center py-3.5">
                            @if($dok->is_public)
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-globe"></i>
                                    <span>Publik</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-lock-fill"></i>
                                    <span>Internal</span>
                                </span>
                            @endif
                        </td>
                        <td class="text-end py-3.5 pe-4">
                            <div class="d-flex gap-1.5 justify-content-end">
                                @if($dok->file_path)
                                <a href="{{ route('dokumen.download', $dok) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50/20 text-emerald-600 transition-all hover:bg-emerald-500 hover:text-white" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                                <a href="{{ route('dokumen.show', $dok) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('dokumen.edit', $dok) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('dokumen.destroy', $dok) }}" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    @if($dokumens->hasPages())
    <div class="p-4 border-t border-slate-100 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-xs font-semibold text-slate-400">
            Menampilkan {{ $dokumens->firstItem() }}-{{ $dokumens->lastItem() }} dari {{ $dokumens->total() }} dokumen
        </div>
        <div class="modern-pagination">
            {{ $dokumens->links() }}
        </div>
    </div>
    @endif
</div>

@else
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden py-5">
    <div class="d-flex flex-column align-items-center justify-center py-5">
        <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
            <i class="bi bi-folder2-open fs-1"></i>
        </div>
        <h6 class="font-bold text-slate-700 mb-1">Belum Ada Dokumen Mutu</h6>
        <p class="text-xs font-medium text-slate-400 mb-4">Mulai tambahkan dokumen mutu pertama Anda.</p>
        <a href="{{ route('dokumen.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Dokumen</span>
        </a>
    </div>
</div>
@endif

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content border-0 rounded-2xl shadow-xl overflow-hidden'>
            <form action='{{ route('dokumen.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-0 py-3.5 px-4'>
                    <div class="d-flex align-items-center gap-2">
                        <i class='bi bi-file-earmark-excel fs-5'></i>
                        <h6 class='modal-title font-bold text-white mb-0' id='importModalLabel'>Import Metadata Dokumen</h6>
                    </div>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold d-flex gap-2 mb-4'>
                        <i class='bi bi-info-circle-fill fs-5 text-blue-500'></i>
                        <span>Fitur ini hanya mengimport metadata dokumen. File fisik dokumen harus diunggah secara manual setelah data berhasil terimport.</span>
                    </div>
                    
                    <div class='mb-4 text-center'>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Format Heading Excel</span>
                        <p class="text-xs font-semibold text-slate-700 bg-slate-100 p-2 rounded-xl border border-slate-200">nama, kategori, kode_standar, versi, tahun, deskripsi</p>
                        <a href="{{ route('dokumen.template') }}" class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50/20 px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 text-decoration-none mt-2">
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
.modern-badge-container .bg-danger {
    background-color: rgba(244, 63, 94, 0.1) !important;
    color: #f43f5e !important;
    border: 1px solid rgba(244, 63, 94, 0.2) !important;
}
.modern-badge-container .bg-warning {
    background-color: rgba(245, 158, 11, 0.1) !important;
    color: #f59e0b !important;
    border: 1px solid rgba(245, 158, 11, 0.2) !important;
}
.modern-badge-container .bg-info {
    background-color: rgba(6, 182, 212, 0.1) !important;
    color: #06b6d4 !important;
    border: 1px solid rgba(6, 182, 212, 0.2) !important;
}
.modern-badge-container .bg-dark {
    background-color: rgba(15, 23, 42, 0.1) !important;
    color: #0f172a !important;
    border: 1px solid rgba(15, 23, 42, 0.2) !important;
}
.modern-badge-container .bg-secondary {
    background-color: rgba(100, 116, 139, 0.1) !important;
    color: #64748b !important;
    border: 1px solid rgba(100, 116, 139, 0.2) !important;
}
.modern-badge-container .bg-indigo {
    background-color: rgba(99, 102, 241, 0.1) !important;
    color: #6366f1 !important;
    border: 1px solid rgba(99, 102, 241, 0.2) !important;
}
</style>
@endpush
