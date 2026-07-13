<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Rapat</h6>
        {!! $rapat->jenis_badge !!}
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <small class="text-muted d-block">Tanggal</small>
                <span class="fw-semibold">{{ $rapat->tanggal->format('l, d M Y') }}</span>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Waktu</small>
                <span class="fw-semibold">{{ substr($rapat->waktu_mulai,0,5) }} – {{ substr($rapat->waktu_selesai,0,5) }}</span>
                <span class="text-muted small ms-1">({{ $rapat->total_durasi }} menit estimasi)</span>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Tempat</small>
                <span class="fw-semibold">{{ $rapat->tempat }}</span>
            </div>
            @if($rapat->deskripsi)
            <div class="col-12">
                <small class="text-muted d-block">Deskripsi / Tujuan</small>
                <p class="mb-0">{{ $rapat->deskripsi }}</p>
            </div>
            @endif
            @if($rapat->alasan_pembatalan)
            <div class="col-12">
                <div class="alert alert-danger py-2 mb-0">
                    <small class="fw-semibold d-block">Alasan Pembatalan:</small>
                    {{ $rapat->alasan_pembatalan }}
                </div>
            </div>
            @endif
        </div>

        @if($rapat->jenis === 'RTM' && ($rapat->input_audit_internal || $rapat->output_keefektifan))
        <hr>
        <h6 class="text-muted mb-3">Input & Output RTM (ISO 9001)</h6>
        <div class="row g-3">
            @foreach([
                'input_audit_internal'  => 'Input: Audit Internal',
                'input_umpan_balik'     => 'Input: Umpan Balik',
                'input_kinerja_proses'  => 'Input: Kinerja Proses',
                'input_status_tindakan' => 'Input: Status Tindakan Sebelumnya',
                'output_keefektifan'    => 'Output: Keefektifan Sistem Mutu',
                'output_perbaikan'      => 'Output: Perbaikan Produk/Layanan',
                'output_sumber_daya'    => 'Output: Sumber Daya',
            ] as $field => $label)
            @if($rapat->$field)
            <div class="col-md-6">
                <small class="text-muted d-block">{{ $label }}</small>
                <p class="mb-0 small">{{ $rapat->$field }}</p>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        @if($rapat->kesimpulan)
        <hr>
        <h6 class="mb-2">Kesimpulan Rapat</h6>
        <p class="mb-0">{{ $rapat->kesimpulan }}</p>
        @endif
    </div>
</div>
