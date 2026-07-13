@extends('sdm::layouts.master')

@section('title', 'Penilaian Kinerja')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Penilaian Kinerja</h1>
        <a href="{{ route('sdm.penilaian-kinerja.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Buat Penilaian
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted small">Total Penilaian</div>
                            <div class="h4 mb-0">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted small">Tahun Ini</div>
                            <div class="h4 mb-0">{{ $stats['tahun_ini'] ?? 0 }}</div>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted small">Sangat Baik</div>
                            <div class="h4 mb-0">{{ $stats['sangat_baik'] ?? 0 }}</div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted small">Rata-rata Nilai</div>
                            <div class="h4 mb-0">{{ number_format($stats['avg_nilai'] ?? 0, 1) }}</div>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Daftar Penilaian Kinerja</h5>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ route('sdm.penilaian-kinerja.index') }}" class="d-flex gap-2">
                        <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <select name="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Periode</option>
                            <option value="semester_1" {{ request('periode') == 'semester_1' ? 'selected' : '' }}>Semester 1</option>
                            <option value="semester_2" {{ request('periode') == 'semester_2' ? 'selected' : '' }}>Semester 2</option>
                            <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Pegawai</th>
                            <th>Penilai</th>
                            <th>Tanggal</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penilaians as $index => $penilaian)
                        <tr>
                            <td>{{ $penilaians->firstItem() + $index }}</td>
                            <td>{{ $penilaian->tahun }} - {{ str_replace('_', ' ', ucwords($penilaian->periode)) }}</td>
                            <td>
                                <div>
                                    <strong>{{ $penilaian->pegawai->nama }}</strong><br>
                                    <small class="text-muted">{{ $penilaian->pegawai->nip ?? '-' }}</small>
                                </div>
                            </td>
                            <td>{{ $penilaian->penilai->nama ?? '-' }}</td>
                            <td>{{ $penilaian->created_at->format('d M Y') }}</td>
                            <td>
                                @if($penilaian->nilai_total)
                                    <span class="badge {{ $penilaian->nilai_total >= 80 ? 'bg-success' : ($penilaian->nilai_total >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ number_format($penilaian->nilai_total, 1) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($penilaian->status == 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($penilaian->status == 'submitted')
                                    <span class="badge bg-warning">Submitted</span>
                                @elseif($penilaian->status == 'verified')
                                    <span class="badge bg-success">Verified</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sdm.penilaian-kinerja.show', $penilaian) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($penilaian->status != 'verified')
                                <a href="{{ route('sdm.penilaian-kinerja.edit', $penilaian) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete({{ $penilaian->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">Belum ada data penilaian kinerja</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penilaians->hasPages())
        <div class="card-footer">
            {{ $penilaians->links() }}
        </div>
        @endif
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus penilaian kinerja ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = '/portal/penilaian-kinerja/' + id;
        form.submit();
    }
}
</script>
@endpush
@endsection

