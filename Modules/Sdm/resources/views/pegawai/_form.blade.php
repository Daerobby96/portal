<div class="row g-3">
    {{-- Nama --}}
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
            value="{{ old('nama', $pegawai->nama ?? '') }}" maxlength="255" required
            placeholder="Nama lengkap pegawai">
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- NIP --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">NIP / NIK</label>
        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
            value="{{ old('nip', $pegawai->nip ?? '') }}" maxlength="50"
            placeholder="Nomor Induk Pegawai">
        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Jenis Pegawai --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">Jenis Pegawai <span class="text-danger">*</span></label>
        <select name="jenis_pegawai" class="form-select @error('jenis_pegawai') is-invalid @enderror" required>
            @foreach(\Modules\Sdm\Models\Pegawai::jenisOptions() as $k => $v)
            <option value="{{ $k }}"
                {{ old('jenis_pegawai', $pegawai->jenis_pegawai ?? 'Lainnya') == $k ? 'selected' : '' }}>
                {{ $v }}
            </option>
            @endforeach
        </select>
        @error('jenis_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Status Kepegawaian --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">Status Kepegawaian</label>
        <select name="status_kepegawaian" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach(\Modules\Sdm\Models\Pegawai::statusOptions() as $s)
            <option value="{{ $s }}"
                {{ old('status_kepegawaian', $pegawai->status_kepegawaian ?? '') == $s ? 'selected' : '' }}>
                {{ $s }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Jabatan --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">Jabatan</label>
        <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror"
            value="{{ old('jabatan', $pegawai->jabatan ?? '') }}" maxlength="255"
            placeholder="contoh: Dosen Tetap, Kabag, Staff">
        @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Unit Kerja --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Unit Kerja / Prodi / Bagian</label>
        <input type="text" name="unit_kerja" class="form-control @error('unit_kerja') is-invalid @enderror"
            value="{{ old('unit_kerja', $pegawai->unit_kerja ?? '') }}" maxlength="255"
            placeholder="contoh: Prodi Teknik Informatika, BAAK, LP3M">
        @error('unit_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $pegawai->email ?? '') }}" maxlength="255"
            placeholder="email@institusi.ac.id">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- No HP --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">No. HP / WA</label>
        <input type="text" name="no_hp" class="form-control"
            value="{{ old('no_hp', $pegawai->no_hp ?? '') }}" maxlength="50"
            placeholder="08xx-xxxx-xxxx">
    </div>

    {{-- Hubungkan ke User Sistem --}}
    <div class="col-12">
        <hr class="my-2">
        <label class="form-label fw-semibold">
            Hubungkan ke Akun Pengguna Sistem
            <span class="text-muted fw-normal">(opsional)</span>
        </label>
        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
            <option value="">-- Tidak dihubungkan --</option>
            @foreach($users as $u)
            <option value="{{ $u->id }}"
                {{ old('user_id', $pegawai->user_id ?? '') == $u->id ? 'selected' : '' }}>
                {{ $u->name }} ({{ $u->email }})
            </option>
            @endforeach
        </select>
        <div class="form-text">
            Jika pegawai ini juga punya akun di sistem SPMI, hubungkan agar bisa menerima notifikasi.
        </div>
        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Status Aktif (edit only) --}}
    @isset($pegawai)
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_aktif" value="1"
                id="isAktif" {{ old('is_aktif', $pegawai->is_aktif) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="isAktif">Pegawai Aktif</label>
        </div>
    </div>
    @endisset
</div>

