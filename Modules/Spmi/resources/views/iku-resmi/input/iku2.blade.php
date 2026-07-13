@extends('layouts.app')

@section('title', 'Input Data IKU 2')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU2</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 2 - Lulusan Bekerja/Wirausaha ≤1 Tahun')

@section('content')
<div class="container-fluid px-4">
    
    <div class="row">
        <div class="col-lg-11 mx-auto">
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 2
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk Pengisian</h6>
                        <ul class="mb-0 small">
                            <li>Isi jumlah responden tracer study untuk setiap kategori</li>
                            <li>Bobot sudah ditentukan berdasarkan Kepmendikti (0.2 - 1.2)</li>
                            <li>Formula: <code>(∑ nᵢ × kᵢ) / t × 100%</code></li>
                            <li>UMP = Upah Minimum Provinsi</li>
                        </ul>
                    </div>
                    
                    <form id="sync-form" method="POST" action="{{ route('iku-resmi.sync', $iku->id) }}" class="d-none">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                    </form>

                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%;">Kategori Lulusan</th>
                                        <th class="text-center" style="width: 150px;">Jumlah Responden (n)</th>
                                        <th class="text-center" style="width: 120px;">Bobot (k)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $categories = [
                                        ['key' => 'BEKERJA_6BLN_GAJI_HIGH', 'nama' => 'Bekerja – masa tunggu <6 bln & gaji >1,2× UMP', 'bobot' => 1],
                                        ['key' => 'BEKERJA_1THN_GAJI_HIGH', 'nama' => 'Bekerja – masa tunggu <1 thn & gaji >1,2× UMP', 'bobot' => 0.8],
                                        ['key' => 'BEKERJA_1THN_GAJI_LOW', 'nama' => 'Bekerja – masa tunggu <1 thn & gaji ≤1,2× UMP', 'bobot' => 0.6],
                                        ['key' => 'WIRAUSAHA_6BLN_HIGH', 'nama' => 'Wirausaha Founder/Co-Founder <6 bln & >1,2× UMP', 'bobot' => 1.2],
                                        ['key' => 'WIRAUSAHA_1THN_HIGH', 'nama' => 'Wirausaha Founder/Co-Founder >6 bln & >1,2× UMP', 'bobot' => 1],
                                        ['key' => 'WIRAUSAHA_6BLN_MID', 'nama' => 'Wirausaha Founder/Co-Founder <6 bln & <1,2× UMP', 'bobot' => 0.8],
                                        ['key' => 'WIRAUSAHA_1THN_MID', 'nama' => 'Wirausaha Founder/Co-Founder >6 bln & <1,2× UMP', 'bobot' => 0.6],
                                        ['key' => 'FREELANCE_6BLN_HIGH', 'nama' => 'Freelancer <6 bln & >1,2× UMP', 'bobot' => 0.5],
                                        ['key' => 'FREELANCE_1THN_HIGH', 'nama' => 'Freelancer >6 bln & >1,2× UMP', 'bobot' => 0.4],
                                        ['key' => 'FREELANCE_6BLN_LOW', 'nama' => 'Freelancer <6 bln & <1,2× UMP', 'bobot' => 0.3],
                                        ['key' => 'FREELANCE_1THN_LOW', 'nama' => 'Freelancer >6 bln & <1,2× UMP', 'bobot' => 0.2],
                                        ['key' => 'STUDI_LANJUT', 'nama' => 'Melanjutkan studi <12 bln setelah lulus', 'bobot' => 0.6],
                                        ['key' => 'BEKERJA_SEBELUM_HIGH', 'nama' => 'Bekerja sebelum lulus (gaji >1,2× UMP)', 'bobot' => 1],
                                        ['key' => 'WIRAUSAHA_SEBELUM_HIGH', 'nama' => 'Wirausaha/Freelancer sebelum lulus (gaji >1,2× UMP)', 'bobot' => 1],
                                        ['key' => 'WIRAUSAHA_SEBELUM_LOW', 'nama' => 'Wirausaha/Freelancer sebelum lulus (gaji ≤1,2× UMP)', 'bobot' => 0.6],
                                    ];
                                    @endphp
                                    
                                    @foreach($categories as $cat)
                                    <tr>
                                        <td><small>{{ $cat['nama'] }}</small></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $cat['key'] }}][nilai]" 
                                                   value="{{ $existingData[$cat['key']]->nilai_input ?? 0 }}"
                                                   min="0">
                                            <input type="hidden" name="data[{{ $cat['key'] }}][bobot]" value="{{ $cat['bobot'] }}">
                                        </td>
                                        <td class="text-center align-middle">
                                            <strong>{{ $cat['bobot'] }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('iku-resmi.show', ['iku_resmi' => $iku->id, 'periode_id' => $periodeId]) }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <div>
                                <button type="submit" form="sync-form" class="btn btn-success me-2">
                                    <i class="bi bi-arrow-repeat me-1"></i> Tarik Data Sistem
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
            
        </div>
    </div>
    
</div>
@endsection
