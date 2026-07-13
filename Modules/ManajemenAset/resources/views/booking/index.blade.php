@extends('manajemenaset::layouts.master')

@section('title', 'Booking Ruangan')
@section('breadcrumb')
<li class="breadcrumb-item active">Booking Ruangan</li>
@endsection
@section('page-title', 'Booking Ruangan')
@section('page-subtitle', 'Kelola pemesanan dan jadwal ruangan')

@section('page-actions')
<a href="{{ route('booking-ruangan.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i>Booking Ruangan
</a>
@endsection

@section('content')
@include('manajemenaset::components.stat-card-styles')

<div class="container-fluid px-4">

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-card-secondary">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-secondary">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ number_format($stats['pending']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Disetujui</div>
                        <div class="stat-value">{{ number_format($stats['disetujui']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-info">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Hari Ini</div>
                        <div class="stat-value">{{ number_format($stats['hari_ini']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('booking-ruangan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Ruangan</label>
                        <select name="ruangan_id" class="form-select">
                            <option value="">Semua Ruangan</option>
                            @foreach($ruangans as $r)
                            <option value="{{ $r->id }}" {{ request('ruangan_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->kode_ruangan }} - {{ $r->nama_ruangan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('booking-ruangan.index') }}" class="btn btn-secondary w-100" title="Reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Tanggal</th>
                            <th>Waktu</th>
                            <th>Ruangan</th>
                            <th>Pemohon</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">{{ $b->tanggal->format('d M Y') }}</div>
                                <small class="text-muted">{{ $b->tanggal->format('l') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $b->waktu }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $b->ruangan->nama_ruangan }}</div>
                                <small class="text-muted">{{ $b->ruangan->kode_ruangan }}</small>
                            </td>
                            <td>{{ $b->pemohon->name }}</td>
                            <td>{{ Str::limit($b->keperluan, 30) }}</td>
                            <td>{!! $b->status_badge !!}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('booking-ruangan.show', $b) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['super_admin', 'staff', 'kaprodi']))
                                        @if($b->status == 'pending')
                                        <form method="POST" action="{{ route('booking-ruangan.approve', $b) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                    @if($b->pemohon_id == auth()->id() || auth()->user()->hasAnyRole(['super_admin', 'staff']))
                                        @if(in_array($b->status, ['pending', 'disetujui']))
                                        <form method="POST" action="{{ route('booking-ruangan.destroy', $b) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data booking.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bookings->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
