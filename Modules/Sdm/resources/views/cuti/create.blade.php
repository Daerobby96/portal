@extends('sdm::layouts.master')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Ajukan Cuti</h1>
        <a href="{{ route('sdm.cuti.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('sdm.cuti.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="jenis_cuti" class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_cuti') is-invalid @enderror" 
                                    id="jenis_cuti" name="jenis_cuti" required>
                                <option value="">-- Pilih Jenis Cuti --</option>
                                <option value="tahunan" {{ old('jenis_cuti') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                <option value="sakit" {{ old('jenis_cuti') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                                <option value="melahirkan" {{ old('jenis_cuti') == 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                                <option value="alasan_penting" {{ old('jenis_cuti') == 'alasan_penting' ? 'selected' : '' }}>Cuti Alasan Penting</option>
                                <option value="bersama" {{ old('jenis_cuti') == 'bersama' ? 'selected' : '' }}>Cuti Bersama</option>
                            </select>
                            @error('jenis_cuti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai') }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Total hari: <span id="total_hari">0</span> hari</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Cuti <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                      id="alasan" name="alasan" rows="4" required>{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat_selama_cuti" class="form-label">Alamat Selama Cuti</label>
                            <textarea class="form-control @error('alamat_selama_cuti') is-invalid @enderror" 
                                      id="alamat_selama_cuti" name="alamat_selama_cuti" rows="3">{{ old('alamat_selama_cuti') }}</textarea>
                            @error('alamat_selama_cuti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">No. Telepon yang Dapat Dihubungi</label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dokumen_pendukung" class="form-label">Dokumen Pendukung</label>
                            <input type="file" class="form-control @error('dokumen_pendukung') is-invalid @enderror" 
                                   id="dokumen_pendukung" name="dokumen_pendukung" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 2MB</small>
                            @error('dokumen_pendukung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('sdm.cuti.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Cuti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sisa Kuota Cuti</h5>
                    <hr>
                    <div class="mb-2">
                        <strong>Cuti Tahunan:</strong>
                        <span class="float-end badge bg-primary">{{ $sisaCutiTahunan ?? 12 }} hari</span>
                    </div>
                    <div class="mb-2">
                        <strong>Cuti Sakit:</strong>
                        <span class="float-end badge bg-success">Unlimited</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Informasi Cuti</h5>
                    <hr>
                    <ul class="small mb-0">
                        <li>Cuti tahunan maksimal 12 hari per tahun</li>
                        <li>Cuti sakit memerlukan surat keterangan dokter</li>
                        <li>Cuti melahirkan maksimal 3 bulan</li>
                        <li>Ajukan cuti minimal 3 hari sebelumnya</li>
                        <li>Menunggu persetujuan atasan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const totalHari = document.getElementById('total_hari');

    function hitungHari() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            const mulai = new Date(tanggalMulai.value);
            const selesai = new Date(tanggalSelesai.value);
            const diffTime = selesai - mulai;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            totalHari.textContent = diffDays > 0 ? diffDays : 0;
        }
    }

    tanggalMulai.addEventListener('change', hitungHari);
    tanggalSelesai.addEventListener('change', hitungHari);
});
</script>
@endpush
@endsection

