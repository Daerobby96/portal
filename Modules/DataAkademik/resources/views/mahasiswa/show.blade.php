@extends('dataakademik::layouts.master')

@section('title', 'Detail Mahasiswa')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Data Mahasiswa</a></li>
<li class="breadcrumb-item active">{{ $mahasiswa->nim }}</li>
@endsection
@section('page-title', 'Profil Mahasiswa')

@section('page-actions')
<a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-primary btn-sm">
    <i class="bi bi-pencil me-1"></i>Edit Data
</a>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="row g-4">
        {{-- Kolom Kiri: Profil Singkat --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center mb-4">
                <div class="card-body py-5">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ $mahasiswa->inisial }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $mahasiswa->nama }}</h5>
                    <p class="text-muted font-monospace mb-2">{{ $mahasiswa->nim }}</p>
                    <div class="mb-3">
                        {!! $mahasiswa->status_badge !!}
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row text-start g-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Program Studi</small>
                            <strong>{{ $mahasiswa->prodi?->nama ?? '-' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Angkatan</small>
                            <strong>{{ $mahasiswa->angkatan ?? '-' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Semester</small>
                            <strong>{{ $mahasiswa->semester_berjalan ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Card Metrik Akademik (Khusus IKU) --}}
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title text-white-50"><i class="bi bi-star me-2"></i>Metrik Kinerja (IKU 1)</h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Indeks Prestasi Kumulatif</span>
                            <span class="fw-bold fs-5">{{ $mahasiswa->ipk ? number_format($mahasiswa->ipk, 2) : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Masa Studi</span>
                            <span class="fw-bold">{{ $mahasiswa->masa_studi_tahun ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Informasi --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Detail</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Jenis Kelamin</label>
                            <div>{{ $mahasiswa->jenis_kelamin_label }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Jalur Masuk</label>
                            <div>{{ $mahasiswa->jalur_masuk ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Nomor HP / WhatsApp</label>
                            <div>{{ $mahasiswa->no_hp ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Email</label>
                            <div>{{ $mahasiswa->email ?? '-' }}</div>
                        </div>
                        
                        <div class="col-12"><hr class="text-black-50"></div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Tanggal Masuk</label>
                            <div>{{ $mahasiswa->tanggal_masuk ? $mahasiswa->tanggal_masuk->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Tanggal Lulus</label>
                            <div>{{ $mahasiswa->tanggal_lulus ? $mahasiswa->tanggal_lulus->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Total Bulan Studi</label>
                            <div>{{ $mahasiswa->masa_studi_bulan ? $mahasiswa->masa_studi_bulan . ' Bulan' : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Diinput Pada Periode</label>
                            <div>{{ $mahasiswa->periode?->nama ?? '-' }} ({{ $mahasiswa->periode?->tahun ?? '-' }})</div>
                        </div>

                        @if($mahasiswa->keterangan)
                        <div class="col-12 mt-4">
                            <label class="form-label text-muted small mb-1">Keterangan Tambahan</label>
                            <div class="bg-light p-3 rounded rounded-3 small">
                                {{ $mahasiswa->keterangan }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
