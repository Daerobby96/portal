@extends('layouts.app')

@section('title', 'Detail ' . $iku->nomor_iku)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item active">{{ $iku->nomor_iku }}</li>
@endsection

@section('page-title')
{{ $iku->nomor_iku }} - {{ $iku->nama }}
@endsection

@section('page-actions')
<div class="d-flex gap-2">
    <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-calendar3 me-1"></i> {{ $triwulanOptions[$triwulan] ?? 'TAHUNAN' }}
        </button>
        <ul class="dropdown-menu">
            @foreach($triwulanOptions as $key => $label)
            <li>
                <a class="dropdown-item {{ $key == $triwulan ? 'active' : '' }}" 
                   href="?periode_id={{ $periodeId }}&triwulan={{ $key }}">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    
    <a href="{{ route('iku-resmi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    @if(in_array($iku->nomor_iku, ['IKU2', 'IKU4']))
    <form method="POST" action="{{ route('iku-resmi.sync', $iku->id) }}" class="d-inline">
        @csrf
        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
        <input type="hidden" name="triwulan" value="TAHUNAN">
        <button type="submit" class="btn btn-success btn-sm">
            <i class="bi bi-arrow-repeat me-1"></i> Tarik Data Sistem
        </button>
    </form>
    @endif
    <a href="{{ route('iku-resmi.input', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId, 'triwulan' => $triwulan]) }}" 
       class="btn btn-primary btn-sm">
        <i class="bi bi-pencil-square me-1"></i> Input Data
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <div class="row g-4">
        
        <!-- Informasi IKU -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-info-circle me-2"></i>Informasi IKU
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th style="width: 40%;">Nomor IKU</th>
                            <td><span class="badge bg-primary">{{ $iku->nomor_iku }}</span></td>
                        </tr>
                        <tr>
                            <th>Sifat</th>
                            <td>{!! $iku->sifat_badge !!}</td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td>{{ $iku->satuan }}</td>
                        </tr>
                        <tr>
                            <th>Sheet Excel</th>
                            <td><code>{{ $iku->sheet_name }}</code></td>
                        </tr>
                        <tr>
                            <th>Referensi</th>
                            <td>{{ $iku->referensi }}</td>
                        </tr>
                    </table>
                    
                    <hr>
                    
                    <h6 class="mb-2">Deskripsi</h6>
                    <p class="small text-muted">{{ $iku->deskripsi }}</p>
                    
                    <h6 class="mb-2 mt-3">Formula</h6>
                    <p class="small"><code class="bg-light p-2 d-block rounded">{{ $iku->formula }}</code></p>
                </div>
            </div>
        </div>
        
        <!-- Hasil Perhitungan -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success bg-opacity-10 border-0">
                    <h6 class="mb-0 text-success">
                        <i class="bi bi-calculator me-2"></i>Hasil Perhitungan
                    </h6>
                </div>
                <div class="card-body">
                    @if($hasil)
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <p class="text-muted small mb-1">Nilai IKU</p>
                                <h2 class="mb-0 fw-bold text-primary">
                                    {{ number_format($hasil->nilai_hasil, 2, ',', '.') }}
                                    <small class="fs-6 text-muted">{{ $iku->satuan }}</small>
                                </h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <p class="text-muted small mb-1">Status Capaian</p>
                                <h5 class="mb-0">{!! $hasil->status_badge !!}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <p class="text-muted small mb-1">Terakhir Dihitung</p>
                                <p class="mb-0 fw-bold">
                                    {{ $hasil->calculated_at ? $hasil->calculated_at->format('d M Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($hasil->catatan)
                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Catatan:</strong> {{ $hasil->catatan }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-calculator-fill text-muted fs-1"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada hasil perhitungan</p>
                        <small class="text-muted">Silakan input data dan hitung IKU terlebih dahulu</small>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Data Input -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-table me-2"></i>Data Input
                    </h6>
                    @if($dataInputs->count() > 0)
                    <form method="POST" action="{{ route('iku-resmi.delete-data', $iku->id) }}" 
                          onsubmit="return confirm('Hapus semua data input untuk {{ $triwulanOptions[$triwulan] ?? $triwulan }} periode ini?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Hapus Data
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($dataInputs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Nilai Input</th>
                                    <th class="text-center">Bobot</th>
                                    <th class="text-end">Nilai Tertimbang</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataInputs as $input)
                                <tr>
                                    <td><strong>{{ $input->kategori }}</strong></td>
                                    <td class="text-end">
                                        {{ number_format($input->nilai_input, 2, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $input->bobot ? number_format($input->bobot, 2) : '-' }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($input->nilai_tertimbang, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $input->keterangan ?? '-' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-end">
                                        {{ number_format($dataInputs->sum('nilai_tertimbang'), 2, ',', '.') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted fs-1"></i>
                        <p class="text-muted mt-2 mb-3">Belum ada data input</p>
                        <a href="{{ route('iku-resmi.input', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId]) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Input Data Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection
