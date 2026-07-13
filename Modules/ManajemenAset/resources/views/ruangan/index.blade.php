@extends('manajemenaset::layouts.master')

@section('title', 'Data Ruangan')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Ruangan</li>
@endsection
@section('page-title', 'Data Ruangan')
@section('page-subtitle', 'Kelola data ruangan dan fasilitas institusi')

@section('page-actions')
<a href="{{ route('ruangan.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i>Tambah Ruangan
</a>
@endsection

@section('content')
@include('manajemenaset::components.stat-card-styles')

<div class="container-fluid px-4">

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Ruangan</div>
                        <div class="stat-value">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Tersedia</div>
                        <div class="stat-value">{{ number_format($stats['tersedia']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-secondary">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-secondary">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Tidak Tersedia</div>
                        <div class="stat-value">{{ number_format($stats['tidak_tersedia']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Perbaikan</div>
                        <div class="stat-value">{{ number_format($stats['dalam_perbaikan']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('ruangan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="kelas" {{ request('jenis') == 'kelas' ? 'selected' : '' }}>Kelas</option>
                            <option value="lab" {{ request('jenis') == 'lab' ? 'selected' : '' }}>Lab</option>
                            <option value="ruang_rapat" {{ request('jenis') == 'ruang_rapat' ? 'selected' : '' }}>Ruang Rapat</option>
                            <option value="ruang_dosen" {{ request('jenis') == 'ruang_dosen' ? 'selected' : '' }}>Ruang Dosen</option>
                            <option value="perpustakaan" {{ request('jenis') == 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Program Studi</label>
                        <select name="prodi_id" class="form-select">
                            <option value="">Semua Prodi</option>
                            @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak_tersedia" {{ request('status') == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            <option value="dalam_perbaikan" {{ request('status') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama/Kode/Gedung" value="{{ request('search') }}">
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
                            <th class="px-4">Kode</th>
                            <th>Nama Ruangan</th>
                            <th>Jenis</th>
                            <th>Lokasi</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ruangans as $r)
                        <tr>
                            <td class="px-4">
                                <span class="badge bg-secondary">{{ $r->kode_ruangan }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $r->nama_ruangan }}</div>
                                @if($r->ber_ac || $r->ber_proyektor)
                                <small class="text-muted">
                                    @if($r->ber_ac)<i class="bi bi-fan"></i> AC @endif
                                    @if($r->ber_proyektor)<i class="bi bi-projector"></i> Proyektor @endif
                                </small>
                                @endif
                            </td>
                            <td>{!! $r->jenis_badge !!}</td>
                            <td>
                                <div>{{ $r->gedung }}@if($r->lantai), Lt. {{ $r->lantai }}@endif</div>
                                @if($r->prodi)<small class="text-muted">{{ $r->prodi->nama }}</small>@endif
                            </td>
                            <td>
                                @if($r->kapasitas)
                                <span class="badge bg-info">{{ $r->kapasitas }} orang</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{!! $r->status_badge !!}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('ruangan.show', $r) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('ruangan.edit', $r) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('ruangan.destroy', $r) }}" class="d-inline" 
                                        onsubmit="return confirm('Hapus ruangan ini?')">
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
                                Belum ada data ruangan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($ruangans->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $ruangans->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
