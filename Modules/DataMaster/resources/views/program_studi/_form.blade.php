@php $ps = $ps ?? new \Modules\DataMaster\Models\ProgramStudi(); @endphp
<div class="row g-4">
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Program Studi</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kode Prodi <span class="text-danger">*</span></label>
                <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                    value="{{ old('kode', $ps->kode) }}" required placeholder="Misal: TI, SI, MNJ">
                @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Nama Program Studi <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $ps->nama) }}" required placeholder="Misal: Teknik Informatika">
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenjang <span class="text-danger">*</span></label>
                <select name="jenjang" class="form-select @error('jenjang') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenjang --</option>
                    @foreach(['D3','D4','S1','S2','S3','Profesi'] as $j)
                        <option value="{{ $j }}" {{ old('jenjang', $ps->jenjang) == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                @error('jenjang')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Akreditasi</label>
                <select name="akreditasi" class="form-select @error('akreditasi') is-invalid @enderror">
                    <option value="">-- Pilih Akreditasi --</option>
                    @foreach(['Unggul','Baik Sekali','Baik','B','A','C','Terakreditasi','Belum Terakreditasi'] as $a)
                        <option value="{{ $a }}" {{ old('akreditasi', $ps->akreditasi) == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
                @error('akreditasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1"
                        {{ old('is_aktif', $ps->is_aktif ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is_aktif">Status Aktif</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Deskripsi singkat tentang program studi...">{{ old('deskripsi', $ps->deskripsi) }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

