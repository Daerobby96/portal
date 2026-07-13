@php $mahasiswa = $mahasiswa ?? new \App\Models\Mahasiswa(); @endphp
<div class="row g-4">
    {{-- Section Identitas --}}
    <div class="col-12">
        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Informasi Identitas</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                <input type="text" name="nim" class="form-control font-monospace @error('nim') is-invalid @enderror" 
                    value="{{ old('nim', $mahasiswa->nim ?? '') }}" required>
                @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                    value="{{ old('nama', $mahasiswa->nama ?? '') }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Nomor HP</label>
                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
                    value="{{ old('no_hp', $mahasiswa->no_hp ?? '') }}">
                @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', $mahasiswa->email ?? '') }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Section Akademik --}}
    <div class="col-12 mt-5">
        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Informasi Akademik</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Program Studi</label>
                <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror">
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} ({{ $p->jenjang }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Angkatan</label>
                <input type="number" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror" 
                    value="{{ old('angkatan', $mahasiswa->angkatan ?? date('Y')) }}" min="2000" max="2100">
                @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Semester Saat Ini</label>
                <input type="number" name="semester_berjalan" class="form-control @error('semester_berjalan') is-invalid @enderror" 
                    value="{{ old('semester_berjalan', $mahasiswa->semester_berjalan ?? '') }}" min="1" max="14" placeholder="Otomatis dihitung jika kosong">
                <div class="form-text">Biarkan kosong agar dihitung otomatis dari Tahun Angkatan.</div>
                @error('semester_berjalan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Jalur Masuk</label>
                <select name="jalur_masuk" class="form-select @error('jalur_masuk') is-invalid @enderror">
                    <option value="">-- Pilih Jalur --</option>
                    @foreach($jalurOptions as $jalur)
                        <option value="{{ $jalur }}" {{ old('jalur_masuk', $mahasiswa->jalur_masuk ?? '') == $jalur ? 'selected' : '' }}>
                            {{ $jalur }}
                        </option>
                    @endforeach
                </select>
                @error('jalur_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Status Mahasiswa <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" id="statusSelect" required>
                    @foreach($statusOptions as $k => $v)
                        <option value="{{ $k }}" {{ old('status', $mahasiswa->status ?? 'aktif') == $k ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">IPK Terakhir</label>
                <input type="number" name="ipk" class="form-control font-monospace @error('ipk') is-invalid @enderror" 
                    value="{{ old('ipk', $mahasiswa->ipk ?? '') }}" step="0.01" min="0" max="4" placeholder="Misal: 3.75">
                @error('ipk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Section Kelulusan (Tampil jika status == lulus) --}}
    <div class="col-12 mt-5" id="kelulusanSection" style="display: none;">
        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Informasi Kelulusan</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                    value="{{ old('tanggal_masuk', $mahasiswa->tanggal_masuk?->format('Y-m-d') ?? '') }}">
                @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Lulus</label>
                <input type="date" name="tanggal_lulus" class="form-control @error('tanggal_lulus') is-invalid @enderror" 
                    value="{{ old('tanggal_lulus', $mahasiswa->tanggal_lulus?->format('Y-m-d') ?? '') }}">
                @error('tanggal_lulus')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Masa Studi (Bulan)</label>
                <input type="number" name="masa_studi_bulan" class="form-control @error('masa_studi_bulan') is-invalid @enderror" 
                    value="{{ old('masa_studi_bulan', $mahasiswa->masa_studi_bulan ?? '') }}" placeholder="Dihitung otomatis jika kosong">
                <div class="form-text">Biarkan kosong agar dihitung otomatis dari tanggal.</div>
                @error('masa_studi_bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Section Keterangan --}}
    <div class="col-12 mt-5">
        <label class="form-label fw-semibold">Keterangan / Catatan</label>
        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $mahasiswa->keterangan ?? '') }}</textarea>
        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('statusSelect');
        const kelulusanSection = document.getElementById('kelulusanSection');

        function toggleKelulusan() {
            if (statusSelect.value === 'lulus') {
                kelulusanSection.style.display = 'block';
            } else {
                kelulusanSection.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', toggleKelulusan);
        toggleKelulusan(); // run on load
    });
</script>
@endpush
