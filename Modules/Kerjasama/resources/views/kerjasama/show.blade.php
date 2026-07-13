@extends('kerjasama::layouts.master')

@section('title', 'Detail Kerjasama')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('kerjasama.index') }}">Data Kerjasama</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('page-title', 'Detail & Evaluasi Mitra')

@section('content')
<div class="container-fluid px-4">
    <div class="row g-4">
        <!-- Detail Card -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-secondary mb-0">Informasi Kerjasama</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Judul Kegiatan</small>
                        <h6 class="fw-bold mt-1 fs-5">{{ $kerjasama->judul_kerjasama }}</h6>
                        <div class="mt-2 d-flex gap-2">
                            {!! $kerjasama->status_badge !!}
                            @if($kerjasama->jenis_dokumen)
                                <span class="badge bg-info text-dark">{{ $kerjasama->jenis_dokumen }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Nama Mitra</span>
                            <span class="fw-semibold">{{ $kerjasama->nama_mitra }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Jenis Mitra</span>
                            <span class="fw-semibold">{{ $kerjasama->jenis_mitra }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tingkat</span>
                            <span>{!! $kerjasama->tingkat_badge !!}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Masa Berlaku</span>
                            <span class="fw-semibold text-end">
                                {{ $kerjasama->tanggal_mulai->format('d M Y') }} - <br>
                                {{ $kerjasama->tanggal_selesai ? $kerjasama->tanggal_selesai->format('d M Y') : 'Seterusnya' }}
                                @if($kerjasama->isExpiring())
                                    <br><span class="text-danger small fw-bold"><i class="bi bi-exclamation-triangle"></i> Akan Kedaluwarsa</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Pengusul</span>
                            <span class="fw-semibold">{{ $kerjasama->prodi ? $kerjasama->prodi->nama : 'Institusi / Universitas' }}</span>
                        </li>
                        @if($kerjasama->keterangan)
                        <li class="list-group-item px-0 py-3">
                            <span class="text-muted d-block mb-1">Keterangan Tambahan</span>
                            <span class="fw-semibold text-break">{{ $kerjasama->keterangan }}</span>
                        </li>
                        @endif
                    </ul>

                    @if($kerjasama->dokumen_mou)
                    <div class="mt-4">
                        <a href="{{ asset('storage/'.$kerjasama->dokumen_mou) }}" target="_blank" class="btn btn-outline-info w-100 rounded-pill">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen MoU / MoA
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Evaluasi Mitra Card -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-secondary mb-0">Evaluasi Mitra & Kegiatan</h5>
                    <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#evaluasiModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Evaluasi
                    </button>
                </div>
                <div class="card-body p-4">
                    @if($kerjasama->evaluasiMitras->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard-x fs-1 d-block mb-3 opacity-50"></i>
                            <p>Belum ada evaluasi kegiatan untuk mitra ini.</p>
                        </div>
                    @else
                        <div class="timeline-container">
                            @foreach($kerjasama->evaluasiMitras->sortByDesc('tanggal_evaluasi') as $eval)
                                <div class="d-flex mb-4">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <h5 class="mb-0 fw-bold">{{ $eval->nilai }}</h5>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 border p-3 rounded-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-0 fw-bold">Skor: {{ $eval->nilai }} / 5</h6>
                                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $eval->tanggal_evaluasi->format('d M Y') }}</small>
                                            </div>
                                            <form action="{{ route('kerjasama.evaluasi.destroy', [$kerjasama, $eval]) }}" method="POST" onsubmit="return confirm('Hapus evaluasi ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger border-0 p-0" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                        <p class="mb-1 text-dark">{{ $eval->catatan ?? '-' }}</p>
                                        <div class="text-muted small mt-2 border-top pt-2">
                                            <i class="bi bi-person me-1"></i>Dievaluasi oleh: {{ $eval->evaluator ? $eval->evaluator->name : 'Sistem' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Evaluasi -->
<div class="modal fade" id="evaluasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('kerjasama.evaluasi.store', $kerjasama) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="modal-title fw-bold text-primary">Tambah Evaluasi Pelaksanaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Evaluasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_evaluasi" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nilai Kinerja Mitra (1-5) <span class="text-danger">*</span></label>
                        <select name="nilai" class="form-select" required>
                            <option value="5">5 - Sangat Baik</option>
                            <option value="4">4 - Baik</option>
                            <option value="3" selected>3 - Cukup</option>
                            <option value="2">2 - Kurang</option>
                            <option value="1">1 - Sangat Kurang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Pelaksanaan / Rekomendasi</label>
                        <textarea name="catatan" class="form-control" rows="4" placeholder="Jelaskan implementasi kegiatan dan evaluasinya..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
