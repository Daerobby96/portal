@extends('dataakademik::layouts.master')

@section('title', 'Data Mahasiswa')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Mahasiswa</li>
@endsection
@section('page-title', 'Data Mahasiswa')
@section('page-subtitle', 'Manajemen data akademis mahasiswa')

@section('page-actions')
<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('mahasiswa.template') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-file-earmark-excel me-1"></i>Template
    </a>
    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
        <i class="bi bi-upload me-1"></i>Import Excel
    </button>
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Mahasiswa
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">

@push('styles')
<style>
    .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
        border-radius: 4px 0 0 4px;
        transition: all 0.3s ease;
    }
    .premium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06);
    }
    .premium-card:hover::before {
        width: 6px;
    }
    .premium-icon-container {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: var(--bg-color);
        color: var(--accent-color);
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    .premium-card:hover .premium-icon-container {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 15px var(--shadow-color);
    }
</style>
@endpush

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php $cards = [
            [
                'label'=>'Total Mahasiswa',
                'value'=>$stats['total'],
                'icon'=>'bi-people-fill',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Mahasiswa Aktif',
                'value'=>$stats['aktif'],
                'icon'=>'bi-person-check-fill',
                'accent'=>'#10b981',
                'bg'=>'rgba(16, 185, 129, 0.08)',
                'shadow'=>'rgba(16, 185, 129, 0.15)'
            ],
            [
                'label'=>'Telah Lulus',
                'value'=>$stats['lulus'],
                'icon'=>'bi-mortarboard-fill',
                'accent'=>'#0ea5e9',
                'bg'=>'rgba(14, 165, 233, 0.08)',
                'shadow'=>'rgba(14, 165, 233, 0.15)'
            ],
            [
                'label'=>'Mengundurkan Diri',
                'value'=>$stats['mengundurkan_diri'],
                'icon'=>'bi-person-dash-fill',
                'accent'=>'#f97316',
                'bg'=>'rgba(249, 115, 22, 0.08)',
                'shadow'=>'rgba(249, 115, 22, 0.15)'
            ],
            [
                'label'=>'Dropout (DO)',
                'value'=>$stats['do'],
                'icon'=>'bi-person-x-fill',
                'accent'=>'#ef4444',
                'bg'=>'rgba(239, 68, 68, 0.08)',
                'shadow'=>'rgba(239, 68, 68, 0.15)'
            ],
        ]; @endphp
        @foreach($cards as $c)
        <div class="col-6 col-md">
            <div class="card premium-card" style="--accent-color: {{ $c['accent'] }};">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="premium-icon-container" style="--bg-color: {{ $c['bg'] }}; --accent-color: {{ $c['accent'] }}; --shadow-color: {{ $c['shadow'] }};">
                        <i class="bi {{ $c['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ $c['label'] }}</p>
                        <h3 class="mb-0 fw-bold text-dark mt-1" style="font-size: 1.8rem; line-height: 1.2;">{{ $c['value'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari NIM atau nama..." value="{{ request('search') }}">
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
                    <select name="angkatan" class="form-select form-select-sm">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatans as $a)
                        <option value="{{ $a }}" {{ request('angkatan') == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $k => $v)
                        <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="jenis_kelamin" class="form-select form-select-sm">
                        <option value="">L/P</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Daftar Mahasiswa</h6>
            <small class="text-muted">{{ $mahasiswas->total() }} data</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>NIM / Nama</th>
                            <th>Prodi & Angkatan</th>
                            <th class="text-center">L/P</th>
                            <th class="text-center">IPK</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswas as $m)
                        <tr>
                            <td class="text-muted small">{{ $mahasiswas->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold
                                        d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:34px;height:34px;font-size:.8rem">
                                        {{ $m->inisial }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small"><a href="{{ route('mahasiswa.show', $m) }}" class="text-decoration-none text-dark">{{ $m->nama }}</a></div>
                                        <div class="text-muted font-monospace" style="font-size:.73rem">
                                            {{ $m->nim }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">{{ $m->prodi?->nama ?? '-' }}</div>
                                <div class="text-muted" style="font-size:.73rem">Angkatan {{ $m->angkatan ?? '-' }}</div>
                            </td>
                            <td class="text-center small">{{ $m->jenis_kelamin ?? '-' }}</td>
                            <td class="text-center font-monospace small">
                                @if($m->ipk)
                                    <span class="{{ $m->ipk >= 3.5 ? 'text-success fw-bold' : '' }}">{{ $m->ipk }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{!! $m->status_badge !!}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('mahasiswa.show', $m) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.edit', $m) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('mahasiswa.destroy', $m) }}"
                                        onsubmit="return confirm('Hapus {{ addslashes($m->nama) }}?')">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-mortarboard fs-1 d-block mb-2 opacity-25"></i>
                                <p class="mb-2">Belum ada data mahasiswa.</p>
                                <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($mahasiswas->hasPages())
        <div class="card-footer bg-white">
            {{ $mahasiswas->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Modal Import --}}
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('mahasiswa.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upload text-primary me-2"></i>Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small py-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Download <a href="{{ route('mahasiswa.template') }}">template Excel</a> terlebih dahulu.
                        <br>Gunakan data dari PDDikti / Siakad untuk kolom NIM, Nama, dan Prodi.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control"
                            accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
