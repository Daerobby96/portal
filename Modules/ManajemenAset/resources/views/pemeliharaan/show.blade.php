@extends('manajemenaset::layouts.master')

@section('title', 'Detail Pemeliharaan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pemeliharaan.index') }}">Pemeliharaan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('page-title', 'Detail Pemeliharaan')
@section('page-subtitle', $pemeliharaan->tanggal_pemeliharaan->locale('id')->translatedFormat('d F Y'))

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <form method="POST" action="{{ route('pemeliharaan.destroy', $pemeliharaan) }}" class="d-inline" 
        onsubmit="return confirm('Hapus data pemeliharaan ini?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>Hapus
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Info Aset -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Aset</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Kode Aset</label>
                            <div class="fw-semibold">{{ $pemeliharaan->aset->kode_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Aset</label>
                            <div class="fw-semibold">{{ $pemeliharaan->aset->nama_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Kategori</label>
                            <div>{!! $pemeliharaan->aset->kategori->badge !!}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Lokasi</label>
                            <div>{{ $pemeliharaan->aset->lokasi }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('aset.show', $pemeliharaan->aset) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-seam me-1"></i>Lihat Detail Aset
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Pemeliharaan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Detail Pemeliharaan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Pemeliharaan</label>
                            <div class="fw-semibold">{{ $pemeliharaan->tanggal_pemeliharaan->locale('id')->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Jenis</label>
                            <div>{!! $pemeliharaan->jenis_badge !!}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small">Deskripsi Kegiatan</label>
                            <div>{{ $pemeliharaan->deskripsi_kegiatan }}</div>
                        </div>
                        @if($pemeliharaan->temuan)
                        <div class="col-md-12">
                            <label class="text-muted small">Temuan</label>
                            <div>{{ $pemeliharaan->temuan }}</div>
                        </div>
                        @endif
                        @if($pemeliharaan->tindakan)
                        <div class="col-md-12">
                            <label class="text-muted small">Tindakan</label>
                            <div>{{ $pemeliharaan->tindakan }}</div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="text-muted small">Hasil</label>
                            <div>{!! $pemeliharaan->hasil_badge !!}</div>
                        </div>
                        @if($pemeliharaan->tanggal_berikutnya)
                        <div class="col-md-6">
                            <label class="text-muted small">Pemeliharaan Berikutnya</label>
                            <div class="fw-semibold">{{ $pemeliharaan->tanggal_berikutnya->locale('id')->translatedFormat('d F Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Biaya & Vendor -->
            @if($pemeliharaan->biaya || $pemeliharaan->vendor)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Biaya & Vendor</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($pemeliharaan->biaya)
                        <div class="col-md-6">
                            <label class="text-muted small">Biaya</label>
                            <div class="fw-semibold fs-5 text-primary">Rp {{ number_format($pemeliharaan->biaya, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        @if($pemeliharaan->vendor)
                        <div class="col-md-6">
                            <label class="text-muted small">Vendor/Teknisi</label>
                            <div>{{ $pemeliharaan->vendor }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Petugas -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Petugas</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                            style="width: 48px; height: 48px;">
                            <span class="fw-bold">{{ strtoupper(substr($pemeliharaan->petugas->name, 0, 2)) }}</span>
                        </div>
                        <div class="ms-3">
                            <div class="fw-semibold">{{ $pemeliharaan->petugas->name }}</div>
                            <small class="text-muted">{{ $pemeliharaan->petugas->email }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bukti Foto -->
            @if($pemeliharaan->bukti_foto)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Bukti Foto</h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ asset('storage/' . $pemeliharaan->bukti_foto) }}" alt="Bukti Foto" 
                        class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <span class="text-muted">Dibuat:</span><br>
                            <span class="fw-semibold">{{ $pemeliharaan->created_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Terakhir Diubah:</span><br>
                            <span class="fw-semibold">{{ $pemeliharaan->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
