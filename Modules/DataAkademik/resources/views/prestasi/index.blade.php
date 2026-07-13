@extends('dataakademik::layouts.master')

@section('title', 'Prestasi Mahasiswa')
@section('page-title', 'Prestasi Mahasiswa')

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
    }
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }
    .premium-icon-container {
        width: 48px;
        height: 48px;
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

    <div class="row mb-4 g-3">
        @php $cards = [
            [
                'label'=>'Total Prestasi',
                'value'=>$stats['total'],
                'icon'=>'bi-trophy-fill',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Akademik',
                'value'=>$stats['akademik'],
                'icon'=>'bi-mortarboard-fill',
                'accent'=>'#0ea5e9',
                'bg'=>'rgba(14, 165, 233, 0.08)',
                'shadow'=>'rgba(14, 165, 233, 0.15)'
            ],
            [
                'label'=>'Internasional',
                'value'=>$stats['internasional'],
                'icon'=>'bi-globe-americas',
                'accent'=>'#f59e0b',
                'bg'=>'rgba(245, 158, 11, 0.08)',
                'shadow'=>'rgba(245, 158, 11, 0.15)'
            ],
        ]; @endphp
        @foreach($cards as $c)
        <div class="col-md-4">
            <div class="card premium-card h-100" style="--accent-color: {{ $c['accent'] }};">
                <div class="card-body d-flex align-items-center gap-3 p-4">
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

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-secondary mb-0">Daftar Prestasi Mahasiswa</h5>
            <a href="{{ route('prestasi.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </a>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('prestasi.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Kegiatan / Nama Mhs..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="jenis_prestasi" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Semua Jenis --</option>
                        @foreach(\App\Models\Prestasi::JENIS_PRESTASI as $jp)
                            <option value="{{ $jp }}" {{ request('jenis_prestasi') == $jp ? 'selected' : '' }}>{{ $jp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tingkat" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Semua Tingkat --</option>
                        @foreach(\App\Models\Prestasi::TINGKAT as $tk)
                            <option value="{{ $tk }}" {{ request('tingkat') == $tk ? 'selected' : '' }}>{{ $tk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill shadow-sm">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="rounded-start-3" width="5%">No</th>
                            <th>Mahasiswa</th>
                            <th>Kegiatan & Peringkat</th>
                            <th>Tingkat</th>
                            <th>Tahun</th>
                            <th class="rounded-end-3 text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($prestasis as $index => $p)
                        <tr>
                            <td class="text-muted text-center">{{ $prestasis->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 text-primary">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $p->mahasiswa->nama }}</h6>
                                        <small class="text-muted">{{ $p->mahasiswa->nim }} - {{ $p->mahasiswa->prodi->nama ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block fw-semibold text-truncate" style="max-width: 250px;" title="{{ $p->nama_kegiatan }}">
                                    {{ $p->nama_kegiatan }}
                                </span>
                                @if($p->peringkat)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">{{ $p->peringkat }}</span>
                                @endif
                                <small class="text-muted ms-1">{{ $p->jenis_prestasi }}</small>
                            </td>
                            <td>{!! $p->tingkat_badge !!}</td>
                            <td>{{ $p->tahun }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if($p->sertifikat)
                                    <a href="{{ asset('storage/'.$p->sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" title="Lihat Sertifikat">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('prestasi.edit', $p) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('prestasi.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-3 opacity-50"></i>
                                Belum ada data prestasi mahasiswa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $prestasis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
