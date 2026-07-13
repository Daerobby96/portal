@extends('sdm::layouts.master')

@section('title', 'Edit Penilaian Kinerja')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Penilaian Kinerja</h1>
        <a href="{{ route('sdm.penilaian-kinerja.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('sdm.penilaian-kinerja.update', $penilaianKinerja) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="alert alert-info">
                            <strong>Pegawai:</strong> {{ $penilaianKinerja->pegawai->nama }}<br>
                            <strong>Periode:</strong> {{ $penilaianKinerja->tahun }} - {{ str_replace('_', ' ', ucwords($penilaianKinerja->periode)) }}
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Aspek Penilaian (Skala 0-100)</h5>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>1. Kedisiplinan</strong>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_disiplin') is-invalid @enderror" 
                                       name="nilai_disiplin" value="{{ old('nilai_disiplin', $penilaianKinerja->nilai_disiplin) }}" 
                                       min="0" max="100" step="0.01" required>
                                @error('nilai_disiplin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>2. Kinerja</strong>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_kinerja') is-invalid @enderror" 
                                       name="nilai_kinerja" value="{{ old('nilai_kinerja', $penilaianKinerja->nilai_kinerja) }}" 
                                       min="0" max="100" step="0.01" required>
                                @error('nilai_kinerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>3. Loyalitas</strong>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_loyalitas') is-invalid @enderror" 
                                       name="nilai_loyalitas" value="{{ old('nilai_loyalitas', $penilaianKinerja->nilai_loyalitas) }}" 
                                       min="0" max="100" step="0.01" required>
                                @error('nilai_loyalitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>4. Kreativitas</strong>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_kreativitas') is-invalid @enderror" 
                                       name="nilai_kreativitas" value="{{ old('nilai_kreativitas', $penilaianKinerja->nilai_kreativitas) }}" 
                                       min="0" max="100" step="0.01" required>
                                @error('nilai_kreativitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>5. Kerjasama</strong>
                            </div>
                            <div class="card-body">
                                <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_kerjasama') is-invalid @enderror" 
                                       name="nilai_kerjasama" value="{{ old('nilai_kerjasama', $penilaianKinerja->nilai_kerjasama) }}" 
                                       min="0" max="100" step="0.01" required>
                                @error('nilai_kerjasama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_atasan" class="form-label">Catatan Atasan</label>
                            <textarea class="form-control @error('catatan_atasan') is-invalid @enderror" 
                                      id="catatan_atasan" name="catatan_atasan" rows="4">{{ old('catatan_atasan', $penilaianKinerja->catatan_atasan) }}</textarea>
                            @error('catatan_atasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan_pegawai" class="form-label">Catatan Pegawai</label>
                            <textarea class="form-control @error('catatan_pegawai') is-invalid @enderror" 
                                      id="catatan_pegawai" name="catatan_pegawai" rows="3">{{ old('catatan_pegawai', $penilaianKinerja->catatan_pegawai) }}</textarea>
                            @error('catatan_pegawai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($penilaianKinerja->file_dokumen)
                        <div class="mb-3">
                            <label class="form-label">Dokumen Saat Ini</label>
                            <div class="border rounded p-2">
                                <a href="{{ Storage::url($penilaianKinerja->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="file_dokumen" class="form-label">{{ $penilaianKinerja->file_dokumen ? 'Ganti Dokumen' : 'Upload Dokumen' }} (PDF)</label>
                            <input type="file" class="form-control @error('file_dokumen') is-invalid @enderror" 
                                   id="file_dokumen" name="file_dokumen" accept=".pdf">
                            <small class="text-muted">Format: PDF, Maksimal 5MB</small>
                            @error('file_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('sdm.penilaian-kinerja.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Status</h5>
                    <hr>
                    <div class="mb-2">
                        <strong>Status Saat Ini:</strong>
                        {!! $penilaianKinerja->status_badge !!}
                    </div>
                    @if($penilaianKinerja->nilai_total)
                    <div class="mb-2">
                        <strong>Nilai Total:</strong>
                        <span class="badge bg-primary float-end">{{ number_format($penilaianKinerja->nilai_total, 2) }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Predikat:</strong>
                        {!! $penilaianKinerja->predikat_badge !!}
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Kriteria Predikat</h5>
                    <hr>
                    <div class="small">
                        <ul class="mb-0">
                            <li><strong>≥ 90:</strong> Sangat Baik</li>
                            <li><strong>80-89:</strong> Baik</li>
                            <li><strong>70-79:</strong> Cukup</li>
                            <li><strong>< 70:</strong> Kurang</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

