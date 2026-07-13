@extends('sdm::layouts.master')

@section('title', 'Rekap Presensi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Rekap Presensi</h1>
            <p class="text-muted small mb-0">Rekapitulasi kehadiran pegawai per bulan</p>
        </div>
        <a href="{{ route('sdm.presensi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->locale('id')->translatedFormat('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Rekap Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pegawai</th>
                            <th>Unit Kerja</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Alpa</th>
                            <th class="text-center">Cuti</th>
                            <th class="text-center">Dinas Luar</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $data['pegawai']->nama }}</div>
                                <small class="text-muted">{{ $data['pegawai']->nip }}</small>
                            </td>
                            <td>{{ $data['pegawai']->unit_kerja }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $data['hadir'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $data['izin'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $data['sakit'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $data['alpa'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $data['cuti'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $data['dinas_luar'] }}</span>
                            </td>
                            <td class="text-center fw-bold">{{ $data['total'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Tidak ada data untuk periode ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
