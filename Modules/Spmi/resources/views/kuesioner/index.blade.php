@extends('layouts.app')

@section('title', 'Manajemen Kuesioner')

@section('page-title', 'Manajemen Kuesioner')
@section('page-subtitle', 'Kelola survei kepuasan dan evaluasi diri institusi.')

@section('page-actions')
    @if(auth()->user()->isSuperAdmin())
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary rounded-xl text-xs font-bold px-3 border-slate-200 hover:border-primary" data-bs-toggle="modal" data-bs-target="#importSiakadModal">
            <i class="bi bi-cloud-arrow-up me-1.5 text-success"></i>Import dari Siakad
        </button>
        <a href="{{ route('kuesioner.create') }}" class="btn btn-primary rounded-xl text-xs font-bold px-3 shadow-sm">
            <i class="bi bi-plus-lg me-1.5"></i>Buat Kuesioner Baru
        </a>
    </div>
    @endif
@endsection

@section('content')
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="ps-4 py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Nama Kuesioner</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 150px">Periode</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 150px">Target</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 150px">Status</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center" style="width: 150px">Responden</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-end pe-4" style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuesioners as $k)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-slate-800" style="font-size: 13.5px;">{{ $k->full_judul }}</div>
                            <small class="text-slate-400 font-semibold d-block mt-0.5" style="max-width: 450px;">{{ Str::limit($k->deskripsi, 80) }}</small>
                        </td>
                        <td>
                            <code class="small px-2 py-1 rounded bg-slate-50 text-slate-600 font-semibold">{{ $k->periode->nama }}</code>
                        </td>
                        <td>
                            <span class="badge bg-slate-100 text-slate-600 border border-slate-200/50 rounded-pill px-2.5 py-1.5 font-bold text-[10px]">
                                {{ $k->target_role ? ucfirst($k->target_role) : 'Semua Unit' }}
                            </span>
                        </td>
                        <td>
                            @if($k->status == 'aktif')
                                <span class="badge bg-emerald-50 text-emerald-600 border border-emerald-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px] d-inline-flex align-items-center gap-1 shadow-none">
                                    <span class="relative d-flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                    </span>
                                    <span>Aktif</span>
                                </span>
                            @elseif($k->status == 'draft')
                                <span class="badge bg-slate-50 text-slate-400 border border-slate-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px]">
                                    Draft
                                </span>
                            @else
                                <span class="badge bg-red-50 text-red-500 border border-red-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px]">
                                    Selesai
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="fw-extrabold fs-5 text-primary" style="line-height: 1.1;">{{ $k->jawabans_count }}</div>
                            <small class="text-slate-400 font-bold" style="font-size: 9px; text-uppercase; tracking-wide">Pengisi</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('kuesioner.show', $k) }}" class="btn btn-sm btn-light text-primary rounded-xl" title="Lihat Hasil">
                                    <i class="bi bi-bar-chart-fill"></i>
                                </a>
                                @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('kuesioner.edit', $k) }}" class="btn btn-sm btn-light text-warning rounded-xl" title="Edit / Pertanyaan">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('kuesioner.destroy', $k) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-xl" title="Hapus Kuesioner" onclick="return confirm('Apakah Anda yakin ingin menghapus kuesioner ini? Seluruh data jawaban responden juga akan terhapus.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-400 py-5">
                            <i class="bi bi-clipboard-x display-4 d-block mb-3 opacity-25"></i>
                            <p class="small font-semibold mb-0">Belum ada kuesioner yang dibuat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($kuesioners->hasPages())
    <div class="card-footer bg-white border-0 py-3 px-4 border-t border-slate-100">
        {{ $kuesioners->links() }}
    </div>
    @endif
</div>
@endsection

@if(auth()->user()->isSuperAdmin())
<!-- Modal Import Siakad (Modernized) -->
<div class="modal fade" id="importSiakadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden bg-white">
            <div class="modal-header border-b border-slate-100 py-3.5 px-4 bg-white">
                <h5 class="modal-title fw-extrabold text-slate-800 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-arrow-up text-primary"></i>
                    <span>Import Kuesioner Siakad</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kuesioner.import-siakad') }}" method="POST" enctype="multipart/form-data" onsubmit="let btn = this.querySelector('button[type=submit]'); btn.disabled = true; btn.innerHTML = '<i class=\'bi bi-hourglass-split me-2\'></i>Memproses...';">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-xl small d-flex gap-2 mb-4">
                        <i class="bi bi-info-circle-fill text-info-600 mt-0.5"></i>
                        <span>Pilih file <strong>Laporan Rekap Kuesioner</strong> (.xls) yang diunduh langsung dari Siakad.</span>
                    </div>
                    
                    <div class="p-4 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200 text-center mb-3">
                        <i class="bi bi-file-earmark-excel fs-1 text-primary mb-3 d-block"></i>
                        <label for="siakad_file" class="form-label font-bold text-slate-700 small mb-2 d-block">PILIH FILE XLS SIAKAD</label>
                        <input type="file" name="file" class="form-control rounded-xl text-xs" id="siakad_file" required>
                        <div class="form-text mt-2 text-[10px] text-slate-400">
                            Sistem akan otomatis mendeteksi periode, judul, dan hasil kuesioner dari file tersebut.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-slate-50 py-3 px-4">
                    <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-xl font-bold text-xs px-4 shadow-sm">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
