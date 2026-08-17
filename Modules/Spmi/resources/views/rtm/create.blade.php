@extends('layouts.app')

@section('title', 'Buat RTM Baru')
@section('page-title', 'Buat RTM Baru')
@section('page-subtitle', 'Input agenda dan tinjauan manajemen sesuai standar SPMI')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rtm.index') }}">RTM</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<form action="{{ route('rtm.store') }}" method="POST" enctype="multipart/form-data" id="rtm-form">
    @csrf
    <div class="row g-4">
        {{-- Sisi Kiri: Info Umum --}}
        <div class="col-lg-4">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] sticky-top" style="top: 80px;">
                <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Header Rapat</h6>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Judul Rapat <span class="text-danger">*</span></label>
                        <input type="text" name="judul_rapat" id="judul_rapat" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('judul_rapat') is-invalid @enderror" 
                               placeholder="Misal: RTM Semester Genap 2024" required value="{{ old('judul_rapat') }}">
                        @error('judul_rapat') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Rapat <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_rapat" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_rapat') is-invalid @enderror" 
                               required value="{{ old('tanggal_rapat', date('Y-m-d')) }}">
                        @error('tanggal_rapat') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">File Absensi (PDF/JPG/PNG)</label>
                        <div class="p-3 rounded-xl border border-dashed border-slate-200 bg-slate-50/30 d-flex flex-column gap-2">
                            <input type="file" name="file_absensi" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('file_absensi') is-invalid @enderror">
                            <div class="text-[10px] font-medium text-slate-400"><i class="bi bi-info-circle me-1"></i>Format: PDF, JPG, PNG (Maksimal: 5MB)</div>
                        </div>
                        @error('file_absensi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                    </div>
                    <hr class="border-slate-100 my-4">
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                            <i class="bi bi-save"></i>
                            <span>Simpan Agenda RTM</span>
                        </button>
                        <a href="{{ route('rtm.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0">
                            <span>Batal</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Input Tinjauan --}}
        <div class="col-lg-8">
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                        <i class="bi bi-journal-text fs-5"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Agenda & Input Tinjauan (Standar ISO/SPMI)</h6>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Agenda Utama</label>
                        <textarea name="agenda" id="agenda" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Garis besar agenda yang dibahas...">{{ old('agenda') }}</textarea>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6 text-section">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Hasil Audit Internal</label>
                                <button type="button" class="inline-flex items-center gap-1 rounded-full bg-blue-50 text-blue-600 border border-blue-200 px-2.5 py-1 text-[11px] font-bold hover:bg-blue-100 transition-all" onclick="pullAuditData()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    <span>Tarik Data AMI Otomatis</span>
                                </button>
                            </div>
                            <textarea name="input_audit_internal" id="input_audit_internal" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Ringkasan temuan dan efektivitas audit...">{{ old('input_audit_internal') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">2. Umpan Balik Pelanggan</label>
                            <textarea name="input_umpan_balik" id="input_umpan_balik" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Keluhan pelanggan, hasil survei kepuasan...">{{ old('input_umpan_balik') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">3. Kinerja Proses</label>
                            <textarea name="input_kinerja_proses" id="input_kinerja_proses" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Capaian IKU/IKT, kesesuaian layanan...">{{ old('input_kinerja_proses') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">4. Status Tindakan Perbaikan</label>
                            <textarea name="input_status_tindakan" id="input_status_tindakan" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Status tindak lanjut dari RTM sebelumnya...">{{ old('input_status_tindakan') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">5. Perubahan Sistem Pengelolaan</label>
                            <textarea name="input_perubahan_sistem" id="input_perubahan_sistem" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Perubahan internal/eksternal yang berdampak pada mutu...">{{ old('input_perubahan_sistem') }}</textarea>
                        </div>
                        <div class="col-md-6 text-section">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">6. Rekomendasi Peningkatan</label>
                            <textarea name="input_rekomendasi" id="input_rekomendasi" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Saran-saran untuk perbaikan berkelanjutan...">{{ old('input_rekomendasi') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan Bawah: Output & Keputusan RTM --}}
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="p-4 bg-emerald-50/70 border-b border-emerald-100/70 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </div>
                        <h6 class="mb-0 font-bold text-slate-800">Output & Keputusan RTM</h6>
                    </div>
                    
                    {{-- GORGEOUS AI GENERATOR BUTTON --}}
                    <button type="button" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-violet-600 via-indigo-600 to-blue-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-gradient-to-r hover:from-violet-700 hover:via-indigo-700 hover:to-blue-700 hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/20 active:translate-y-0" id="btn-generate-rtm-ai" onclick="generateRtmDraftAI()">
                        <i class="bi bi-cpu text-xs animate-pulse"></i>
                        <span>AI Draft Generator</span>
                    </button>
                </div>
                <div class="p-4 bg-gradient-to-b from-white to-slate-50/20">
                    <div class="mb-4 text-section">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Notulensi Rapat (Umum)</label>
                        <textarea name="notulensi" id="notulensi" rows="5" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Catatan jalannya rapat...">{{ old('notulensi') }}</textarea>
                    </div>

                    <div class="mb-4 text-section">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keputusan Terkait Keefektifan SPMI</label>
                        <textarea name="output_keefektifan" id="output_keefektifan" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Keputusan untuk meningkatkan efektivitas sistem...">{{ old('output_keefektifan') }}</textarea>
                    </div>

                    <div class="mb-4 text-section">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keputusan Perbaikan Produk/Layanan</label>
                        <textarea name="output_perbaikan" id="output_perbaikan" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Rencana perbaikan layanan kepada stakeholder...">{{ old('output_perbaikan') }}</textarea>
                    </div>

                    <div class="mb-4 text-section">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kebutuhan Sumber Daya</label>
                        <textarea name="output_sumber_daya" id="output_sumber_daya" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Alokasi SDM, anggaran, atau sarana prasarana baru...">{{ old('output_sumber_daya') }}</textarea>
                    </div>

                    <div class="mb-3 text-section">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kesimpulan / Keputusan Manajemen Lainnya</label>
                        <textarea name="keputusan_manajemen" id="keputusan_manajemen" rows="4" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10" placeholder="Resume keputusan strategis lainnya...">{{ old('keputusan_manajemen') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Fungsi Typewriter Kustom yang mengetik di MULTIPLE TEXTAREA SECARA CONCURRENT
function runMultiTypewriter(inputs) {
    inputs.forEach(item => {
        item.element.value = '';
        item.element.disabled = true;
    });

    let maxLen = Math.max(...inputs.map(item => item.text.length));
    let i = 0;
    let speed = 4; // Typing speed slightly faster since typing 5 items simultaneously

    function type() {
        let done = true;
        inputs.forEach(item => {
            if (i < item.text.length) {
                item.element.value += item.text.charAt(i);
                // auto-expand height
                item.element.style.height = 'auto';
                item.element.style.height = item.element.scrollHeight + 'px';
                done = false;
            }
        });

        if (!done) {
            i++;
            setTimeout(type, speed);
        } else {
            inputs.forEach(item => {
                item.element.disabled = false;
            });
        }
    }
    type();
}

async function generateRtmDraftAI() {
    const btn = document.getElementById('btn-generate-rtm-ai');
    
    // UI Feedback
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5 text-white" style="width: 12px; height: 12px;"></span>Menganalisis Input...';
    btn.disabled = true;
    
    // Kumpulkan data input
    const payload = {
        judul_rapat: document.getElementById('judul_rapat').value,
        agenda: document.getElementById('agenda').value,
        input_audit_internal: document.getElementById('input_audit_internal').value,
        input_umpan_balik: document.getElementById('input_umpan_balik').value,
        input_kinerja_proses: document.getElementById('input_kinerja_proses').value,
        input_status_tindakan: document.getElementById('input_status_tindakan').value,
        input_perubahan_sistem: document.getElementById('input_perubahan_sistem').value,
        input_rekomendasi: document.getElementById('input_rekomendasi').value
    };
    
    // Validasi minimal Judul Rapat terisi
    if (!payload.judul_rapat) {
        alert('Silakan isi Judul Rapat terlebih dahulu!');
        btn.innerHTML = originalHtml;
        btn.disabled = false;
        return;
    }
    
    try {
        const response = await fetch('{{ route("ai.rtm-draft") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            const data = result.data;
            
            // Map textareas untuk typewriter
            const itemsToType = [
                { element: document.getElementById('notulensi'), text: data.notulensi || '' },
                { element: document.getElementById('output_keefektifan'), text: data.output_keefektifan || '' },
                { element: document.getElementById('output_perbaikan'), text: data.output_perbaikan || '' },
                { element: document.getElementById('output_sumber_daya'), text: data.output_sumber_daya || '' },
                { element: document.getElementById('keputusan_manajemen'), text: data.keputusan_manajemen || '' }
            ];
            
            // Jalankan ketikan magis simultan!
            runMultiTypewriter(itemsToType);
            
        } else {
            alert(result.message || 'Gagal memformulasikan draf keputusan RTM.');
        }
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan koneksi saat merumuskan keputusan RTM.');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}

async function pullAuditData() {
    const btn = event.currentTarget;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width: 10px; height: 10px;"></span>Menarik...';
    btn.disabled = true;

    try {
        const response = await fetch('{{ route("rtm.compile-audit-data") }}');
        const result = await response.json();
        if (result.success && result.data) {
            document.getElementById('input_audit_internal').value = result.data;
        } else {
            alert('Tidak ditemukan data temuan audit internal pada periode ini.');
        }
    } catch(e) {
        console.error(e);
        alert('Gagal mengambil data audit internal.');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}
</script>
@endpush
