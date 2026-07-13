@extends('layouts.app')

@section('title', 'Input Data ' . $iku->nomor_iku)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">{{ $iku->nomor_iku }}</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title')
Input Data {{ $iku->nomor_iku }} - {{ $iku->nama }}
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data
                    </h5>
                </div>
                <div class="card-body">
                    
                    <!-- Informasi IKU -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Informasi
                        </h6>
                        <p class="mb-2"><strong>Formula:</strong> <code>{{ $iku->formula }}</code></p>
                        <p class="mb-0"><strong>Deskripsi:</strong> {{ $iku->deskripsi }}</p>
                    </div>
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        
                        <!-- Selector Triwulan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar3 me-1"></i>Pilih Triwulan
                                </label>
                                <select name="triwulan" class="form-select" required>
                                    @foreach($triwulanOptions as $key => $label)
                                    <option value="{{ $key }}" {{ $key == $triwulan ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Pilih triwulan pelaporan atau TAHUNAN untuk data akumulasi
                                </small>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Form input untuk IKU ini sedang dalam pengembangan. 
                            Silakan gunakan form input spesifik atau hubungi administrator.
                        </div>
                        
                        <!-- Generic Input Fields -->
                        <div id="input-fields">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addInputRow()">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Data Input
                                    </button>
                                </div>
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

@push('scripts')
<script>
let rowCount = 0;

function addInputRow() {
    rowCount++;
    const html = `
        <div class="row mb-3 input-row" id="row-${rowCount}">
            <div class="col-md-4">
                <input type="text" class="form-control" name="data[ROW_${rowCount}][kategori]" 
                       placeholder="Kategori" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" class="form-control" name="data[ROW_${rowCount}][nilai]" 
                       placeholder="Nilai" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control" name="data[ROW_${rowCount}][bobot]" 
                       placeholder="Bobot">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="data[ROW_${rowCount}][keterangan]" 
                       placeholder="Keterangan">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeRow(${rowCount})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    document.getElementById('input-fields').insertAdjacentHTML('beforeend', html);
}

function removeRow(id) {
    document.getElementById(`row-${id}`).remove();
}
</script>
@endpush
