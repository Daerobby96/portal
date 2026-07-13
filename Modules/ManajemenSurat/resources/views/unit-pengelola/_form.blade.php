<div class="card border-0 rounded-2xl shadow-sm">
    <div class="card-header bg-white p-4">
        <h6 class="mb-0 font-bold">{{ isset($unitPengelola) ? 'Edit' : 'Tambah' }} Unit Pengelola</h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label font-semibold">Nama Unit <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                       value="{{ old('nama', $unitPengelola->nama ?? '') }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold">Kode Unit <span class="text-danger">*</span></label>
                <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" 
                       value="{{ old('kode', $unitPengelola->kode ?? '') }}" placeholder="Contoh: YYS, STMIK, TI" required>
                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Kode unik untuk format nomor surat</small>
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold">Jenis Institusi <span class="text-danger">*</span></label>
                <select name="jenis_institusi" class="form-select @error('jenis_institusi') is-invalid @enderror" required>
                    <option value="">Pilih Jenis</option>
                    <option value="yayasan" {{ old('jenis_institusi', $unitPengelola->jenis_institusi ?? '') == 'yayasan' ? 'selected' : '' }}>Yayasan</option>
                    <option value="perguruan_tinggi" {{ old('jenis_institusi', $unitPengelola->jenis_institusi ?? '') == 'perguruan_tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                </select>
                @error('jenis_institusi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Menentukan kop surat yang digunakan dari pengaturan aplikasi</small>
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold">Format Nomor Custom</label>
                <input type="text" name="prefix_format" class="form-control @error('prefix_format') is-invalid @enderror" 
                       value="{{ old('prefix_format', $unitPengelola->prefix_format ?? '{nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}') }}" 
                       placeholder="{nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}">
                @error('prefix_format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Variabel: {nomor}, {kode_jenis}, {kode_unit}, {bulan}, {tahun}</small>
            </div>

            <div class="col-12">
                <div class="alert alert-info border-0 rounded-xl d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle"></i>
                    <div class="text-sm">
                        <strong>Info Kop Surat:</strong> Kop surat otomatis diambil dari pengaturan aplikasi berdasarkan jenis institusi.
                        <ul class="mb-0 mt-1">
                            <li>Yayasan → Menggunakan kop yayasan dari pengaturan</li>
                            <li>Perguruan Tinggi → Menggunakan kop PT dari pengaturan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label font-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $unitPengelola->deskripsi ?? '') }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold">Nama PIC</label>
                <input type="text" name="pic_nama" class="form-control @error('pic_nama') is-invalid @enderror" 
                       value="{{ old('pic_nama', $unitPengelola->pic_nama ?? '') }}">
                @error('pic_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold">Jabatan PIC</label>
                <input type="text" name="pic_jabatan" class="form-control @error('pic_jabatan') is-invalid @enderror" 
                       value="{{ old('pic_jabatan', $unitPengelola->pic_jabatan ?? '') }}">
                @error('pic_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold">NIP PIC</label>
                <input type="text" name="pic_nip" class="form-control @error('pic_nip') is-invalid @enderror" 
                       value="{{ old('pic_nip', $unitPengelola->pic_nip ?? '') }}">
                @error('pic_nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                           value="1" {{ old('is_active', $unitPengelola->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Unit Aktif</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-slate-50 p-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save"></i> Simpan
        </button>
        <a href="{{ route('unit-pengelola.index') }}" class="btn btn-light px-4">
            <i class="bi bi-x-lg"></i> Batal
        </a>
    </div>
</div>
