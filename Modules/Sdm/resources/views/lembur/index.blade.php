@extends('sdm::layouts.master')

@section('title', 'Lembur')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Daftar Lembur</h1>
        <a href="{{ route('sdm.lembur.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajukan Lembur
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
                            <div class="text-muted small">Total Lembur</div>
                            <div class="h4 mb-0">{{ $total }}</div>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-clock fa-2x"></i>
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
                            <div class="text-muted small">Menunggu</div>
                            <div class="h4 mb-0">{{ $pending }}</div>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-hourglass-half fa-2x"></i>
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
                            <div class="text-muted small">Disetujui</div>
                            <div class="h4 mb-0">{{ $disetujui }}</div>
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
                            <div class="text-muted small">Total Jam Bulan Ini</div>
                            <div class="h4 mb-0">{{ $totalJamBulanIni ?? 0 }} jam</div>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-calendar-check fa-2x"></i>
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
                    <h5 class="mb-0">Riwayat Lembur</h5>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ route('sdm.lembur.index') }}" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
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
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Durasi</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lemburList as $index => $lembur)
                        <tr>
                            <td>{{ $lemburList->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($lembur->tanggal)->format('d M Y') }}</td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($lembur->waktu_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($lembur->waktu_selesai)->format('H:i') }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $lembur->durasi_jam }} jam</span></td>
                            <td>
                                <small>{{ Str::limit($lembur->keterangan, 50) }}</small>
                            </td>
                            <td>
                                @if($lembur->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($lembur->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sdm.lembur.show', $lembur->id) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($lembur->status == 'pending')
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete({{ $lembur->id }})" title="Batalkan">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">Belum ada data lembur</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($lemburList->hasPages())
        <div class="card-footer">
            {{ $lemburList->links() }}
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
    if (confirm('Apakah Anda yakin ingin membatalkan pengajuan lembur ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = '/portal/lembur/' + id;
        form.submit();
    }
}
</script>
@endpush
@endsection

