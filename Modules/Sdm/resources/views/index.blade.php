@extends('sdm::layouts.master')

@section('title', 'Dashboard SDM')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-gray-800">Dashboard SDM</h1>
            <p class="text-muted small mb-0">Manajemen Sumber Daya Manusia</p>
        </div>
        <div class="text-muted small">
            <i class="bi bi-calendar3"></i> {{ now()->locale('id')->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        {{-- Presensi Hari Ini --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-calendar-check fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Hadir Hari Ini</div>
                            <h4 class="mb-0">{{ $stats['hadir_hari_ini'] }}/{{ $stats['presensi_hari_ini'] }}</h4>
                            <small class="text-muted">Pegawai</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('sdm.presensi.index') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        {{-- Cuti Pending --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Cuti Pending</div>
                            <h4 class="mb-0">{{ $stats['cuti_pending'] }}</h4>
                            <small class="text-success">{{ $stats['cuti_aktif'] }} sedang cuti</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('sdm.cuti.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        {{-- Lembur Bulan Ini --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Lembur Bulan Ini</div>
                            <h4 class="mb-0">{{ number_format($stats['lembur_bulan_ini'], 1) }}</h4>
                            <small class="text-muted">Jam</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('sdm.lembur.index') }}" class="btn btn-sm btn-outline-info w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        {{-- Surat Tugas Aktif --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-file-earmark-text fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Surat Tugas Aktif</div>
                            <h4 class="mb-0">{{ $stats['surat_tugas_aktif'] }}</h4>
                            <small class="text-warning">{{ $stats['surat_tugas_pending'] }} menunggu</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('sdm.surat-tugas.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Recent Activities --}}
        <div class="col-lg-8">
            {{-- Recent Cuti --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-x text-warning me-2"></i>Pengajuan Cuti Terbaru
                        </h6>
                        <a href="{{ route('sdm.cuti.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($recentCutis as $cuti)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-semibold">{{ $cuti->pegawai->nama }}</div>
                            <div class="small text-muted">
                                {{ $cuti->tanggal_mulai->format('d M') }} - {{ $cuti->tanggal_selesai->format('d M Y') }} ({{ $cuti->jumlah_hari }} hari)
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            {!! $cuti->status_badge !!}
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                        <small>Belum ada pengajuan cuti</small>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Surat Tugas --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>Surat Tugas Terbaru
                        </h6>
                        <a href="{{ route('sdm.surat-tugas.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($recentSuratTugas as $st)
                    <div class="d-flex align-items-start p-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-file-text"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-semibold">{{ $st->perihal }}</div>
                            <div class="small text-muted">
                                {{ $st->tempat_tujuan }} • {{ $st->tanggal_mulai->format('d M') }} - {{ $st->tanggal_selesai->format('d M Y') }}
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-people"></i> {{ $st->pegawais->count() }} pegawai
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            {!! $st->status_badge !!}
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                        <small>Belum ada surat tugas</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Side Stats --}}
        <div class="col-lg-4">
            {{-- Pegawai Stats --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-people text-success me-2"></i>Data Pegawai
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="text-muted small">Total Pegawai</div>
                            <h4 class="mb-0">{{ $stats['total_pegawai'] }}</h4>
                        </div>
                        <i class="bi bi-people fs-1 text-success opacity-25"></i>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Dosen</span>
                        <span class="fw-semibold">{{ $stats['dosen'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Tenaga Kependidikan</span>
                        <span class="fw-semibold">{{ $stats['tendik'] }}</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('sdm.pegawai.index') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Data Pegawai
                    </a>
                </div>
            </div>

            {{-- Penilaian Kinerja --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-star text-warning me-2"></i>Penilaian Kinerja {{ now()->year }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="text-muted small">Total Penilaian</div>
                            <h4 class="mb-0">{{ $stats['penilaian_tahun_ini'] }}</h4>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Rata-rata Nilai</div>
                            <h4 class="mb-0 text-success">{{ number_format($stats['avg_nilai_tahun_ini'] ?? 0, 1) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('sdm.penilaian-kinerja.index') }}" class="btn btn-sm btn-outline-warning w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Penilaian
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
