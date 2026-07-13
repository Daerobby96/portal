<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-people text-primary me-2"></i>Peserta
            <small class="text-muted fw-normal ms-1">({{ $rapat->peserta->count() }} orang)</small>
        </h6>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex gap-1">
                <span class="badge bg-success" title="Hadir">H:{{ $ringkasanKehadiran['hadir'] }}</span>
                <span class="badge bg-danger" title="Tidak Hadir">TH:{{ $ringkasanKehadiran['tidak_hadir'] }}</span>
                <span class="badge bg-warning text-dark" title="Izin">I:{{ $ringkasanKehadiran['izin'] }}</span>
                <span class="badge bg-secondary" title="Belum konfirmasi">D:{{ $ringkasanKehadiran['diundang'] }}</span>
            </div>
            @if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !$rapat->isLocked())
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                <i class="bi bi-person-plus me-1"></i>Tambah Peserta
            </button>
            @endif
        </div>
    </div>

    <div class="list-group list-group-flush">
        @forelse($rapat->peserta as $p)
        <div class="list-group-item px-3 py-2">
            <div class="d-flex align-items-start gap-2">
                <div class="rounded-circle bg-{{ $p->avatar_color }} bg-opacity-{{ $p->isEksternal() ? '25' : '100' }}
                    text-{{ $p->isEksternal() ? 'secondary' : 'white' }} d-flex align-items-center
                    justify-content-center fw-bold flex-shrink-0 border"
                    style="width:36px;height:36px;font-size:.85rem">
                    {{ $p->inisial }}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <span class="fw-semibold small">{{ $p->nama_display }}</span>
                        @if($p->user_id)
                            <span class="badge bg-light text-dark border" style="font-size:.6rem">Internal</span>
                        @elseif($p->pegawai_id)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:.6rem">Pegawai</span>
                        @else
                            <span class="badge bg-info text-dark" style="font-size:.6rem">Eksternal</span>
                        @endif
                        <span class="badge bg-light text-dark border" style="font-size:.62rem">{{ $p->peran }}</span>
                    </div>
                    <div class="text-muted" style="font-size:.73rem">
                        @if($p->instansi_display) {{ $p->instansi_display }} @endif
                        @if($p->email_display)
                            · <i class="bi bi-envelope"></i> {{ $p->email_display }}
                        @endif
                        @if($p->no_hp_display)
                            · <i class="bi bi-telephone"></i> {{ $p->no_hp_display }}
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                    {!! $p->status_badge !!}
                    @php
                        $canUpdateKehadiran = in_array($rapat->status, ['berlangsung','selesai'])
                            && (auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()
                                || (!$p->isEksternal() && $p->user_id === auth()->id()));
                    @endphp
                    @if($canUpdateKehadiran)
                    <form method="POST" action="{{ route('rapat.peserta.kehadiran', [$rapat, $p]) }}">
                        @csrf @method('PATCH')
                        <select name="status_kehadiran" class="form-select form-select-sm py-0"
                            style="font-size:.7rem;height:22px;min-width:95px" onchange="this.form.submit()">
                            <option value="diundang"    {{ $p->status_kehadiran=='diundang'?'selected':'' }}>Diundang</option>
                            <option value="hadir"       {{ $p->status_kehadiran=='hadir'?'selected':'' }}>Hadir</option>
                            <option value="tidak_hadir" {{ $p->status_kehadiran=='tidak_hadir'?'selected':'' }}>Tidak Hadir</option>
                            <option value="izin"        {{ $p->status_kehadiran=='izin'?'selected':'' }}>Izin</option>
                        </select>
                    </form>
                    @endif
                    @if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !in_array($rapat->status, ['selesai','dibatalkan']))
                    <form method="POST" action="{{ route('rapat.peserta.destroy', [$rapat, $p]) }}"
                        onsubmit="return confirm('Hapus {{ addslashes($p->nama_display) }} dari peserta?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger py-0 px-1"
                            style="font-size:.7rem;height:22px;border-radius:6px" title="Hapus">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="list-group-item text-center text-muted py-4">
            <i class="bi bi-people fs-2 d-block mb-1 opacity-25"></i>
            <small>Belum ada peserta.</small>
            @if((auth()->user()->isSuperAdmin() || auth()->user()->isPimpinan()) && !$rapat->isLocked())
            <div class="mt-2">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                    <i class="bi bi-person-plus me-1"></i>Tambah Peserta Pertama
                </button>
            </div>
            @endif
        </div>
        @endforelse
    </div>
</div>
