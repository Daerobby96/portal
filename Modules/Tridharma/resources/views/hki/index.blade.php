@extends('layouts.app')

@section('title', 'Data HKI & Paten')
@section('page-title', 'Data HKI & Paten')

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
                'label'=>'Total Data',
                'value'=>$stats['total'],
                'icon'=>'bi-lightbulb-fill',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Status Granted',
                'value'=>$stats['granted'],
                'icon'=>'bi-check-circle-fill',
                'accent'=>'#10b981',
                'bg'=>'rgba(16, 185, 129, 0.08)',
                'shadow'=>'rgba(16, 185, 129, 0.15)'
            ],
            [
                'label'=>'Total Paten',
                'value'=>$stats['paten'],
                'icon'=>'bi-shield-check',
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
            <h5 class="fw-bold text-secondary mb-0">Daftar HKI & Paten Dosen</h5>
            <a href="{{ route('hki.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </a>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('hki.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Judul / Nama Dosen..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="jenis_hki" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Semua Jenis --</option>
                        @foreach(\App\Models\Hki::JENIS_HKI as $jp)
                            <option value="{{ $jp }}" {{ request('jenis_hki') == $jp ? 'selected' : '' }}>{{ $jp }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Semua Status --</option>
                        @foreach(\App\Models\Hki::STATUS as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
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
                            <th>Judul / Nomor Pencatatan</th>
                            <th>Dosen Pengusul</th>
                            <th>Jenis</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th class="rounded-end-3 text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($hkis as $index => $h)
                        <tr>
                            <td class="text-muted text-center">{{ $hkis->firstItem() + $index }}</td>
                            <td>
                                <span class="d-block fw-semibold" style="max-width: 250px;">
                                    {{ $h->judul_hki }}
                                </span>
                                @if($h->nomor_pencatatan)
                                    <small class="text-muted"><i class="bi bi-upc-scan me-1"></i>{{ $h->nomor_pencatatan }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2 text-primary">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $h->pegawai->nama }}</h6>
                                        <small class="text-muted">{{ $h->pegawai->nip ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ $h->jenis_hki }}</span>
                            </td>
                            <td>{{ $h->tahun_terbit }}</td>
                            <td>{!! $h->status_badge !!}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if($h->sertifikat)
                                    <a href="{{ asset('storage/'.$h->sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" title="Lihat Sertifikat">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('hki.edit', $h) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('hki.destroy', $h) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-3 opacity-50"></i>
                                Belum ada data HKI & Paten
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $hkis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
