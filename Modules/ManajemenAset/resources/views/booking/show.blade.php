@extends('manajemenaset::layouts.master')

@section('title', 'Detail Booking')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('booking-ruangan.index') }}">Booking Ruangan</a></li>
<li class="breadcrumb-item active">Detail Booking</li>
@endsection
@section('page-title', 'Detail Booking Ruangan')
@section('page-subtitle', 'Booking #' . $bookingRuangan->id)

@section('page-actions')
@if(auth()->user()->hasAnyRole(['super_admin', 'staff', 'kaprodi']))
    @if($bookingRuangan->status == 'pending')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
            <i class="bi bi-check-lg me-1"></i>Setujui
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-lg me-1"></i>Tolak
        </button>
    </div>
    @endif
@endif
@if($bookingRuangan->pemohon_id == auth()->id() || auth()->user()->hasAnyRole(['super_admin', 'staff']))
    @if(in_array($bookingRuangan->status, ['pending', 'disetujui']))
    <form method="POST" action="{{ route('booking-ruangan.destroy', $bookingRuangan) }}" class="d-inline" 
        onsubmit="return confirm('Batalkan booking ini?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-warning">
            <i class="bi bi-x-circle me-1"></i>Batalkan Booking
        </button>
    </form>
    @endif
@endif
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Info Ruangan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Ruangan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Kode Ruangan</label>
                            <div class="fw-semibold">{{ $bookingRuangan->ruangan->kode_ruangan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Ruangan</label>
                            <div class="fw-semibold">{{ $bookingRuangan->ruangan->nama_ruangan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Jenis</label>
                            <div>{!! $bookingRuangan->ruangan->jenis_badge !!}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Lokasi</label>
                            <div>{{ $bookingRuangan->ruangan->gedung }}@if($bookingRuangan->ruangan->lantai), Lt. {{ $bookingRuangan->ruangan->lantai }}@endif</div>
                        </div>
                        @if($bookingRuangan->ruangan->kapasitas)
                        <div class="col-md-12">
                            <label class="text-muted small">Kapasitas</label>
                            <div>{{ $bookingRuangan->ruangan->kapasitas }} Orang</div>
                        </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('ruangan.show', $bookingRuangan->ruangan) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-door-open me-1"></i>Lihat Detail Ruangan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Booking -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Detail Booking</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Pemohon</label>
                            <div class="fw-semibold">{{ $bookingRuangan->pemohon->name }}</div>
                            <small class="text-muted">{{ $bookingRuangan->pemohon->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <div>{!! $bookingRuangan->status_badge !!}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small">Keperluan</label>
                            <div class="fw-semibold">{{ $bookingRuangan->keperluan }}</div>
                        </div>
                        @if($bookingRuangan->deskripsi)
                        <div class="col-md-12">
                            <label class="text-muted small">Deskripsi</label>
                            <div>{{ $bookingRuangan->deskripsi }}</div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <label class="text-muted small">Tanggal</label>
                            <div class="fw-semibold">{{ $bookingRuangan->tanggal->locale('id')->translatedFormat('d F Y') }}</div>
                            <small class="text-muted">{{ $bookingRuangan->tanggal->locale('id')->translatedFormat('l') }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Waktu</label>
                            <div class="fw-semibold">{{ $bookingRuangan->waktu }}</div>
                        </div>
                        @if($bookingRuangan->jumlah_peserta)
                        <div class="col-md-4">
                            <label class="text-muted small">Jumlah Peserta</label>
                            <div>{{ $bookingRuangan->jumlah_peserta }} Orang</div>
                        </div>
                        @endif
                        @if($bookingRuangan->catatan_pemohon)
                        <div class="col-md-12">
                            <label class="text-muted small">Catatan Pemohon</label>
                            <div>{{ $bookingRuangan->catatan_pemohon }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Approval Info -->
            @if($bookingRuangan->approver)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Approval</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Disetujui/Ditolak Oleh</label>
                            <div class="fw-semibold">{{ $bookingRuangan->approver->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal</label>
                            <div>{{ $bookingRuangan->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        @if($bookingRuangan->catatan_approval)
                        <div class="col-md-12">
                            <label class="text-muted small">Catatan Approval</label>
                            <div>{{ $bookingRuangan->catatan_approval }}</div>
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
                                <small class="text-muted">{{ $bookingRuangan->created_at->locale('id')->translatedFormat('d M Y H:i') }}</small>
                                <div class="fw-semibold">Booking Dibuat</div>
                            </div>
                        </div>
                        @if(in_array($bookingRuangan->status, ['disetujui', 'ditolak']))
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $bookingRuangan->status == 'ditolak' ? 'bg-danger' : 'bg-success' }}"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $bookingRuangan->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</small>
                                <div class="fw-semibold">{{ $bookingRuangan->status == 'ditolak' ? 'Ditolak' : 'Disetujui' }}</div>
                            </div>
                        </div>
                        @endif
                        @if($bookingRuangan->status == 'dibatalkan')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $bookingRuangan->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</small>
                                <div class="fw-semibold">Dibatalkan</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        @if($bookingRuangan->status == 'pending')
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-clock-history"></i>
                            Booking menunggu approval dari admin/kaprodi
                        </div>
                        @elseif($bookingRuangan->status == 'disetujui')
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle"></i>
                            Booking telah disetujui. Silakan gunakan ruangan sesuai jadwal.
                        </div>
                        @elseif($bookingRuangan->status == 'ditolak')
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-x-circle"></i>
                            Booking ditolak. Cek catatan approval untuk informasi lebih lanjut.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <span class="text-muted">Dibuat:</span><br>
                            <span class="fw-semibold">{{ $bookingRuangan->created_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Terakhir Diubah:</span><br>
                            <span class="fw-semibold">{{ $bookingRuangan->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</span>
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
            <form method="POST" action="{{ route('booking-ruangan.approve', $bookingRuangan) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan_approval" class="form-control" rows="3" placeholder="Catatan approval (opsional)"></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small><i class="bi bi-info-circle"></i> Pastikan tidak ada konflik jadwal dengan booking lain</small>
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
            <form method="POST" action="{{ route('booking-ruangan.reject', $bookingRuangan) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Booking</h5>
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
