@extends('layouts.app')

@section('title', 'Input Data IKU 1')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU1</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 1 - Angka Efisiensi Edukasi PT (AEE PT)')

@section('content')
<div class="container-fluid px-4">
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 1
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk Pengisian</h6>
                        <ul class="mb-0 small">
                            <li>Isi jumlah <strong>Lulus Tepat Waktu</strong> dan <strong>Total Mahasiswa</strong> untuk setiap program pendidikan yang aktif</li>
                            <li>AEE Ideal sudah ditentukan (D1=100%, D2=50%, D3=33%, D4/S1=25%, S2=50%, S3=33%, Profesi/Spesialis/Subspesialis=100%)</li>
                            <li>Sistem akan menghitung AEE Realisasi dan Tingkat Pencapaian secara otomatis</li>
                            <li>Program dengan Total Mahasiswa = 0 tidak akan dihitung</li>
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
                                        <th>Program Pendidikan</th>
                                        <th class="text-center" style="width: 150px;">Lulus Tepat Waktu (n)</th>
                                        <th class="text-center" style="width: 150px;">Total Mahasiswa (t)</th>
                                        <th class="text-center" style="width: 120px;">AEE Ideal (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $programs = [
                                        ['key' => 'D1', 'nama' => 'Diploma 1 (D1)', 'aee_ideal' => 100],
                                        ['key' => 'D2', 'nama' => 'Diploma 2 (D2)', 'aee_ideal' => 50],
                                        ['key' => 'D3', 'nama' => 'Diploma 3 (D3)', 'aee_ideal' => 33],
                                        ['key' => 'D4', 'nama' => 'Diploma 4 / Sarjana Terapan (D4)', 'aee_ideal' => 25],
                                        ['key' => 'S1', 'nama' => 'Sarjana (S1)', 'aee_ideal' => 25],
                                        ['key' => 'S2', 'nama' => 'Magister / Magister Terapan (S2)', 'aee_ideal' => 50],
                                        ['key' => 'S3', 'nama' => 'Doktor / Doktor Terapan (S3)', 'aee_ideal' => 33],
                                        ['key' => 'PROFESI', 'nama' => 'Profesi', 'aee_ideal' => 100],
                                        ['key' => 'SPESIALIS', 'nama' => 'Spesialis', 'aee_ideal' => 100],
                                        ['key' => 'SUBSPESIALIS', 'nama' => 'Subspesialis', 'aee_ideal' => 100],
                                    ];
                                    @endphp
                                    
                                    @foreach($programs as $prog)
                                    <tr>
                                        <td><strong>{{ $prog['nama'] }}</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $prog['key'] }}][nilai]" 
                                                   value="{{ $existingData[$prog['key']]->nilai_input ?? 0 }}"
                                                   min="0">
                                            <input type="hidden" name="data[{{ $prog['key'] }}][bobot]" value="{{ $prog['aee_ideal'] }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $prog['key'] }}][metadata][total_mahasiswa]" 
                                                   value="{{ $existingData[$prog['key']]->metadata['total_mahasiswa'] ?? 0 }}"
                                                   min="0">
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $prog['aee_ideal'] }}%</strong>
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
