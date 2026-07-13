@extends('manajemenaset::layouts.master')

@section('title', 'Inventaris Aset')
@section('breadcrumb')
<li class="breadcrumb-item active">Inventaris Aset</li>
@endsection
@section('page-title', 'Inventaris Aset')
@section('page-subtitle', 'Kelola data aset & inventaris institusi')

@section('page-actions')
<a href="{{ route('aset.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i>Tambah Aset
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
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Aset</div>
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
                        <div class="stat-label">Aktif</div>
                        <div class="stat-value">{{ number_format($stats['aktif']) }}</div>
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
                        <div class="stat-label">Rusak</div>
                        <div class="stat-value">{{ number_format($stats['rusak']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-icon stat-icon-info">
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
            <form method="GET" action="{{ route('aset.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Program Studi</label>
                        <select name="prodi_id" class="form-select">
                            <option value="">Semua Prodi</option>
                            @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select">
                            <option value="">Semua</option>
                            <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama/Kode/Lokasi" value="{{ request('search') }}">
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
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asets as $aset)
                        <tr>
                            <td class="px-4">
                                <span class="badge bg-secondary">{{ $aset->kode_aset }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $aset->nama_aset }}</div>
                                @if($aset->merk)
                                <small class="text-muted">{{ $aset->merk }} {{ $aset->tipe }}</small>
                                @endif
                            </td>
                            <td>{!! $aset->kategori->badge !!}</td>
                            <td>
                                <div>{{ $aset->lokasi }}</div>
                                @if($aset->ruangan)
                                <small class="text-muted">{{ $aset->ruangan }}</small>
                                @endif
                            </td>
                            <td>{!! $aset->kondisi_badge !!}</td>
                            <td>{!! $aset->status_badge !!}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('aset.show', $aset) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('aset.edit', $aset) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('aset.destroy', $aset) }}" class="d-inline" 
                                        onsubmit="return confirm('Hapus aset ini?')">
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
                                Belum ada data aset.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($asets->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $asets->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
