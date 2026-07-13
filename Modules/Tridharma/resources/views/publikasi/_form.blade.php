@php $publikasi = $publikasi ?? new \Modules\Tridharma\Models\Publikasi(); @endphp
<div class="row g-4">
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Informasi Publikasi</h6>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Judul Publikasi <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $publikasi->judul ?? '') }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-5">
                <label class="form-label fw-semibold">Penulis Utama / Koresponden</label>
                <select name="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $d)
                        <option value="{{ $d->id }}" {{ old('pegawai_id', $publikasi->pegawai_id ?? '') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama }} {{ $d->nip ? '('.$d->nip.')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Publikasi <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                    @foreach(['Jurnal Nasional', 'Jurnal Internasional', 'Prosiding', 'Buku', 'HKI', 'Lainnya'] as $j)
                        <option value="{{ $j }}" {{ old('jenis', $publikasi->jenis ?? 'Jurnal Nasional') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                    value="{{ old('tahun', $publikasi->tahun ?? date('Y')) }}" min="2000" max="2100" required>
                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Detail Jurnal / Penerbit</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Jurnal / Penerbit</label>
                <input type="text" name="nama_jurnal_penerbit" class="form-control @error('nama_jurnal_penerbit') is-invalid @enderror"
                    value="{{ old('nama_jurnal_penerbit', $publikasi->nama_jurnal_penerbit ?? '') }}"
                    placeholder="Nama jurnal, prosiding, atau penerbit buku">
                @error('nama_jurnal_penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Volume / Nomor / Halaman</label>
                <input type="text" name="volume_nomor" class="form-control @error('volume_nomor') is-invalid @enderror"
                    value="{{ old('volume_nomor', $publikasi->volume_nomor ?? '') }}"
                    placeholder="Contoh: Vol. 5, No. 2, Hal. 10-20">
                @error('volume_nomor')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Level / Sinta / Scopus</label>
                <select name="tingkat_sinta" class="form-select @error('tingkat_sinta') is-invalid @enderror">
                    <option value="">-- Pilih Level --</option>
                    @foreach(['Sinta 1', 'Sinta 2', 'Sinta 3', 'Sinta 4', 'Sinta 5', 'Sinta 6', 'Scopus Q1', 'Scopus Q2', 'Scopus Q3', 'Scopus Q4', 'WoS', 'Lainnya'] as $sl)
                        <option value="{{ $sl }}" {{ old('tingkat_sinta', $publikasi->tingkat_sinta ?? '') == $sl ? 'selected' : '' }}>{{ $sl }}</option>
                    @endforeach
                </select>
                @error('tingkat_sinta')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-9">
                <label class="form-label fw-semibold">URL / DOI Tautan Publikasi</label>
                <input type="url" name="url_tautan" class="form-control @error('url_tautan') is-invalid @enderror"
                    value="{{ old('url_tautan', $publikasi->url_tautan ?? '') }}"
                    placeholder="https://doi.org/... atau link ke jurnal">
                @error('url_tautan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Program Studi</label>
                <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id', $publikasi->prodi_id ?? '') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

