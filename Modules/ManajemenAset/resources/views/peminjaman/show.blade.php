@extends('manajemenaset::layouts.master')

@section('title', 'Detail Peminjaman')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Peminjaman Aset</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('page-title', 'Detail Peminjaman')
@section('page-subtitle', 'Peminjaman #' . $peminjaman->id)

@section('page-actions')
@if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
    @if($peminjaman->status == 'pending')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
            <i class="bi bi-check-lg me-1"></i>Setujui
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-lg me-1"></i>Tolak
        </button>
    </div>
    @elseif($peminjaman->status == 'disetujui')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#returnModal">
        <i class="bi bi-arrow-return-left me-1"></i>Proses Pengembalian
    </button>
    @endif
@endif
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
                            <div class="fw-semibold">{{ $peminjaman->aset->kode_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Aset</label>
                            <div class="fw-semibold">{{ $peminjaman->aset->nama_aset }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Kategori</label>
                            <div>{!! $peminjaman->aset->kategori->badge !!}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Lokasi</label>
                            <div>{{ $peminjaman->aset->lokasi }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('aset.show', $peminjaman->aset) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-seam me-1"></i>Lihat Detail Aset
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Peminjaman -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Detail Peminjaman</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Peminjam</label>
                            <div class="fw-semibold">{{ $peminjaman->peminjam->name }}</div>
                            <small class="text-muted">{{ $peminjaman->peminjam->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <div>{!! $peminjaman->status_badge !!}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small">Keperluan</label>
                            <div>{{ $peminjaman->keperluan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Pinjam</label>
                            <div class="fw-semibold">{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Rencana Kembali</label>
                            <div class="fw-semibold">{{ $peminjaman->tanggal_kembali_rencana->locale('id')->translatedFormat('d F Y') }}</div>
                            @if($peminjaman->is_terlambat)
                            <small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Terlambat</small>
                            @endif
                        </div>
                        @if($peminjaman->tanggal_kembali_aktual)
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Kembali Aktual</label>
                            <div class="fw-semibold text-success">{{ $peminjaman->tanggal_kembali_aktual->locale('id')->translatedFormat('d F Y') }}</div>
                        </div>
                        @endif
                        @if($peminjaman->catatan_peminjam)
                        <div class="col-md-12">
                            <label class="text-muted small">Catatan Peminjam</label>
                            <div>{{ $peminjaman->catatan_peminjam }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Approval Info -->
            @if($peminjaman->approver)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Approval</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Disetujui/Ditolak Oleh</label>
                            <div class="fw-semibold">{{ $peminjaman->approver->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal</label>
                            <div>{{ $peminjaman->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        @if($peminjaman->catatan_approval)
                        <div class="col-md-12">
                            <label class="text-muted small">Catatan Approval</label>
                            <div>{{ $peminjaman->catatan_approval }}</div>
                        </div>
                        @endif
                        @if($peminjaman->kondisi_saat_pinjam)
                        <div class="col-md-6">
                            <label class="text-muted small">Kondisi Saat Dipinjam</label>
                            <div>{{ $peminjaman->kondisi_saat_pinjam }}</div>
                        </div>
                        @endif
                        @if($peminjaman->kondisi_saat_kembali)
                        <div class="col-md-6">
                            <label class="text-muted small">Kondisi Saat Dikembalikan</label>
                            <div>{{ $peminjaman->kondisi_saat_kembali }}</div>
                        </div>
                        @endif
                        @if($peminjaman->denda && $peminjaman->denda > 0)
                        <div class="col-md-12">
                            <label class="text-muted small">Denda</label>
                            <div class="fw-semibold text-danger fs-5">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $peminjaman->created_at->locale('id')->translatedFormat('d M Y H:i') }}</small>
                                <div class="fw-semibold">Pengajuan Dibuat</div>
                            </div>
                        </div>
                        @if(in_array($peminjaman->status, ['disetujui', 'ditolak', 'dikembalikan']))
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $peminjaman->status == 'ditolak' ? 'bg-danger' : 'bg-success' }}"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $peminjaman->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</small>
                                <div class="fw-semibold">{{ $peminjaman->status == 'ditolak' ? 'Ditolak' : 'Disetujui' }}</div>
                            </div>
                        </div>
                        @endif
                        @if($peminjaman->tanggal_kembali_aktual)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $peminjaman->tanggal_kembali_aktual->locale('id')->translatedFormat('d M Y H:i') }}</small>
                                <div class="fw-semibold">Aset Dikembalikan</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <span class="text-muted">Dibuat:</span><br>
                            <span class="fw-semibold">{{ $peminjaman->created_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Terakhir Diubah:</span><br>
                            <span class="fw-semibold">{{ $peminjaman->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('peminjaman.approve', $peminjaman) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kondisi Aset Saat Dipinjam</label>
                        <textarea name="kondisi_saat_pinjam" class="form-control" rows="3" placeholder="Kondisi aset saat diserahkan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan_approval" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('peminjaman.reject', $peminjaman) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Alasan Penolakan</label>
                        <textarea name="catatan_approval" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('peminjaman.return', $peminjaman) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Proses Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Kondisi Saat Dikembalikan</label>
                        <textarea name="kondisi_saat_kembali" class="form-control" rows="3" placeholder="Kondisi aset saat dikembalikan" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Denda (Rp)</label>
                        <input type="number" name="denda" class="form-control" placeholder="0" min="0" step="1000">
                        <small class="text-muted">Jika ada kerusakan atau keterlambatan</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -22px;
    top: 12px;
    width: 2px;
    height: 100%;
    background: #e9ecef;
}
.timeline-marker {
    position: absolute;
    left: -28px;
    top: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
}
.required::after {
    content: " *";
    color: #dc3545;
}
</style>
@endpush
@endsection
