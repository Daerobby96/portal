@extends('layouts.app')

@section('title', 'Tambah Dokumen')
@section('page-title', 'Tambah Dokumen Baru')
@section('page-subtitle', 'Isi form berikut untuk menambahkan dokumen mutu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen Mutu</a></li>
    <li class="breadcrumb-item active">Tambah Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">

        <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
            
            {{-- Progress bar untuk upload --}}
            <div id="uploadProgress" class="d-none mb-4">
                <div class="progress rounded-full overflow-hidden" style="height: 20px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-to-r from-blue-600 to-indigo-600" 
                         role="progressbar" style="width: 0%">0%</div>
                </div>
                <small class="text-xs font-bold text-indigo-500 mt-1.5 d-block"><i class="bi bi-cpu animate-pulse me-1"></i>Mengupload berkas dokumen...</small>
            </div>

            <div class="row g-4">

                {{-- Informasi Dokumen --}}
                <div class="col-12">
                    <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                        <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                            <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                                <i class="bi bi-info-circle-fill fs-5"></i>
                            </div>
                            <h6 class="mb-0 font-bold text-slate-800">Informasi Dokumen</h6>
                        </div>
                        <div class="p-4">
                            <div class="row g-4">

                                <div class="col-md-6">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kategori Dokumen <span class="text-danger">*</span></label>
                                    <select name="kategori_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kategori_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoris as $k)
                                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
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
                                            <option value="{{ $s->id }}" {{ (is_array(old('standar_ids')) && in_array($s->id, old('standar_ids'))) || request('standar_id') == $s->id ? 'selected' : '' }}>
                                                [{{ $s->kode }}] {{ $s->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('standar_ids') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                    <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Anda dapat memetakan dokumen ke beberapa standar sekaligus</div>
                                </div>

                                <div class="col-12">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Judul Dokumen <span class="text-danger">*</span></label>
                                    <input type="text" name="judul"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('judul') is-invalid @enderror"
                                        value="{{ old('judul') }}"
                                        placeholder="Contoh: SOP Penerimaan Mahasiswa Baru"
                                        required>
                                    @error('judul') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Unit Pemilik <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_pemilik"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('unit_pemilik') is-invalid @enderror"
                                        value="{{ old('unit_pemilik') }}"
                                        placeholder="Contoh: Prodi Teknik Informatika"
                                        required>
                                    @error('unit_pemilik') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                    <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Digunakan untuk menyusun kode dokumen secara otomatis</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Versi <span class="text-danger">*</span></label>
                                    <input type="text" name="versi"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('versi') is-invalid @enderror"
                                        value="{{ old('versi', '1.0') }}"
                                        placeholder="1.0"
                                        required>
                                    @error('versi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('status') is-invalid @enderror" required>
                                        <option value="draft"    {{ old('status','draft') == 'draft'    ? 'selected' : '' }}>Draft</option>
                                        <option value="review"   {{ old('status') == 'review'   ? 'selected' : '' }}>Review</option>
                                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="obsolete" {{ old('status') == 'obsolete' ? 'selected' : '' }}>Obsolete</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4 d-flex align-items-center">
                                    <div class="form-check form-switch p-0 d-flex align-items-center gap-2">
                                        <input class="form-check-input ms-0" type="checkbox" name="is_public" id="is_public" value="1" style="width: 2.5em; height: 1.25em;" {{ old('is_public') ? 'checked' : '' }}>
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
                                        value="{{ old('tanggal_terbit', now()->format('Y-m-d')) }}"
                                        required>
                                    @error('tanggal_terbit') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Kadaluarsa</label>
                                    <input type="date" name="tanggal_kadaluarsa"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_kadaluarsa') is-invalid @enderror"
                                        value="{{ old('tanggal_kadaluarsa') }}">
                                    @error('tanggal_kadaluarsa') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                                    <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Kosongkan jika dokumen tidak memiliki masa berlaku</div>
                                </div>

                                <div class="col-12">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keterangan</label>
                                    <textarea name="keterangan" rows="3"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('keterangan') is-invalid @enderror"
                                        placeholder="Deskripsi singkat dokumen...">{{ old('keterangan') }}</textarea>
                                    @error('keterangan') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
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
                            <h6 class="mb-0 font-bold text-slate-800">Upload File Dokumen</h6>
                        </div>
                        <div class="p-4">
                            <div class="p-4 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/30 d-flex flex-column align-items-center justify-center text-center transition-all hover:bg-slate-50/50" id="uploadArea" style="cursor: pointer;">
                                <input type="file" name="file" id="fileInput"
                                    class="@error('file') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                    style="display:none">
                                <div id="uploadPlaceholder" class="py-3">
                                    <div class="d-inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-500 mb-3 shadow-inner">
                                        <i class="bi bi-cloud-arrow-up fs-3"></i>
                                    </div>
                                    <h6 class="font-bold text-slate-700 mb-1">Klik atau drag & drop file ke sini</h6>
                                    <p class="text-xs font-semibold text-slate-400 mb-3">PDF, Word, Excel, PowerPoint — Maksimal 20MB</p>
                                    <button type="button" class="inline-flex items-center gap-1 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 px-3.5 py-1.5 text-xs font-bold hover:bg-blue-100"
                                        onclick="document.getElementById('fileInput').click()">
                                        <i class="bi bi-folder2-open"></i>
                                        <span>Pilih Berkas</span>
                                    </button>
                                </div>
                                <div id="filePreview" class="d-none w-full max-w-md">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                                        <div class="d-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 shadow-inner" id="fileIcon">
                                            <i class="bi bi-file-earmark-fill fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1 text-start">
                                            <div class="text-sm font-bold text-slate-800 truncate max-w-[200px]" id="fileName">-</div>
                                            <div class="text-[10px] font-bold text-slate-400" id="fileSize">-</div>
                                        </div>
                                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white"
                                            onclick="clearFile()">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('file') <div class="text-danger text-xs font-bold mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
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
                            <span>Simpan Dokumen</span>
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
    /* Drag over styles */
    .drag-over {
        border-color: #3b82f6 !important;
        background-color: rgba(59, 130, 246, 0.05) !important;
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
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.getElementById('uploadArea');
    const uploadForm = document.getElementById('uploadForm');
    const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB in bytes
    const ALLOWED_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (validateFile(file)) {
                showPreview(file);
            } else {
                this.value = '';
            }
        }
    });

    // Drag & drop triggers
    uploadArea.addEventListener('click', () => {
        if (fileInput.value === '') {
            fileInput.click();
        }
    });
    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && validateFile(file)) {
            fileInput.files = e.dataTransfer.files;
            showPreview(file);
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

    function showPreview(file) {
        document.getElementById('uploadPlaceholder').classList.add('d-none');
        document.getElementById('filePreview').classList.remove('d-none');
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatSize(file.size);

        const ext = file.name.split('.').pop().toLowerCase();
        const icons = { pdf: 'bi-file-earmark-pdf-fill text-rose-500', docx: 'bi-file-earmark-word-fill text-blue-500',
                        doc: 'bi-file-earmark-word-fill text-blue-500', xlsx: 'bi-file-earmark-excel-fill text-emerald-500',
                        xls: 'bi-file-earmark-excel-fill text-emerald-500', pptx: 'bi-file-earmark-slides-fill text-orange-500',
                        ppt: 'bi-file-earmark-slides-fill text-orange-500' };
        document.getElementById('fileIcon').innerHTML =
            `<i class="bi ${icons[ext] || 'bi-file-earmark-fill'} fs-4"></i>`;
    }

    function clearFile() {
        fileInput.value = '';
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('filePreview').classList.add('d-none');
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