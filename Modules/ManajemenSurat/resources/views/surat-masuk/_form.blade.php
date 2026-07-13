<div class="card border-0 rounded-2xl shadow-sm">
    <div class="card-header bg-white p-4">
        <h6 class="mb-0 font-bold">{{ isset($suratMasuk) ? 'Edit' : 'Catat' }} Surat Masuk</h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label font-semibold">Jenis Surat <span class="text-danger">*</span></label>
                <select name="jenis_surat_id" class="form-select @error('jenis_surat_id') is-invalid @enderror" required>
                    <option value="">Pilih Jenis</option>
                    @foreach($jenisSurat as $jenis)
                    <option value="{{ $jenis->id }}" {{ old('jenis_surat_id', $suratMasuk->jenis_surat_id ?? '') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                @error('jenis_surat_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Nomor Surat <span class="text-danger">*</span></label>
                <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat', $suratMasuk->nomor_surat ?? '') }}" required>
                @error('nomor_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', isset($suratMasuk) ? $suratMasuk->tanggal_surat->format('Y-m-d') : '') }}" required>
                @error('tanggal_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Tanggal Terima <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', isset($suratMasuk) ? $suratMasuk->tanggal_terima->format('Y-m-d') : date('Y-m-d')) }}" required>
                @error('tanggal_terima')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Pengirim <span class="text-danger">*</span></label>
                <input type="text" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror" value="{{ old('pengirim', $suratMasuk->pengirim ?? '') }}" required>
                @error('pengirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Alamat Pengirim</label>
                <input type="text" name="alamat_pengirim" class="form-control @error('alamat_pengirim') is-invalid @enderror" value="{{ old('alamat_pengirim', $suratMasuk->alamat_pengirim ?? '') }}">
                @error('alamat_pengirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label font-semibold">Perihal <span class="text-danger">*</span></label>
                <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal', $suratMasuk->perihal ?? '') }}" required>
                @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label font-semibold">Sifat <span class="text-danger">*</span></label>
                <select name="sifat" class="form-select @error('sifat') is-invalid @enderror" required>
                    <option value="biasa" {{ old('sifat', $suratMasuk->sifat ?? 'biasa') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                    <option value="segera" {{ old('sifat', $suratMasuk->sifat ?? '') == 'segera' ? 'selected' : '' }}>Segera</option>
                    <option value="sangat_segera" {{ old('sifat', $suratMasuk->sifat ?? '') == 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                    <option value="rahasia" {{ old('sifat', $suratMasuk->sifat ?? '') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                </select>
                @error('sifat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label font-semibold">Prioritas <span class="text-danger">*</span></label>
                <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                    <option value="rendah" {{ old('prioritas', $suratMasuk->prioritas ?? '') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="sedang" {{ old('prioritas', $suratMasuk->prioritas ?? 'sedang') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="tinggi" {{ old('prioritas', $suratMasuk->prioritas ?? '') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>
                @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label font-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="baru" {{ old('status', $suratMasuk->status ?? 'baru') == 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="proses" {{ old('status', $suratMasuk->status ?? '') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ old('status', $suratMasuk->status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="arsip" {{ old('status', $suratMasuk->status ?? '') == 'arsip' ? 'selected' : '' }}>Arsip</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Jumlah Lampiran</label>
                <input type="number" name="jumlah_lampiran" min="0" class="form-control" value="{{ old('jumlah_lampiran', $suratMasuk->jumlah_lampiran ?? 0) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Keterangan Lampiran</label>
                <input type="text" name="keterangan_lampiran" class="form-control" value="{{ old('keterangan_lampiran', $suratMasuk->keterangan_lampiran ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label font-semibold">File Scan Surat (PDF/JPG/PNG, max 5MB)</label>
                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if(isset($suratMasuk) && $suratMasuk->file_path)
                <small class="text-muted">File saat ini: {{ basename($suratMasuk->file_path) }}</small>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label font-semibold">Catatan</label>
                <textarea name="catatan" rows="3" class="form-control">{{ old('catatan', $suratMasuk->catatan ?? '') }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer bg-slate-50 p-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> Batal</a>
    </div>
</div>
