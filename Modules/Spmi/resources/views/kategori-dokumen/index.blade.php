@extends('layouts.app')

@section('title', 'Kategori Dokumen')
@section('page-title', 'Kategori Dokumen')
@section('page-subtitle', 'Kelola kategori untuk pengelompokan dokumen mutu')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori Dokumen</li>
@endsection

@section('content')
<div class="row g-4">

    {{-- Daftar Kategori --}}
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th width="60" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                                <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kode</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Nama Kategori</th>
                                <th width="130" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Warna Badge</th>
                                <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Dokumen</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Keterangan</th>
                                <th width="100" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-t-0 modern-badge-container">
                            @forelse($kategoris as $k)
                            <tr class="border-b border-slate-100 transition-colors hover:bg-slate-50/40">
                                <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center rounded-lg bg-{{ $k->warna ?? 'secondary' }}-50 border border-{{ $k->warna ?? 'secondary' }}-200 text-{{ $k->warna ?? 'secondary' }}-600 px-2.5 py-1 text-xs font-extrabold">
                                        {{ $k->kode }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-sm font-bold text-slate-800">{{ $k->nama }}</td>
                                <td class="py-3.5 text-xs font-semibold text-slate-500">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="h-2 w-2 rounded-full bg-{{ $k->warna ?? 'secondary' }}"></span>
                                        <span>{{ ucfirst($k->warna ?? 'secondary') }}</span>
                                    </span>
                                </td>
                                <td class="text-center py-3.5">
                                    <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-slate-50 border border-slate-150 px-2 text-xs font-bold text-slate-500">
                                        {{ $k->dokumens_count }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-xs font-medium text-slate-400 max-w-xs truncate">{{ $k->keterangan ?? '—' }}</td>
                                <td class="text-center py-3.5 pe-4">
                                    <div class="d-flex gap-1.5 justify-content-end">
                                        <a href="{{ route('kategori-dokumen.edit', $k) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategori-dokumen.destroy', $k) }}"
                                              method="POST" class="d-inline m-0"
                                              onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white"
                                                {{ $k->dokumens_count > 0 ? 'disabled' : '' }}
                                                title="{{ $k->dokumens_count > 0 ? 'Masih ada dokumen terikat' : 'Hapus Kategori' }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="d-flex flex-column align-items-center justify-center py-4">
                                        <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                            <i class="bi bi-tags fs-1"></i>
                                        </div>
                                        <h6 class="font-bold text-slate-700 mb-1">Belum Ada Kategori</h6>
                                        <p class="text-xs font-medium text-slate-400 mb-0">Belum ada kategori dokumen yang ditambahkan.</p>
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

    {{-- Form Tambah Cepat --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-tag-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Tambah Kategori Baru</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('kategori-dokumen.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kode') is-invalid @enderror"
                            value="{{ old('kode') }}"
                            placeholder="SOP / SK / PM / IK / FR"
                            style="text-transform:uppercase"
                            maxlength="10" required>
                        @error('kode') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Singkatan max 10 karakter</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}"
                            placeholder="Standar Operasional Prosedur"
                            required>
                        @error('nama') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Warna Badge</label>
                        <select name="warna" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                            @foreach(['primary','success','danger','warning','info','dark','secondary','indigo'] as $w)
                            <option value="{{ $w }}" {{ old('warna') == $w ? 'selected' : '' }}>
                                {{ ucfirst($w) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Deskripsi singkat...">{{ old('keterangan') }}</textarea>
                    </div>
                    
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Kategori</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Info warna yang tersedia --}}
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-palette-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Contoh Warna Badge</h6>
            </div>
            <div class="p-4 d-flex flex-wrap gap-2 modern-badge-container">
                @foreach(['primary','success','danger','warning','info','dark','secondary','indigo'] as $w)
                    <span class="badge bg-{{ $w }}">{{ ucfirst($w) }}</span>
                @endforeach
            </div>
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