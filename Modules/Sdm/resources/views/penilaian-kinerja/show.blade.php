@extends('sdm::layouts.master')

@section('title', 'Detail Penilaian Kinerja')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Penilaian Kinerja</h1>
        <div>
            @if($penilaianKinerja->status != 'verified')
                <a href="{{ route('sdm.penilaian-kinerja.edit', $penilaianKinerja) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            @endif
            <a href="{{ route('sdm.penilaian-kinerja.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Penilaian</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Tahun:</strong>
                            <p>{{ $penilaianKinerja->tahun }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Periode:</strong>
                            <p>{{ str_replace('_', ' ', ucwords($penilaianKinerja->periode)) }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Tanggal Dibuat:</strong>
                            <p>{{ $penilaianKinerja->created_at->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>{!! $penilaianKinerja->status_badge !!}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Nilai Total:</strong>
                            <p>
                                @if($penilaianKinerja->nilai_total)
                                    <span class="badge {{ $penilaianKinerja->nilai_total >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_total >= 60 ? 'bg-warning' : 'bg-danger') }} fs-5">
                                        {{ number_format($penilaianKinerja->nilai_total, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detail Penilaian</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Aspek Penilaian</th>
                                    <th width="20%" class="text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Kedisiplinan</td>
                                    <td class="text-center">
                                        <span class="badge {{ $penilaianKinerja->nilai_disiplin >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_disiplin >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ number_format($penilaianKinerja->nilai_disiplin, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Kinerja</td>
                                    <td class="text-center">
                                        <span class="badge {{ $penilaianKinerja->nilai_kinerja >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_kinerja >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ number_format($penilaianKinerja->nilai_kinerja, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Loyalitas</td>
                                    <td class="text-center">
                                        <span class="badge {{ $penilaianKinerja->nilai_loyalitas >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_loyalitas >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ number_format($penilaianKinerja->nilai_loyalitas, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td>Kreativitas</td>
                                    <td class="text-center">
                                        <span class="badge {{ $penilaianKinerja->nilai_kreativitas >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_kreativitas >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ number_format($penilaianKinerja->nilai_kreativitas, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td>Kerjasama</td>
                                    <td class="text-center">
                                        <span class="badge {{ $penilaianKinerja->nilai_kerjasama >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_kerjasama >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ number_format($penilaianKinerja->nilai_kerjasama, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Nilai Total (Rata-rata):</th>
                                    <th class="text-center">
                                        <span class="badge bg-primary fs-6">
                                            {{ number_format($penilaianKinerja->nilai_total, 2) }}
                                        </span>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">Predikat:</th>
                                    <th class="text-center">
                                        {!! $penilaianKinerja->predikat_badge !!}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($penilaianKinerja->catatan_atasan)
                    <div class="mt-3">
                        <strong>Catatan Atasan:</strong>
                        <p class="text-muted mt-2">{{ $penilaianKinerja->catatan_atasan }}</p>
                    </div>
                    @endif

                    @if($penilaianKinerja->catatan_pegawai)
                    <div class="mt-3">
                        <strong>Catatan Pegawai:</strong>
                        <p class="text-muted mt-2">{{ $penilaianKinerja->catatan_pegawai }}</p>
                    </div>
                    @endif

                    @if($penilaianKinerja->file_dokumen)
                    <div class="mt-3">
                        <strong>Dokumen Pendukung:</strong>
                        <div class="border rounded p-3 mt-2">
                            <a href="{{ Storage::url($penilaianKinerja->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-2"></i>Lihat Dokumen
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($penilaianKinerja->nilai_total)
            <div class="card shadow-sm">
                <div class="card-header {{ $penilaianKinerja->nilai_total >= 80 ? 'bg-success' : ($penilaianKinerja->nilai_total >= 60 ? 'bg-warning' : 'bg-danger') }} text-white">
                    <h5 class="mb-0">Kategori Kinerja</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <h2 class="display-6 mb-3">
                            @if($penilaianKinerja->nilai_total >= 90)
                                <i class="fas fa-star text-warning"></i> Sangat Baik
                            @elseif($penilaianKinerja->nilai_total >= 80)
                                <i class="fas fa-thumbs-up text-success"></i> Baik
                            @elseif($penilaianKinerja->nilai_total >= 70)
                                <i class="fas fa-check text-info"></i> Cukup
                            @else
                                <i class="fas fa-exclamation-triangle text-danger"></i> Kurang
                            @endif
                        </h2>
                        <p class="text-muted mb-0">
                            Nilai: <strong>{{ number_format($penilaianKinerja->nilai_total, 2) }}</strong> dari 100
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Data Pegawai</h5>
                    <hr>
                    <div class="mb-2">
                        <strong>Nama:</strong>
                        <p>{{ $penilaianKinerja->pegawai->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>NIP:</strong>
                        <p>{{ $penilaianKinerja->pegawai->nip ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Jabatan:</strong>
                        <p>{{ $penilaianKinerja->pegawai->jabatan ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Unit Kerja:</strong>
                        <p>{{ $penilaianKinerja->pegawai->unit_kerja ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Data Penilai</h5>
                    <hr>
                    <div class="mb-2">
                        <strong>Nama:</strong>
                        <p>{{ $penilaianKinerja->penilai->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Tanggal Dibuat:</strong>
                        <p>{{ $penilaianKinerja->created_at->format('d F Y H:i') }}</p>
                    </div>
                    @if($penilaianKinerja->updated_at != $penilaianKinerja->created_at)
                    <div class="mb-2">
                        <strong>Terakhir Diupdate:</strong>
                        <p>{{ $penilaianKinerja->updated_at->format('d F Y H:i') }}</p>
                    </div>
                    @endif
                    @if($penilaianKinerja->submitted_at)
                    <div class="mb-2">
                        <strong>Tanggal Diajukan:</strong>
                        <p>{{ $penilaianKinerja->submitted_at->format('d F Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($penilaianKinerja->status == 'draft')
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-file-alt me-2"></i>Status Draft
                    </h5>
                    <p class="small mb-0">Penilaian ini masih dalam status draft dan belum diajukan.</p>
                </div>
            </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Skala Penilaian</h5>
                    <hr>
                    <div class="small">
                        <ul class="mb-0">
                            <li><strong>≥ 90:</strong> Sangat Baik</li>
                            <li><strong>80-89:</strong> Baik</li>
                            <li><strong>70-79:</strong> Cukup</li>
                            <li><strong>< 70:</strong> Kurang</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

