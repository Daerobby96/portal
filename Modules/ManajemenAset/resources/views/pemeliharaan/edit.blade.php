@extends('manajemenaset::layouts.master')

@section('title', 'Edit Pemeliharaan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pemeliharaan.index') }}">Pemeliharaan</a></li>
<li class="breadcrumb-item"><a href="{{ route('pemeliharaan.show', $pemeliharaan) }}">Detail</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Pemeliharaan')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Info Aset -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Kode Aset</label>
                            <div class="fw-semibold">{{ $pemeliharaan->aset->kode_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Aset</label>
                            <div class="fw-semibold">{{ $pemeliharaan->aset->nama_aset }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('pemeliharaan.update', $pemeliharaan) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Pemeliharaan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Pemeliharaan</label>
                                <input type="date" name="tanggal_pemeliharaan" class="form-control @error('tanggal_pemeliharaan') is-invalid @enderror" 
                                    value="{{ old('tanggal_pemeliharaan', $pemeliharaan->tanggal_pemeliharaan->format('Y-m-d')) }}" required>
                                @error('tanggal_pemeliharaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Jenis Pemeliharaan</label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="preventif" {{ old('jenis', $pemeliharaan->jenis) == 'preventif' ? 'selected' : '' }}>Preventif</option>
                                    <option value="korektif" {{ old('jenis', $pemeliharaan->jenis) == 'korektif' ? 'selected' : '' }}>Korektif</option>
                                    <option value="kalibrasi" {{ old('jenis', $pemeliharaan->jenis) == 'kalibrasi' ? 'selected' : '' }}>Kalibrasi</option>
                                    <option value="inspeksi" {{ old('jenis', $pemeliharaan->jenis) == 'inspeksi' ? 'selected' : '' }}>Inspeksi</option>
                                </select>
                                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label required">Deskripsi Kegiatan</label>
                                <textarea name="deskripsi_kegiatan" class="form-control @error('deskripsi_kegiatan') is-invalid @enderror" rows="3" required>{{ old('deskripsi_kegiatan', $pemeliharaan->deskripsi_kegiatan) }}</textarea>
                                @error('deskripsi_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Temuan</label>
                                <textarea name="temuan" class="form-control @error('temuan') is-invalid @enderror" rows="3">{{ old('temuan', $pemeliharaan->temuan) }}</textarea>
                                @error('temuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Tindakan</label>
                                <textarea name="tindakan" class="form-control @error('tindakan') is-invalid @enderror" rows="3">{{ old('tindakan', $pemeliharaan->tindakan) }}</textarea>
                                @error('tindakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Hasil Pemeliharaan</label>
                                <select name="hasil" class="form-select @error('hasil') is-invalid @enderror" required>
                                    <option value="">Pilih Hasil</option>
                                    <option value="baik" {{ old('hasil', $pemeliharaan->hasil) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="perlu_perbaikan" {{ old('hasil', $pemeliharaan->hasil) == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                    <option value="perlu_penggantian" {{ old('hasil', $pemeliharaan->hasil) == 'perlu_penggantian' ? 'selected' : '' }}>Perlu Penggantian</option>
                                </select>
                                @error('hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pemeliharaan Berikutnya</label>
                                <input type="date" name="tanggal_berikutnya" class="form-control @error('tanggal_berikutnya') is-invalid @enderror" 
                                    value="{{ old('tanggal_berikutnya', $pemeliharaan->tanggal_berikutnya?->format('Y-m-d')) }}">
                                @error('tanggal_berikutnya')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Biaya & Vendor</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Biaya (Rp)</label>
                                <input type="number" name="biaya" class="form-control @error('biaya') is-invalid @enderror" 
                                    value="{{ old('biaya', $pemeliharaan->biaya) }}" min="0" step="0.01">
                                @error('biaya')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Vendor/Teknisi</label>
                                <input type="text" name="vendor" class="form-control @error('vendor') is-invalid @enderror" 
                                    value="{{ old('vendor', $pemeliharaan->vendor) }}">
                                @error('vendor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Bukti Foto</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Upload Foto</label>
                                @if($pemeliharaan->bukti_foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $pemeliharaan->bukti_foto) }}" alt="Bukti Foto" class="img-thumbnail" style="max-height: 200px">
                                    <div class="form-text">Foto saat ini. Upload file baru untuk menggantinya.</div>
                                </div>
                                @endif
                                <input type="file" name="bukti_foto" class="form-control @error('bukti_foto') is-invalid @enderror" 
                                    accept="image/*">
                                @error('bukti_foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('pemeliharaan.show', $pemeliharaan) }}" class="btn btn-secondary">
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
