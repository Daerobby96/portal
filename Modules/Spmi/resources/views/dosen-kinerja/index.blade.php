@extends('layouts.app')

@section('title', 'Kinerja Dosen (EDOM)')

@section('page-title', 'Analisis Kinerja Dosen')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.export.pdf', ['type' => 'edom', 'periode_id' => $selectedPeriodeId]) }}" class="btn btn-outline-dark rounded-xl d-flex align-items-center gap-2 text-sm font-bold border-slate-200" target="_blank">
            <i class="bi bi-file-pdf text-danger"></i>
            <span>Cetak Laporan Resmi</span>
        </a>
        <button class="btn btn-primary rounded-xl d-flex align-items-center gap-2 text-sm font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up"></i>
            <span>Import Nilai EDOM</span>
        </button>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Kinerja Dosen</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-body p-4">
                <form action="{{ route('kinerja-dosen.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="small font-bold text-slate-400 text-uppercase tracking-wider d-block mb-2">PILIH PERIODE</label>
                        <select name="periode_id" class="form-select border-slate-200 rounded-xl py-2 px-3 text-slate-700" onchange="this.form.submit()">
                            @foreach($periodes as $p)
                                <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 text-md-end mt-4 mt-md-0">
                        <div class="d-flex justify-content-md-end gap-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-slate-400 font-semibold small text-uppercase tracking-wider">Total Dosen</div>
                                    <div class="fs-4 font-extrabold text-slate-800">{{ $kinerjas->count() }}</div>
                                </div>
                            </div>
                            <div class="border-start border-slate-100 ps-4 d-flex align-items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                                    <i class="bi bi-award fs-4"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-slate-400 font-semibold small text-uppercase tracking-wider">Rerata Institusi</div>
                                    <div class="fs-4 font-extrabold text-emerald-600">{{ number_format($kinerjas->avg('total_rerata'), 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-slate-50/70">
                        <tr>
                            <th class="ps-4 py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 80px">Rank</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Nama Dosen</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center" style="width: 150px">NIP</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Homebase</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center" style="width: 150px">Skor Rerata</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center" style="width: 180px">Predikat</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center pe-4" style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kinerjas as $index => $k)
                        <tr>
                            <td class="ps-4">
                                <div class="rank-badge-lux {{ $index == 0 ? 'rank-gold' : ($index == 1 ? 'rank-silver' : ($index == 2 ? 'rank-bronze' : 'rank-normal')) }}">
                                    @if($index < 3)
                                        <i class="bi bi-trophy-fill me-0.5" style="font-size: 11px;"></i>
                                    @endif
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-slate-800">{{ $k->dosen_name }}</div>
                            </td>
                            <td class="text-center">
                                <code class="small px-2 py-1 rounded bg-slate-50 text-slate-600">{{ $k->dosen_nip ?? '-' }}</code>
                            </td>
                            <td>
                                <span class="small text-slate-500 font-medium">{{ $k->homebase }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fs-5 font-extrabold {{ $k->total_rerata >= 4.5 ? 'text-emerald-600' : ($k->total_rerata >= 3.75 ? 'text-blue-600' : ($k->total_rerata >= 3 ? 'text-warning' : 'text-danger')) }}">
                                    {{ number_format($k->total_rerata, 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $score = $k->total_rerata;
                                    $label = 'Cukup'; 
                                    $class = 'bg-amber-50 text-amber-600 border-amber-200/50';
                                    if($score >= 4.5) { 
                                        $label = 'Sangat Baik'; 
                                        $class = 'bg-emerald-50 text-emerald-600 border-emerald-200/50'; 
                                    }
                                    elseif($score >= 3.75) { 
                                        $label = 'Baik'; 
                                        $class = 'bg-blue-50 text-blue-600 border-blue-200/50'; 
                                    }
                                    elseif($score < 3) { 
                                        $label = 'Kurang'; 
                                        $class = 'bg-red-50 text-red-600 border-red-200/50'; 
                                    }
                                @endphp
                                <span class="badge {{ $class }} rounded-pill px-3 py-1.5 border font-bold small shadow-none">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('kinerja-dosen.show', $k->id) }}" class="btn btn-sm btn-light border-0 rounded-xl px-3 font-bold text-xs text-slate-700 hover:bg-slate-100 transition-all">
                                    Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-slate-400">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-30"></i>
                                    <span class="small font-semibold">Belum ada data kinerja dosen untuk periode ini.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal (Modernized Uploader) -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl shadow-xl overflow-hidden">
            <div class="modal-header border-b border-slate-100 py-3.5 px-4 bg-white">
                <h5 class="fw-bold text-slate-800 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up text-primary"></i>
                    <span>Import Nilai EDOM (SIAKAD)</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kinerja-dosen.import-edom') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-slate-400 small mb-4">Silakan unggah dokumen laporan evaluasi dosen (format file .xls langsung dari SIAKAD) untuk memproses penilaian otomatis.</p>
                    <div class="p-4 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200 text-center mb-3">
                        <i class="bi bi-file-earmark-excel fs-1 text-primary mb-3 d-block"></i>
                        <label class="form-label small fw-bold text-slate-700 mb-2">PILIH BERKAS LAPORAN (.XLS)</label>
                        <input type="file" name="file" class="form-control rounded-xl text-xs" accept=".xls" required>
                    </div>
                    <div class="alert alert-info border-0 rounded-xl small d-flex gap-2">
                        <i class="bi bi-info-circle-fill text-info-600 mt-0.5"></i>
                        <span>Sistem akan mengurai skor kuesioner mahasiswa, lalu menghitung rata-rata indikator kinerja pengajaran dosen secara otomatis.</span>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-slate-50 py-3 px-4">
                    <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-xl font-bold text-xs px-4 shadow-sm">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rank-badge-lux {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 800;
        font-size: 0.8rem;
    }
    .rank-gold {
        background: #fbbf24;
        color: #fff;
        box-shadow: 0 4px 10px rgba(251, 191, 36, 0.3);
    }
    .rank-silver {
        background: #94a3b8;
        color: #fff;
        box-shadow: 0 4px 10px rgba(148, 163, 184, 0.3);
    }
    .rank-bronze {
        background: #b45309;
        color: #fff;
        box-shadow: 0 4px 10px rgba(180, 83, 9, 0.3);
    }
    .rank-normal {
        background: #f1f5f9;
        color: #64748b;
    }
</style>
@endsection
