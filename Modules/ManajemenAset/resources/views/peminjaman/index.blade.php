@extends('manajemenaset::layouts.master')

@section('title', 'Peminjaman Aset')
@section('breadcrumb')
<li class="breadcrumb-item active">Peminjaman Aset</li>
@endsection
@section('page-title', 'Peminjaman Aset')
@section('page-subtitle', 'Kelola peminjaman aset institusi')

@section('page-actions')
<a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i>Ajukan Peminjaman
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
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Sedang Dipinjam</div>
                        <div class="stat-value">{{ number_format($stats['dipinjam']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Terlambat</div>
                        <div class="stat-value">{{ number_format($stats['terlambat']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('peminjaman.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Cari Aset</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama aset" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
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
                            <th class="px-4">Aset</th>
                            <th>Peminjam</th>
                            <th>Keperluan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        <tr class="{{ $p->is_terlambat ? 'table-warning' : '' }}">
                            <td class="px-4">
                                <div class="fw-semibold">{{ $p->aset->nama_aset }}</div>
                                <small class="text-muted">{{ $p->aset->kode_aset }}</small>
                            </td>
                            <td>{{ $p->peminjam->name }}</td>
                            <td>{{ Str::limit($p->keperluan, 30) }}</td>
                            <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                            <td>
                                @if($p->tanggal_kembali_aktual)
                                <span class="text-success">{{ $p->tanggal_kembali_aktual->format('d M Y') }}</span>
                                @else
                                {{ $p->tanggal_kembali_rencana->format('d M Y') }}
                                @if($p->is_terlambat)
                                <br><small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Terlambat</small>
                                @endif
                                @endif
                            </td>
                            <td>{!! $p->status_badge !!}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
                                        @if($p->status == 'pending')
                                        <form method="POST" action="{{ route('peminjaman.approve', $p) }}" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
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
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($peminjamans->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $peminjamans->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
