@extends('sdm::layouts.master')

@section('title', 'Data Presensi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Data Presensi</h1>
            <p class="text-muted small mb-0">Kelola data kehadiran pegawai</p>
        </div>
        <a href="{{ route('sdm.presensi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Presensi
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
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
                            <div class="text-muted small">Hadir Hari Ini</div>
                            <h4 class="mb-0">{{ $stats['hadir_hari_ini'] }}</h4>
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
                                <i class="bi bi-exclamation-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Izin/Sakit</div>
                            <h4 class="mb-0">{{ $stats['izin_hari_ini'] }}</h4>
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
                            <div class="text-muted small">Alpa Hari Ini</div>
                            <h4 class="mb-0">{{ $stats['alpa_hari_ini'] }}</h4>
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
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Hari Ini</div>
                            <h4 class="mb-0">{{ $stats['total_hari_ini'] }}</h4>
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
                    <label class="form-label small fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', today()->format('Y-m-d')) }}">
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
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\Modules\Sdm\Models\Presensi::statusOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('sdm.presensi.index') }}" class="btn btn-outline-secondary">Reset</a>
                        <a href="{{ route('sdm.presensi.rekap') }}" class="btn btn-outline-info">
                            <i class="bi bi-table me-1"></i>Rekap
                        </a>
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
                            <th>Tanggal</th>
                            <th>Pegawai</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Durasi Kerja</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $p)
                        <tr>
                            <td>{{ $p->tanggal->format('d M Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->pegawai->nama }}</div>
                                <small class="text-muted">{{ $p->pegawai->unit_kerja }}</small>
                            </td>
                            <td>{{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }}</td>
                            <td>{{ $p->jam_keluar ? substr($p->jam_keluar, 0, 5) : '-' }}</td>
                            <td>{{ $p->durasi_kerja ?? '-' }}</td>
                            <td>{!! $p->status_badge !!}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('sdm.presensi.edit', $p) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('sdm.presensi.destroy', $p) }}" 
                                        onsubmit="return confirm('Hapus data presensi ini?')" class="d-inline">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data presensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($presensis->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $presensis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
