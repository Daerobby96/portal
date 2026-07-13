@php $penelitian = $penelitian ?? new \Modules\Tridharma\Models\Penelitian(); @endphp
<div class="row g-4">
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Penelitian</h6>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Judul Penelitian <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $penelitian->judul ?? '') }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Ketua Peneliti</label>
                <select name="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $d)
                        <option value="{{ $d->id }}" {{ old('pegawai_id', $penelitian->pegawai_id ?? '') == $d->id ? 'selected' : '' }}>
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
                        <option value="{{ $p->id }}" {{ old('prodi_id', $penelitian->prodi_id ?? '') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun Penelitian <span class="text-danger">*</span></label>
                <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                    value="{{ old('tahun', $penelitian->tahun ?? date('Y')) }}" min="2000" max="2100" required>
                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Anggota Peneliti</label>
                <input type="text" name="anggota" class="form-control @error('anggota') is-invalid @enderror"
                    value="{{ old('anggota', $penelitian->anggota ?? '') }}"
                    placeholder="Contoh: Dr. Siti Aisyah, M.Si; Ahmad Fauzi, M.Kom">
                <div class="form-text">Pisahkan nama anggota dengan titik koma (;)</div>
                @error('anggota')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Sumber & Pendanaan</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sumber Dana</label>
                <input type="text" name="sumber_dana" class="form-control @error('sumber_dana') is-invalid @enderror"
                    value="{{ old('sumber_dana', $penelitian->sumber_dana ?? '') }}"
                    placeholder="Contoh: DIPA, Mandiri, BRIN, dll.">
                @error('sumber_dana')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Jumlah Dana (Rp)</label>
                <input type="number" name="jumlah_dana" class="form-control @error('jumlah_dana') is-invalid @enderror"
                    value="{{ old('jumlah_dana', $penelitian->jumlah_dana ?? '') }}"
                    min="0" placeholder="Contoh: 50000000">
                @error('jumlah_dana')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                <select name="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                    @foreach(['Lokal', 'Nasional', 'Internasional'] as $t)
                        <option value="{{ $t }}" {{ old('tingkat', $penelitian->tingkat ?? 'Lokal') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('tingkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(['Usulan', 'Berjalan', 'Selesai'] as $s)
                        <option value="{{ $s }}" {{ old('status', $penelitian->status ?? 'Selesai') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

