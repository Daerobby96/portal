@extends('sdm::layouts.master')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Pengajuan Cuti</h1>
        <div>
            @if($cuti->status == 'pending')
                <a href="{{ route('sdm.cuti.edit', $cuti->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            @endif
            <a href="{{ route('sdm.cuti.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>No. Pengajuan:</strong>
                            <p>{{ $cuti->nomor_pengajuan ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Pengajuan:</strong>
                            <p>{{ $cuti->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Jenis Cuti:</strong>
                            <p class="text-capitalize">{{ str_replace('_', ' ', $cuti->jenis_cuti) }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>
                                @if($cuti->status == 'pending')
                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                @elseif($cuti->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Tanggal Mulai:</strong>
                            <p>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Tanggal Selesai:</strong>
                            <p>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Jumlah Hari:</strong>
                            <p><span class="badge bg-info">{{ $cuti->jumlah_hari }} hari</span></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Alasan Cuti:</strong>
                        <p class="text-muted">{{ $cuti->alasan }}</p>
                    </div>

                    @if($cuti->alamat_selama_cuti)
                    <div class="mb-3">
                        <strong>Alamat Selama Cuti:</strong>
                        <p class="text-muted">{{ $cuti->alamat_selama_cuti }}</p>
                    </div>
                    @endif

                    @if($cuti->no_telepon)
                    <div class="mb-3">
                        <strong>No. Telepon:</strong>
                        <p class="text-muted">{{ $cuti->no_telepon }}</p>
                    </div>
                    @endif

                    @if($cuti->dokumen_pendukung)
                    <div class="mb-3">
                        <strong>Dokumen Pendukung:</strong>
                        <div class="border rounded p-3 mt-2">
                            <a href="{{ Storage::url($cuti->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-2"></i>Lihat Dokumen
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($cuti->status != 'pending')
            <div class="card shadow-sm">
                <div class="card-header {{ $cuti->status == 'disetujui' ? 'bg-success' : 'bg-danger' }} text-white">
                    <h5 class="mb-0">Hasil Persetujuan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Diproses Oleh:</strong>
                        <p>{{ $cuti->atasan->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Tanggal Diproses:</strong>
                        <p>{{ $cuti->tanggal_disetujui ? $cuti->tanggal_disetujui->format('d F Y H:i') : '-' }}</p>
                    </div>
                    @if($cuti->catatan_atasan)
                    <div>
                        <strong>Catatan:</strong>
                        <p class="text-muted">{{ $cuti->catatan_atasan }}</p>
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
                        <p>{{ $cuti->user->nama }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>NIP:</strong>
                        <p>{{ $cuti->user->nip ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Jabatan:</strong>
                        <p>{{ $cuti->user->jabatan ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Unit Kerja:</strong>
                        <p>{{ $cuti->user->unit_kerja ?? '-' }}</p>
                    </div>
                </div>
            </div>

            @if($cuti->status == 'pending')
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-clock me-2"></i>Status Pending
                    </h5>
                    <p class="small mb-0">Pengajuan cuti Anda sedang menunggu persetujuan dari atasan.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
