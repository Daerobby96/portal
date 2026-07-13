@php $hki = $hki ?? new \App\Models\Hki(); @endphp
<div class="row g-4">
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Pengusul</h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Dosen / Pegawai <span class="text-danger">*</span></label>
                <select name="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" {{ old('pegawai_id', $hki->pegawai_id) == $pegawai->id ? 'selected' : '' }}>
                            {{ $pegawai->nama }} @if($pegawai->nip) ({{ $pegawai->nip }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Detail HKI / Paten</h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Judul HKI / Paten <span class="text-danger">*</span></label>
                <input type="text" name="judul_hki" class="form-control @error('judul_hki') is-invalid @enderror" 
                    value="{{ old('judul_hki', $hki->judul_hki) }}" required placeholder="Misal: Algoritma Pencarian Cepat Berbasis AI">
                @error('judul_hki')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis HKI <span class="text-danger">*</span></label>
                <select name="jenis_hki" class="form-select @error('jenis_hki') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(\App\Models\Hki::JENIS_HKI as $jh)
                        <option value="{{ $jh }}" {{ old('jenis_hki', $hki->jenis_hki) == $jh ? 'selected' : '' }}>{{ $jh }}</option>
                    @endforeach
                </select>
                @error('jenis_hki')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomor Pencatatan / Sertifikat</label>
                <input type="text" name="nomor_pencatatan" class="form-control @error('nomor_pencatatan') is-invalid @enderror" 
                    value="{{ old('nomor_pencatatan', $hki->nomor_pencatatan) }}" placeholder="Misal: EC00202312345">
                @error('nomor_pencatatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" 
                    value="{{ old('tahun_terbit', $hki->tahun_terbit ?? date('Y')) }}" required min="2000" max="{{ date('Y')+1 }}">
                @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(\App\Models\Hki::STATUS as $st)
                        <option value="{{ $st }}" {{ old('status', $hki->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Bukti & Dokumen</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Upload Sertifikat (PDF)</label>
                <input type="file" name="sertifikat" class="form-control @error('sertifikat') is-invalid @enderror" accept=".pdf">
                @if($hki->sertifikat)
                    <div class="form-text text-success mt-1">
                        <i class="bi bi-check-circle me-1"></i>Sertifikat sudah diunggah. <a href="{{ asset('storage/'.$hki->sertifikat) }}" target="_blank">Lihat Dokumen</a>
                    </div>
                @endif
                <div class="form-text">Maksimal 5MB. Kosongkan jika tidak ingin mengubah.</div>
                @error('sertifikat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label fw-semibold">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $hki->keterangan) }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>
