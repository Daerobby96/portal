@extends('tracerstudy::layouts.master')

@section('title', 'Tracer Study Alumni')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="mb-1">Tracer Study Alumni</h4>
            <p class="text-muted small mb-0">Manajemen data lulusan dan penyerapan kerja sesuai standar PDDIKTI</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('tracer-study.template') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-download me-1"></i> Template
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-upload me-1"></i> Import Data
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-primary bg-opacity-10 p-2">
                                <i class="bi bi-people-fill text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Total Alumni</div>
                            <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
                            <small class="text-muted">Responden</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-success bg-opacity-10 p-2">
                                <i class="bi bi-briefcase-fill text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Bekerja</div>
                            <h4 class="mb-0">{{ $stats['bekerja_persen'] }}%</h4>
                            <small class="text-muted">{{ number_format($stats['bekerja']) }} orang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-warning bg-opacity-10 p-2">
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Rerata Tunggu</div>
                            <h4 class="mb-0">{{ number_format($stats['avg_tunggu'], 1) }}</h4>
                            <small class="text-muted">Bulan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #06b6d4 !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded bg-info bg-opacity-10 p-2">
                                <i class="bi bi-wallet2 text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Rerata Gaji</div>
                            <h4 class="mb-0">{{ number_format($stats['avg_gaji'] / 1000000, 1) }}jt</h4>
                            <small class="text-muted">Per bulan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Analysis & Distribution -->
    <div class="row g-4 mb-4">
        {{-- AI Smart Insight Box --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-header text-white border-0" style="background: transparent;">
                    <h6 class="mb-0">
                        <i class="bi bi-robot me-2"></i>
                        AI Smart Insight - Analisa Otomatis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ai-content text-white">
                        {!! $aiInsight !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- PPEPP Integration --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-diagram-3 text-danger me-2"></i>
                        Integrasi PPEPP
                    </h6>
                    <form action="{{ route('tracer-study.sync-ppepp') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-arrow-repeat me-1"></i> Sinkronkan
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    @if(count($ppeppData) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($ppeppData as $item)
                                <div class="list-group-item px-0 border-0 py-2">
                                    <div class="d-flex justify-content-between mb-1 align-items-center">
                                        <span class="text-dark fw-semibold small">{{ $item['nama'] }}</span>
                                        <span class="badge {{ $item['status'] == 'Tercapai' ? 'bg-success' : 'bg-warning' }} text-white">
                                            {{ $item['status'] }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-bold text-dark small">
                                            {{ number_format($item['capaian'], 1) }} / {{ number_format($item['target'], 1) }} 
                                            <span class="text-muted">{{ $item['satuan'] }}</span>
                                        </div>
                                        <div class="progress w-50" style="height: 5px;">
                                            @php $perc = min(100, ($item['capaian'] / max(1, $item['target'])) * 100); @endphp
                                            <div class="progress-bar {{ $perc >= 100 ? 'bg-success' : 'bg-warning' }}" style="width: {{ $perc }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-exclamation-triangle fs-1 mb-3"></i>
                            <p class="small mb-0">Indikator Kinerja terkait Lulusan belum didefinisikan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Chart -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0">
                <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                Sebaran Status Lulusan
            </h6>
        </div>
        <div class="card-body text-center">
            <canvas id="statusChart" style="max-height: 200px;"></canvas>
        </div>
    </div>

    {{-- Data Lulusan Grid --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0">
                <i class="bi bi-table text-primary me-2"></i>
                Data Lulusan
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NIM / Nama</th>
                        <th>Prodi / Lulus</th>
                        <th>Status Kerja</th>
                        <th>Perusahaan / Jabatan</th>
                        <th>Gaji / Tunggu</th>
                        <th class="text-end pe-4" style="width: 120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tracerData as $data)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $data->nama }}</div>
                            <div class="text-muted small"><code>{{ $data->nim }}</code></div>
                        </td>
                        <td>
                            <div class="fw-semibold small">{{ $data->prodi ?? '-' }}</div>
                            <span class="badge bg-light text-dark border">TA {{ $data->tahun_lulus }}</span>
                        </td>
                        <td>
                            @php
                                $sKerja = strtolower($data->status_kerja ?? '');
                            @endphp
                            @if(str_starts_with($sKerja, 'bekerja') || $sKerja == '1')
                                <span class="badge bg-success">Bekerja</span>
                            @elseif(str_contains($sKerja, 'wirausaha') || $sKerja == '2')
                                <span class="badge bg-primary">Wirausaha</span>
                            @elseif(str_contains($sKerja, 'melanjutkan') || $sKerja == '3')
                                <span class="badge bg-info">Lanjut Studi</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $data->status_kerja ?? '-' }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold small">{{ $data->perusahaan ?? '-' }}</div>
                            <div class="small text-muted">{{ $data->jabatan }}</div>
                        </td>
                        <td>
                            <div class="text-success fw-bold small">Rp {{ number_format($data->gaji) }}</div>
                            <div class="small text-muted">{{ $data->waktu_tunggu_bulan }} Bulan</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $data->id }}" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <form action="{{ route('tracer-study.destroy', $data) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Detail Alumni Modal -->
                            <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-person-vcard text-primary me-2"></i>
                                                Detail Alumni
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <!-- Identitas Alumni -->
                                                <div class="col-12">
                                                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                                                        <i class="bi bi-person-fill me-2"></i>
                                                        Identitas Alumni
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">Nama Lengkap</label>
                                                            <div class="fw-semibold">{{ $data->nama }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">NIM</label>
                                                            <div class="fw-semibold"><code>{{ $data->nim }}</code></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small text-muted fw-bold">Program Studi</label>
                                                            <div class="fw-semibold">{{ $data->prodi ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small text-muted fw-bold">Tahun Lulus</label>
                                                            <div class="fw-semibold">{{ $data->tahun_lulus ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small text-muted fw-bold">Kontak</label>
                                                            <div class="fw-semibold">{{ $data->telepon ?? '-' }}</div>
                                                            <div class="text-muted small">{{ $data->email ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Pekerjaan -->
                                                <div class="col-12 mt-4">
                                                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                                                        <i class="bi bi-briefcase-fill me-2"></i>
                                                        Informasi Pekerjaan
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label class="small text-muted fw-bold">Status Pekerjaan</label>
                                                            <div class="fw-bold">{{ $data->status_kerja ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">Instansi / Perusahaan</label>
                                                            <div class="fw-semibold">{{ $data->perusahaan ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">Jabatan</label>
                                                            <div class="fw-semibold">{{ $data->jabatan ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small text-muted fw-bold">Tingkat Instansi</label>
                                                            <div class="fw-semibold">{{ $data->tingkat_instansi ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small text-muted fw-bold">Gaji / Pendapatan</label>
                                                            <div class="fw-bold text-success">Rp {{ number_format($data->gaji ?? 0) }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small text-muted fw-bold">Waktu Tunggu</label>
                                                            <div class="fw-semibold">{{ $data->waktu_tunggu_bulan ?? 0 }} Bulan</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Keselarasan -->
                                                <div class="col-12 mt-4">
                                                    <h6 class="border-bottom pb-2 mb-3 text-primary">
                                                        <i class="bi bi-check2-circle me-2"></i>
                                                        Keselarasan
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">Keselarasan Horisontal</label>
                                                            <div class="fw-semibold small">{{ $data->keselarasan_horisontal ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">Keselarasan Vertikal</label>
                                                            <div class="fw-semibold small">{{ $data->keselarasan_vertikal ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <span>Belum ada data tracer study. Silakan import file Excel.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tracerData->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $tracerData->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('tracer-study.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-cloud-arrow-up text-primary me-2"></i>
                    Import Data Alumni
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center p-4 border-2 border-dashed rounded">
                    <i class="bi bi-file-earmark-spreadsheet fs-1 text-primary mb-3 d-block"></i>
                    <p class="text-muted mb-3">Pilih berkas Excel Template PDDIKTI (.xlsx, .xls)</p>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>Sistem akan otomatis mendeteksi kolom seperti NIM, Nama, Gaji, dan Status Kerja dari header file.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i> Import Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart
    const statusData = @json($statusDist);
    const labels = Object.keys(statusData);
    const values = Object.values(statusData);
    
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: values,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(6, 182, 212, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(99, 102, 241)',
                        'rgb(6, 182, 212)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

