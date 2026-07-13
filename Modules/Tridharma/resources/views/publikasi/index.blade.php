@extends('tridharma::layouts.master')

@section('title', 'Data Publikasi')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Publikasi</li>
@endsection
@section('page-title', 'Data Publikasi Dosen')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('publikasi.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Publikasi
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
                        placeholder="Cari judul publikasi..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('publikasi.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
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
                            <th>Judul Publikasi</th>
                            <th>Penulis Utama</th>
                            <th>Jenis Publikasi</th>
                            <th>Jurnal / Penerbit</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center" style="width:100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($publikasis as $p)
                        <tr>
                            <td class="text-muted small">{{ $publikasis->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold text-wrap" style="max-width:250px;">{{ $p->judul }}</div>
                                @if($p->url_tautan)
                                    <a href="{{ $p->url_tautan }}" target="_blank" class="small"><i class="bi bi-link-45deg"></i> Link Publikasi</a>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold small">{{ $p->pegawai?->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $p->jenis }}</span>
                            </td>
                            <td>
                                <div>{{ $p->nama_jurnal_penerbit ?? '-' }}</div>
                                <div class="text-muted small">{{ $p->volume_nomor ?? '' }}</div>
                                @if($p->tingkat_sinta) <span class="badge bg-warning text-dark mt-1">{{ $p->tingkat_sinta }}</span> @endif
                            </td>
                            <td class="text-center fw-bold">{{ $p->tahun }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('publikasi.edit', $p) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('publikasi.destroy', $p) }}" onsubmit="return confirm('Hapus Publikasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">Belum ada data publikasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($publikasis->hasPages())
        <div class="card-footer bg-white">{{ $publikasis->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection

