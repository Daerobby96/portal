@extends('layouts.app')

@section('title', 'Edit Kategori Dokumen')
@section('page-title', 'Edit Kategori Dokumen')
@section('page-subtitle', 'Ubah data kategori dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kategori-dokumen.index') }}">Kategori Dokumen</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-pencil-square fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Edit Kategori: {{ $kategoriDokumen->nama }}</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('kategori-dokumen.update', $kategoriDokumen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kode') is-invalid @enderror"
                            value="{{ old('kode', $kategoriDokumen->kode) }}"
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
                            value="{{ old('nama', $kategoriDokumen->nama) }}"
                            placeholder="Standar Operasional Prosedur"
                            required>
                        @error('nama') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Warna Badge</label>
                        <select name="warna" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                            @foreach(['primary','success','danger','warning','info','dark','secondary','indigo'] as $w)
                            <option value="{{ $w }}" {{ (old('warna', $kategoriDokumen->warna) ?? 'primary') == $w ? 'selected' : '' }}>
                                {{ ucfirst($w) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Deskripsi singkat...">{{ old('keterangan', $kategoriDokumen->keterangan) }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('kategori-dokumen.index') }}" class="flex-fill inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                            <i class="bi bi-arrow-left"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="flex-fill inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                            <i class="bi bi-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Informasi Kategori</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3 modern-badge-container">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Kategori</span>
                        <span class="inline-flex items-center rounded-lg bg-{{ $kategoriDokumen->warna ?? 'secondary' }}-50 border border-{{ $kategoriDokumen->warna ?? 'secondary' }}-200 text-{{ $kategoriDokumen->warna ?? 'secondary' }}-600 px-2.5 py-1 text-xs font-extrabold">
                            {{ $kategoriDokumen->kode }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Kategori</span>
                        <span class="text-sm font-bold text-slate-700">{{ $kategoriDokumen->nama }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jumlah Dokumen</span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-2.5 py-0.5 text-xs font-bold">
                            {{ $kategoriDokumen->dokumens()->count() }} dokumen
                        </span>
                    </div>
                    @if($kategoriDokumen->keterangan)
                    <div class="py-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider d-block mb-1">Keterangan</span>
                        <p class="mb-0 text-slate-600 text-sm leading-relaxed font-medium">{{ $kategoriDokumen->keterangan }}</p>
                    </div>
                    @endif
                </div>
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
