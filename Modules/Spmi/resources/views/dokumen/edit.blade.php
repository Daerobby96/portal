@extends('layouts.app')

@section('title', 'Edit Dokumen')
@section('page-title', 'Edit Dokumen')
@section('page-subtitle', $dokumen->kode_dokumen . ' — ' . $dokumen->judul)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen Mutu</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">

        <form action="{{ route('dokumen.update', $dokumen) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
            
            {{-- Progress bar untuk upload --}}
            <div id="uploadProgress" class="d-none mb-4">
                <div class="progress rounded-full overflow-hidden" style="height: 20px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-to-r from-blue-600 to-indigo-600" 
                         role="progressbar" style="width: 0%">0%</div>
                </div>
                <small class="text-xs font-bold text-indigo-500 mt-1.5 d-block"><i class="bi bi-cpu animate-pulse me-1"></i>Mengupload berkas dokumen baru...</small>
            </div>

            <div class="row g-4">
                {{-- Info Kode --}}
                <div class="col-12">
                    <div class="alert alert-info border-0 rounded-2xl bg-blue-50 text-blue-700 text-xs font-semibold d-flex align-items-center gap-2 py-3 px-4">
                        <i class="bi bi-info-circle-fill fs-5 text-blue-500"></i>
                        <div>
                            <span>Kode Dokumen: <strong class="text-blue-900">{{ $dokumen->kode_dokumen }}</strong></span>
                            <span class="mx-2 text-slate-300">|</span>
                            <span>Dibuat oleh: <strong class="text-blue-900">{{ $dokumen->pembuat->name }}</strong></span>
                            <span class="mx-2 text-slate-300">|</span>
                            <span>Diunduh: <strong class="text-blue-900">{{ $dokumen->download_count }} kali</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Informasi Dokumen --}}
                <div class="col-12">
                    <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                        <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                            <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </div>
                            <h6 class="mb-0 font-bold text-slate-800">Edit Informasi Dokumen</h6>
                        </div>
                        <div class="p-4">
                            <div class="row g-4">

                                <div class="col-md-6">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kategori Dokumen <span class="text-danger">*</span></label>
                                    <select name="kategori_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kategori_id') is-invalid @enderror" required>
                                        @foreach($kategoris as $k)
                                            <option value="{{ $k->id }}"
                                                {{ (old('kategori_id', $dokumen->kategori_id) == $k->id) ? 'selected' : '' }}>
                                                [{{ $k->kode }}] {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Standar Mutu Terkait</label>
                                    <select name="standar_ids[]" id="standar_ids" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all @error('standar_ids') is-invalid @enderror" multiple placeholder="Pilih satu atau lebih standar...">
                                        @foreach($standars as $s)
                                            <option value="{{ $s->id }}" 
                                                {{ in_array($s->id, old('standar_ids', $dokumen->standars->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                [{{ $s->kode }}] {{ $s->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('standar_ids') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Judul Dokumen <span class="text-danger">*</span></label>
                                    <input type="text" name="judul"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('judul') is-invalid @enderror"
                                        value="{{ old('judul', $dokumen->judul) }}" required>
                                    @error('judul') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Unit Pemilik <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_pemilik"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('unit_pemilik') is-invalid @enderror"
                                        value="{{ old('unit_pemilik', $dokumen->unit_pemilik) }}" required>
                                    @error('unit_pemilik') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Versi <span class="text-danger">*</span></label>
                                    <input type="text" name="versi"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('versi') is-invalid @enderror"
                                        value="{{ old('versi', $dokumen->versi) }}" required>
                                    @error('versi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" required>
                                        @foreach(['draft','review','approved','obsolete'] as $s)
                                            <option value="{{ $s }}"
                                                {{ old('status', $dokumen->status) == $s ? 'selected' : '' }}>
                                                {{ ucfirst($s) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-check form-switch p-0 d-flex align-items-center gap-2">
                                        <input class="form-check-input ms-0" type="checkbox" name="is_public" id="is_public" value="1" style="width: 2.5em; height: 1.25em;" {{ old('is_public', $dokumen->is_public) ? 'checked' : '' }}>
                                        <div>
                                            <label class="text-xs font-bold text-slate-700" for="is_public">Akses Publik</label>
                                            <div class="text-[10px] font-medium text-slate-400">Dapat diakses tanpa memerlukan autentikasi login</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Terbit <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_terbit"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_terbit') is-invalid @enderror"
                                        value="{{ old('tanggal_terbit', $dokumen->tanggal_terbit->format('Y-m-d')) }}" required>
                                    @error('tanggal_terbit') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Kadaluarsa</label>
                                    <input type="date" name="tanggal_kadaluarsa"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                        value="{{ old('tanggal_kadaluarsa', $dokumen->tanggal_kadaluarsa?->format('Y-m-d')) }}">
                                </div>

                                <div class="col-12">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keterangan</label>
                                    <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                        placeholder="Deskripsi singkat...">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upload File --}}
                <div class="col-12">
                    <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                        <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                            <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                                <i class="bi bi-paperclip fs-5"></i>
                            </div>
                            <h6 class="mb-0 font-bold text-slate-800">File Dokumen</h6>
                        </div>
                        <div class="p-4">
                            @if($dokumen->file_path)
                            <div class="d-flex align-items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 mb-4">
                                <div class="d-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 shadow-inner">
                                    <i class="bi bi-file-earmark-check-fill fs-4"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Berkas Aktif Saat Ini</div>
                                    <div class="text-sm font-bold text-slate-800 mt-0.5">
                                        {{ strtoupper($dokumen->file_type) }} &nbsp;·&nbsp; {{ $dokumen->file_size_formatted }}
                                    </div>
                                </div>
                                <a href="{{ route('dokumen.download', $dokumen) }}" class="inline-flex items-center gap-1 rounded-xl bg-emerald-100 text-emerald-700 px-3 py-1.5 text-xs font-bold text-decoration-none">
                                    <i class="bi bi-download"></i>
                                    <span>Download</span>
                                </a>
                            </div>
                            <div class="alert alert-warning border-0 rounded-xl bg-amber-50 text-amber-800 text-xs font-semibold d-flex gap-2 mb-4">
                                <i class="bi bi-exclamation-triangle-fill fs-5 text-amber-500"></i>
                                <span>Unggah file baru di bawah ini hanya untuk menimpa file yang aktif saat ini. Kosongkan jika Anda tidak bermaksud mengubah dokumen fisik.</span>
                            </div>
                            @endif

                            <input type="file" name="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            <div class="text-[10px] font-medium text-slate-400 mt-1.5"><i class="bi bi-info-circle me-1"></i>Ekstensi yang didukung: PDF, Word, Excel, PowerPoint (Maksimal 20MB)</div>
                            @error('file') <div class="text-danger text-xs font-bold mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end pt-2">
                        <a href="{{ route('dokumen.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                            <i class="bi bi-arrow-left"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                            <i class="bi bi-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    /* Styling TomSelect to fit modern design tokens */
    .ts-wrapper.multi .ts-control > div {
        background-image: linear-gradient(to right, #3b82f6, #4f46e5) !important;
        color: #fff !important;
        border: 0 !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        padding: 3px 8px !important;
    }
    .ts-control {
        border-radius: 12px !important;
        padding: 10px 12px !important;
        background-color: rgba(248, 250, 252, 0.5) !important;
        border-color: #e2e8f0 !important;
    }
    .ts-control input {
        font-size: 14px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect("#standar_ids",{
        plugins: ['remove_button'],
        maxItems: null,
    });
</script>
<script>
    const fileInput = document.querySelector('input[name="file"]');
    const uploadForm = document.getElementById('uploadForm');
    const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
    const ALLOWED_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (!validateFile(file)) {
                this.value = '';
            }
        }
    });

    function validateFile(file) {
        if (file.size > MAX_FILE_SIZE) {
            alert('Ukuran file terlalu besar! Maksimal 20MB.\n\nUkuran file Anda: ' + formatSize(file.size));
            return false;
        }

        const ext = file.name.split('.').pop().toLowerCase();
        if (!ALLOWED_TYPES.includes(ext)) {
            alert('Tipe file tidak diizinkan!\n\nTipe yang diizinkan: PDF, Word, Excel, PowerPoint');
            return false;
        }

        return true;
    }

    function formatSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + units[i];
    }

    uploadForm.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (file && file.size > MAX_FILE_SIZE) {
            e.preventDefault();
            alert('Ukuran file terlalu besar! Maksimal 20MB.\n\nUkuran file Anda: ' + formatSize(file.size));
            return false;
        }
        
        if (file) {
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            progressDiv.classList.remove('d-none');
            
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                if (progress <= 90) {
                    progressBar.style.width = progress + '%';
                    progressBar.textContent = progress + '%';
                }
            }, 200);
            
            setTimeout(() => {
                clearInterval(interval);
            }, 10000);
        }
    });
</script>
@endpush