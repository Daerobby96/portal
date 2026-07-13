@extends('layouts.app')

@section('title', 'Input Data IKU 10')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU10</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 10 - Zona Integritas (WBK/WBBM)')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 10
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <p class="mb-0">Formula: <code>Jumlah Unit WBK + Jumlah Unit WBBM</code></p>
                        <ul class="mb-0 small mt-2">
                            <li><strong>WBK</strong> = Wilayah Bebas dari Korupsi</li>
                            <li><strong>WBBM</strong> = Wilayah Birokrasi Bersih dan Melayani</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <div class="row mb-3">
                            <label class="col-sm-7 col-form-label">Jumlah Unit Kerja yang Mengajukan Zona Integritas (WBK)</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[WBK][nilai]" 
                                       value="{{ $existingData['WBK']->nilai_input ?? 0 }}" min="0" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-7 col-form-label">Jumlah Unit Kerja yang Mengajukan Zona Integritas (WBBM)</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="data[WBBM][nilai]" 
                                       value="{{ $existingData['WBBM']->nilai_input ?? 0 }}" min="0" required>
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
