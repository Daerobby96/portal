@extends('manajemenaset::layouts.master')

@section('title', 'Edit Aset')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Inventaris Aset</a></li>
<li class="breadcrumb-item"><a href="{{ route('aset.show', $aset) }}">{{ $aset->nama_aset }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Aset')
@section('page-subtitle', 'Perbarui informasi aset')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('aset.update', $aset) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Dasar</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kategori Aset</label>
                                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $aset->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Program Studi</label>
                                <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                                    <option value="">Umum</option>
                                    @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_id', $aset->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Kode Aset</label>
                                <input type="text" name="kode_aset" class="form-control @error('kode_aset') is-invalid @enderror" 
                                    value="{{ old('kode_aset', $aset->kode_aset) }}" required>
                                @error('kode_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Nama Aset</label>
                                <input type="text" name="nama_aset" class="form-control @error('nama_aset') is-invalid @enderror" 
                                    value="{{ old('nama_aset', $aset->nama_aset) }}" required>
                                @error('nama_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Merk</label>
                                <input type="text" name="merk" class="form-control @error('merk') is-invalid @enderror" 
                                    value="{{ old('merk', $aset->merk) }}">
                                @error('merk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipe/Model</label>
                                <input type="text" name="tipe" class="form-control @error('tipe') is-invalid @enderror" 
                                    value="{{ old('tipe', $aset->tipe) }}">
                                @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Nomor Seri</label>
                                <input type="text" name="nomor_seri" class="form-control @error('nomor_seri') is-invalid @enderror" 
                                    value="{{ old('nomor_seri', $aset->nomor_seri) }}">
                                @error('nomor_seri')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Spesifikasi</label>
                                <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror" rows="3">{{ old('spesifikasi', $aset->spesifikasi) }}</textarea>
                                @error('spesifikasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Status & Kondisi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi</label>
                                <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi', $aset->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi', $aset->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi', $aset->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    <option value="hilang" {{ old('kondisi', $aset->kondisi) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $aset->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non_aktif" {{ old('status', $aset->status) == 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
                                    <option value="dalam_perbaikan" {{ old('status', $aset->status) == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                    <option value="dihapuskan" {{ old('status', $aset->status) == 'dihapuskan' ? 'selected' : '' }}>Dihapuskan</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Lokasi & Penempatan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" 
                                    value="{{ old('lokasi', $aset->lokasi) }}" required>
                                @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ruangan</label>
                                <input type="text" name="ruangan" class="form-control @error('ruangan') is-invalid @enderror" 
                                    value="{{ old('ruangan', $aset->ruangan) }}">
                                @error('ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                    value="{{ old('penanggung_jawab', $aset->penanggung_jawab) }}">
                                @error('penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Perolehan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Perolehan</label>
                                <input type="date" name="tanggal_perolehan" class="form-control @error('tanggal_perolehan') is-invalid @enderror" 
                                    value="{{ old('tanggal_perolehan', $aset->tanggal_perolehan?->format('Y-m-d')) }}">
                                @error('tanggal_perolehan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Sumber Perolehan</label>
                                <input type="text" name="sumber_perolehan" class="form-control @error('sumber_perolehan') is-invalid @enderror" 
                                    value="{{ old('sumber_perolehan', $aset->sumber_perolehan) }}">
                                @error('sumber_perolehan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga Perolehan (Rp)</label>
                                <input type="number" name="harga_perolehan" class="form-control @error('harga_perolehan') is-invalid @enderror" 
                                    value="{{ old('harga_perolehan', $aset->harga_perolehan) }}" min="0" step="0.01">
                                @error('harga_perolehan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Umur Ekonomis (Tahun)</label>
                                <input type="number" name="umur_ekonomis" class="form-control @error('umur_ekonomis') is-invalid @enderror" 
                                    value="{{ old('umur_ekonomis', $aset->umur_ekonomis) }}" min="1">
                                @error('umur_ekonomis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Dokumentasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Foto Aset</label>
                                @if($aset->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $aset->foto) }}" alt="Foto Aset" class="img-thumbnail" style="max-height: 200px">
                                    <div class="form-text">Foto saat ini. Upload file baru untuk menggantinya.</div>
                                </div>
                                @endif
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                                    accept="image/*">
                                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $aset->keterangan) }}</textarea>
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('aset.show', $aset) }}" class="btn btn-secondary">
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
