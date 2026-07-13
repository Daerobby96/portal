@extends('layouts.app')

@section('title', 'Tambah Temuan')
@section('page-title', 'Tambah Temuan Baru')
@section('page-subtitle', 'Audit: ' . $audit->nama_audit)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item"><a href="{{ route('audit.show', $audit) }}">{{ $audit->kode_audit }}</a></li>
    <li class="breadcrumb-item active">Tambah Temuan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Form Tambah Temuan Formal</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('audit.temuan.store', $audit) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($checklist))
                        <input type="hidden" name="audit_checklist_id" value="{{ $checklist->id }}">
                    @endif
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kategori Temuan <span class="text-danger">*</span></label>
                            <select name="kategori" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="KTS_Mayor" {{ (old('kategori') === 'KTS_Mayor' || (isset($checklist) && $checklist->status == 'tidak_sesuai')) ? 'selected' : '' }}>KTS Mayor (Ketidaksesuaian Berat)</option>
                                <option value="KTS_Minor" {{ old('kategori') === 'KTS_Minor' ? 'selected' : '' }}>KTS Minor (Ketidaksesuaian Ringan)</option>
                                <option value="OB" {{ (old('kategori') === 'OB' || (isset($checklist) && $checklist->status == 'observasi')) ? 'selected' : '' }}>Observasi (OB)</option>
                                <option value="Rekomendasi" {{ old('kategori') === 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi Peningkatan</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Klausul Standar / Acuan</label>
                            <input type="text" name="klausul_standar"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('klausul_standar') is-invalid @enderror"
                                value="{{ old('klausul_standar', $checklist->indikator->standar->kode ?? '') }}"
                                placeholder="Contoh: Kode Klausul 7.1.1">
                            @error('klausul_standar') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
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
                                placeholder="Jelaskan temuan secara detail dan objektif..." required>{{ old('uraian_temuan', $checklist->catatan ?? '') }}</textarea>
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
                                placeholder="Tuliskan bukti fisik, dokumen, atau saksi penunjang temuan...">{{ old('bukti_objektif', $checklist->bukti_objektif ?? '') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Lampiran Bukti Foto/Dokumen</label>
                            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-6 text-center hover:bg-slate-50 hover:border-primary/50 transition-colors position-relative">
                                <input type="file" name="file_bukti" class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer" id="file_bukti" 
                                    accept="image/*,application/pdf" capture="environment">
                                <div class="d-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-500 border border-blue-100/50 mb-3 mx-auto shadow-inner">
                                    <i class="bi bi-cloud-upload fs-3"></i>
                                </div>
                                <h6 class="text-sm font-bold text-slate-700 mb-1">Ambil Foto atau Pilih File</h6>
                                <p class="text-[10px] font-semibold text-slate-400 mb-0">Mendukung format gambar JPEG/PNG atau dokumen PDF (Maksimal 5MB)</p>
                            </div>
                            @error('file_bukti') <div class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Batas Tindak Lanjut</label>
                            <input type="date" name="batas_tindak_lanjut"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('batas_tindak_lanjut') is-invalid @enderror"
                                value="{{ old('batas_tindak_lanjut') }}">
                            @error('batas_tindak_lanjut') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                            <div class="text-[10px] font-medium text-slate-400 mt-1.5"><i class="bi bi-info-circle me-1"></i>Tanggal batas akhir bagi unit pelaksana untuk merampungkan tindakan koreksi.</div>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('audit.show', $audit) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Temuan</span>
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