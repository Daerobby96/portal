@php $prestasi = $prestasi ?? new \App\Models\Prestasi(); @endphp
<div class="row g-4">
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Mahasiswa</h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Pilih Mahasiswa <span class="text-danger">*</span></label>
                <select name="mahasiswa_id" class="form-select @error('mahasiswa_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($mahasiswas as $mhs)
                        <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $prestasi->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
                @error('mahasiswa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Detail Kegiatan & Prestasi</h6>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Nama Kegiatan / Kompetisi <span class="text-danger">*</span></label>
                <input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" 
                    value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" required placeholder="Misal: Gemastik 2024">
                @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Penyelenggara</label>
                <input type="text" name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror" 
                    value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" placeholder="Misal: Puspresnas Kemdikbud">
                @error('penyelenggara')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tahun Kegiatan <span class="text-danger">*</span></label>
                <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" 
                    value="{{ old('tahun', $prestasi->tahun ?? date('Y')) }}" required min="2000" max="{{ date('Y')+1 }}">
                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Prestasi <span class="text-danger">*</span></label>
                <select name="jenis_prestasi" class="form-select @error('jenis_prestasi') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(\App\Models\Prestasi::JENIS_PRESTASI as $jp)
                        <option value="{{ $jp }}" {{ old('jenis_prestasi', $prestasi->jenis_prestasi) == $jp ? 'selected' : '' }}>{{ $jp }}</option>
                    @endforeach
                </select>
                @error('jenis_prestasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                    <option value="">-- Pilih Tingkat --</option>
                    @foreach(\App\Models\Prestasi::TINGKAT as $tk)
                        <option value="{{ $tk }}" {{ old('tingkat', $prestasi->tingkat) == $tk ? 'selected' : '' }}>{{ $tk }}</option>
                    @endforeach
                </select>
                @error('tingkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Peringkat / Capaian</label>
                <input type="text" name="peringkat" class="form-control @error('peringkat') is-invalid @enderror" 
                    value="{{ old('peringkat', $prestasi->peringkat) }}" placeholder="Misal: Juara 1, Medali Emas, dll">
                @error('peringkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-3">Bukti & Dokumen</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Upload Sertifikat (PDF)</label>
                <input type="file" name="sertifikat" class="form-control @error('sertifikat') is-invalid @enderror" accept=".pdf">
                @if($prestasi->sertifikat)
                    <div class="form-text text-success mt-1">
                        <i class="bi bi-check-circle me-1"></i>Sertifikat sudah diunggah. <a href="{{ asset('storage/'.$prestasi->sertifikat) }}" target="_blank">Lihat Dokumen</a>
                    </div>
                @endif
                <div class="form-text">Maksimal 5MB. Kosongkan jika tidak ingin mengubah.</div>
                @error('sertifikat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label fw-semibold">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $prestasi->keterangan) }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>
