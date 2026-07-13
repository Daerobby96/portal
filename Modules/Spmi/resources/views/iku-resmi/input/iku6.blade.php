@extends('layouts.app')

@section('title', 'Input Data IKU 6')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU6</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 6 - Publikasi Bereputasi Internasional (Scopus/WoS)')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 6
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <ul class="mb-0 small">
                            <li>Isi jumlah publikasi per kuartil Scopus/WoS</li>
                            <li>Centang jika ada kolaborasi internasional (tambahan bobot +0.25)</li>
                            <li>Penerbit seperti MDPI, Frontiers, Hindawi tidak dihitung</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Jenis Publikasi</th>
                                        <th class="text-center" style="width: 120px;">Jumlah</th>
                                        <th class="text-center" style="width: 100px;">Bobot</th>
                                        <th class="text-center" style="width: 150px;">Kolaborasi Intl?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $publikasi = [
                                        ['key' => 'TOP_TIER', 'nama' => 'Jurnal Top Tier (Scopus/WoS)', 'bobot' => 1.2],
                                        ['key' => 'Q1', 'nama' => 'Jurnal Q1 (Scopus/WoS)', 'bobot' => 1],
                                        ['key' => 'Q2', 'nama' => 'Jurnal Q2 (Scopus/WoS)', 'bobot' => 0.75],
                                        ['key' => 'Q3', 'nama' => 'Jurnal Q3 (Scopus/WoS)', 'bobot' => 0.5],
                                        ['key' => 'Q4', 'nama' => 'Jurnal Q4 (Scopus/WoS)', 'bobot' => 0.25],
                                        ['key' => 'PROSIDING', 'nama' => 'Prosiding Internasional (Scopus/WoS)', 'bobot' => 0.25],
                                    ];
                                    @endphp
                                    @foreach($publikasi as $pub)
                                    <tr>
                                        <td>{{ $pub['nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $pub['key'] }}][nilai]" 
                                                   value="{{ $existingData[$pub['key']]->nilai_input ?? 0 }}" min="0">
                                            <input type="hidden" name="data[{{ $pub['key'] }}][bobot]" value="{{ $pub['bobot'] }}">
                                        </td>
                                        <td class="text-center">{{ $pub['bobot'] }}</td>
                                        <td class="text-center">
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $pub['key'] }}][metadata][kolaborasi_intl]" 
                                                   value="{{ $existingData[$pub['key']]->metadata['kolaborasi_intl'] ?? 0 }}" 
                                                   min="0" placeholder="0">
                                            <small class="text-muted">jumlah artikel</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label fw-bold">Total Publikasi PT (t)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="data[TOTAL_PUBLIKASI][nilai]" 
                                       value="{{ $existingData['TOTAL_PUBLIKASI']->nilai_input ?? 0 }}" min="1" required>
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
