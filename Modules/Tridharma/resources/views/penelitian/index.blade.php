@extends('tridharma::layouts.master')

@section('title', 'Data Penelitian Dosen')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Penelitian</li>
@endsection
@section('page-title', 'Data Penelitian Dosen')
@section('page-subtitle', 'Manajemen data tridharma penelitian')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('penelitian.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Penelitian
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari judul atau nama ketua..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <select name="prodi" class="form-select form-select-sm">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ request('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($tahuns as $t)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('penelitian.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Judul Penelitian</th>
                            <th>Ketua Peneliti</th>
                            <th>Tahun & Dana</th>
                            <th>Tingkat</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width:100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penelitians as $p)
                        <tr>
                            <td class="text-muted small">{{ $penelitians->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold text-wrap" style="max-width:300px;">{{ $p->judul }}</div>
                                <div class="text-muted small">{{ $p->prodi?->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold
                                        d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:34px;height:34px;font-size:.8rem">
                                        {{ $p->pegawai?->inisial ?? '?' }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $p->pegawai?->nama ?? '-' }}</div>
                                        @if($p->anggota)
                                            <div class="text-muted" style="font-size:.7rem" title="{{ $p->anggota }}">
                                                + Anggota
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $p->tahun }}</div>
                                <div class="text-muted small">{{ $p->sumber_dana ?? 'Mandiri' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $p->tingkat }}</span>
                            </td>
                            <td class="text-center">{!! $p->status_badge !!}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('penelitian.edit', $p) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('penelitian.destroy', $p) }}" onsubmit="return confirm('Hapus penelitian ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada data penelitian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penelitians->hasPages())
        <div class="card-footer bg-white">
            {{ $penelitians->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

