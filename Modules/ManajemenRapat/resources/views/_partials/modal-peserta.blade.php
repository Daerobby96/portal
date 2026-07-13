@if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !$rapat->isLocked())
<div class="modal fade" id="modalTambahPeserta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus text-primary me-2"></i>Tambah Peserta Rapat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- ── Live Search ────────────────────────────────────────── --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cari Peserta</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="pesertaSearch" class="form-control border-start-0 ps-0"
                            placeholder="Ketik nama, NIP, jabatan, atau unit kerja..."
                            autocomplete="off">
                        <button type="button" class="btn btn-outline-secondary" id="btnClearSearch" style="display:none">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="form-text">Cari dari pengguna sistem. Jika tidak ditemukan, isi form manual di bawah.</div>
                </div>

                {{-- ── Hasil Pencarian ─────────────────────────────────────── --}}
                <div id="searchResults" class="mb-3" style="display:none">
                    <div class="border rounded overflow-hidden" id="searchList">
                        {{-- Diisi oleh JS --}}
                    </div>
                </div>

                <div id="searchEmpty" class="text-center text-muted py-3 mb-3" style="display:none">
                    <i class="bi bi-person-x fs-3 d-block mb-1 opacity-25"></i>
                    <small>Tidak ditemukan di sistem. Gunakan form di bawah untuk isi manual.</small>
                </div>

                {{-- ── Form Utama ──────────────────────────────────────────── --}}
                <form method="POST" action="{{ route('rapat.peserta.store', $rapat) }}" id="formPeserta">
                    @csrf
                    <input type="hidden" name="tipe_peserta" id="tipePeserta" value="eksternal">
                    <input type="hidden" name="user_id" id="fieldUserId" value="">
                    <input type="hidden" name="pegawai_id" id="fieldPegawaiId" value="">

                    <hr class="my-3">
                    <h6 class="text-muted small fw-bold text-uppercase mb-3" id="formTitle">
                        <i class="bi bi-pencil-square me-1"></i>Detail Peserta
                    </h6>

                    <div class="row g-3">
                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama_eksternal" id="fieldNama"
                                class="form-control @error('nama_eksternal') is-invalid @enderror"
                                value="{{ old('nama_eksternal') }}" maxlength="255" required
                                placeholder="Nama lengkap">
                            @error('nama_eksternal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Instansi --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instansi / Unit Kerja</label>
                            <input type="text" name="instansi" id="fieldInstansi"
                                class="form-control"
                                value="{{ old('instansi') }}" maxlength="255"
                                placeholder="contoh: BAAK, Prodi Teknik, PT XYZ">
                        </div>

                        {{-- Jabatan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" name="jabatan_eksternal" id="fieldJabatan"
                                class="form-control"
                                value="{{ old('jabatan_eksternal') }}" maxlength="255"
                                placeholder="contoh: Dosen, Kabag, Dekan">
                        </div>

                        {{-- Peran --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Peran <span class="text-danger">*</span></label>
                            <select name="peran" id="fieldPeran" class="form-select" required>
                                <option value="Peserta" {{ old('peran','Peserta')=='Peserta'?'selected':'' }}>Peserta</option>
                                <option value="Ketua"   {{ old('peran')=='Ketua'?'selected':'' }}>Ketua</option>
                                <option value="Notulis" {{ old('peran')=='Notulis'?'selected':'' }}>Notulis</option>
                            </select>
                        </div>

                        {{-- Kehadiran awal --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status Awal</label>
                            <select name="status_kehadiran_awal" class="form-select">
                                <option value="diundang" selected>Diundang</option>
                                <option value="hadir">Hadir</option>
                            </select>
                        </div>

                        {{-- Email (opsional) --}}
                        <div class="col-md-6" id="rowEmail">
                            <label class="form-label fw-semibold">Email <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="email" name="email_eksternal" id="fieldEmail"
                                class="form-control"
                                value="{{ old('email_eksternal') }}" maxlength="255"
                                placeholder="email@instansi.ac.id">
                        </div>

                        {{-- No HP (opsional) --}}
                        <div class="col-md-6" id="rowHp">
                            <label class="form-label fw-semibold">No. HP <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="text" name="no_hp_eksternal" id="fieldHp"
                                class="form-control"
                                value="{{ old('no_hp_eksternal') }}" maxlength="50"
                                placeholder="08xx-xxxx-xxxx">
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="text" name="keterangan" class="form-control"
                                value="{{ old('keterangan') }}" maxlength="500"
                                placeholder="Catatan tambahan...">
                        </div>
                    </div>

                    {{-- Tag info peserta terpilih --}}
                    <div id="selectedUserTag" class="mt-3" style="display:none">
                        <div class="alert alert-success py-2 mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-person-check-fill text-success fs-5"></i>
                            <div class="flex-grow-1">
                                <span class="fw-semibold" id="selectedUserName"></span>
                                <small class="text-muted d-block" id="selectedUserInfo"></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnClearUser">
                                <i class="bi bi-x-lg"></i> Ganti
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitPeserta">
                            <i class="bi bi-person-check me-1"></i>Tambahkan Peserta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const searchInput  = document.getElementById('pesertaSearch');
    const searchResult = document.getElementById('searchResults');
    const searchList   = document.getElementById('searchList');
    const searchEmpty  = document.getElementById('searchEmpty');
    const btnClear     = document.getElementById('btnClearSearch');
    const btnClearUser = document.getElementById('btnClearUser');
    const selectedTag  = document.getElementById('selectedUserTag');
    const formTitle    = document.getElementById('formTitle');

    // Form fields
    const tipePeserta  = document.getElementById('tipePeserta');
    const fieldUserId  = document.getElementById('fieldUserId');
    const fieldPegawaiId = document.getElementById('fieldPegawaiId');
    const fieldNama    = document.getElementById('fieldNama');
    const fieldInstansi= document.getElementById('fieldInstansi');
    const fieldJabatan = document.getElementById('fieldJabatan');
    const fieldEmail   = document.getElementById('fieldEmail');
    const fieldHp      = document.getElementById('fieldHp');
    const rowEmail     = document.getElementById('rowEmail');
    const rowHp        = document.getElementById('rowHp');

    let searchTimeout;
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const searchUrl = '{{ route("sdm.pegawai.search") }}';

    // ── Live search ──────────────────────────────────────────────
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        btnClear.style.display = q ? 'block' : 'none';

        if (q.length < 2) {
            hideResults();
            return;
        }

        searchTimeout = setTimeout(() => fetchResults(q), 300);
    });

    btnClear.addEventListener('click', function () {
        searchInput.value = '';
        btnClear.style.display = 'none';
        hideResults();
        searchInput.focus();
    });

    function hideResults() {
        searchResult.style.display = 'none';
        searchEmpty.style.display  = 'none';
    }

    function fetchResults(q) {
        fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, {
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                searchResult.style.display = 'none';
                searchEmpty.style.display  = 'block';
                return;
            }
            searchEmpty.style.display  = 'none';
            searchResult.style.display = 'block';
            renderResults(data);
        })
        .catch(() => hideResults());
    }

    function renderResults(users) {
        searchList.innerHTML = '';
        users.forEach(u => {
            const item = document.createElement('div');
            item.className = 'px-3 py-2 border-bottom d-flex align-items-center gap-3 search-item';
            item.style.cursor = 'pointer';
            
            // Build info line with available data
            let infoLine = '';
            if (u.jenis || u.role) {
                infoLine += `<span class="badge bg-light text-dark border me-1" style="font-size:.6rem">${escHtml(u.jenis || u.role)}</span>`;
            }
            if (u.jabatan) {
                infoLine += escHtml(u.jabatan);
            }
            if (u.unit_kerja) {
                infoLine += (u.jabatan ? ' · ' : '') + escHtml(u.unit_kerja);
            }
            if (u.nip) {
                infoLine += `<br><span class="font-monospace">NIP: ${escHtml(u.nip)}</span>`;
            }
            if (u.email) {
                infoLine += (u.nip ? ' · ' : '<br>') + `<i class="bi bi-envelope"></i> ${escHtml(u.email)}`;
            }
            if (u.no_hp) {
                infoLine += ` · <i class="bi bi-phone"></i> ${escHtml(u.no_hp)}`;
            }
            
            item.innerHTML = `
                <div class="rounded-circle bg-primary text-white d-flex align-items-center
                    justify-content-center fw-bold flex-shrink-0"
                    style="width:36px;height:36px;font-size:.85rem">
                    ${u.name.charAt(0).toUpperCase()}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold small">${escHtml(u.name)}</div>
                    <div class="text-muted" style="font-size:.73rem">
                        ${infoLine}
                    </div>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Pilih</span>`;

            item.addEventListener('mouseenter', () => item.classList.add('bg-light'));
            item.addEventListener('mouseleave', () => item.classList.remove('bg-light'));
            item.addEventListener('click', () => selectUser(u));
            searchList.appendChild(item);
        });
    }

    function selectUser(u) {
        // Set hidden fields
        tipePeserta.value = u.user_id ? 'internal' : 'eksternal';
        fieldUserId.value = u.user_id || '';
        fieldPegawaiId.value = u.pegawai_id || '';

        // Populate ALL form fields (auto-fill semua data)
        fieldNama.value     = u.name;
        fieldNama.readOnly  = true;
        fieldInstansi.value = u.unit_kerja || '';
        fieldInstansi.readOnly = true;
        fieldJabatan.value  = u.jabatan || '';
        fieldJabatan.readOnly = true;
        fieldEmail.value    = u.email || '';
        fieldEmail.readOnly = true;
        fieldHp.value       = u.no_hp || '';
        fieldHp.readOnly    = true;

        // Tetap tampilkan email/hp (hanya readonly, tidak perlu disembunyikan)
        rowEmail.style.display = '';
        rowHp.style.display    = '';

        // Show selected tag
        document.getElementById('selectedUserName').textContent = u.name;
        document.getElementById('selectedUserInfo').textContent =
            [u.jenis || u.role, u.jabatan, u.unit_kerja].filter(Boolean).join(' · ');
        selectedTag.style.display = 'block';

        // Update form title
        if (u.source === 'pegawai') {
            formTitle.innerHTML = '<i class="bi bi-person-check-fill text-success me-1"></i>Data dari Pegawai';
        } else {
            formTitle.innerHTML = '<i class="bi bi-person-check-fill text-success me-1"></i>Data dari Pengguna';
        }
        formTitle.classList.add('text-success');

        // Hide search
        searchResult.style.display = 'none';
        searchInput.value = '';
        btnClear.style.display = 'none';
    }

    function clearUser() {
        tipePeserta.value   = 'eksternal';
        fieldUserId.value   = '';
        fieldPegawaiId.value = '';

        fieldNama.value     = '';
        fieldNama.readOnly  = false;
        fieldInstansi.value = '';
        fieldInstansi.readOnly = false;
        fieldJabatan.value  = '';
        fieldJabatan.readOnly = false;
        fieldEmail.value    = '';
        fieldEmail.readOnly = false;
        fieldHp.value       = '';
        fieldHp.readOnly    = false;

        rowEmail.style.display = '';
        rowHp.style.display    = '';

        selectedTag.style.display = 'none';
        formTitle.innerHTML = '<i class="bi bi-pencil-square me-1"></i>Detail Peserta';
        formTitle.classList.remove('text-success');

        searchInput.focus();
    }

    btnClearUser.addEventListener('click', clearUser);

    // Reset modal saat ditutup
    document.getElementById('modalTambahPeserta')?.addEventListener('hidden.bs.modal', function () {
        clearUser();
        searchInput.value = '';
        btnClear.style.display = 'none';
        hideResults();
        document.getElementById('formPeserta').reset();
    });

    function escHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
})();
</script>
@endpush
@endif
