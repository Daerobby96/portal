@extends('manajemen-surat::layouts.master')

@section('title', 'Surat Keluar')
@section('page-title', 'Surat Keluar')
@section('page-subtitle', 'Kelola semua surat keluar instansi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Surat Keluar</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Alert --}}
    <div class="col-12">
        <div class="alert alert-info border-0 rounded-2xl d-flex align-items-start gap-3 bg-blue-50 border-l-4 border-blue-500">
            <i class="bi bi-info-circle-fill text-blue-600 fs-5 mt-1"></i>
            <div>
                <h6 class="font-bold text-blue-800 mb-1">Sistem Pencatatan Surat (Record-Only)</h6>
                <p class="text-sm text-blue-700 mb-0">
                    Sistem ini mencatat metadata surat saja untuk menghemat storage. 
                    File PDF akan <strong>di-generate otomatis</strong> saat Anda klik tombol "Preview" atau "Download" 
                    dan <strong>tidak disimpan</strong> di server.
                </p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('surat-keluar.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-xs font-semibold text-slate-600">Jenis Surat</label>
                        <select name="jenis_surat_id" class="form-select">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSurat as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-semibold text-slate-600">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-xs font-semibold text-slate-600">Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-xs font-semibold text-slate-600">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Nomor/perihal/tujuan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('surat-keluar.index') }}" class="btn btn-light">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-header bg-white border-b border-slate-100 p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-xl bg-blue-50 p-2">
                        <i class="bi bi-box-arrow-up-right text-blue-600"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-bold text-slate-800">Daftar Surat Keluar</h6>
                        <p class="text-xs text-slate-400 mb-0">Total: {{ $suratKeluar->total() }} surat</p>
                    </div>
                </div>
                <a href="{{ route('surat-keluar.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition-all">
                    <i class="bi bi-plus-lg"></i> Buat Surat Baru
                </a>
            </div>

            @if(session('success'))
            <div class="mx-4 mt-3 alert alert-success d-flex align-items-center gap-2 border-0 rounded-xl bg-emerald-50 text-emerald-700">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mx-4 mt-3 alert alert-danger d-flex align-items-center gap-2 border-0 rounded-xl bg-rose-50 text-rose-700">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
            @endif

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-slate-50/70">
                            <tr>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3 px-4">#</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3">Nomor Surat</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3">Jenis</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3">Perihal</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3">Tujuan</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3">Tanggal</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3 text-center">Status</th>
                                <th class="text-xs font-bold uppercase text-slate-400 py-3 text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suratKeluar as $surat)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 text-sm text-slate-400 font-semibold">{{ $loop->iteration + ($suratKeluar->currentPage() - 1) * $suratKeluar->perPage() }}</td>
                                <td class="py-3">
                                    <div class="font-bold text-sm text-slate-800">{{ $surat->nomor_surat }}</div>
                                    <div class="text-xs text-slate-400">{{ $surat->creator->name }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="text-xs font-semibold text-slate-600">{{ $surat->jenisSurat->nama }}</span>
                                </td>
                                <td class="py-3 max-w-xs">
                                    <div class="text-sm text-slate-700 line-clamp-2">{{ $surat->perihal }}</div>
                                </td>
                                <td class="py-3 text-sm text-slate-600">{{ Str::limit($surat->tujuan, 30) }}</td>
                                <td class="py-3 text-sm text-slate-600">{{ $surat->tanggal_surat->format('d M Y') }}</td>
                                <td class="py-3 text-center">
                                    @if($surat->status === 'draft')
                                    <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 px-2.5 py-1 text-xs font-bold text-slate-600">Draft</span>
                                    @elseif($surat->status === 'pending')
                                    <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2.5 py-1 text-xs font-bold text-amber-600">Pending</span>
                                    @elseif($surat->status === 'approved')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-xs font-bold text-emerald-600">Approved</span>
                                    @elseif($surat->status === 'published')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-blue-600">Published</span>
                                    @else
                                    <span class="inline-flex items-center rounded-full bg-rose-50 border border-rose-200 px-2.5 py-1 text-xs font-bold text-rose-600">Rejected</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('surat-keluar.show', $surat) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('surat-keluar.pdf', $surat) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all" title="Generate & Download PDF">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                        @if($surat->isEditable())
                                        <a href="{{ route('surat-keluar.edit', $surat) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        <form action="{{ route('surat-keluar.destroy', $surat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus surat ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-5">
                                    <div class="text-center">
                                        <div class="rounded-2xl bg-slate-50 d-inline-flex p-4 mb-3">
                                            <i class="bi bi-inbox text-slate-300 fs-1"></i>
                                        </div>
                                        <h6 class="font-bold text-slate-700">Belum Ada Surat</h6>
                                        <p class="text-sm text-slate-400 mb-3">Mulai buat surat keluar pertama</p>
                                        <a href="{{ route('surat-keluar.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">
                                            <i class="bi bi-plus-lg"></i> Buat Surat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($suratKeluar->hasPages())
            <div class="card-footer bg-white border-t border-slate-100 p-4">
                {{ $suratKeluar->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
