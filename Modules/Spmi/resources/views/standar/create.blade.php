@extends('layouts.app')

@section('title', 'Tambah Standar')
@section('page-title', 'Tambah Standar Mutu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('standar.index') }}">Standar Mutu</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-bookmark-plus-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Form Standar Mutu Baru</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('standar.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kode Standar <span class="text-danger">*</span></label>
                            <input type="text" name="kode"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kode') is-invalid @enderror"
                                value="{{ old('kode') }}"
                                placeholder="SNDikti / ISO9001"
                                style="text-transform:uppercase"
                                required>
                            @error('kode') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nama Standar <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}"
                                placeholder="Standar Nasional Pendidikan Tinggi"
                                required>
                            @error('nama') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Bidang <span class="text-danger">*</span></label>
                            <select name="bidang" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('bidang') is-invalid @enderror" required>
                                <option value="">Pilih Bidang</option>
                                @foreach($bidangOptions as $val => $label)
                                    <option value="{{ $val }}" {{ old('bidang') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('bidang') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Jenis Standar <span class="text-danger">*</span></label>
                            <select name="jenis" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('jenis') is-invalid @enderror" required>
                                @foreach($jenisOptions as $val => $label)
                                    <option value="{{ $val }}" {{ old('jenis') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('jenis') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nomor Urut <span class="text-muted text-[10px]">(Opsional)</span></label>
                            <input type="number" name="nomor" min="1"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('nomor') is-invalid @enderror"
                                value="{{ old('nomor') }}"
                                placeholder="1, 2, 3...">
                            @error('nomor') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="4"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('deskripsi') is-invalid @enderror"
                                placeholder="Deskripsi standar mutu...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-0 d-flex align-items-center gap-2">
                                <input class="form-check-input ms-0" type="checkbox" name="is_aktif"
                                    id="isAktif" value="1" checked style="width: 2.5em; height: 1.25em;">
                                <label class="text-xs font-semibold text-slate-500" for="isAktif">
                                    Standar Aktif (dapat digunakan sebagai acuan dokumen)
                                </label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('standar.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Standar</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection