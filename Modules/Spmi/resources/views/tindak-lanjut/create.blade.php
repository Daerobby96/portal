@extends('layouts.app')

@section('title', 'Buat Tindak Lanjut')
@section('page-title', 'Buat Tindak Lanjut')
@section('page-subtitle', 'Temuan: ' . $temuan->kode_temuan)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tindak-lanjut.index') }}">Tindak Lanjut</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Temuan --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Informasi Temuan</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Temuan</span>
                        <span class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-0.5 text-xs font-mono font-bold text-blue-600 border border-blue-100">
                            {{ $temuan->kode_temuan }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</span>
                        <span class="modern-badge-container">{!! $temuan->kategori_badge !!}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Audit</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $temuan->audit->kode_audit ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Kerja</span>
                        <span class="text-sm font-semibold text-slate-700 text-end">{{ $temuan->audit->unit_yang_diaudit ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Batas Tindak Lanjut</span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg py-1 px-2 text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                            <i class="bi bi-calendar-event"></i>
                            {{ $temuan->batas_tindak_lanjut?->translatedFormat('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-xl border border-blue-100/70 bg-gradient-to-br from-blue-50/40 to-indigo-50/20 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 h-16 w-16 rounded-full bg-blue-500/5 blur-lg"></div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-500 d-block mb-1">Uraian Temuan</span>
                    <p class="mb-0 text-slate-700 text-sm leading-relaxed font-medium">{{ $temuan->uraian_temuan }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Tindak Lanjut --}}
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-arrow-repeat fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Form Tindak Lanjut</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('tindak-lanjut.store') }}" method="POST" enctype="multipart/form-data" class="m-0">
                    @csrf
                    <input type="hidden" name="temuan_id" value="{{ $temuan->id }}">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Penanggung Jawab <span class="text-danger">*</span></label>
                            <select name="penanggung_jawab_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('penanggung_jawab_id') is-invalid @enderror" required>
                                <option value="">Pilih Penanggung Jawab</option>
                                @foreach($petugas as $p)
                                    <option value="{{ $p->id }}" {{ old('penanggung_jawab_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->unit_kerja }})
                                    </option>
                                @endforeach
                            </select>
                            @error('penanggung_jawab_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Target Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="target_selesai"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('target_selesai') is-invalid @enderror"
                                value="{{ old('target_selesai') }}" required>
                            @error('target_selesai') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Analisa Penyebab <span class="text-danger">*</span></label>
                                <button type="button" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-violet-600 via-indigo-600 to-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-gradient-to-r hover:from-violet-700 hover:via-indigo-700 hover:to-blue-700 hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/20 active:translate-y-0 ai-btn" 
                                    onclick="analyzeAI('root-cause', 'analisa_penyebab')">
                                    <i class="bi bi-cpu text-xs animate-pulse"></i>
                                    <span>Analisa Penyebab AI</span>
                                </button>
                            </div>
                            <textarea name="analisa_penyebab" id="analisa_penyebab" rows="4" 
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('analisa_penyebab') is-invalid @enderror"
                                placeholder="Tuliskan analisa penyebab terjadinya temuan, atau klik tombol AI di atas untuk rekomendasi cerdas..." required>{{ old('analisa_penyebab') }}</textarea>
                            @error('analisa_penyebab') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Rencana Tindakan <span class="text-danger">*</span></label>
                                <button type="button" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-violet-600 via-indigo-600 to-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-gradient-to-r hover:from-violet-700 hover:via-indigo-700 hover:to-blue-700 hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/20 active:translate-y-0 ai-btn" 
                                    onclick="analyzeAI('recommendation', 'rencana_tindakan')">
                                    <i class="bi bi-cpu text-xs animate-pulse"></i>
                                    <span>Sugesti Tindakan AI</span>
                                </button>
                            </div>
                            <textarea name="rencana_tindakan" id="rencana_tindakan" rows="4" 
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('rencana_tindakan') is-invalid @enderror"
                                placeholder="Tuliskan rencana tindakan penyelesaian, atau gunakan bantuan asisten AI di atas..." required>{{ old('rencana_tindakan') }}</textarea>
                            @error('rencana_tindakan') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Bukti Tindakan</label>
                            <div class="p-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/30 d-flex flex-column gap-2">
                                <input type="file" name="bukti_tindakan"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('bukti_tindakan') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.jpg,.png">
                                <div class="text-[10px] font-medium text-slate-400"><i class="bi bi-info-circle me-1"></i>Format: PDF, DOC, DOCX, JPG, PNG (Maksimal: 10MB)</div>
                            </div>
                            @error('bukti_tindakan') <div class="invalid-feedback mt-1 text-xs d-block">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('tindak-lanjut.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0">
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Tindak Lanjut</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Override bootstrap badge di dalam kategori_badge agar serasi dengan konsep pastel modern */
    .modern-badge-container .badge {
        font-weight: 700 !important;
        font-size: 0.72rem !important;
        padding: 4px 10px !important;
        border-radius: 9999px !important;
        box-shadow: none !important;
        letter-spacing: 0.01em !important;
    }
    .modern-badge-container .badge.bg-danger {
        background-color: #fef2f2 !important;
        color: #ef4444 !important;
        border: 1px solid #fee2e2 !important;
    }
    .modern-badge-container .badge.bg-warning {
        background-color: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fef3c7 !important;
    }
    .modern-badge-container .badge.bg-info {
        background-color: #f0f9ff !important;
        color: #0284c7 !important;
        border: 1px solid #e0f2fe !important;
    }
    .modern-badge-container .badge.bg-success {
        background-color: #f0fdf4 !important;
        color: #16a34a !important;
        border: 1px solid #dcfce7 !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Fungsi Typewriter untuk efek ketikan cerdas AI
function runTypewriter(element, text, speed = 8) {
    element.value = '';
    let i = 0;
    element.disabled = true;
    
    function type() {
        if (i < text.length) {
            element.value += text.charAt(i);
            i++;
            // Auto-expand textarea
            element.style.height = 'auto';
            element.style.height = element.scrollHeight + 'px';
            setTimeout(type, speed);
        } else {
            element.disabled = false;
        }
    }
    type();
}

async function analyzeAI(type, targetId) {
    const btn = event.currentTarget;
    const target = document.getElementById(targetId);
    const uraianTemuan = "{{ $temuan->uraian_temuan }}";
    
    // UI Feedback
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5 text-white" style="width: 12px; height: 12px;"></span>Berpikir...';
    btn.disabled = true;
    
    try {
        const endpoint = type === 'root-cause' ? '{{ route("ai.analyze-root-cause") }}' : '{{ route("ai.suggest-recommendation") }}';
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ text: uraianTemuan })
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            // Jalankan efek ketikan karakter demi karakter demi visual elite!
            runTypewriter(target, result.data);
        } else {
            alert(result.message || 'Gagal memproses permintaan AI');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan koneksi saat berkomunikasi dengan AI.');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}
</script>
@endpush