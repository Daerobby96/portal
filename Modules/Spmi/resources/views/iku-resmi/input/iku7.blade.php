@extends('layouts.app')

@section('title', 'Input Data IKU 7')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU7</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 7 - Keterlibatan PT dalam SDGs')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 7
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <ul class="mb-0 small">
                            <li><strong>SDG Wajib:</strong> SDG 1 (Tanpa Kemiskinan), SDG 4 (Pendidikan Berkualitas), SDG 17 (Kemitraan)</li>
                            <li><strong>SDG Pilihan:</strong> Pilih 2 SDG sesuai keunggulan PT Anda</li>
                            <li>Isi jumlah program/kegiatan per SDG</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <h6 class="text-danger mb-3">🔴 SDG Wajib</h6>
                        <div class="row mb-2">
                            <label class="col-sm-6 col-form-label">SDG 1 - Tanpa Kemiskinan</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[SDG1][nilai]" 
                                       value="{{ $existingData['SDG1']->nilai_input ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-sm-6 col-form-label">SDG 4 - Pendidikan Berkualitas</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[SDG4][nilai]" 
                                       value="{{ $existingData['SDG4']->nilai_input ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-6 col-form-label">SDG 17 - Kemitraan</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[SDG17][nilai]" 
                                       value="{{ $existingData['SDG17']->nilai_input ?? 0 }}" min="0">
                            </div>
                        </div>
                        
                        <h6 class="text-primary mb-3">🔵 SDG Pilihan (Sesuai Keunggulan PT)</h6>
                        <div class="row mb-2">
                            <label class="col-sm-6 col-form-label">SDG Pilihan 1</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[SDG_PILIHAN1][nilai]" 
                                       value="{{ $existingData['SDG_PILIHAN1']->nilai_input ?? 0 }}" min="0">
                                <small class="text-muted">Misal: SDG 3 (Kesehatan), SDG 8 (Pekerjaan), dll</small>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-6 col-form-label">SDG Pilihan 2</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[SDG_PILIHAN2][nilai]" 
                                       value="{{ $existingData['SDG_PILIHAN2']->nilai_input ?? 0 }}" min="0">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label fw-bold">Total Seluruh Program SDGs PT</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[TOTAL_PROGRAM][nilai]" 
                                       value="{{ $existingData['TOTAL_PROGRAM']->nilai_input ?? 0 }}" min="1" required>
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
