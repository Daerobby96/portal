{{-- Widget Integrasi Data untuk Dashboard Utama --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-gradient-primary text-white d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="bi bi-diagram-3 fs-5 me-2"></i>
            <div>
                <h6 class="mb-0">Data Terintegrasi</h6>
                <small class="opacity-75">{{ $periode ? $periode->nama : 'Semua Data' }}</small>
            </div>
        </div>
        <a href="{{ route('spmi.integration.dashboard') }}" class="btn btn-sm btn-light">
            <i class="bi bi-arrow-right-circle me-1"></i>
            Lihat Detail
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            {{-- Data Akademik --}}
            @if($data['mahasiswa']['available'])
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded p-2">
                            <i class="bi bi-mortarboard text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Mahasiswa Aktif</div>
                        <div class="fw-bold fs-5">{{ number_format($data['mahasiswa']['aktif']) }}</div>
                        <small class="text-muted">dari {{ number_format($data['mahasiswa']['total']) }} total</small>
                    </div>
                </div>
            </div>
            @endif

            @if($data['pegawai']['available'])
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded p-2">
                            <i class="bi bi-people-fill text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Pegawai (Dosen & Tendik)</div>
                        <div class="fw-bold fs-5">{{ number_format($data['pegawai']['aktif']) }}</div>
                        <small class="text-muted">
                            {{ number_format($data['pegawai']['dosen']) }} Dosen · 
                            {{ number_format($data['pegawai']['tendik']) }} Tendik
                        </small>
                    </div>
                </div>
            </div>
            @endif

            @if($data['prestasi']['available'])
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded p-2">
                            <i class="bi bi-trophy text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Prestasi</div>
                        <div class="fw-bold fs-5">{{ number_format($data['prestasi']['total']) }}</div>
                        <small class="text-muted">pencapaian</small>
                    </div>
                </div>
            </div>
            @endif

            {{-- Tridharma --}}
            @if($data['penelitian']['available'])
            <div class="col-md-4">
                <div class="text-center p-2 border rounded">
                    <i class="bi bi-journal-richtext text-info fs-5"></i>
                    <div class="fw-bold mt-1">{{ number_format($data['penelitian']['total']) }}</div>
                    <small class="text-muted">Penelitian</small>
                </div>
            </div>
            @endif

            @if($data['pengabdian']['available'])
            <div class="col-md-4">
                <div class="text-center p-2 border rounded">
                    <i class="bi bi-people text-success fs-5"></i>
                    <div class="fw-bold mt-1">{{ number_format($data['pengabdian']['total']) }}</div>
                    <small class="text-muted">Pengabdian</small>
                </div>
            </div>
            @endif

            @if($data['publikasi']['available'])
            <div class="col-md-4">
                <div class="text-center p-2 border rounded">
                    <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                    <div class="fw-bold mt-1">{{ number_format($data['publikasi']['total']) }}</div>
                    <small class="text-muted">Publikasi</small>
                </div>
            </div>
            @endif

            {{-- Kerjasama --}}
            @if($data['kerjasama']['available'])
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded p-2">
                            <i class="bi bi-briefcase text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Kerjasama Aktif</div>
                        <div class="fw-bold fs-5">{{ number_format($data['kerjasama']['aktif']) }}</div>
                        <small class="text-muted">dari {{ number_format($data['kerjasama']['total']) }} total</small>
                    </div>
                </div>
            </div>
            @endif

            {{-- Tracer Study --}}
            @if($data['tracer_study']['available'])
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded p-2">
                            <i class="bi bi-graph-up text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Alumni Bekerja</div>
                        <div class="fw-bold fs-5">{{ number_format($data['tracer_study']['bekerja']) }}</div>
                        <small class="text-muted">dari {{ number_format($data['tracer_study']['total']) }} responden</small>
                    </div>
                </div>
            </div>
            @endif

            {{-- Aset --}}
            @if($data['aset']['available'])
            <div class="col-12">
                <div class="d-flex align-items-center p-3 bg-light rounded">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded p-2">
                            <i class="bi bi-box-seam text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Aset</div>
                        <div class="fw-bold fs-5">{{ number_format($data['aset']['total']) }}</div>
                        @if(!empty($data['aset']['by_kondisi']))
                        <div class="mt-1">
                            @foreach($data['aset']['by_kondisi'] as $kondisi => $count)
                            <span class="badge {{ $kondisi == 'baik' ? 'bg-success' : ($kondisi == 'rusak' ? 'bg-danger' : 'bg-warning') }} me-1">
                                {{ ucfirst($kondisi) }}: {{ $count }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Info jika tidak ada data --}}
        @if(!$data['mahasiswa']['available'] && !$data['pegawai']['available'] && !$data['penelitian']['available'] && !$data['kerjasama']['available'] && !$data['aset']['available'])
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle me-2"></i>
            Belum ada modul yang terhubung. Data akan muncul saat modul tersedia.
        </div>
        @endif
    </div>
</div>
