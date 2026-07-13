@extends('sdm::layouts.master')

@section('title', 'Buat Penilaian Kinerja')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Buat Penilaian Kinerja</h1>
        <a href="{{ route('sdm.penilaian-kinerja.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('sdm.penilaian-kinerja.store') }}" method="POST" id="formPenilaian">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pegawai_id" class="form-label">Pegawai yang Dinilai <span class="text-danger">*</span></label>
                                <select class="form-select @error('pegawai_id') is-invalid @enderror" 
                                        id="pegawai_id" name="pegawai_id" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawais ?? [] as $pegawai)
                                        <option value="{{ $pegawai->id }}" {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                                            {{ $pegawai->nama }} @if($pegawai->nip)({{ $pegawai->nip }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('pegawai_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <select class="form-select @error('tahun') is-invalid @enderror" 
                                        id="tahun" name="tahun" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for($y = date('Y') + 1; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ old('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="periode" class="form-label">Periode <span class="text-danger">*</span></label>
                                <select class="form-select @error('periode') is-invalid @enderror" 
                                        id="periode" name="periode" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="semester_1" {{ old('periode') == 'semester_1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="semester_2" {{ old('periode') == 'semester_2' ? 'selected' : '' }}>Semester 2</option>
                                    <option value="tahunan" {{ old('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                                @error('periode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                       name="nilai_disiplin" value="{{ old('nilai_disiplin') }}" 
                                       min="0" max="100" step="0.01" required>
                                <small class="text-muted">Meliputi: kehadiran, ketepatan waktu, kepatuhan terhadap aturan</small>
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
                                       name="nilai_kinerja" value="{{ old('nilai_kinerja') }}" 
                                       min="0" max="100" step="0.01" required>
                                <small class="text-muted">Meliputi: pencapaian target, kualitas kerja, produktivitas</small>
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
                                       name="nilai_loyalitas" value="{{ old('nilai_loyalitas') }}" 
                                       min="0" max="100" step="0.01" required>
                                <small class="text-muted">Meliputi: komitmen, dedikasi, integritas</small>
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
                                       name="nilai_kreativitas" value="{{ old('nilai_kreativitas') }}" 
                                       min="0" max="100" step="0.01" required>
                                <small class="text-muted">Meliputi: inovasi, inisiatif, pemecahan masalah</small>
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
                                       name="nilai_kerjasama" value="{{ old('nilai_kerjasama') }}" 
                                       min="0" max="100" step="0.01" required>
                                <small class="text-muted">Meliputi: teamwork, komunikasi, kolaborasi</small>
                                @error('nilai_kerjasama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_atasan" class="form-label">Catatan Atasan</label>
                            <textarea class="form-control @error('catatan_atasan') is-invalid @enderror" 
                                      id="catatan_atasan" name="catatan_atasan" rows="4">{{ old('catatan_atasan') }}</textarea>
                            <small class="text-muted">Catatan atau komentar mengenai kinerja pegawai</small>
                            @error('catatan_atasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file_dokumen" class="form-label">Dokumen Pendukung (PDF)</label>
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
                                <i class="fas fa-save me-2"></i>Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Informasi Penilaian</h5>
                    <hr>
                    <ul class="small mb-0">
                        <li>Pilih pegawai yang akan dinilai</li>
                        <li>Tentukan tahun dan periode penilaian</li>
                        <li>Berikan nilai untuk setiap aspek (0-100)</li>
                        <li>Nilai total akan dihitung otomatis</li>
                        <li>Tambahkan catatan jika diperlukan</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Kriteria Predikat</h5>
                    <hr>
                    <div class="small">
                        <strong>Rentang Nilai:</strong>
                        <ul class="mb-2">
                            <li><strong>≥ 90:</strong> Sangat Baik</li>
                            <li><strong>80-89:</strong> Baik</li>
                            <li><strong>70-79:</strong> Cukup</li>
                            <li><strong>< 70:</strong> Kurang</li>
                        </ul>
                        <strong>5 Aspek Penilaian:</strong>
                        <ul class="mb-0">
                            <li>Kedisiplinan (20%)</li>
                            <li>Kinerja (20%)</li>
                            <li>Loyalitas (20%)</li>
                            <li>Kreativitas (20%)</li>
                            <li>Kerjasama (20%)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

