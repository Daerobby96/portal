@extends('systemadmin::layouts.master')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-subtitle', 'Kustomisasi tampilan dan konfigurasi aplikasi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .cropper-container { max-height: 400px; width: 100%; }
</style>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- General Settings --}}
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
                <div class="card-header bg-white border-b border-slate-100 py-3.5 px-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Pengaturan Umum</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Nama Aplikasi <span class="text-danger">*</span></label>
                            <input type="text" name="app_name" class="form-control rounded-xl text-xs py-2.5 text-slate-700"
                                   value="{{ old('app_name', $settings['general']->where('key', 'app_name')->first()?->value ?? 'SPMI') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Tagline</label>
                            <input type="text" name="app_tagline" class="form-control rounded-xl text-xs py-2.5 text-slate-700"
                                   value="{{ old('app_tagline', $settings['general']->where('key', 'app_tagline')->first()?->value ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Theme Settings --}}
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
                <div class="card-header bg-white border-b border-slate-100 py-3.5 px-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-xl">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Pengaturan Tema</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Warna Utama <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="colorPicker" class="form-control form-control-color border-slate-200"
                                       value="{{ old('theme_primary', $settings['theme']->where('key', 'theme_primary')->first()?->value ?? '#4e73df') }}"
                                       style="width: 60px; height: 38px; border-radius: 12px 0 0 12px;">
                                <input type="text" name="theme_primary" id="colorHex" class="form-control border-slate-200 border-start-0 rounded-end-xl text-xs"
                                       value="{{ old('theme_primary', $settings['theme']->where('key', 'theme_primary')->first()?->value ?? '#4e73df') }}"
                                       pattern="^#[a-fA-F0-9]{6}$" required style="border-radius: 0 12px 12px 0;">
                            </div>
                            <div class="form-text text-[10px] text-slate-400 mt-1.5">Klik kotak warna atau masukkan kode hex (contoh: #4e73df)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Tema Sidebar <span class="text-danger">*</span></label>
                            <select name="theme_sidebar" class="form-select border-slate-200 rounded-xl text-xs py-2.5 text-slate-700" required>
                                <option value="dark" {{ ($settings['theme']->where('key', 'theme_sidebar')->first()?->value ?? 'dark') === 'dark' ? 'selected' : '' }}>
                                    Gelap (Dark Graphite-Navy)
                                </option>
                                <option value="light" {{ ($settings['theme']->where('key', 'theme_sidebar')->first()?->value ?? 'dark') === 'light' ? 'selected' : '' }}>
                                    Terang (Light)
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Preset Colors --}}
                    <div class="mt-4">
                        <label class="form-label font-bold text-slate-700 small mb-2 d-block">Preset Warna Populer:</label>
                        <div class="d-flex flex-wrap gap-2.5">
                            @php
                                $presets = [
                                    '#2563eb' => 'Primary Blue',
                                    '#10b981' => 'Success Green',
                                    '#06b6d4' => 'Info Cyan',
                                    '#f59e0b' => 'Warning Yellow',
                                    '#ef4444' => 'Danger Red',
                                    '#8b5cf6' => 'Purple',
                                    '#f97316' => 'Orange',
                                    '#14b8a6' => 'Teal',
                                    '#64748b' => 'Secondary Gray',
                                    '#1e293b' => 'Dark Graphite',
                                ];
                            @endphp
                            @foreach($presets as $color => $name)
                                <button type="button" class="btn color-preset" 
                                        data-color="{{ $color }}"
                                        style="background-color: {{ $color }}; width: 32px; height: 32px; border-radius: 50%; box-shadow: 0 3px 8px rgba(0,0,0,0.1); border: 2px solid white;"
                                        title="{{ $name }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Institusi Settings --}}
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
                <div class="card-header bg-white border-b border-slate-100 py-3.5 px-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i class="bi bi-building-fill"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Data Institusi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label font-bold text-slate-700 small mb-2">Nama Institusi</label>
                            <input type="text" name="nama_institusi" class="form-control rounded-xl text-xs py-2.5 text-slate-700"
                                   value="{{ old('nama_institusi', $settings['institusi']->where('key', 'nama_institusi')->first()?->value ?? '') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label font-bold text-slate-700 small mb-2">Alamat Institusi</label>
                            <textarea name="alamat_institusi" class="form-control rounded-xl text-xs text-slate-700" rows="2">{{ old('alamat_institusi', $settings['institusi']->where('key', 'alamat_institusi')->first()?->value ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700 small mb-2">Kota</label>
                            <input type="text" name="kota_institusi" class="form-control rounded-xl text-xs py-2.5 text-slate-700"
                                   value="{{ old('kota_institusi', $settings['institusi']->where('key', 'kota_institusi')->first()?->value ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Logo Institusi</label>
                            @php $currentLogoInstitusi = $settings['institusi']->where('key', 'logo_institusi')->first()?->value; @endphp
                            @if($currentLogoInstitusi)
                                <div class="mb-3 p-2 bg-slate-50 border border-slate-100 rounded-xl d-inline-block">
                                    <img src="{{ asset('storage/' . $currentLogoInstitusi) }}" alt="Logo Institusi" height="60" style="object-fit:contain;">
                                </div>
                            @endif
                            <input type="file" name="logo_institusi" class="form-control rounded-xl text-xs" accept="image/png,jpg,jpeg">
                            <div class="form-text text-[10px] text-slate-400 mt-1.5">PNG, JPG. Max 2MB. Disarankan ukuran 200x200 pixel</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kop Surat Settings --}}
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
                <div class="card-header bg-white border-b border-slate-100 py-3.5 px-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                        <i class="bi bi-file-earmark-image-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-bold text-slate-800">Kop Surat</h6>
                        <div class="text-[11px] text-slate-400 mt-0.5">Gambar kop surat resmi yang dipakai dalam Generator SK (format landscape A4, PNG/JPG)</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Kop Yayasan --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-xl border border-purple-100 bg-purple-50/30">
                                <label class="form-label font-bold text-slate-700 small mb-2 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-building text-purple-500"></i>
                                    Kop Surat Yayasan
                                </label>
                                @php $kopYayasan = \App\Models\Setting::get('kop_surat_yayasan'); @endphp
                                @if($kopYayasan)
                                    <div class="mb-3 p-2 bg-white border border-slate-200 rounded-xl">
                                        <img src="{{ asset('storage/' . $kopYayasan) }}" alt="Kop Yayasan"
                                             class="w-full" style="max-height:90px; object-fit:contain; object-position:left;">
                                        <div class="text-[10px] text-slate-400 mt-1 text-center">Kop Surat Yayasan aktif</div>
                                    </div>
                                @else
                                    <div class="mb-3 p-3 bg-white border border-dashed border-slate-200 rounded-xl text-center">
                                        <i class="bi bi-image text-slate-300 fs-3 d-block mb-1"></i>
                                        <div class="text-[10px] text-slate-400">Belum ada kop surat Yayasan</div>
                                    </div>
                                @endif
                                <input type="file" name="kop_surat_yayasan" id="kop_surat_yayasan"
                                       class="form-control rounded-xl text-xs" accept="image/png,image/jpg,image/jpeg"
                                       onchange="previewKop(this, 'preview-kop-yayasan')">
                                <div class="form-text text-[10px] text-slate-400 mt-1.5">
                                    <i class="bi bi-info-circle me-1"></i>PNG/JPG, max 4MB. Gunakan gambar full-width kop surat (landscape).
                                </div>
                                <div id="preview-kop-yayasan" class="mt-2 d-none">
                                    <div class="text-[10px] text-slate-500 font-semibold mb-1">Preview:</div>
                                    <img src="" alt="preview" class="w-full rounded-lg border border-slate-200" style="max-height:80px;object-fit:contain;">
                                </div>
                            </div>
                        </div>

                        {{-- Kop PT --}}
                        <div class="col-md-6">
                            <div class="p-3 rounded-xl border border-emerald-100 bg-emerald-50/30">
                                <label class="form-label font-bold text-slate-700 small mb-2 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-mortarboard text-emerald-500"></i>
                                    Kop Surat Perguruan Tinggi
                                </label>
                                @php $kopPt = \App\Models\Setting::get('kop_surat_pt'); @endphp
                                @if($kopPt)
                                    <div class="mb-3 p-2 bg-white border border-slate-200 rounded-xl">
                                        <img src="{{ asset('storage/' . $kopPt) }}" alt="Kop PT"
                                             class="w-full" style="max-height:90px; object-fit:contain; object-position:left;">
                                        <div class="text-[10px] text-slate-400 mt-1 text-center">Kop Surat PT aktif</div>
                                    </div>
                                @else
                                    <div class="mb-3 p-3 bg-white border border-dashed border-slate-200 rounded-xl text-center">
                                        <i class="bi bi-image text-slate-300 fs-3 d-block mb-1"></i>
                                        <div class="text-[10px] text-slate-400">Belum ada kop surat Perguruan Tinggi</div>
                                    </div>
                                @endif
                                <input type="file" name="kop_surat_pt" id="kop_surat_pt"
                                       class="form-control rounded-xl text-xs" accept="image/png,image/jpg,image/jpeg"
                                       onchange="previewKop(this, 'preview-kop-pt')">
                                <div class="form-text text-[10px] text-slate-400 mt-1.5">
                                    <i class="bi bi-info-circle me-1"></i>PNG/JPG, max 4MB. Gunakan gambar full-width kop surat (landscape).
                                </div>
                                <div id="preview-kop-pt" class="mt-2 d-none">
                                    <div class="text-[10px] text-slate-500 font-semibold mb-1">Preview:</div>
                                    <img src="" alt="preview" class="w-full rounded-lg border border-slate-200" style="max-height:80px;object-fit:contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logo Settings --}}
            <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] mb-4">
                <div class="card-header bg-white border-b border-slate-100 py-3.5 px-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-cyan-50 text-cyan-600 rounded-xl">
                        <i class="bi bi-image-fill"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Logo & Favicon Aplikasi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Logo Aplikasi</label>
                            @php $currentLogo = $settings['logo']->where('key', 'logo')->first()?->value; @endphp
                            @if($currentLogo)
                                <div class="mb-3 p-2 bg-slate-50 border border-slate-100 rounded-xl d-inline-block">
                                    <img src="{{ asset('storage/' . $currentLogo) }}" alt="Logo" height="40" style="object-fit:contain;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control rounded-xl text-xs" accept="image/png,jpg,jpeg,svg">
                            <div class="form-text text-[10px] text-slate-400 mt-1.5">PNG, JPG, SVG. Max 2MB. Disarankan ukuran 200x50 pixel</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-bold text-slate-700 small mb-2">Favicon</label>
                            @php $currentFavicon = $settings['logo']->where('key', 'favicon')->first()?->value; @endphp
                            @if($currentFavicon)
                                <div class="mb-3 p-2 bg-slate-50 border border-slate-100 rounded-xl d-inline-block">
                                    <img src="{{ asset('storage/' . $currentFavicon) }}" alt="Favicon" height="28" style="object-fit:contain;">
                                </div>
                            @endif
                            <input type="file" name="favicon" class="form-control rounded-xl text-xs" accept="image/ico,png">
                            <div class="form-text text-[10px] text-slate-400 mt-1.5">ICO, PNG. Max 512KB. Disarankan 32x32 pixel</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2 justify-content-between">
                <button type="submit" class="btn btn-primary rounded-xl font-bold text-xs px-4 py-2.5 shadow-sm">
                    <i class="bi bi-save me-1.5"></i>Simpan Pengaturan
                </button>
                <button type="button" class="btn btn-outline-danger rounded-xl font-bold text-xs px-4 py-2.5 border-slate-200" onclick="if(confirm('Reset semua pengaturan ke default?')) document.getElementById('reset-form').submit();">
                    <i class="bi bi-arrow-counterclockwise me-1.5"></i>Reset ke Default
                </button>
            </div>
        </form>

        <form id="reset-form" action="{{ route('settings.reset') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    {{-- Premium Preview Sidebar --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] sticky-top" style="top: 24px;">
            <div class="card-header bg-white border-b border-slate-100 py-3.5 px-4 d-flex align-items-center gap-2">
                <div class="p-2 bg-slate-50 text-slate-700 rounded-xl">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Live Preview</h6>
            </div>
            <div class="card-body p-4">
                <!-- Sidebar Simulation Wrapper -->
                <div class="preview-sidebar-lux rounded-2xl p-3.5 mb-4 relative overflow-hidden" id="previewSidebar">
                    <div class="absolute -right-16 -bottom-16 h-40 w-40 rounded-full bg-blue-500/5 blur-3xl"></div>
                    <div class="d-flex align-items-center gap-2.5 mb-4 pb-2 border-b border-slate-800/50" id="previewHeaderBorder">
                        <div class="preview-logo-lux" id="previewLogo">
                            <i class="bi bi-shield-check-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-extrabold text-white" id="previewName" style="font-size: 13.5px; tracking-wide: 0.5px;">SPMI</div>
                            <div class="text-slate-400 font-bold text-[10px] text-uppercase" id="previewTagline" style="letter-spacing:0.5px;">Penjaminan Mutu</div>
                        </div>
                    </div>
                    <div class="preview-menu-item-lux active-preview" id="previewActiveItem">
                        <i class="bi bi-speedometer2 me-2.5"></i>Dashboard
                    </div>
                    <div class="preview-menu-item-lux">
                        <i class="bi bi-folder2-open me-2.5"></i>Dokumen Mutu
                    </div>
                    <div class="preview-menu-item-lux">
                        <i class="bi bi-clipboard-check me-2.5"></i>Audit Mutu
                    </div>
                </div>

                <!-- Custom Button simulation -->
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-center" id="previewButton">
                    <button class="btn text-white rounded-xl text-xs font-bold px-4 py-2.5 border-0 shadow-sm transition-all" id="previewBtn">
                        <i class="bi bi-plus-lg me-1.5"></i>Contoh Tombol Utama
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Crop Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-2xl border-0 shadow-lg">
            <div class="modal-header border-b border-slate-100 py-3 px-4">
                <h5 class="modal-title font-bold text-slate-800 text-sm" id="cropModalLabel">Crop Gambar Kop Surat</h5>
                <button type="button" class="btn-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-slate-50">
                <div class="img-container">
                    <img id="imageToCrop" src="" alt="Picture" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer border-t border-slate-100 py-3 px-4">
                <button type="button" class="btn btn-outline-secondary rounded-xl text-xs font-bold px-4 py-2" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-xl text-xs font-bold px-4 py-2" id="btnCrop">Selesai Crop</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.color-preset {
    cursor: pointer;
    transition: all 0.25s ease;
}
.color-preset:hover {
    transform: scale(1.15) translateY(-2px);
    box-shadow: 0 5px 12px rgba(0,0,0,0.15) !important;
}
.preview-sidebar-lux {
    background: #0f172a;
    color: #fff;
    min-height: 220px;
}
.preview-sidebar-lux.light {
    background: #f8fafc;
    color: #1e293b;
    border: 1px solid #e2e8f0;
}
.preview-sidebar-lux.light #previewHeaderBorder {
    border-color: #e2e8f0 !important;
}
.preview-sidebar-lux.light #previewActiveItem {
    background-color: rgba(37, 99, 235, 0.08) !important;
}
.preview-logo-lux {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.preview-sidebar-lux.light .preview-logo-lux {
    background: #fff;
    border-color: #e2e8f0;
}
.preview-menu-item-lux {
    padding: 9px 12px;
    border-radius: 10px;
    margin-bottom: 5px;
    font-size: 11.5px;
    font-weight: bold;
    color: #94a3b8;
    display: flex;
    align-items: center;
}
.preview-sidebar-lux.light .preview-menu-item-lux {
    color: #64748b;
}
.active-preview {
    background-color: rgba(255, 255, 255, 0.06);
    color: #fff;
}
.preview-sidebar-lux.light .active-preview {
    color: #2563eb;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('colorPicker');
    const colorHex = document.getElementById('colorHex');
    const themeSidebar = document.querySelector('[name="theme_sidebar"]');
    const appNameInput = document.querySelector('[name="app_name"]');
    const appTaglineInput = document.querySelector('[name="app_tagline"]');

    function updatePreview(color) {
        document.getElementById('previewBtn').style.backgroundColor = color;
        document.getElementById('previewLogo').style.color = color;
        
        // Active item left border indicator or subtle highlight
        const activeItem = document.getElementById('previewActiveItem');
        const isLight = themeSidebar.value === 'light';
        if (isLight) {
            activeItem.style.color = color;
            activeItem.style.borderLeft = `3px solid ${color}`;
            activeItem.style.borderRadius = '0 10px 10px 0';
        } else {
            activeItem.style.color = '#fff';
            activeItem.style.borderLeft = `3px solid ${color}`;
            activeItem.style.borderRadius = '0 10px 10px 0';
        }
    }

    // Color picker sync
    colorPicker.addEventListener('input', function() {
        colorHex.value = this.value;
        updatePreview(this.value);
    });

    colorHex.addEventListener('input', function() {
        if (/^#[a-fA-F0-9]{6}$/.test(this.value)) {
            colorPicker.value = this.value;
            updatePreview(this.value);
        }
    });

    // Preset colors
    document.querySelectorAll('.color-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            const color = this.dataset.color;
            colorPicker.value = color;
            colorHex.value = color;
            updatePreview(color);
        });
    });

    // Sidebar theme preview
    function checkSidebarTheme() {
        const sidebar = document.getElementById('previewSidebar');
        const activeItem = document.getElementById('previewActiveItem');
        const textName = document.getElementById('previewName');
        const textTagline = document.getElementById('previewTagline');
        const isLight = themeSidebar.value === 'light';
        
        if (isLight) {
            sidebar.classList.add('light');
            textName.classList.remove('text-white');
            textName.classList.add('text-slate-800');
            textTagline.classList.remove('text-slate-400');
            textTagline.classList.add('text-slate-400');
        } else {
            sidebar.classList.remove('light');
            textName.classList.add('text-white');
            textName.classList.remove('text-slate-800');
            textTagline.classList.add('text-slate-400');
            textTagline.classList.remove('text-slate-400');
        }
        updatePreview(colorPicker.value);
    }

    themeSidebar.addEventListener('change', checkSidebarTheme);

    // App name & tagline inputs
    appNameInput.addEventListener('input', function() {
        document.getElementById('previewName').textContent = this.value || 'SPMI';
    });

    appTaglineInput.addEventListener('input', function() {
        document.getElementById('previewTagline').textContent = this.value || 'Penjaminan Mutu';
    });

    // Initial load
    checkSidebarTheme();
});

let cropper;
let currentFileInput;
let currentPreviewId;
const imageToCrop = document.getElementById('imageToCrop');
const cropModalElement = document.getElementById('cropModal');
const cropModal = new bootstrap.Modal(cropModalElement);

function previewKop(input, previewId) {
    if (input.files && input.files[0]) {
        currentFileInput = input;
        currentPreviewId = previewId;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imageToCrop.src = e.target.result;
            cropModal.show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

cropModalElement.addEventListener('shown.bs.modal', function () {
    cropper = new Cropper(imageToCrop, {
        aspectRatio: NaN, // Allow free crop but can be set to eg. 210/40 for specific landscape
        viewMode: 1,
        autoCropArea: 1,
    });
});

cropModalElement.addEventListener('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

document.getElementById('btnCrop').addEventListener('click', function() {
    if (!cropper) return;
    
    // Get cropped canvas
    const canvas = cropper.getCroppedCanvas({
        maxWidth: 2480, // A4 width at 300dpi limit approx
    });
    
    // Convert to Blob and set it to the original input file
    canvas.toBlob(function(blob) {
        const file = new File([blob], currentFileInput.files[0].name, { type: 'image/png' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        currentFileInput.files = dataTransfer.files;
        
        // Show preview in UI
        const previewDiv = document.getElementById(currentPreviewId);
        const img = previewDiv.querySelector('img');
        img.src = canvas.toDataURL('image/png');
        previewDiv.classList.remove('d-none');
        
        cropModal.hide();
    }, 'image/png');
});
</script>
@endpush
@endsection
