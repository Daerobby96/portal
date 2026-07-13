@extends('manajemenaset::layouts.master')

@section('title', 'Tambah Kategori Aset')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('kategori-aset.index') }}">Kategori Aset</a></li>
<li class="breadcrumb-item active">Tambah Kategori</li>
@endsection
@section('page-title', 'Tambah Kategori Aset')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('kategori-aset.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kode Kategori</label>
                                <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                                    value="{{ old('kode') }}" placeholder="KAT-001" required>
                                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Kode unik untuk kategori</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Nama Kategori</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama') }}" placeholder="Elektronik" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Icon (Bootstrap Icons)</label>
                                <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                    value="{{ old('icon', 'bi bi-box') }}" placeholder="bi bi-box" required>
                                @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Contoh: bi bi-laptop, bi bi-printer</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Warna Badge</label>
                                <select name="color" class="form-select @error('color') is-invalid @enderror" required>
                                    <option value="primary" {{ old('color') == 'primary' ? 'selected' : '' }}>Primary (Biru)</option>
                                    <option value="success" {{ old('color') == 'success' ? 'selected' : '' }}>Success (Hijau)</option>
                                    <option value="danger" {{ old('color') == 'danger' ? 'selected' : '' }}>Danger (Merah)</option>
                                    <option value="warning" {{ old('color') == 'warning' ? 'selected' : '' }}>Warning (Kuning)</option>
                                    <option value="info" {{ old('color') == 'info' ? 'selected' : '' }}>Info (Cyan)</option>
                                    <option value="secondary" {{ old('color') == 'secondary' ? 'selected' : '' }}>Secondary (Abu)</option>
                                    <option value="dark" {{ old('color') == 'dark' ? 'selected' : '' }}>Dark (Hitam)</option>
                                </select>
                                @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" 
                                    placeholder="Deskripsi kategori (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_aktif" class="form-check-input" id="is_aktif" 
                                        value="1" {{ old('is_aktif', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_aktif">
                                        Kategori Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                    <a href="{{ route('kategori-aset.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}
</style>
@endpush
@endsection
