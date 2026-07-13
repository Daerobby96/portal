@php $pengabdian = $pengabdian ?? new \Modules\Tridharma\Models\Pengabdian(); @endphp
<div class="row g-4">
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Kegiatan PkM</h6>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Judul Kegiatan PkM <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $pengabdian->judul ?? '') }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Ketua Pengabdi</label>
                <select name="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $d)
                        <option value="{{ $d->id }}" {{ old('pegawai_id', $pengabdian->pegawai_id ?? '') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama }} {{ $d->nip ? '('.$d->nip.')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Program Studi</label>
                <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id', $pengabdian->prodi_id ?? '') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                    value="{{ old('tahun', $pengabdian->tahun ?? date('Y')) }}" min="2000" max="2100" required>
                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Mitra / Institusi Mitra</label>
                <input type="text" name="mitra" class="form-control @error('mitra') is-invalid @enderror"
                    value="{{ old('mitra', $pengabdian->mitra ?? '') }}"
                    placeholder="Nama instansi/perusahaan mitra">
                @error('mitra')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Lokasi Kegiatan</label>
                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                    value="{{ old('lokasi', $pengabdian->lokasi ?? '') }}"
                    placeholder="Desa/Kota/Provinsi lokasi PkM">
                @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Sumber Dana</label>
                <input type="text" name="sumber_dana" class="form-control @error('sumber_dana') is-invalid @enderror"
                    value="{{ old('sumber_dana', $pengabdian->sumber_dana ?? '') }}"
                    placeholder="DIPA, Mandiri, CSR, dll.">
                @error('sumber_dana')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jumlah Dana (Rp)</label>
                <input type="number" name="jumlah_dana" class="form-control @error('jumlah_dana') is-invalid @enderror"
                    value="{{ old('jumlah_dana', $pengabdian->jumlah_dana ?? '') }}" min="0">
                @error('jumlah_dana')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Anggota Tim</label>
                <input type="text" name="anggota" class="form-control @error('anggota') is-invalid @enderror"
                    value="{{ old('anggota', $pengabdian->anggota ?? '') }}"
                    placeholder="Pisahkan nama anggota dengan titik koma (;)">
                @error('anggota')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

