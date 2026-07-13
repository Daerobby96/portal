<div class="modal fade" id="modalStatus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rapat.ubah-status', $rapat) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status Rapat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Baru</label>
                        <select name="status" id="selectStatus" class="form-select" required
                            onchange="toggleAlasan(this.value)">
                            @foreach(\Modules\ManajemenRapat\Models\Rapat::statusOptions() as $k => $v)
                            <option value="{{ $k }}" {{ $rapat->status === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="fieldAlasan" style="display:none">
                        <label class="form-label fw-semibold">
                            Alasan Pembatalan <span class="text-danger">*</span>
                        </label>
                        <textarea name="alasan_pembatalan" class="form-control" rows="3"
                            maxlength="500" placeholder="Tuliskan alasan pembatalan..."></textarea>
                    </div>

                    <div class="mb-3" id="fieldKesimpulan" style="display:none">
                        <label class="form-label fw-semibold">Kesimpulan Rapat</label>
                        <textarea name="kesimpulan" class="form-control" rows="3"
                            placeholder="Tuliskan kesimpulan rapat...">{{ $rapat->kesimpulan }}</textarea>
                    </div>

                    <div class="alert alert-info py-2 small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Mengubah ke <strong>Terjadwal</strong> akan mengirim notifikasi undangan ke semua peserta.
                        Mengubah ke <strong>Dibatalkan</strong> akan mengirim notifikasi pembatalan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAlasan(status) {
    document.getElementById('fieldAlasan').style.display    = (status === 'dibatalkan') ? 'block' : 'none';
    document.getElementById('fieldKesimpulan').style.display = (status === 'selesai')    ? 'block' : 'none';

    const alasanField = document.querySelector('[name="alasan_pembatalan"]');
    if (alasanField) alasanField.required = (status === 'dibatalkan');
}
// Init
toggleAlasan(document.getElementById('selectStatus')?.value);
</script>
@endpush

