<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-paperclip text-primary me-2"></i>Lampiran</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border">{{ $rapat->lampirans->count() }}/20</span>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUploadLampiran">
                <i class="bi bi-upload me-1"></i>Upload
            </button>
            @endif
        </div>
    </div>

    <div class="list-group list-group-flush">
        @forelse($rapat->lampirans as $lp)
        <div class="list-group-item px-3 py-2 d-flex align-items-center gap-2">
            <i class="bi {{ $lp->icon }} fs-5 flex-shrink-0"></i>
            <div class="flex-grow-1 min-w-0">
                <div class="small fw-semibold text-truncate">{{ $lp->nama_asli }}</div>
                <div class="text-muted" style="font-size:.72rem">
                    {{ $lp->ukuran_format }}
                    · diupload oleh {{ $lp->uploader?->name ?? '-' }}
                    · {{ $lp->created_at->format('d M Y') }}
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <a href="{{ route('rapat.lampiran.download', [$rapat, $lp]) }}"
                   class="btn btn-outline-primary btn-sm" title="Unduh">
                    <i class="bi bi-download"></i>
                </a>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
                <form method="POST" action="{{ route('rapat.lampiran.destroy', [$rapat, $lp]) }}"
                    onsubmit="return confirm('Hapus file {{ addslashes($lp->nama_asli) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="list-group-item text-center text-muted py-4">
            <i class="bi bi-paperclip fs-2 d-block mb-1 opacity-25"></i>
            <small>Belum ada lampiran.</small>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Upload Lampiran --}}
@if(auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan())
<div class="modal fade" id="modalUploadLampiran" tabindex="-1"
    @if($errors->has('file')) data-show="true" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rapat.lampiran.store', $rapat) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upload text-primary me-2"></i>Upload Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File <span class="text-danger">*</span></label>
                        <input type="file" name="file"
                            class="form-control @error('file') is-invalid @enderror"
                            accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx,.pptx"
                            required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="alert alert-light border small mb-0 py-2">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        Ukuran maks. <strong>10 MB</strong>.
                        Format yang diizinkan: <strong>PDF, JPG, PNG, DOCX, XLSX, PPTX</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Upload File
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
