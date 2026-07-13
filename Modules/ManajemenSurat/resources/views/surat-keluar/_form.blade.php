<div class="card border-0 rounded-2xl shadow-sm">
    <div class="card-header bg-white border-b border-slate-100 p-4">
        <h6 class="mb-0 font-bold text-slate-800">{{ isset($suratKeluar) ? 'Edit' : 'Buat' }} Surat Keluar</h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label font-semibold text-slate-700">Jenis Surat <span class="text-danger">*</span></label>
                <select name="jenis_surat_id" class="form-select @error('jenis_surat_id') is-invalid @enderror" required>
                    <option value="">Pilih Jenis Surat</option>
                    @foreach($jenisSurat as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_surat_id', $suratKeluar->jenis_surat_id ?? '') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama }}
                    </option>
                    @endforeach
                </select>
                @error('jenis_surat_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold text-slate-700">Unit Pengelola <span class="text-danger">*</span></label>
                <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                    <option value="">Pilih Unit Pengelola</option>
                    @foreach($unitPengelola as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $suratKeluar->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama }} ({{ $unit->kode }})
                    </option>
                    @endforeach
                </select>
                @error('unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Menentukan format nomor surat</small>
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold text-slate-700">Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" 
                       value="{{ old('tanggal_surat', isset($suratKeluar) ? $suratKeluar->tanggal_surat->format('Y-m-d') : date('Y-m-d')) }}" required>
                @error('tanggal_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label font-semibold text-slate-700">Perihal <span class="text-danger">*</span></label>
                <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" 
                       value="{{ old('perihal', $suratKeluar->perihal ?? '') }}" placeholder="Contoh: Undangan Rapat Koordinasi" required>
                @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label font-semibold text-slate-700">Isi Surat</label>
                <textarea name="isi_surat" id="isi_surat" rows="15" class="form-control @error('isi_surat') is-invalid @enderror">{{ old('isi_surat', $suratKeluar->isi_surat ?? '') }}</textarea>
                @error('isi_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Gunakan editor untuk memformat isi surat</small>
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold text-slate-700">Tujuan <span class="text-danger">*</span></label>
                <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" 
                       value="{{ old('tujuan', $suratKeluar->tujuan ?? '') }}" required>
                @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold text-slate-700">Alamat Tujuan</label>
                <input type="text" name="alamat_tujuan" class="form-control @error('alamat_tujuan') is-invalid @enderror" 
                       value="{{ old('alamat_tujuan', $suratKeluar->alamat_tujuan ?? '') }}">
                @error('alamat_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold text-slate-700">Nama Penandatangan <span class="text-danger">*</span></label>
                <input type="text" name="penandatangan_nama" class="form-control @error('penandatangan_nama') is-invalid @enderror" 
                       value="{{ old('penandatangan_nama', $suratKeluar->penandatangan_nama ?? '') }}" required>
                @error('penandatangan_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold text-slate-700">Jabatan <span class="text-danger">*</span></label>
                <input type="text" name="penandatangan_jabatan" class="form-control @error('penandatangan_jabatan') is-invalid @enderror" 
                       value="{{ old('penandatangan_jabatan', $suratKeluar->penandatangan_jabatan ?? '') }}" required>
                @error('penandatangan_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label font-semibold text-slate-700">NIP</label>
                <input type="text" name="penandatangan_nip" class="form-control @error('penandatangan_nip') is-invalid @enderror" 
                       value="{{ old('penandatangan_nip', $suratKeluar->penandatangan_nip ?? '') }}">
                @error('penandatangan_nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold text-slate-700">Jumlah Lampiran</label>
                <input type="number" name="jumlah_lampiran" min="0" class="form-control @error('jumlah_lampiran') is-invalid @enderror" 
                       value="{{ old('jumlah_lampiran', $suratKeluar->jumlah_lampiran ?? 0) }}">
                @error('jumlah_lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold text-slate-700">Keterangan Lampiran</label>
                <input type="text" name="keterangan_lampiran" class="form-control @error('keterangan_lampiran') is-invalid @enderror" 
                       value="{{ old('keterangan_lampiran', $suratKeluar->keterangan_lampiran ?? '') }}">
                @error('keterangan_lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold text-slate-700">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="draft" {{ old('status', $suratKeluar->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ old('status', $suratKeluar->status ?? '') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="published" {{ old('status', $suratKeluar->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label font-semibold text-slate-700">Catatan</label>
                <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $suratKeluar->catatan ?? '') }}</textarea>
                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-slate-50 p-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save"></i> Simpan
        </button>
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-light px-4">
            <i class="bi bi-x-lg"></i> Batal
        </a>
    </div>
</div>
