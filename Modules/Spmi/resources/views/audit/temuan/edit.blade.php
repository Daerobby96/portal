@extends('layouts.app')

@section('title', 'Edit Temuan')
@section('page-title', 'Edit Temuan')
@section('page-subtitle', $temuan->kode_temuan)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item"><a href="{{ route('audit.show', $audit) }}">{{ $audit->kode_audit }}</a></li>
    <li class="breadcrumb-item active">Edit Temuan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-pencil-square fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Edit Temuan — <span class="font-mono">{{ $temuan->kode_temuan }}</span></h6>
            </div>
            <div class="p-4">
                <form action="{{ route('audit.temuan.update', [$audit, $temuan]) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kategori Temuan <span class="text-danger">*</span></label>
                            <select name="kategori" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kategori') is-invalid @enderror" required>
                                <option value="KTS_Mayor" {{ old('kategori', $temuan->kategori) === 'KTS_Mayor' ? 'selected' : '' }}>KTS Mayor (Ketidaksesuaian Berat)</option>
                                <option value="KTS_Minor" {{ old('kategori', $temuan->kategori) === 'KTS_Minor' ? 'selected' : '' }}>KTS Minor (Ketidaksesuaian Ringan)</option>
                                <option value="OB" {{ old('kategori', $temuan->kategori) === 'OB' ? 'selected' : '' }}>Observasi (OB)</option>
                                <option value="Rekomendasi" {{ old('kategori', $temuan->kategori) === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi Peningkatan</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Status Temuan <span class="text-danger">*</span></label>
                            <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('status') is-invalid @enderror" required>
                                <option value="open" {{ old('status', $temuan->status) === 'open' ? 'selected' : '' }}>🔴 Open</option>
                                <option value="in_progress" {{ old('status', $temuan->status) === 'in_progress' ? 'selected' : '' }}>🟡 In Progress</option>
                                <option value="closed" {{ old('status', $temuan->status) === 'closed' ? 'selected' : '' }}>🟢 Closed</option>
                                <option value="verified" {{ old('status', $temuan->status) === 'verified' ? 'selected' : '' }}>🔵 Verified</option>
                            </select>
                            @error('status') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Klausul Standar / Acuan</label>
                            <input type="text" name="klausul_standar"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('klausul_standar') is-invalid @enderror"
                                value="{{ old('klausul_standar', $temuan->klausul_standar) }}">
                            @error('klausul_standar') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Batas Tindak Lanjut</label>
                            <input type="date" name="batas_tindak_lanjut"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('batas_tindak_lanjut') is-invalid @enderror"
                                value="{{ old('batas_tindak_lanjut', $temuan->batas_tindak_lanjut?->format('Y-m-d')) }}">
                            @error('batas_tindak_lanjut') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0">Uraian Temuan <span class="text-danger">*</span></label>
                                <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-primary bg-primary-light px-2.5 py-1 text-xs font-bold text-primary transition-colors btn-voice" data-target="uraian_temuan" title="Catat dengan suara">
                                    <i class="bi bi-mic-fill animate-pulse"></i>
                                    <span>Suara</span>
                                </button>
                            </div>
                            <textarea name="uraian_temuan" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('uraian_temuan') is-invalid @enderror"
                                required>{{ old('uraian_temuan', $temuan->uraian_temuan) }}</textarea>
                            @error('uraian_temuan') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0">Bukti Objektif (Keterangan)</label>
                                <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-primary bg-primary-light px-2.5 py-1 text-xs font-bold text-primary transition-colors btn-voice" data-target="bukti_objektif" title="Catat dengan suara">
                                    <i class="bi bi-mic-fill animate-pulse"></i>
                                    <span>Suara</span>
                                </button>
                            </div>
                            <textarea name="bukti_objektif" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Bukti-bukti objektif pendukung...">{{ old('bukti_objektif', $temuan->bukti_objektif) }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('audit.temuan.show', [$audit, $temuan]) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Perbarui Temuan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        
        if (!SpeechRecognition) {
            document.querySelectorAll('.btn-voice').forEach(btn => btn.style.display = 'none');
            console.warn('Browser tidak mendukung Speech Recognition.');
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        let activeTarget = null;
        let activeBtn = null;

        document.querySelectorAll('.btn-voice').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetName = this.getAttribute('data-target');
                activeTarget = document.querySelector(`[name="${targetName}"]`);
                activeBtn = this;

                if (activeBtn.classList.contains('bg-rose-500')) {
                    recognition.stop();
                    return;
                }

                try {
                    recognition.start();
                } catch (e) {
                    recognition.stop();
                    setTimeout(() => recognition.start(), 100);
                }
            });
        });

        recognition.onstart = function() {
            if (activeBtn) {
                activeBtn.className = 'inline-flex items-center gap-1.5 rounded-lg bg-rose-500 text-white px-2.5 py-1 text-xs font-bold transition-colors btn-voice';
                activeBtn.innerHTML = '<i class="bi bi-record-fill animate-ping"></i><span>Mendengarkan...</span>';
            }
        };

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            if (activeTarget) {
                const startPos = activeTarget.selectionStart;
                const endPos = activeTarget.selectionEnd;
                const value = activeTarget.value;
                
                // Cek apakah textarea kosong atau butuh spasi
                const prefix = (value.length > 0 && startPos > 0 && value[startPos-1] !== ' ') ? ' ' : '';
                
                activeTarget.value = value.substring(0, startPos) + prefix + transcript + value.substring(endPos);
                
                // Geser kursor ke akhir teks yang baru dimasukkan
                const newPos = startPos + prefix.length + transcript.length;
                activeTarget.setSelectionRange(newPos, newPos);
                activeTarget.focus();
            }
        };

        recognition.onend = function() {
            if (activeBtn) {
                activeBtn.className = 'inline-flex items-center gap-1.5 rounded-lg border border-primary bg-primary-light px-2.5 py-1 text-xs font-bold text-primary transition-colors btn-voice';
                activeBtn.innerHTML = '<i class="bi bi-mic-fill"></i><span>Suara</span>';
            }
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            if (activeBtn) {
                activeBtn.className = 'inline-flex items-center gap-1.5 rounded-lg border border-primary bg-primary-light px-2.5 py-1 text-xs font-bold text-primary transition-colors btn-voice';
                activeBtn.innerHTML = '<i class="bi bi-mic-fill"></i><span>Suara</span>';
            }
            if (event.error === 'not-allowed') {
                alert('Izin mikrofon ditolak. Harap izinkan akses mikrofon di browser Anda.');
            }
        };
    });
</script>
@endpush