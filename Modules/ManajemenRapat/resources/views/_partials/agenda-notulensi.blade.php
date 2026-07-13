<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-list-ol text-primary me-2"></i>Agenda Rapat</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">Total estimasi: {{ $rapat->total_durasi }} menit</span>
            @if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !in_array($rapat->status, ['selesai','dibatalkan']))
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAgenda">
                <i class="bi bi-plus-lg me-1"></i>Tambah Agenda
            </button>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @forelse($rapat->agendas as $agenda)
        <div class="border-bottom px-4 py-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div class="d-flex gap-3 flex-grow-1">
                    <span class="badge bg-primary d-flex align-items-center justify-content-center fw-bold"
                        style="width:28px;height:28px;flex-shrink:0;border-radius:50%;font-size:.8rem">{{ $agenda->urutan }}</span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $agenda->judul }}</div>
                        @if($agenda->deskripsi)
                            <p class="text-muted small mb-1">{{ $agenda->deskripsi }}</p>
                        @endif
                        <span class="badge bg-light text-dark border small">
                            <i class="bi bi-clock me-1"></i>{{ $agenda->estimasi_durasi }} menit
                        </span>

                        {{-- Notulensi --}}
                        @if(in_array($rapat->status, ['berlangsung','selesai']))
                        <div class="mt-2">
                            @if($agenda->notulensi)
                                <div class="bg-light rounded p-2 small mb-1" style="white-space:pre-wrap">{{ $agenda->notulensi }}</div>
                                <small class="text-muted">
                                    Diperbarui oleh {{ $agenda->notulensiUpdatedBy?->name ?? '-' }}
                                    {{ $agenda->notulensi_updated_at?->diffForHumans() }}
                                </small>
                            @endif

                            @php $canEditNotulensi = auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()
                                || $rapat->peserta->where('user_id', auth()->id())->where('peran','Notulis')->isNotEmpty();
                            @endphp

                            @if($canEditNotulensi && ($rapat->status !== 'selesai' || auth()->user()->isSuperAdmin()))
                            <button class="btn btn-outline-secondary btn-sm mt-1"
                                data-bs-toggle="modal"
                                data-bs-target="#modalNotulensi{{ $agenda->id }}">
                                <i class="bi bi-pencil me-1"></i>{{ $agenda->notulensi ? 'Edit' : 'Isi' }} Notulensi
                            </button>

                            {{-- Modal Notulensi per Agenda --}}
                            <div class="modal fade" id="modalNotulensi{{ $agenda->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('rapat.agenda.notulensi', [$rapat, $agenda]) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Notulensi: {{ $agenda->judul }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <textarea name="notulensi" class="form-control" rows="8"
                                                    placeholder="Tulis notulensi pembahasan agenda ini...">{{ $agenda->notulensi }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save me-1"></i>Simpan Notulensi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                @if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !in_array($rapat->status, ['selesai','dibatalkan']))
                <form method="POST" action="{{ route('rapat.agenda.destroy', [$rapat, $agenda]) }}"
                    onsubmit="return confirm('Hapus agenda ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Agenda">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-list-ol fs-1 d-block mb-2 opacity-25"></i>
            <p class="mb-0">Belum ada agenda.</p>
            @if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !in_array($rapat->status, ['selesai','dibatalkan']))
            <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahAgenda">
                <i class="bi bi-plus-lg me-1"></i>Tambah Agenda Pertama
            </button>
            @endif
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Agenda --}}
@if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !in_array($rapat->status, ['selesai','dibatalkan']))
<div class="modal fade" id="modalTambahAgenda" tabindex="-1"
    @if($errors->has('judul') || $errors->has('estimasi_durasi')) data-show="true" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rapat.agenda.store', $rapat) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Agenda <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul') }}" maxlength="255" required
                            placeholder="contoh: Pembukaan dan Sambutan">
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estimasi Durasi (menit) <span class="text-danger">*</span></label>
                        <input type="number" name="estimasi_durasi" class="form-control @error('estimasi_durasi') is-invalid @enderror"
                            value="{{ old('estimasi_durasi', 30) }}" min="1" max="999" required>
                        @error('estimasi_durasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                            placeholder="Deskripsi singkat agenda...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Agenda
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
