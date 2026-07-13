@extends('manajemen-surat::layouts.master')

@section('content')
@section('title', 'Buat Surat Keputusan')
@section('page-title', 'Generator Surat Keputusan')
@section('page-subtitle', 'Isi formulir untuk membuat dokumen SK dalam format PDF resmi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat-keputusan.index') }}">Generator SK</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<form action="{{ route('surat-keputusan.store') }}" method="POST" id="form-sk">
    @csrf
    <div class="row g-4">

        {{-- Kiri: Form Utama --}}
        <div class="col-lg-8">

            {{-- Identitas SK --}}
            <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <i class="bi bi-card-heading"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Identitas Surat Keputusan</h6>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Jenis SK <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <label class="sk-type-radio flex-1 {{ old('jenis_sk') == 'yayasan' ? 'active' : '' }}">
                                    <input type="radio" name="jenis_sk" value="yayasan" {{ old('jenis_sk') == 'yayasan' ? 'checked' : '' }} required>
                                    <div class="sk-type-card">
                                        <div class="icon"><i class="bi bi-building"></i></div>
                                        <div class="label">SK Yayasan</div>
                                    </div>
                                </label>
                                <label class="sk-type-radio flex-1 {{ old('jenis_sk') == 'pt' || !old('jenis_sk') ? 'active' : '' }}">
                                    <input type="radio" name="jenis_sk" value="pt" {{ old('jenis_sk', 'pt') == 'pt' ? 'checked' : '' }}>
                                    <div class="sk-type-card">
                                        <div class="icon"><i class="bi bi-mortarboard"></i></div>
                                        <div class="label">SK Perguruan Tinggi</div>
                                    </div>
                                </label>
                            </div>
                            @error('jenis_sk') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nomor SK <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_sk" id="nomor_sk"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-slate-700 transition-all focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-400/10 @error('nomor_sk') border-rose-400 bg-rose-50/30 @enderror"
                                value="{{ old('nomor_sk') }}"
                                placeholder="Contoh: 001/SK-YYS/VI/2025">
                            @error('nomor_sk') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tentang (Perihal SK) <span class="text-danger">*</span></label>
                            <textarea name="tentang" id="tentang" rows="2"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-400/10 @error('tentang') border-rose-400 bg-rose-50/30 @enderror"
                                placeholder="Contoh: Pengangkatan Ketua Lembaga Penjaminan Mutu Perguruan Tinggi">{{ old('tentang') }}</textarea>
                            @error('tentang') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Ditetapkan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_ditetapkan" id="tanggal_ditetapkan"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-slate-700 transition-all focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-400/10 @error('tanggal_ditetapkan') border-rose-400 bg-rose-50/30 @enderror"
                                value="{{ old('tanggal_ditetapkan', date('Y-m-d')) }}">
                            @error('tanggal_ditetapkan') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Isi SK (Jodit Editor) --}}
            <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <i class="bi bi-file-earmark-richtext"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Isi Surat Keputusan</h6>
                </div>
                <div class="p-4">
                    <textarea name="isi_sk" id="isi_sk_editor" class="d-none">{{ old('isi_sk', '<p><strong>Menimbang:</strong></p><ol><li>bahwa ...;</li><li>bahwa ...;</li></ol><p><br></p><p><strong>Mengingat:</strong></p><ol><li>Undang-Undang Nomor ...;</li><li>Peraturan Pemerintah Nomor ...;</li></ol><p><br></p><p style="text-align: center;"><strong>MEMUTUSKAN</strong></p><p><strong>Menetapkan:</strong></p><p><strong>Pasal 1</strong></p><p>...</p><p><strong>Pasal 2</strong></p><p>...</p>') }}</textarea>
                    @error('isi_sk') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Kanan: Penandatangan & Aksi --}}
        <div class="col-lg-4">

            {{-- Penandatangan --}}
            <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                        <i class="bi bi-pen-fill"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Penandatangan</h6>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nama Penandatangan <span class="text-danger">*</span></label>
                        <input type="text" name="penandatangan_nama" id="penandatangan_nama"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-slate-700 transition-all focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-400/10 @error('penandatangan_nama') border-rose-400 @enderror"
                            value="{{ old('penandatangan_nama') }}"
                            placeholder="Dr. Ahmad Fauzi, M.Pd.">
                        @error('penandatangan_nama') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Jabatan Penandatangan <span class="text-danger">*</span></label>
                        <input type="text" name="penandatangan_jabatan" id="penandatangan_jabatan"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-slate-700 transition-all focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-400/10 @error('penandatangan_jabatan') border-rose-400 @enderror"
                            value="{{ old('penandatangan_jabatan') }}"
                            placeholder="Ketua Yayasan / Rektor">
                        @error('penandatangan_jabatan') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Info Kop --}}
            <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
                <div class="p-4 bg-amber-50/50 border-b border-amber-100 d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600 flex-shrink-0">
                        <i class="bi bi-file-earmark-image-fill"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-700">Kop Surat</div>
                        <div class="text-xs text-slate-400">Diambil dari Pengaturan Aplikasi</div>
                    </div>
                </div>
                <div class="p-4">
                    @php
                        $kopYayasan = \App\Models\Setting::get('kop_surat_yayasan');
                        $kopPt      = \App\Models\Setting::get('kop_surat_pt');
                    @endphp

                    <div class="mb-3">
                        <div class="text-xs font-bold text-purple-600 mb-1.5 d-flex align-items-center gap-1">
                            <i class="bi bi-building"></i> Kop SK Yayasan
                        </div>
                        @if($kopYayasan)
                            <div class="rounded-xl border border-purple-100 overflow-hidden bg-white">
                                <img src="{{ asset('storage/' . $kopYayasan) }}" alt="Kop Yayasan" class="w-full" style="max-height:60px; object-fit:contain; object-position:left; padding:4px;">
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-3 text-center">
                                <i class="bi bi-image text-slate-300 d-block fs-5 mb-1"></i>
                                <div class="text-[10px] text-slate-400">Belum diupload</div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <div class="text-xs font-bold text-emerald-600 mb-1.5 d-flex align-items-center gap-1">
                            <i class="bi bi-mortarboard"></i> Kop SK Perguruan Tinggi
                        </div>
                        @if($kopPt)
                            <div class="rounded-xl border border-emerald-100 overflow-hidden bg-white">
                                <img src="{{ asset('storage/' . $kopPt) }}" alt="Kop PT" class="w-full" style="max-height:60px; object-fit:contain; object-position:left; padding:4px;">
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-3 text-center">
                                <i class="bi bi-image text-slate-300 d-block fs-5 mb-1"></i>
                                <div class="text-[10px] text-slate-400">Belum diupload</div>
                            </div>
                        @endif
                    </div>

                    @if(!$kopYayasan || !$kopPt)
                        <div class="rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-700 d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-amber-500 mt-0.5 flex-shrink-0"></i>
                            <div>
                                Ada kop surat yang belum diupload. SK akan menggunakan <strong>kop teks fallback</strong>.
                                <a href="{{ route('settings.index') }}" class="text-amber-700 font-bold underline ms-1">Upload sekarang →</a>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-2 text-xs text-emerald-700 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-emerald-500"></i>
                            Kop surat sudah siap digunakan
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="p-4">
                    <button type="button" id="btn-preview" onclick="previewPdf()"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-600 transition-all hover:bg-blue-100 mb-2">
                        <i class="bi bi-eye-fill"></i>
                        <span>Live Preview PDF</span>
                    </button>
                    <button type="submit" id="btn-generate"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition-all hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-md hover:shadow-blue-600/20 active:translate-y-0 mb-3">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Generate & Simpan PDF</span>
                    </button>
                    <a href="{{ route('surat-keputusan.index') }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-500 transition-all hover:bg-slate-100">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.css"/>
<style>
/* Simulate A4 PDF appearance inside Jodit editor */
.jodit-wysiwyg {
    padding: 1.2cm 1.2cm 1.2cm 1.6cm !important; /* proportional to @page 3cm 3cm 3cm 4cm */
    font-family: 'Georgia', 'Times New Roman', serif !important;
    font-size: 11.5px !important;
    line-height: 1.6 !important;
    color: #1a1a1a !important;
    text-align: justify !important;
    background: #fff !important;
    min-height: 460px !important;
}
.jodit-wysiwyg p { margin-bottom: 8px !important; }
.jodit-wysiwyg ol,
.jodit-wysiwyg ul { margin-left: 20px !important; margin-bottom: 8px !important; }
.jodit-wysiwyg li { margin-bottom: 4px !important; }
.jodit-wysiwyg strong { font-weight: bold !important; }
.jodit-wysiwyg table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
.jodit-wysiwyg td, .jodit-wysiwyg th { border: 1px solid #888; padding: 4px 6px; }
</style>
<style>
.sk-type-radio {
    cursor: pointer;
}
.sk-type-radio input[type="radio"] {
    display: none;
}
.sk-type-card {
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 12px;
    padding: 14px 12px;
    text-align: center;
    transition: all 0.2s ease;
}
.sk-type-card .icon {
    font-size: 22px;
    color: #94a3b8;
    margin-bottom: 4px;
    transition: color 0.2s;
}
.sk-type-card .label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    transition: color 0.2s;
}
.sk-type-radio:has(input:checked) .sk-type-card,
.sk-type-radio.active .sk-type-card {
    border-color: #3b82f6;
    background: #eff6ff;
}
.sk-type-radio:has(input:checked) .sk-type-card .icon,
.sk-type-radio.active .sk-type-card .icon {
    color: #3b82f6;
}
.sk-type-radio:has(input:checked) .sk-type-card .label,
.sk-type-radio.active .sk-type-card .label {
    color: #1d4ed8;
}
.sk-textarea {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
    padding: 10px 14px;
    font-size: 13px;
    color: #334155;
    resize: vertical;
    transition: all 0.2s;
    font-family: inherit;
    line-height: 1.5;
}
.sk-textarea:focus {
    border-color: #3b82f6;
    background: #fff;
    outline: none;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
}
.row-label {
    min-width: 30px;
    padding-top: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}
.row-label.pasal {
    min-width: 58px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #10b981;
}
.btn-remove-row {
    background: none;
    border: 1.5px solid #fca5a5;
    border-radius: 8px;
    color: #f43f5e;
    width: 32px;
    height: 32px;
    min-width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    margin-top: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-remove-row:hover {
    background: #f43f5e;
    color: white;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = new Jodit('#isi_sk_editor', {
        height: 500,
        toolbarSticky: false,
        toolbarAdaptive: false, // prevent toolbar from collapsing on small screens
        enter: 'p',
        language: 'id',
        // Use default full toolbar – all features unlocked
        buttons: Jodit.defaultOptions.buttons,
        buttonsMD: Jodit.defaultOptions.buttons,
        buttonsSM: Jodit.defaultOptions.buttons,
        buttonsXS: Jodit.defaultOptions.buttons,
        controls: {
            ol: {
                list: {
                    '': 'Default (1, 2, 3)',
                    'lower-alpha': 'Lower Alpha (a, b, c)',
                    'upper-alpha': 'Upper Alpha (A, B, C)',
                    'lower-roman': 'Lower Roman (i, ii, iii)',
                    'upper-roman': 'Upper Roman (I, II, III)',
                },
            },
            ul: {
                list: {
                    '': 'Disc (•)',
                    'circle': 'Circle (○)',
                    'square': 'Square (▪)',
                },
            },
        },
    });
});

// Radio card toggle
document.querySelectorAll('.sk-type-radio').forEach(label => {
    label.addEventListener('click', function() {
        document.querySelectorAll('.sk-type-radio').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});

// Submit loading state
document.getElementById('form-sk').addEventListener('submit', function(e) {
    // Only show loading state if it's the main save submission, not preview
    if (this.action.includes('preview')) return;
    
    const btn = document.getElementById('btn-generate');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Generating PDF...</span>';
    btn.disabled = true;
});

function previewPdf() {
    const form = document.getElementById('form-sk');
    const originalAction = form.action;
    const originalTarget = form.target || '';
    
    form.action = "{{ route('surat-keputusan.preview') }}";
    form.target = "_blank"; // Open preview in new tab
    form.submit();
    
    // Restore form back to normal save behavior
    setTimeout(() => {
        form.action = originalAction;
        form.target = originalTarget;
    }, 100);
}
</script>
@endpush

