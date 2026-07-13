@extends('layouts.app')

@section('title', 'Input Data IKU 5')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU5</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 5 - Luaran Kerjasama PT-Industri/Lembaga')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 5
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <p class="mb-0">Formula: <code>(Jumlah Luaran / Total Kerjasama) × 100%</code></p>
                        <p class="mb-0 small mt-2">Luaran dapat berupa: produk, jasa, prototipe, publikasi, paten, karya ilmiah, dsb.</p>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Jumlah Luaran Hasil Kerjasama (judul/karya)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[JUMLAH_LUARAN][nilai]" 
                                       value="{{ $existingData['JUMLAH_LUARAN']->nilai_input ?? 0 }}" min="0" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Total Kerjasama Perguruan Tinggi</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[TOTAL_KERJASAMA][nilai]" 
                                       value="{{ $existingData['TOTAL_KERJASAMA']->nilai_input ?? 0 }}" min="1" required>
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
