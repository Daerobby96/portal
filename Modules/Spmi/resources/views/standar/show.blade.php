@extends('layouts.app')

@section('title', 'Detail Standar')
@section('page-title', 'Detail Standar Mutu')
@section('page-subtitle', $standar->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('standar.index') }}">Standar Mutu</a></li>
    <li class="breadcrumb-item active">{{ $standar->kode }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Standar --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] sticky-top" style="top: 80px; overflow: hidden;">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                        <i class="bi bi-bookmark-check-fill fs-5"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Informasi Standar</h6>
                </div>
                
                @if($standar->is_aktif)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600 border border-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span>Aktif</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-400 border border-slate-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                        <span>Nonaktif</span>
                    </span>
                @endif
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Standar</span>
                        <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-xs font-extrabold text-indigo-600">
                            {{ $standar->kode }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Standar</span>
                        <span class="text-sm font-bold text-slate-700 text-end max-w-[180px]">{{ $standar->nama }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jumlah Dokumen</span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-2.5 py-0.5 text-xs font-bold">
                            {{ $standar->dokumens->count() }} dokumen
                        </span>
                    </div>
                </div>

                @if($standar->deskripsi)
                <div class="mb-4 p-3 rounded-xl border border-slate-100 bg-slate-50/40 relative overflow-hidden">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 d-block mb-1">Deskripsi Standar</span>
                    <p class="mb-0 text-slate-700 text-sm leading-relaxed font-medium">{{ $standar->deskripsi }}</p>
                </div>
                @endif
                
                <div class="d-flex gap-2 mt-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('standar.edit', $standar) }}" class="flex-fill inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('standar.index') }}" class="flex-fill inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Dokumen --}}
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-file-earmark-text fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Dokumen Terkait</h6>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th width="60" class="text-center text-xs font-bold uppercase tracking-wider text-slate-400 py-3 ps-4">#</th>
                                <th class="text-xs font-bold uppercase tracking-wider text-slate-400 py-3">Judul Dokumen</th>
                                <th width="120" class="text-xs font-bold uppercase tracking-wider text-slate-400 py-3">Kategori</th>
                                <th width="120" class="text-xs font-bold uppercase tracking-wider text-slate-400 py-3">Periode</th>
                                <th width="120" class="text-xs font-bold uppercase tracking-wider text-slate-400 py-3">Status</th>
                                <th width="100" class="text-center text-xs font-bold uppercase tracking-wider text-slate-400 py-3 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-t-0">
                            @forelse($standar->dokumens as $dokumen)
                            <tr class="border-b border-slate-100 transition-colors hover:bg-slate-50/40">
                                <td class="text-center text-slate-400 text-sm font-semibold py-3.5 ps-4">{{ $loop->iteration }}</td>
                                <td class="py-3.5 text-sm font-bold text-slate-800">
                                    <a href="{{ route('dokumen.show', $dokumen) }}" class="text-inherit text-decoration-none hover:text-primary transition-colors">{{ $dokumen->judul }}</a>
                                </td>
                                <td class="py-3.5 text-xs font-bold">
                                    @if($dokumen->kategori)
                                        <span class="inline-flex items-center rounded-lg bg-{{ $dokumen->kategori->warna ?? 'secondary' }}-50 border border-{{ $dokumen->kategori->warna ?? 'secondary' }}-200 text-{{ $dokumen->kategori->warna ?? 'secondary' }}-600 px-2 py-0.5">
                                            {{ $dokumen->kategori->kode }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-xs font-bold text-slate-500">
                                    {{ $dokumen->periode->nama ?? '—' }}
                                </td>
                                <td class="py-3.5 text-xs font-bold">
                                    @if($dokumen->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5">
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5">
                                            <span>Tidak Aktif</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-3.5 pe-4">
                                    <a href="{{ route('dokumen.show', $dokumen) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-5">
                                    <div class="d-flex flex-column align-items-center justify-center py-4">
                                        <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                            <i class="bi bi-file-earmark-x fs-1"></i>
                                        </div>
                                        <h6 class="font-bold text-slate-700 mb-1">Belum Ada Dokumen</h6>
                                        <p class="text-xs font-medium text-slate-400 mb-0">Belum ada dokumen yang dikaitkan dengan standar mutu ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
