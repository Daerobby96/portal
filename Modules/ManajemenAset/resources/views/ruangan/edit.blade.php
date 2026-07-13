@extends('manajemenaset::layouts.master')

@section('title', 'Edit Ruangan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('ruangan.index') }}">Data Ruangan</a></li>
<li class="breadcrumb-item"><a href="{{ route('ruangan.show', $ruangan) }}">{{ $ruangan->nama_ruangan }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Ruangan')
@section('page-subtitle', 'Perbarui informasi ruangan')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('ruangan.update', $ruangan) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Dasar</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kode Ruangan</label>
                                <input type="text" name="kode_ruangan" class="form-control @error('kode_ruangan') is-invalid @enderror" 
                                    value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}" required>
                                @error('kode_ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Nama Ruangan</label>
                                <input type="text" name="nama_ruangan" class="form-control @error('nama_ruangan') is-invalid @enderror" 
                                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
                                @error('nama_ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Jenis Ruangan</label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="kelas" {{ old('jenis', $ruangan->jenis) == 'kelas' ? 'selected' : '' }}>Kelas</option>
                                    <option value="lab" {{ old('jenis', $ruangan->jenis) == 'lab' ? 'selected' : '' }}>Lab</option>
                                    <option value="ruang_rapat" {{ old('jenis', $ruangan->jenis) == 'ruang_rapat' ? 'selected' : '' }}>Ruang Rapat</option>
                                    <option value="ruang_dosen" {{ old('jenis', $ruangan->jenis) == 'ruang_dosen' ? 'selected' : '' }}>Ruang Dosen</option>
                                    <option value="perpustakaan" {{ old('jenis', $ruangan->jenis) == 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                                    <option value="lainnya" {{ old('jenis', $ruangan->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Program Studi</label>
                                <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                                    <option value="">Umum</option>
                                    @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_id', $ruangan->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Lokasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Gedung</label>
                                <input type="text" name="gedung" class="form-control @error('gedung') is-invalid @enderror" 
                                    value="{{ old('gedung', $ruangan->gedung) }}">
                                @error('gedung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Lantai</label>
                                <input type="text" name="lantai" class="form-control @error('lantai') is-invalid @enderror" 
                                    value="{{ old('lantai', $ruangan->lantai) }}">
                                @error('lantai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Spesifikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kapasitas (Orang)</label>
                                <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" 
                                    value="{{ old('kapasitas', $ruangan->kapasitas) }}" min="1">
                                @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Luas (m²)</label>
                                <input type="number" name="luas" class="form-control @error('luas') is-invalid @enderror" 
                                    value="{{ old('luas', $ruangan->luas) }}" min="0" step="0.01">
                                @error('luas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Kondisi</label>
                                <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                    <option value="baik" {{ old('kondisi', $ruangan->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi', $ruangan->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi', $ruangan->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="tersedia" {{ old('status', $ruangan->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="tidak_tersedia" {{ old('status', $ruangan->status) == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    <option value="dalam_perbaikan" {{ old('status', $ruangan->status) == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="ber_ac" class="form-check-input" id="ber_ac" 
                                                value="1" {{ old('ber_ac', $ruangan->ber_ac) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ber_ac">
                                                <i class="bi bi-fan"></i> Ber-AC
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="ber_proyektor" class="form-check-input" id="ber_proyektor" 
                                                value="1" {{ old('ber_proyektor', $ruangan->ber_proyektor) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ber_proyektor">
                                                <i class="bi bi-projector"></i> Ber-Proyektor
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Tambahan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                    value="{{ old('penanggung_jawab', $ruangan->penanggung_jawab) }}">
                                @error('penanggung_jawab')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Fasilitas</label>
                                <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror" rows="3">{{ old('fasilitas', $ruangan->fasilitas) }}</textarea>
                                @error('fasilitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $ruangan->keterangan) }}</textarea>
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Foto Ruangan</label>
                                @if($ruangan->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $ruangan->foto) }}" alt="Foto Ruangan" class="img-thumbnail" style="max-height: 200px">
                                    <div class="form-text">Foto saat ini. Upload file baru untuk menggantinya.</div>
                                </div>
                                @endif
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                                    accept="image/*">
                                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('ruangan.show', $ruangan) }}" class="btn btn-secondary">
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
