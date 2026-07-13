@extends('layouts.app')

@section('title', 'Monitoring IKU/IKT')
@section('page-title', 'Monitoring IKU/IKT')
@section('page-subtitle', 'Pantau capaian indikator kinerja unit kerja')

@section('page-actions')
    <div class="d-flex gap-2">
        <button type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel text-emerald-500"></i>
            <span>Import Excel</span>
        </button>
        <form action="{{ route('monitoring.sync-siakad') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0">
                <i class="bi bi-arrow-repeat text-blue-500"></i>
                <span>Sync SIAKAD</span>
            </button>
        </form>
        <a href="{{ route('monitoring.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-plus-lg"></i>
            <span>Input Data Baru</span>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Monitoring IKU/IKT</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-blue-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-blue-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-bar-chart-line fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['total'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Total Data</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-emerald-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-emerald-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['tercapai'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Tercapai</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-rose-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-rose-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-x-circle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['tidak'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Tidak Tercapai</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-amber-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-amber-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-500 shadow-inner" style="min-width: 48px;">
                    <i class="bi bi-clock fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['belum_eval_'] ?? $stats['belum_eval'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Belum Evaluasi</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
    <div class="p-4 py-3">
        <form method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="periode_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 ps-9 pe-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                            placeholder="Cari indikator..."
                            value="{{ request('search') }}">
                        <i class="bi bi-search position-absolute text-slate-400 text-sm" style="left: 12px; top: 12px;"></i>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-1.5">
                    <button class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-3 py-2.5 text-sm font-bold text-white hover:bg-primary-dark border-0">
                        <i class="bi bi-search"></i>
                        <span>Cari</span>
                    </button>
                    @if(request()->hasAny(['periode_id','status','search']))
                        <a href="{{ route('monitoring.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors text-decoration-none" style="min-width: 40px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100">
                        <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                        <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tanggal Input</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Indikator Kinerja</th>
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Unit Kerja</th>
                        <th width="100" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Target</th>
                        <th width="140" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Capaian</th>
                        <th width="140" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kinerja</th>
                        <th width="140" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status Data</th>
                        <th width="150" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0">
                    @forelse($indikators as $i)
                    @php 
                        $m = $i->monitorings->first(); 
                    @endphp
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                        <td class="py-3.5 text-xs font-bold text-slate-500">
                            {{ $m ? $m->tanggal_input->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="py-3.5">
                            <span class="text-sm font-bold text-slate-800 d-block leading-snug">{{ $i->nama }}</span>
                            <span class="text-xs font-mono font-bold text-primary mt-1 d-block">{{ $i->kode }}</span>
                        </td>
                        <td class="py-3.5 text-sm font-semibold text-slate-600">{{ $i->unit_kerja }}</td>
                        <td class="text-center py-3.5 text-sm font-bold text-slate-700">
                            {{ $i->target_nilai + 0 }} {{ $i->unit_pengukuran }}
                        </td>
                        <td class="text-center py-3.5">
                            <input type="number" step="any" 
                                   class="w-full text-center rounded-xl border border-slate-200 bg-slate-50/50 px-2 py-1.5 text-sm font-bold text-slate-700 inline-edit focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" 
                                   data-indikator-id="{{ $i->id }}" data-periode-id="{{ $periodeSel->id }}" data-field="nilai_capaian"
                                   value="{{ $m ? ($m->nilai_capaian + 0) : '' }}"
                                   placeholder="..."
                                   style="max-width: 100px; margin: 0 auto;">
                        </td>
                        <td class="text-center py-3.5" id="kinerja-{{ $i->id }}">
                            @if($m)
                                @if($m->is_tercapai)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                        Tercapai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-2.5 py-0.5 text-xs font-bold">
                                        Tidak Tercapai
                                    </span>
                                @endif
                                <div class="text-[10px] font-bold text-slate-400 mt-1">{{ number_format($m->persentase_capaian, 1) }}%</div>
                            @else
                                <span class="text-xs font-semibold text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="text-center py-3.5">
                            <select class="w-full rounded-xl border border-slate-100 bg-slate-50/30 px-2.5 py-1.5 text-xs font-bold text-slate-700 inline-edit focus:bg-white" 
                                    data-indikator-id="{{ $i->id }}" data-periode-id="{{ $periodeSel->id }}" data-field="status"
                                    style="max-width: 110px; margin: 0 auto; -webkit-appearance: none; -moz-appearance: none; appearance: none; cursor: pointer; text-align-last: center;">
                                <option value="draft" {{ ($m && $m->status === 'draft') ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ ($m && $m->status === 'submitted') ? 'selected' : '' }}>Submitted</option>
                                <option value="verified" {{ ($m && $m->status === 'verified') ? 'selected' : '' }}>Verified</option>
                            </select>
                        </td>
                        <td class="text-end py-3.5 pe-4">
                            @if($m)
                            <div class="d-flex gap-1.5 justify-content-end">
                                <a href="{{ route('monitoring.show', $m) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('monitoring.edit', $m) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('monitoring.destroy', $m) }}" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-xs font-bold text-slate-400 italic">Belum terisi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-center py-5">
                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                    <i class="bi bi-bar-chart-fill fs-1"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Indikator Aktif</h6>
                                <p class="text-xs font-medium text-slate-400 mb-0">Belum ada indikator kinerja aktif dalam periode yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($indikators->count() > 0)
    <div class="p-4 border-t border-slate-100">
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
            Menampilkan semua data (Total: {{ $indikators->count() }} indikator)
        </div>
    </div>
    @endif
</div>

{{-- Import Modal --}}
<div class='modal fade' id='importModal' tabindex='-1' aria-labelledby='importModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content border-0 rounded-2xl shadow-xl overflow-hidden'>
            <form action='{{ route('monitoring.import') }}' method='POST' enctype='multipart/form-data'>
                @csrf
                <div class='modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-0 py-3.5 px-4'>
                    <div class="d-flex align-items-center gap-2">
                        <i class='bi bi-file-earmark-excel fs-5'></i>
                        <h6 class='modal-title font-bold text-white mb-0' id='importModalLabel'>Import Capaian Monitoring</h6>
                    </div>
                    <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body p-4'>
                    <div class='alert alert-info border-0 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold d-flex gap-2 mb-4'>
                        <i class='bi bi-info-circle-fill fs-5 text-blue-500'></i>
                        <span>Fitur ini mengunggah dan mengupdate capaian indikator secara massal pada Periode Aktif.</span>
                    </div>
                    
                    <div class='mb-4 text-center'>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Heading Kolom Excel</span>
                        <p class="text-xs font-semibold text-slate-700 bg-slate-100 p-2.5 rounded-xl border border-slate-200">
                            kode_indikator, capaian_nilai, analisis, kendala, tindakan
                        </p>
                        <a href="{{ route('monitoring.template') }}" class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50/20 px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 text-decoration-none mt-3">
                            <i class="bi bi-download"></i>
                            <span>Download Template Excel</span>
                        </a>
                    </div>
                    
                    <div>
                        <label class='text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2'>Pilih File (.xlsx / .xls / .csv)</label>
                        <div class="p-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/30 d-flex flex-column gap-2">
                            <input type='file' name='file' class='w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100' accept='.xlsx,.xls,.csv' required>
                        </div>
                    </div>
                </div>
                <div class='modal-footer bg-slate-50 border-0 p-3 px-4 d-flex justify-content-end gap-2'>
                    <button type='button' class='inline-flex items-center justify-center rounded-xl bg-white border border-slate-200 px-4 py-2 text-sm font-bold text-slate-500 hover:bg-slate-50' data-bs-modal='modal' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' class='inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white hover:bg-primary-dark shadow-sm'>Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.inline-edit').forEach(el => {
    el.addEventListener('change', function() {
        const indikator_id = this.dataset.indikatorId;
        const periode_id = this.dataset.periodeId;
        const field = this.dataset.field;
        const value = this.value;
        
        // Visual feedback
        this.style.opacity = '0.5';

        fetch("{{ route('monitoring.update-inline') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ indikator_id, periode_id, field, value })
        })
        .then(response => response.json())
        .then(data => {
            this.style.opacity = '1';
            if (data.success) {
                // Flash success color
                this.classList.add('text-success');
                setTimeout(() => this.classList.remove('text-success'), 1000);
                
                // Update kinerja cell if needed
                if (data.is_tercapai !== undefined) {
                    const kinerjaCell = document.getElementById(`kinerja-${indikator_id}`);
                    if (kinerjaCell) {
                        const badgeClass = data.is_tercapai ? 'bg-success' : 'bg-danger';
                        const badgeText = data.is_tercapai ? 'Tercapai' : 'Tidak Tercapai';
                        kinerjaCell.innerHTML = `<span class="inline-flex items-center gap-1 rounded-full ${badgeClass} text-white border-0 px-2.5 py-0.5 text-xs font-bold">${badgeText}</span><div class="text-[10px] font-bold text-slate-400 mt-1">${data.persentase}</div>`;
                        
                        // Wait, our tailwind overrides look great. Let's make sure bg-success is styled correctly via inline style or custom classes if bg-success isn't overwritten.
                        // Actually, our layout has standard modern badges:
                        const finalBadgeClass = data.is_tercapai ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-rose-50 border-rose-100 text-rose-600';
                        kinerjaCell.innerHTML = `<span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-bold ${finalBadgeClass}">${badgeText}</span><div class="text-[10px] font-bold text-slate-400 mt-1">${data.persentase}</div>`;
                    }
                }
            } else {
                alert(data.message || 'Gagal memperbarui data.');
            }
        })
        .catch(err => {
            this.style.opacity = '1';
            alert('Terjadi kesalahan sistem.');
            console.error(err);
        });
    });
});
</script>
<style>
.inline-edit:focus {
    background-color: #fff !important;
    border: 1px solid #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
}
.inline-edit {
    cursor: pointer;
    transition: all 0.2s;
}
.inline-edit:hover {
    background-color: rgba(0,0,0,0.02) !important;
}
</style>
@endpush
