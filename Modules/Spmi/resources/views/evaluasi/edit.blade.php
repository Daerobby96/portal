@extends('layouts.app')

@section('title', 'Edit Evaluasi')
@section('page-title', 'Edit Evaluasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evaluasi.index') }}">Evaluasi</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@push('scripts')
<script>
async function summarizeAI() {
    const btn = event.currentTarget;
    const target = document.querySelector('textarea[name="analisa"]');
    const text = target.value;
    
    if (!text || text.length < 20) {
        alert('Silakan tulis narasi analisa terlebih dahulu (min 20 karakter) sebelum diringkas.');
        return;
    }
    
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width: 12px; height: 12px;"></span>Meringkas...';
    btn.disabled = true;
    
    try {
        const response = await fetch('{{ route("ai.summarize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ text: text })
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            target.value = result.data;
        } else {
            alert(result.message || 'Gagal meringkas teks');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan koneksi');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}
</script>
@endpush

@section('content')
<div class="row g-4">
    {{-- Info Monitoring --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Detail Monitoring</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3.5">
                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Indikator</span>
                        <span class="text-sm font-bold text-slate-700 mt-0.5 d-block">{{ $evaluasi->monitoring->indikator->nama ?? '-' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Unit Kerja Pelaksana</span>
                        <span class="text-sm font-semibold text-slate-600 mt-0.5 d-block">{{ $evaluasi->monitoring->indikator->unit_kerja ?? '-' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Target Indikator</span>
                        <span class="text-sm font-extrabold text-slate-700 mt-0.5 d-block">{{ $evaluasi->monitoring->indikator->target_nilai ?? '-' }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Capaian Riil</span>
                        <span class="text-sm font-extrabold text-primary mt-0.5 d-block">{{ $evaluasi->monitoring->nilai_capaian }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}</span>
                    </div>

                    <div class="py-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-1">Persentase Target</span>
                        @php $persen = $evaluasi->monitoring->persentase_capaian; @endphp
                        <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-extrabold">
                            {{ number_format($persen, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Edit --}}
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-clipboard-check fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Edit Evaluasi Kinerja</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('evaluasi.update', $evaluasi) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Evaluasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_evaluasi"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_evaluasi') is-invalid @enderror"
                                value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi->format('Y-m-d')) }}" required>
                            @error('tanggal_evaluasi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Hasil Evaluasi <span class="text-danger">*</span></label>
                            <select name="hasil" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('hasil') is-invalid @enderror" required>
                                <option value="tercapai" {{ old('hasil', $evaluasi->hasil) === 'tercapai' ? 'selected' : '' }}>🟢 Tercapai</option>
                                <option value="tidak_tercapai" {{ old('hasil', $evaluasi->hasil) === 'tidak_tercapai' ? 'selected' : '' }}>🔴 Tidak Tercapai</option>
                                <option value="perlu_perhatian" {{ old('hasil', $evaluasi->hasil) === 'perlu_perhatian' ? 'selected' : '' }}>🟡 Perlu Perhatian</option>
                            </select>
                            @error('hasil') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0">Analisa <span class="text-danger">*</span></label>
                                <button type="button" class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-2.5 py-1 text-xs font-extrabold hover:shadow-sm" onclick="summarizeAI()">
                                    <i class="bi bi-robot animate-pulse"></i>
                                    <span>Ringkas AI</span>
                                </button>
                            </div>
                            <textarea name="analisa" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('analisa') is-invalid @enderror"
                                required>{{ old('analisa', $evaluasi->analisa) }}</textarea>
                            @error('analisa') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Rekomendasi</label>
                            <textarea name="rekomendasi" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Rekomendasi tindak lanjut (opsional)">{{ old('rekomendasi', $evaluasi->rekomendasi) }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('evaluasi.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Perbarui Evaluasi</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection