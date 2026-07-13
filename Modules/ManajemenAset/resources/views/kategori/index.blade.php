@extends('manajemenaset::layouts.master')

@section('title', 'Kategori Aset')
@section('breadcrumb')
<li class="breadcrumb-item active">Kategori Aset</li>
@endsection
@section('page-title', 'Kategori Aset')
@section('page-subtitle', 'Kelola kategori untuk klasifikasi aset')

@section('page-actions')
<a href="{{ route('kategori-aset.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
</a>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Kode</th>
                            <th>Nama Kategori</th>
                            <th>Icon</th>
                            <th>Jumlah Aset</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kategori)
                        <tr>
                            <td class="px-4">
                                <span class="badge bg-secondary">{{ $kategori->kode }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $kategori->nama }}</div>
                                @if($kategori->keterangan)
                                <small class="text-muted">{{ Str::limit($kategori->keterangan, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                {!! $kategori->badge !!}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $kategori->asets_count }} aset</span>
                            </td>
                            <td>
                                @if($kategori->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('kategori-aset.edit', $kategori) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($kategori->asets_count == 0)
                                    <form method="POST" action="{{ route('kategori-aset.destroy', $kategori) }}" class="d-inline" 
                                        onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Tidak dapat dihapus (ada aset)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada kategori aset.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
