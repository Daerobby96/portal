@extends('sdm::layouts.master')

@section('title', 'Manajemen Cuti')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Manajemen Cuti</h1>
            <p class="text-muted small mb-0">Kelola pengajuan cuti pegawai</p>
        </div>
        <a href="{{ route('sdm.cuti.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Ajukan Cuti
        </a>
    </div>

    {{-- Statistics --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-calendar-x fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Cuti</div>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Menunggu</div>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Disetujui</div>
                            <h4 class="mb-0">{{ $stats['approved'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Ditolak</div>
                            <h4 class="mb-0">{{ $stats['rejected'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\Modules\Sdm\Models\Cuti::statusOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Jenis Cuti</label>
                    <select name="jenis_cuti" class="form-select">
                        <option value="">Semua Jenis</option>
                        @foreach(\Modules\Sdm\Models\Cuti::jenisOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('jenis_cuti') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Pegawai</label>
                    <select name="pegawai_id" class="form-select">
                        <option value="">Semua Pegawai</option>
                        @foreach($pegawais as $p)
                        <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('sdm.cuti.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pegawai</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutis as $c)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $c->pegawai->nama }}</div>
                                <small class="text-muted">{{ $c->pegawai->unit_kerja }}</small>
                            </td>
                            <td>{!! $c->jenis_badge !!}</td>
                            <td>
                                {{ $c->tanggal_mulai->format('d M Y') }} - 
                                {{ $c->tanggal_selesai->format('d M Y') }}
                            </td>
                            <td>{{ $c->jumlah_hari }} hari</td>
                            <td>
                                <small>{{ \Str::limit($c->alasan, 50) }}</small>
                            </td>
                            <td>{!! $c->status_badge !!}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('sdm.cuti.show', $c) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($c->status === 'pending')
                                    <a href="{{ route('sdm.cuti.edit', $c) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('sdm.cuti.destroy', $c) }}" 
                                        onsubmit="return confirm('Hapus pengajuan cuti ini?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada pengajuan cuti
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($cutis->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $cutis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
