@extends('sdm::layouts.master')

@section('title', 'Detail Lembur')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Lembur</h1>
        <a href="{{ route('sdm.lembur.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Lembur</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Pengajuan:</strong>
                            <p>{{ $lembur->created_at->format('d F Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>
                                @if($lembur->status == 'pending')
                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                @elseif($lembur->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Lembur:</strong>
                            <p>{{ \Carbon\Carbon::parse($lembur->tanggal)->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Durasi:</strong>
                            <p><span class="badge bg-info">{{ $lembur->durasi_jam }} jam</span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Waktu Mulai:</strong>
                            <p>{{ \Carbon\Carbon::parse($lembur->waktu_mulai)->format('H:i') }} WIB</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu Selesai:</strong>
                            <p>{{ \Carbon\Carbon::parse($lembur->waktu_selesai)->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Keterangan Pekerjaan:</strong>
                        <p class="text-muted">{{ $lembur->keterangan }}</p>
                    </div>
                </div>
            </div>

            @if($lembur->status != 'pending')
            <div class="card shadow-sm">
                <div class="card-header {{ $lembur->status == 'disetujui' ? 'bg-success' : 'bg-danger' }} text-white">
                    <h5 class="mb-0">Hasil Persetujuan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Diproses Oleh:</strong>
                        <p>{{ $lembur->atasan->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Tanggal Diproses:</strong>
                        <p>{{ $lembur->tanggal_disetujui ? $lembur->tanggal_disetujui->format('d F Y H:i') : '-' }}</p>
                    </div>
                    @if($lembur->catatan_atasan)
                    <div>
                        <strong>Catatan:</strong>
                        <p class="text-muted">{{ $lembur->catatan_atasan }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Data Pengaju</h5>
                    <hr>
                    <div class="mb-2">
                        <strong>Nama:</strong>
                        <p>{{ $lembur->user->nama }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>NIP:</strong>
                        <p>{{ $lembur->user->nip ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Jabatan:</strong>
                        <p>{{ $lembur->user->jabatan ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Unit Kerja:</strong>
                        <p>{{ $lembur->user->unit_kerja ?? '-' }}</p>
                    </div>
                </div>
            </div>

            @if($lembur->status == 'pending')
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-clock me-2"></i>Status Pending
                    </h5>
                    <p class="small mb-0">Pengajuan lembur Anda sedang menunggu persetujuan dari atasan.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

