@extends('tridharma::layouts.master')

@section('title', 'Data PkM')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Pengabdian</li>
@endsection
@section('page-title', 'Data Pengabdian kepada Masyarakat (PkM)')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('pengabdian.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah PkM
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari judul atau ketua..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('pengabdian.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Judul PkM</th>
                            <th>Ketua Pengabdi</th>
                            <th>Mitra / Lokasi</th>
                            <th>Tahun</th>
                            <th class="text-center" style="width:100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengabdians as $p)
                        <tr>
                            <td class="text-muted small">{{ $pengabdians->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold text-wrap" style="max-width:300px;">{{ $p->judul }}</div>
                                <div class="text-muted small">{{ $p->prodi?->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold small">{{ $p->pegawai?->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $p->mitra ?? '-' }}</div>
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $p->lokasi ?? '-' }}</div>
                            </td>
                            <td class="fw-bold">{{ $p->tahun }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pengabdian.edit', $p) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('pengabdian.destroy', $p) }}" onsubmit="return confirm('Hapus PkM ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada data PkM.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pengabdians->hasPages())
        <div class="card-footer bg-white">{{ $pengabdians->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection

