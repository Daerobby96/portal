@extends('layouts.app')

@section('title', 'Buat Evaluasi')
@section('page-title', 'Buat Evaluasi Monitoring')
@section('page-subtitle', 'Evaluasi data monitoring yang telah disubmit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evaluasi.index') }}">Evaluasi</a></li>
    <li class="breadcrumb-item active">Buat Evaluasi</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Form Evaluasi --}}
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-clipboard-check fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Form Evaluasi Kinerja</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('evaluasi.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Data Monitoring <span class="text-danger">*</span></label>
                            <select name="monitoring_id" id="monitoringSelect" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('monitoring_id') is-invalid @enderror" required>
                                <option value="">Pilih Data Monitoring</option>
                                @foreach($monitorings as $m)
                                    <option value="{{ $m->id }}" 
                                        data-target="{{ $m->indikator->target_nilai ?? 0 }}"
                                        data-capaian="{{ $m->nilai_capaian }}"
                                        data-unit="{{ $m->indikator->unit_pengukuran ?? '' }}"
                                        {{ $selected && $selected->id == $m->id ? 'selected' : '' }}>
                                        [{{ $m->indikator->kode }}] — {{ $m->indikator->nama }} 
                                        ({{ $m->tanggal_input->translatedFormat('d F Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('monitoring_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Evaluasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_evaluasi"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_evaluasi') is-invalid @enderror"
                                value="{{ old('tanggal_evaluasi', date('Y-m-d')) }}" required>
                            @error('tanggal_evaluasi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Hasil Evaluasi <span class="text-danger">*</span></label>
                            <select name="hasil" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('hasil') is-invalid @enderror" required>
                                <option value="">Pilih Hasil</option>
                                <option value="tercapai" {{ old('hasil') === 'tercapai' ? 'selected' : '' }}>🟢 Tercapai</option>
                                <option value="tidak_tercapai" {{ old('hasil') === 'tidak_tercapai' ? 'selected' : '' }}>🔴 Tidak Tercapai</option>
                                <option value="perlu_perhatian" {{ old('hasil') === 'perlu_perhatian' ? 'selected' : '' }}>🟡 Perlu Perhatian</option>
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
                                placeholder="Analisa pencapaian indikator..." required>{{ old('analisa') }}</textarea>
                            @error('analisa') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Rekomendasi</label>
                            <textarea name="rekomendasi" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Rekomendasi tindak lanjut (opsional)">{{ old('rekomendasi') }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('evaluasi.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Evaluasi</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Monitoring --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Detail Monitoring Terpilih</h6>
            </div>
            <div class="p-4" id="monitoringInfo">
                @if($selected)
                <div class="d-flex flex-column gap-3.5">
                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Indikator</span>
                        <span class="text-sm font-bold text-slate-700 mt-0.5 d-block">{{ $selected->indikator->nama ?? '-' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Unit Kerja Pelaksana</span>
                        <span class="text-sm font-semibold text-slate-600 mt-0.5 d-block">{{ $selected->indikator->unit_kerja ?? '-' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Target Indikator</span>
                        <span class="text-sm font-extrabold text-slate-700 mt-0.5 d-block">{{ $selected->indikator->target_nilai ?? '-' }} {{ $selected->indikator->unit_pengukuran ?? '' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Capaian Riil</span>
                        <span class="text-sm font-extrabold text-primary mt-0.5 d-block">{{ $selected->nilai_capaian }} {{ $selected->indikator->unit_pengukuran ?? '' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-1">Persentase Target</span>
                        @php $persen = $selected->persentase_capaian; @endphp
                        <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-extrabold">
                            {{ number_format($persen, 1) }}%
                        </span>
                    </div>

                    <div class="py-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Tanggal Input</span>
                        <span class="text-xs font-bold text-slate-500 mt-0.5 d-block">{{ $selected->tanggal_input->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
                @else
                <div class="py-5 text-center">
                    <div class="d-flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 text-slate-300 border border-slate-100 mb-3 mx-auto">
                        <i class="bi bi-arrow-left-right fs-4"></i>
                    </div>
                    <h6 class="text-xs font-bold text-slate-500 mb-0">Silakan Pilih Data Monitoring</h6>
                </div>
                @endif
            </div>
        </div>

        @if($monitorings->isEmpty())
        <div class="alert alert-warning border-0 rounded-2xl bg-amber-50 text-amber-800 text-xs font-semibold d-flex gap-2 mt-4">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-amber-500"></i>
            <span>Tidak ditemukan data monitoring yang siap dievaluasi. Harap pastikan berkas monitoring aktif berstatus "Submitted".</span>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('monitoringSelect')?.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const target = option.dataset.target || 0;
        const capaian = option.dataset.capaian || 0;
        const unit = option.dataset.unit || '';
        
        if (this.value) {
            const persen = target > 0 ? ((capaian / target) * 100).toFixed(1) : 0;
            const badgeClass = persen >= 100 ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : (persen >= 80 ? 'bg-amber-50 border-amber-100 text-amber-600' : 'bg-rose-50 border-rose-100 text-rose-600');
            
            document.getElementById('monitoringInfo').innerHTML = `
                <div class="d-flex flex-column gap-3.5">
                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Target Indikator</span>
                        <span class="text-sm font-extrabold text-slate-700 mt-0.5 d-block">${target} ${unit}</span>
                    </div>
                    <div class="py-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block">Capaian Riil</span>
                        <span class="text-sm font-extrabold text-primary mt-0.5 d-block">${capaian} ${unit}</span>
                    </div>
                    <div class="py-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-1">Persentase Target</span>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-extrabold ${badgeClass}">${persen}%</span>
                    </div>
                </div>
            `;
        }
    });

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