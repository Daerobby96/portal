@extends('manajemenaset::layouts.master')

@section('title', 'Pemeliharaan Aset')
@section('breadcrumb')
<li class="breadcrumb-item active">Pemeliharaan Aset</li>
@endsection
@section('page-title', 'Pemeliharaan Aset')
@section('page-subtitle', 'Kelola riwayat pemeliharaan dan perawatan aset')

@section('content')
@include('manajemenaset::components.stat-card-styles')

<div class="container-fluid px-4">

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Pemeliharaan</div>
                        <div class="stat-value">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-info">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Bulan Ini</div>
                        <div class="stat-value">{{ number_format($stats['bulan_ini']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Perlu Perbaikan</div>
                        <div class="stat-value">{{ number_format($stats['perlu_perbaikan']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Perlu Penggantian</div>
                        <div class="stat-value">{{ number_format($stats['perlu_penggantian']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pemeliharaan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="preventif" {{ request('jenis') == 'preventif' ? 'selected' : '' }}>Preventif</option>
                            <option value="korektif" {{ request('jenis') == 'korektif' ? 'selected' : '' }}>Korektif</option>
                            <option value="kalibrasi" {{ request('jenis') == 'kalibrasi' ? 'selected' : '' }}>Kalibrasi</option>
                            <option value="inspeksi" {{ request('jenis') == 'inspeksi' ? 'selected' : '' }}>Inspeksi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hasil</label>
                        <select name="hasil" class="form-select">
                            <option value="">Semua Hasil</option>
                            <option value="baik" {{ request('hasil') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="perlu_perbaikan" {{ request('hasil') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                            <option value="perlu_penggantian" {{ request('hasil') == 'perlu_penggantian' ? 'selected' : '' }}>Perlu Penggantian</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Cari Aset</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama/Kode Aset" value="{{ request('search') }}">
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
                            <th class="px-4">Tanggal</th>
                            <th>Aset</th>
                            <th>Jenis</th>
                            <th>Hasil</th>
                            <th>Petugas</th>
                            <th>Biaya</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemeliharaans as $p)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">{{ $p->tanggal_pemeliharaan->format('d M Y') }}</div>
                                @if($p->tanggal_berikutnya)
                                <small class="text-muted">Next: {{ $p->tanggal_berikutnya->format('d M Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->aset->nama_aset }}</div>
                                <small class="text-muted">{{ $p->aset->kode_aset }}</small>
                            </td>
                            <td>{!! $p->jenis_badge !!}</td>
                            <td>{!! $p->hasil_badge !!}</td>
                            <td>{{ $p->petugas->name }}</td>
                            <td>
                                @if($p->biaya)
                                <span class="fw-semibold">Rp {{ number_format($p->biaya, 0, ',', '.') }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pemeliharaan.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('pemeliharaan.edit', $p) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('pemeliharaan.destroy', $p) }}" class="d-inline" 
                                        onsubmit="return confirm('Hapus data pemeliharaan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data pemeliharaan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pemeliharaans->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $pemeliharaans->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
