@php $kerjasama = $kerjasama ?? new \Modules\Kerjasama\Models\Kerjasama(); @endphp
<div class="row g-4">
    {{-- Detail Mitra --}}
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Mitra</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                <input type="text" name="nama_mitra" class="form-control @error('nama_mitra') is-invalid @enderror" 
                    value="{{ old('nama_mitra', $kerjasama->nama_mitra) }}" required placeholder="Misal: Universitas Gadjah Mada">
                @error('nama_mitra')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis Mitra <span class="text-danger">*</span></label>
                <select name="jenis_mitra" class="form-select @error('jenis_mitra') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(\Modules\Kerjasama\Models\Kerjasama::JENIS_MITRA as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_mitra', $kerjasama->jenis_mitra) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>
                @error('jenis_mitra')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Detail Kerjasama --}}
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Detail Kegiatan / Ruang Lingkup</h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Judul Kegiatan / Kerjasama <span class="text-danger">*</span></label>
                <input type="text" name="judul_kerjasama" class="form-control @error('judul_kerjasama') is-invalid @enderror" 
                    value="{{ old('judul_kerjasama', $kerjasama->judul_kerjasama) }}" required placeholder="Misal: MoU Pertukaran Pelajar dan Riset Bersama">
                @error('judul_kerjasama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Jenis Dokumen <span class="text-danger">*</span></label>
                <select name="jenis_dokumen" class="form-select @error('jenis_dokumen') is-invalid @enderror" required>
                    <option value="">-- Pilih --</option>
                    @foreach(\Modules\Kerjasama\Models\Kerjasama::JENIS_DOKUMEN as $jd)
                        <option value="{{ $jd }}" {{ old('jenis_dokumen', $kerjasama->jenis_dokumen) == $jd ? 'selected' : '' }}>{{ $jd }}</option>
                    @endforeach
                </select>
                @error('jenis_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                    <option value="">-- Pilih Tingkat --</option>
                    @foreach(\Modules\Kerjasama\Models\Kerjasama::TINGKAT as $tk)
                        <option value="{{ $tk }}" {{ old('tingkat', $kerjasama->tingkat) == $tk ? 'selected' : '' }}>{{ $tk }}</option>
                    @endforeach
                </select>
                @error('tingkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(\Modules\Kerjasama\Models\Kerjasama::STATUS as $st)
                        <option value="{{ $st }}" {{ old('status', $kerjasama->status ?? 'Draft') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Program Studi Pengusul</label>
                <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                    <option value="">-- Institusi (Umum) --</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $kerjasama->prodi_id) == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Periode & Dokumen --}}
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Waktu & Berkas (MoU/MoA)</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                    value="{{ old('tanggal_mulai', $kerjasama->tanggal_mulai?->format('Y-m-d')) }}" required>
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                    value="{{ old('tanggal_selesai', $kerjasama->tanggal_selesai?->format('Y-m-d')) }}">
                <div class="form-text">Kosongkan jika tidak ada batas waktu.</div>
                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Dokumen MoU / MoA (PDF)</label>
                <input type="file" name="dokumen_mou" class="form-control @error('dokumen_mou') is-invalid @enderror" accept=".pdf">
                @if($kerjasama->dokumen_mou)
                    <div class="form-text text-success mt-1">
                        <i class="bi bi-check-circle me-1"></i>Dokumen sudah diunggah. <br>
                        <a href="{{ asset('storage/'.$kerjasama->dokumen_mou) }}" target="_blank">Lihat Dokumen Saat Ini</a>
                    </div>
                @endif
                <div class="form-text">Maksimal 10MB. Kosongkan jika tidak ingin mengubah.</div>
                @error('dokumen_mou')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-12">
                <label class="form-label fw-semibold">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $kerjasama->keterangan) }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

