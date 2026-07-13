@extends('manajemenaset::layouts.master')

@section('title', 'Detail Ruangan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('ruangan.index') }}">Data Ruangan</a></li>
<li class="breadcrumb-item active">{{ $ruangan->nama_ruangan }}</li>
@endsection
@section('page-title', $ruangan->nama_ruangan)
@section('page-subtitle', $ruangan->kode_ruangan)

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('booking-ruangan.create') }}?ruangan_id={{ $ruangan->id }}" class="btn btn-primary">
        <i class="bi bi-calendar-check me-1"></i>Booking Ruangan
    </a>
    <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <form method="POST" action="{{ route('ruangan.destroy', $ruangan) }}" class="d-inline" 
        onsubmit="return confirm('Hapus ruangan ini?')">
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
            <!-- Informasi Dasar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Informasi Ruangan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Kode Ruangan</label>
                            <div class="fw-semibold">{{ $ruangan->kode_ruangan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Ruangan</label>
                            <div class="fw-semibold">{{ $ruangan->nama_ruangan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Jenis</label>
                            <div>{!! $ruangan->jenis_badge !!}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Program Studi</label>
                            <div>{{ $ruangan->prodi?->nama ?? 'Umum' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Lokasi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($ruangan->gedung)
                        <div class="col-md-6">
                            <label class="text-muted small">Gedung</label>
                            <div class="fw-semibold">{{ $ruangan->gedung }}</div>
                        </div>
                        @endif
                        @if($ruangan->lantai)
                        <div class="col-md-6">
                            <label class="text-muted small">Lantai</label>
                            <div>{{ $ruangan->lantai }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Spesifikasi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Spesifikasi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($ruangan->kapasitas)
                        <div class="col-md-4">
                            <label class="text-muted small">Kapasitas</label>
                            <div class="fw-semibold">{{ $ruangan->kapasitas }} Orang</div>
                        </div>
                        @endif
                        @if($ruangan->luas)
                        <div class="col-md-4">
                            <label class="text-muted small">Luas</label>
                            <div>{{ $ruangan->luas }} m²</div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <label class="text-muted small">Kondisi</label>
                            <div>{!! $ruangan->kondisi_badge !!}</div>
                        </div>
                        <div class="col-md-12">
                            <label class="text-muted small">Fasilitas</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @if($ruangan->ber_ac)
                                <span class="badge bg-info"><i class="bi bi-fan"></i> AC</span>
                                @endif
                                @if($ruangan->ber_proyektor)
                                <span class="badge bg-info"><i class="bi bi-projector"></i> Proyektor</span>
                                @endif
                                @if(!$ruangan->ber_ac && !$ruangan->ber_proyektor)
                                <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        @if($ruangan->fasilitas)
                        <div class="col-md-12">
                            <label class="text-muted small">Detail Fasilitas</label>
                            <div>{{ $ruangan->fasilitas }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status & Info Tambahan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Status & Informasi Tambahan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <div>{!! $ruangan->status_badge !!}</div>
                        </div>
                        @if($ruangan->penanggung_jawab)
                        <div class="col-md-6">
                            <label class="text-muted small">Penanggung Jawab</label>
                            <div>{{ $ruangan->penanggung_jawab }}</div>
                        </div>
                        @endif
                        @if($ruangan->keterangan)
                        <div class="col-md-12">
                            <label class="text-muted small">Keterangan</label>
                            <div>{{ $ruangan->keterangan }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Jadwal Booking -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Jadwal Booking</h6>
                    <a href="{{ route('booking-ruangan.create') }}?ruangan_id={{ $ruangan->id }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg me-1"></i>Booking
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($ruangan->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4">Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Pemohon</th>
                                    <th>Keperluan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ruangan->bookings->take(5) as $b)
                                <tr>
                                    <td class="px-4">{{ $b->tanggal->format('d M Y') }}</td>
                                    <td>{{ $b->jam_mulai }} - {{ $b->jam_selesai }}</td>
                                    <td>{{ $b->pemohon->name }}</td>
                                    <td>{{ Str::limit($b->keperluan, 30) }}</td>
                                    <td>{!! $b->status_badge !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                        Belum ada jadwal booking
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Foto Ruangan -->
            @if($ruangan->foto)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">Foto Ruangan</h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ asset('storage/' . $ruangan->foto) }}" alt="{{ $ruangan->nama_ruangan }}" 
                        class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @endif

            <!-- QR Code -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">QR Code Ruangan</h6>
                </div>
                <div class="card-body text-center">
                    <div class="qrcode-container mb-3" id="qrcode-{{ $ruangan->id }}"></div>
                    <small class="text-muted">Scan untuk akses cepat</small>
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
                            <span class="fw-semibold">{{ $ruangan->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Terakhir Diubah:</span><br>
                            <span class="fw-semibold">{{ $ruangan->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById('qrcode-{{ $ruangan->id }}'), {
        text: '{{ route("ruangan.show", $ruangan) }}',
        width: 200,
        height: 200,
        correctLevel: QRCode.CorrectLevel.H
    });
});
</script>
@endpush
@endsection
