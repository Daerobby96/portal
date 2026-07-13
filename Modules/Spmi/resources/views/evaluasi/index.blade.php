@extends('layouts.app')

@section('title', 'Evaluasi Monitoring')
@section('page-title', 'Evaluasi Monitoring')
@section('page-subtitle', 'Evaluasi hasil capaian indikator kinerja')

@section('page-actions')
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2 m-0" id="periodeForm">
            <select name="periode_id" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-primary/10" style="min-width: 160px;" onchange="this.form.submit()">
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ $periodeSel->id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            @if(request('hasil'))
                <input type="hidden" name="hasil" value="{{ request('hasil') }}">
            @endif
        </form>
        <a href="{{ route('evaluasi.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-plus-lg"></i>
            <span>Buat Evaluasi</span>
        </a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Evaluasi Monitoring</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-4 mb-4">
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
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['tidak_tercapai'] }}</h2>
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
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>
                <div>
                    <h2 class="mb-0 font-extrabold tracking-tight text-slate-800 fs-2">{{ $stats['perlu_perhatian'] }}</h2>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 d-block">Perlu Perhatian</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-l-slate-500 h-100 overflow-hidden relative group transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -bottom-6 h-24 w-24 rounded-full bg-slate-500/5 blur-lg group-hover:scale-150 transition-all duration-700"></div>
            <div class="card-body p-4 d-flex align-items-center gap-3 relative z-10">
                <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 shadow-inner" style="min-width: 48px;">
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
                <div class="col-md-5">
                    <select name="hasil" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-sm text-slate-700 focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                        <option value="">Semua Hasil Evaluasi</option>
                        <option value="tercapai" {{ request('hasil') === 'tercapai' ? 'selected' : '' }}>Tercapai</option>
                        <option value="tidak_tercapai" {{ request('hasil') === 'tidak_tercapai' ? 'selected' : '' }}>Tidak Tercapai</option>
                        <option value="perlu_perhatian" {{ request('hasil') === 'perlu_perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1.5">
                    <button class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-3 py-2.5 text-sm font-bold text-white hover:bg-primary-dark transition-colors border-0">
                        <i class="bi bi-filter"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->hasAny(['hasil']))
                        <a href="{{ route('evaluasi.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors text-decoration-none" style="min-width: 40px;">
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
                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Indikator Mutu</th>
                        <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Capaian</th>
                        <th style="min-width: 320px;" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Analisa & Rekomendasi Evaluator</th>
                        <th width="160" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Hasil Akhir</th>
                        <th width="150" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Pelapor Data</th>
                        <th width="120" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t-0">
                    @forelse($monitorings as $m)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                        <td class="py-3.5">
                            <span class="text-sm font-bold text-slate-800 d-block leading-snug">{{ $m->indikator->nama }}</span>
                            <span class="text-xs font-mono font-bold text-primary mt-1 d-block">{{ $m->indikator->kode }}</span>
                            <span class="text-[10px] font-bold text-slate-400 mt-1 d-block">
                                <i class="bi bi-bullseye me-1"></i>Target: {{ $m->indikator->target_nilai + 0 }} {{ $m->indikator->unit_pengukuran }}
                            </span>
                        </td>
                        <td class="text-center py-3.5">
                            <div class="text-sm font-extrabold text-slate-700 mb-1">{{ $m->nilai_capaian + 0 }} {{ $m->indikator->unit_pengukuran }}</div>
                            @php $persen = $m->persentase_capaian; @endphp
                            @if($persen >= 100)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2 py-0.5 text-[10px] font-bold">
                                    {{ number_format($persen, 1) }}%
                                </span>
                            @elseif($persen >= 80)
                                <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-2 py-0.5 text-[10px] font-bold">
                                    {{ number_format($persen, 1) }}%
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-2 py-0.5 text-[10px] font-bold">
                                    {{ number_format($persen, 1) }}%
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5">
                            <textarea class="w-full text-xs font-semibold text-slate-600 rounded-xl border border-slate-200 bg-slate-50/30 px-3 py-2 inline-eval focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" 
                                      data-m-id="{{ $m->id }}" data-field="analisa"
                                      rows="2" placeholder="Tulis analisis evaluasi di sini...">{{ $m->evaluasi->analisa ?? '' }}</textarea>
                        </td>
                        <td class="text-center py-3.5">
                            <select class="w-full rounded-xl border border-slate-200 bg-slate-50/30 px-2.5 py-1.5 text-xs font-bold text-slate-700 inline-eval focus:bg-white focus:outline-none" 
                                    data-m-id="{{ $m->id }}" data-field="hasil"
                                    style="max-width: 140px; margin: 0 auto; -webkit-appearance: none; -moz-appearance: none; appearance: none; text-align-last: center; cursor: pointer;">
                                <option value="">- Pilih Hasil -</option>
                                <option value="tercapai" {{ ($m->evaluasi && $m->evaluasi->hasil === 'tercapai') ? 'selected' : '' }}>🟢 Tercapai</option>
                                <option value="tidak_tercapai" {{ ($m->evaluasi && $m->evaluasi->hasil === 'tidak_tercapai') ? 'selected' : '' }}>🔴 Tidak Tercapai</option>
                                <option value="perlu_perhatian" {{ ($m->evaluasi && $m->evaluasi->hasil === 'perlu_perhatian') ? 'selected' : '' }}>🟡 Perlu Perhatian</option>
                            </select>
                        </td>
                        <td class="py-3.5">
                            <div class="text-xs font-bold text-slate-700">{{ $m->pelapor->name ?? '-' }}</div>
                            <div class="text-[10px] font-medium text-slate-400 mt-0.5"><i class="bi bi-clock me-1"></i>{{ $m->tanggal_input->format('d/m/y') }}</div>
                        </td>
                        <td class="text-end py-3.5 pe-4">
                            <div class="d-flex gap-1.5 justify-content-end">
                                @if($m->evaluasi)
                                <a href="{{ route('evaluasi.show', $m->evaluasi) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail Evaluasi">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                                <a href="{{ route('monitoring.show', $m) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-500 transition-all hover:bg-blue-500 hover:text-white" title="Detail Monitoring">
                                    <i class="bi bi-search"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-center py-5">
                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                    <i class="bi bi-clipboard-check fs-1"></i>
                                </div>
                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Data Monitoring</h6>
                                <p class="text-xs font-medium text-slate-400 mb-0">Belum ada data capaian monitoring yang perlu dievaluasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($monitorings->count() > 0)
    <div class="p-4 border-t border-slate-100">
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
            Menampilkan {{ $monitorings->count() }} data monitoring yang perlu dievaluasi
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.inline-eval').forEach(el => {
    el.addEventListener('change', function() {
        const m_id = this.dataset.mId;
        const field = this.dataset.field;
        const value = this.value;
        
        // Visual feedback
        this.style.opacity = '0.5';

        fetch("{{ route('evaluasi.update-inline') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ monitoring_id: m_id, field, value })
        })
        .then(response => response.json())
        .then(data => {
            this.style.opacity = '1';
            if (data.success) {
                this.classList.add('text-success');
                setTimeout(() => this.classList.remove('text-success'), 1000);
            } else {
                alert(data.message || 'Gagal menyimpan evaluasi.');
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
.inline-eval:focus {
    background-color: #fff !important;
    border: 1px solid #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
}
</style>
@endpush