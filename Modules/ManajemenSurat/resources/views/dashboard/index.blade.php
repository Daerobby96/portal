@extends('manajemen-surat::layouts.master')

@section('title', 'Dashboard Manajemen Surat')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview sistem manajemen surat')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Welcome Banner with System Info --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="font-bold text-slate-800 mb-2">
                            <i class="bi bi-speedometer2 text-blue-600"></i> Dashboard Manajemen Surat
                        </h5>
                        <p class="text-sm text-slate-600 mb-3">
                            Sistem pencatatan dan monitoring surat keluar, surat masuk, dan disposisi secara digital.
                        </p>
                        <div class="d-flex gap-4 text-xs">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-database text-blue-600"></i>
                                <span class="text-slate-600"><strong>Record-Only:</strong> Metadata tersimpan di database</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-lightning-charge text-amber-600"></i>
                                <span class="text-slate-600"><strong>Generate On-Demand:</strong> PDF dibuat saat dibutuhkan</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-check text-emerald-600"></i>
                                <span class="text-slate-600"><strong>Hemat Storage:</strong> Tidak simpan file permanen</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</div>
                        <div class="text-xs text-slate-400">{{ now()->format('H:i') }} WIB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="col-12">
        <div class="row g-3">
            {{-- Surat Keluar Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-blue-600 to-blue-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-box-arrow-up-right fs-3"></i>
                            </div>
                            <div>
                                <div class="text-blue-100 text-xs font-semibold">Surat Keluar</div>
                                <div class="text-white text-2xl font-black">{{ $stats['total_surat_keluar'] }}</div>
                                <div class="text-blue-200 text-xs">{{ $stats['total_surat_keluar_bulan_ini'] }} bulan ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Surat Masuk Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-emerald-600 to-emerald-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-box-arrow-in-down-left fs-3"></i>
                            </div>
                            <div>
                                <div class="text-emerald-100 text-xs font-semibold">Surat Masuk</div>
                                <div class="text-white text-2xl font-black">{{ $stats['total_surat_masuk'] }}</div>
                                <div class="text-emerald-200 text-xs">{{ $stats['surat_masuk_baru'] }} surat baru</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Approval Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-amber-600 to-amber-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-clock-history fs-3"></i>
                            </div>
                            <div>
                                <div class="text-amber-100 text-xs font-semibold">Pending Approval</div>
                                <div class="text-white text-2xl font-black">{{ $stats['pending_approval'] }}</div>
                                <div class="text-amber-200 text-xs">Perlu persetujuan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- My Disposisi Card --}}
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-purple-600 to-purple-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-xl bg-white/10 p-3">
                                <i class="bi bi-person-badge fs-3"></i>
                            </div>
                            <div>
                                <div class="text-purple-100 text-xs font-semibold">Disposisi Saya</div>
                                <div class="text-white text-2xl font-black">{{ $stats['my_disposisi_pending'] }}</div>
                                <div class="text-purple-200 text-xs">{{ $stats['my_disposisi_overdue'] }} overdue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="col-md-8">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-header bg-white border-0 p-4">
                <h6 class="mb-0 font-bold text-slate-800">Tren Surat 6 Bulan Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="chartSurat" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- My Disposisi Alerts --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-bold text-slate-800">Disposisi Saya</h6>
                <a href="{{ route('disposisi.my-disposisi') }}" class="text-xs text-blue-600 font-semibold hover:text-blue-700">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($myDisposisi as $disposisi)
                <div class="p-3 border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <div class="d-flex gap-2">
                        @if($disposisi->prioritas === 'tinggi')
                        <div class="h-2 w-2 rounded-full bg-rose-500 mt-1.5 flex-shrink-0"></div>
                        @elseif($disposisi->prioritas === 'sedang')
                        <div class="h-2 w-2 rounded-full bg-amber-500 mt-1.5 flex-shrink-0"></div>
                        @else
                        <div class="h-2 w-2 rounded-full bg-slate-400 mt-1.5 flex-shrink-0"></div>
                        @endif
                        <div class="flex-grow-1">
                            <a href="{{ route('disposisi.show', $disposisi) }}" class="text-sm font-semibold text-slate-800 hover:text-blue-600 transition-colors">
                                {{ Str::limit($disposisi->suratMasuk->perihal, 50) }}
                            </a>
                            <div class="text-xs text-slate-400 mt-0.5">
                                Dari: {{ $disposisi->dari->name }}
                            </div>
                            @if($disposisi->batas_waktu)
                            <div class="text-xs text-slate-500 mt-1">
                                <i class="bi bi-clock"></i> {{ $disposisi->batas_waktu->format('d M Y') }}
                                @if($disposisi->isOverdue())
                                <span class="text-rose-600 font-semibold">(Overdue)</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-5 text-center">
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-2xl bg-slate-50 p-4 mb-3">
                            <i class="bi bi-inbox text-slate-300 fs-1"></i>
                        </div>
                        <p class="text-slate-400 text-sm mb-0">Tidak ada disposisi aktif</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Surat Masuk --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-bold text-slate-800">Surat Masuk Terbaru</h6>
                <a href="{{ route('surat-masuk.index') }}" class="text-xs text-blue-600 font-semibold hover:text-blue-700">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @forelse($recentSuratMasuk as $surat)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="text-sm font-semibold text-slate-800">{{ $surat->nomor_agenda }}</div>
                                    <div class="text-xs text-slate-400">{{ $surat->pengirim }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="text-sm text-slate-600">{{ Str::limit($surat->perihal, 40) }}</div>
                                </td>
                                <td class="py-3 px-3 text-end">
                                    <div class="text-xs text-slate-400">{{ $surat->tanggal_terima->format('d M') }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-5 text-center text-slate-400 text-sm">Belum ada surat masuk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Surat Keluar --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-bold text-slate-800">Surat Keluar Terbaru</h6>
                <a href="{{ route('surat-keluar.index') }}" class="text-xs text-blue-600 font-semibold hover:text-blue-700">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @forelse($recentSuratKeluar as $surat)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="text-sm font-semibold text-slate-800">{{ $surat->nomor_surat }}</div>
                                    <div class="text-xs text-slate-400">{{ $surat->jenisSurat->nama }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="text-sm text-slate-600">{{ Str::limit($surat->perihal, 40) }}</div>
                                </td>
                                <td class="py-3 px-3 text-end">
                                    <div class="text-xs text-slate-400">{{ $surat->tanggal_surat->format('d M') }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-5 text-center text-slate-400 text-sm">Belum ada surat keluar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($pendingApprovals) && count($pendingApprovals) > 0)
    {{-- Pending Approvals (for admin) --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-l-4 border-amber-500">
            <div class="card-header bg-amber-50 border-0 p-4">
                <h6 class="mb-0 font-bold text-amber-900 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Surat Menunggu Persetujuan
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-xs font-bold text-slate-600 py-3">Nomor Surat</th>
                                <th class="text-xs font-bold text-slate-600 py-3">Jenis</th>
                                <th class="text-xs font-bold text-slate-600 py-3">Perihal</th>
                                <th class="text-xs font-bold text-slate-600 py-3">Dibuat Oleh</th>
                                <th class="text-xs font-bold text-slate-600 py-3">Tanggal</th>
                                <th class="text-xs font-bold text-slate-600 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingApprovals as $surat)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 text-sm font-semibold text-slate-800">{{ $surat->nomor_surat }}</td>
                                <td class="py-3 text-xs text-slate-600">{{ $surat->jenisSurat->nama }}</td>
                                <td class="py-3 text-sm text-slate-600">{{ Str::limit($surat->perihal, 50) }}</td>
                                <td class="py-3 text-sm text-slate-600">{{ $surat->creator->name }}</td>
                                <td class="py-3 text-xs text-slate-500">{{ $surat->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('surat-keluar.show', $surat) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartSurat');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
            {
                label: 'Surat Masuk',
                data: {!! json_encode($chartData['surat_masuk']) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Surat Keluar',
                data: {!! json_encode($chartData['surat_keluar']) !!},
                borderColor: 'rgb(37, 99, 235)',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>
@endpush
@endsection
