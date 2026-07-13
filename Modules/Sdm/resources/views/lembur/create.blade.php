@extends('sdm::layouts.master')

@section('title', 'Ajukan Lembur')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Ajukan Lembur</h1>
        <a href="{{ route('sdm.lembur.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('sdm.lembur.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Lembur <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="waktu_mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                       id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                                @error('waktu_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="waktu_selesai" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                       id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                                @error('waktu_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Durasi: <span id="durasi">0</span> jam</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan Pekerjaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" rows="4" required>{{ old('keterangan') }}</textarea>
                            <small class="text-muted">Jelaskan pekerjaan yang dilakukan saat lembur</small>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('sdm.lembur.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Ajukan Lembur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Informasi Lembur</h5>
                    <hr>
                    <ul class="small mb-0">
                        <li>Ajukan lembur sebelum atau setelah melakukan pekerjaan lembur</li>
                        <li>Pastikan mencatat waktu mulai dan selesai dengan akurat</li>
                        <li>Jelaskan detail pekerjaan yang dilakukan</li>
                        <li>Menunggu persetujuan atasan untuk proses pembayaran</li>
                        <li>Lembur minimal 1 jam</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Lembur Bulan Ini</h5>
                    <hr>
                    <div class="text-center">
                        <h2 class="display-4 text-primary">{{ $totalJamBulanIni ?? 0 }}</h2>
                        <p class="text-muted mb-0">jam</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const waktuMulai = document.getElementById('waktu_mulai');
    const waktuSelesai = document.getElementById('waktu_selesai');
    const durasi = document.getElementById('durasi');

    function hitungDurasi() {
        if (waktuMulai.value && waktuSelesai.value) {
            const mulai = new Date('2000-01-01 ' + waktuMulai.value);
            const selesai = new Date('2000-01-01 ' + waktuSelesai.value);
            let diffMs = selesai - mulai;
            
            // Jika waktu selesai lebih kecil, berarti melewati tengah malam
            if (diffMs < 0) {
                diffMs += 24 * 60 * 60 * 1000;
            }
            
            const diffHrs = Math.round((diffMs / (1000 * 60 * 60)) * 10) / 10;
            durasi.textContent = diffHrs > 0 ? diffHrs : 0;
        }
    }

    waktuMulai.addEventListener('change', hitungDurasi);
    waktuSelesai.addEventListener('change', hitungDurasi);
});
</script>
@endpush
@endsection

