<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-check2-square text-primary me-2"></i>Tindak Lanjut</h6>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex gap-1">
                <span class="badge bg-secondary">Belum: {{ $ringkasanTL['belum_mulai'] }}</span>
                <span class="badge bg-primary">Proses: {{ $ringkasanTL['dalam_proses'] }}</span>
                <span class="badge bg-success">Selesai: {{ $ringkasanTL['selesai'] }}</span>
            </div>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTL">
                <i class="bi bi-plus-lg me-1"></i>Tambah
            </button>
            @endif
        </div>
    </div>

    <div class="card-body p-0">
        @forelse($rapat->tindakLanjuts as $tl)
        <div class="border-bottom px-4 py-3 {{ $tl->is_overdue ? 'bg-danger bg-opacity-5' : '' }}">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        {!! $tl->prioritas_badge !!}
                        {!! $tl->status_badge !!}
                        @if($tl->is_overdue)
                        <span class="badge bg-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
                        </span>
                        @endif
                    </div>
                    <p class="mb-1 fw-semibold small">{{ $tl->deskripsi }}</p>
                    <div class="text-muted" style="font-size:.8rem">
                        <i class="bi bi-person me-1"></i>PIC: <strong>{{ $tl->pic?->name ?? '-' }}</strong>
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar me-1"></i>Deadline:
                        <strong class="{{ $tl->is_overdue ? 'text-danger' : '' }}">
                            {{ $tl->deadline->format('d M Y') }}
                        </strong>
                        @if($tl->tanggal_selesai_aktual)
                        &nbsp;·&nbsp;
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Selesai: {{ $tl->tanggal_selesai_aktual->format('d M Y') }}
                        @endif
                    </div>
                    @if($tl->catatan_progres)
                    <div class="bg-light rounded p-2 mt-1 small border-start border-3 border-info">
                        <i class="bi bi-chat-left-text me-1 text-info"></i>{{ $tl->catatan_progres }}
                    </div>
                    @endif
                </div>

                {{-- Tombol update status --}}
                @php
                    $canUpdateTL = auth()->user()->isSuperAdmin()
                        || auth()->user()->isPimpinan()
                        || $tl->pic_id === auth()->id();
                @endphp
                @if($canUpdateTL && !in_array($tl->status, ['selesai','dibatalkan']))
                <button class="btn btn-outline-secondary btn-sm flex-shrink-0"
                    data-bs-toggle="modal"
                    data-bs-target="#modalUpdateTL{{ $tl->id }}">
                    <i class="bi bi-pencil-square me-1"></i>Update
                </button>
                @endif
            </div>
        </div>

        {{-- Modal Update Status TL --}}
        @if($canUpdateTL && !in_array($tl->status, ['selesai','dibatalkan']))
        <div class="modal fade" id="modalUpdateTL{{ $tl->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('rapat.tl.update', [$rapat, $tl]) }}">
                        @csrf @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Update Tindak Lanjut</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small mb-3">{{ $tl->deskripsi }}</p>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="belum_mulai"  {{ $tl->status=='belum_mulai'?'selected':'' }}>Belum Mulai</option>
                                    <option value="dalam_proses" {{ $tl->status=='dalam_proses'?'selected':'' }}>Dalam Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan Progres</label>
                                <textarea name="catatan_progres" class="form-control" rows="3"
                                    maxlength="500"
                                    placeholder="Tambahkan catatan perkembangan...">{{ $tl->catatan_progres }}</textarea>
                                <div class="form-text">Maks. 500 karakter</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-check2-square fs-1 d-block mb-2 opacity-25"></i>
            <p class="mb-0">Belum ada tindak lanjut.</p>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
            <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahTL">
                <i class="bi bi-plus-lg me-1"></i>Tambah Tindak Lanjut
            </button>
            @endif
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Tindak Lanjut --}}
@if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
<div class="modal fade" id="modalTambahTL" tabindex="-1"
    @if($errors->has('deskripsi') || $errors->has('pic_id') || $errors->has('deadline')) data-show="true" @endif>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('rapat.tl.store', $rapat) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Tindak Lanjut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Tindakan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                            rows="3" maxlength="1000" required
                            placeholder="Tuliskan tindak lanjut yang harus dilakukan...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">PIC (Penanggung Jawab) <span class="text-danger">*</span></label>
                            <select name="pic_id" class="form-select @error('pic_id') is-invalid @enderror" required>
                                <option value="">-- Pilih PIC --</option>
                                @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('pic_id')==$u->id?'selected':'' }}>
                                    {{ $u->name }}
                                    @if($u->roles->isNotEmpty()) ({{ $u->roles->first()->display_name ?? $u->roles->first()->name }})@endif
                                </option>
                                @endforeach
                            </select>
                            @error('pic_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="deadline"
                                class="form-control @error('deadline') is-invalid @enderror"
                                value="{{ old('deadline') }}"
                                min="{{ date('Y-m-d') }}" required>
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Prioritas <span class="text-danger">*</span></label>
                            <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                <option value="Tinggi" {{ old('prioritas')=='Tinggi'?'selected':'' }}>🔴 Tinggi</option>
                                <option value="Sedang" {{ old('prioritas','Sedang')=='Sedang'?'selected':'' }}>🟡 Sedang</option>
                                <option value="Rendah" {{ old('prioritas')=='Rendah'?'selected':'' }}>🟢 Rendah</option>
                            </select>
                            @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Tindak Lanjut
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
