@extends('kerjasama::layouts.master')

@section('title', 'Data Kerjasama')
@section('page-title', 'Manajemen Mitra & Kerjasama')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Flash Messages --}}

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
                'label'=>'Total Kerjasama',
                'value'=>$stats['total'],
                'icon'=>'bi-link-45deg',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Status Aktif',
                'value'=>$stats['aktif'],
                'icon'=>'bi-check-circle-fill',
                'accent'=>'#10b981',
                'bg'=>'rgba(16, 185, 129, 0.08)',
                'shadow'=>'rgba(16, 185, 129, 0.15)'
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
            <h5 class="fw-bold text-secondary mb-0">Daftar Kerja Sama & Mitra</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import Data
                </button>
                <a href="{{ route('kerjasama.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('kerjasama.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Mitra / Judul..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="tingkat" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Tingkat --</option>
                        @foreach(\Modules\Kerjasama\Models\Kerjasama::TINGKAT as $tk)
                            <option value="{{ $tk }}" {{ request('tingkat') == $tk ? 'selected' : '' }}>{{ $tk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="jenis_dokumen" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Dokumen --</option>
                        @foreach(\Modules\Kerjasama\Models\Kerjasama::JENIS_DOKUMEN as $jd)
                            <option value="{{ $jd }}" {{ request('jenis_dokumen') == $jd ? 'selected' : '' }}>{{ $jd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm rounded-pill shadow-sm">
                        <option value="">-- Semua Status --</option>
                        @foreach(\Modules\Kerjasama\Models\Kerjasama::STATUS as $st)
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
                            <th>Nama Mitra</th>
                            <th>Judul Kegiatan & Dokumen</th>
                            <th>Tingkat</th>
                            <th>Status</th>
                            <th>Masa Berlaku</th>
                            <th class="rounded-end-3 text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($kerjasamas as $index => $k)
                        <tr>
                            <td class="text-muted text-center">{{ $kerjasamas->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3 text-primary">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $k->nama_mitra }}</h6>
                                        <small class="text-muted">{{ $k->jenis_mitra }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-truncate" style="max-width: 200px;" title="{{ $k->judul_kerjasama }}">
                                    {{ $k->judul_kerjasama }}
                                </span>
                                @if($k->jenis_dokumen)
                                    <small class="badge bg-info text-dark border">{{ $k->jenis_dokumen }}</small>
                                @endif
                                @if($k->prodi)
                                    <small class="badge bg-light text-secondary border">Prodi: {{ $k->prodi->nama }}</small>
                                @endif
                            </td>
                            <td>{!! $k->tingkat_badge !!}</td>
                            <td>
                                {!! $k->status_badge !!}
                                @if($k->isExpiring())
                                    <div class="text-danger small mt-1 fw-bold" style="font-size: 0.7rem;"><i class="bi bi-exclamation-triangle me-1"></i>Akan Kedaluwarsa</div>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted d-block"><i class="bi bi-play-circle me-1"></i>{{ $k->tanggal_mulai->format('d M Y') }}</small>
                                <small class="{{ $k->isExpiring() ? 'text-danger fw-bold' : 'text-muted' }} d-block"><i class="bi bi-stop-circle me-1"></i>{{ $k->tanggal_selesai ? $k->tanggal_selesai->format('d M Y') : 'Seterusnya' }}</small>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if($k->dokumen_mou)
                                    <a href="{{ asset('storage/'.$k->dokumen_mou) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" title="Lihat Dokumen">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('kerjasama.show', $k) }}" class="btn btn-sm btn-outline-secondary rounded-circle" title="Detail & Evaluasi">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('kerjasama.edit', $k) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kerjasama.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                                Belum ada data kerjasama/mitra
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $kerjasamas->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('kerjasama.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="modal-title fw-bold text-primary" id="importModalLabel">Import Data Kerjasama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Upload file excel (.xlsx, .xls) sesuai dengan template yang disediakan.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('kerjasama.template') }}" class="btn btn-sm btn-outline-info rounded-pill">
                            <i class="bi bi-download me-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-upload me-1"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


