@extends('layouts.app')

@section('title', 'Input Data IKU 9')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU9</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 9 - Pendapatan Non-Akademik (Non-UKT)')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 9
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <p class="mb-0">Formula: <code>(Pendapatan Non-Mahasiswa / Total Pendapatan) × 100%</code></p>
                        <p class="mb-0 small mt-2">Isi dalam Rupiah (Rp)</p>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <h6 class="text-primary mb-3">💰 Pendapatan Non-Akademik (Non-UKT)</h6>
                        
                        <div class="row mb-2">
                            <label class="col-sm-7 col-form-label">Pendapatan dari Riset & Inovasi</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[RISET_INOVASI][nilai]" 
                                       value="{{ $existingData['RISET_INOVASI']->nilai_input ?? 0 }}" min="0" step="0.01">
                                <small class="text-muted">Hibah, royalti, komersialisasi</small>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <label class="col-sm-7 col-form-label">Pendapatan dari Kerjasama & Layanan</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[KERJASAMA_LAYANAN][nilai]" 
                                       value="{{ $existingData['KERJASAMA_LAYANAN']->nilai_input ?? 0 }}" min="0" step="0.01">
                                <small class="text-muted">Konsultasi, pelatihan, lab</small>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <label class="col-sm-7 col-form-label">Pendapatan dari Usaha & Unit Bisnis PT</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[USAHA_BISNIS][nilai]" 
                                       value="{{ $existingData['USAHA_BISNIS']->nilai_input ?? 0 }}" min="0" step="0.01">
                                <small class="text-muted">Aset produktif, koperasi</small>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <label class="col-sm-7 col-form-label">Sumbangan / Filantropi</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[SUMBANGAN][nilai]" 
                                       value="{{ $existingData['SUMBANGAN']->nilai_input ?? 0 }}" min="0" step="0.01">
                                <small class="text-muted">Masuk LK resmi</small>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <label class="col-sm-7 col-form-label">Hasil Pengembangan Dana Abadi</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[DANA_ABADI][nilai]" 
                                       value="{{ $existingData['DANA_ABADI']->nilai_input ?? 0 }}" min="0" step="0.01">
                                <small class="text-muted">Bunga, dividen</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-3">
                            <label class="col-sm-7 col-form-label fw-bold">Total SELURUH Pendapatan PT (termasuk UKT/BOPTN)</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[TOTAL_PENDAPATAN][nilai]" 
                                       value="{{ $existingData['TOTAL_PENDAPATAN']->nilai_input ?? 0 }}" min="1" step="0.01" required>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('iku-resmi.show', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId]) }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
