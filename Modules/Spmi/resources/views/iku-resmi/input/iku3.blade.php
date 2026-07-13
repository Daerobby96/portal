@extends('layouts.app')

@section('title', 'Input Data IKU 3')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU3</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 3 - Mahasiswa Berkegiatan/Berprestasi di Luar Prodi')

@section('content')
<div class="container-fluid px-4">
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 3
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk Pengisian</h6>
                        <ul class="mb-0 small">
                            <li>Isi jumlah mahasiswa untuk setiap kategori kegiatan/prestasi</li>
                            <li>Bobot sudah ditentukan (0.05 - 1)</li>
                            <li>Jangan lupa isi <strong>Total Mahasiswa D & S Aktif</strong> di bagian bawah</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <!-- Pembelajaran Luar Kampus -->
                        <h6 class="text-primary mb-3">📚 Pembelajaran Luar Kampus</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Jenis Kegiatan</th>
                                        <th class="text-center" style="width: 150px;">Jumlah Mahasiswa</th>
                                        <th class="text-center" style="width: 100px;">Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $kegiatan = [
                                        ['key' => 'KAMPUS_10SKS', 'nama' => 'Pembelajaran luar kampus ≥10 SKS', 'bobot' => 1],
                                        ['key' => 'KAMPUS_6_10SKS', 'nama' => 'Pembelajaran luar kampus 6–10 SKS', 'bobot' => 0.6],
                                        ['key' => 'KAMPUS_5SKS', 'nama' => 'Pembelajaran luar kampus ≤5 SKS', 'bobot' => 0.4],
                                    ];
                                    @endphp
                                    @foreach($kegiatan as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $item['key'] }}][nilai]" 
                                                   value="{{ $existingData[$item['key']]->nilai_input ?? 0 }}" min="0">
                                            <input type="hidden" name="data[{{ $item['key'] }}][bobot]" value="{{ $item['bobot'] }}">
                                        </td>
                                        <td class="text-center">{{ $item['bobot'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Prestasi Internasional -->
                        <h6 class="text-primary mb-3">🌍 Prestasi Tingkat Internasional</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Kategori Prestasi</th>
                                        <th class="text-center" style="width: 150px;">Jumlah Mahasiswa</th>
                                        <th class="text-center" style="width: 100px;">Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $prestasiIntl = [
                                        ['key' => 'INTL_JUARA1', 'nama' => 'Juara 1', 'bobot' => 1],
                                        ['key' => 'INTL_JUARA23', 'nama' => 'Juara 2/3/Favorit', 'bobot' => 0.5],
                                        ['key' => 'INTL_HARAPAN', 'nama' => 'Juara Harapan', 'bobot' => 0.3],
                                        ['key' => 'INTL_FINALIS', 'nama' => 'Finalis', 'bobot' => 0.2],
                                    ];
                                    @endphp
                                    @foreach($prestasiIntl as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $item['key'] }}][nilai]" 
                                                   value="{{ $existingData[$item['key']]->nilai_input ?? 0 }}" min="0">
                                            <input type="hidden" name="data[{{ $item['key'] }}][bobot]" value="{{ $item['bobot'] }}">
                                        </td>
                                        <td class="text-center">{{ $item['bobot'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Prestasi Nasional -->
                        <h6 class="text-primary mb-3">🇮🇩 Prestasi Tingkat Nasional</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Kategori Prestasi</th>
                                        <th class="text-center" style="width: 150px;">Jumlah Mahasiswa</th>
                                        <th class="text-center" style="width: 100px;">Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $prestasiNas = [
                                        ['key' => 'NAS_JUARA1', 'nama' => 'Juara 1', 'bobot' => 0.6],
                                        ['key' => 'NAS_JUARA23', 'nama' => 'Juara 2/3/Favorit', 'bobot' => 0.3],
                                        ['key' => 'NAS_HARAPAN', 'nama' => 'Juara Harapan', 'bobot' => 0.2],
                                        ['key' => 'NAS_FINALIS', 'nama' => 'Finalis', 'bobot' => 0.1],
                                    ];
                                    @endphp
                                    @foreach($prestasiNas as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $item['key'] }}][nilai]" 
                                                   value="{{ $existingData[$item['key']]->nilai_input ?? 0 }}" min="0">
                                            <input type="hidden" name="data[{{ $item['key'] }}][bobot]" value="{{ $item['bobot'] }}">
                                        </td>
                                        <td class="text-center">{{ $item['bobot'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Prestasi Provinsi -->
                        <h6 class="text-primary mb-3">🏛️ Prestasi Tingkat Provinsi</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Kategori Prestasi</th>
                                        <th class="text-center" style="width: 150px;">Jumlah Mahasiswa</th>
                                        <th class="text-center" style="width: 100px;">Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $prestasiProv = [
                                        ['key' => 'PROV_JUARA1', 'nama' => 'Juara 1', 'bobot' => 0.4],
                                        ['key' => 'PROV_JUARA23', 'nama' => 'Juara 2/3/Favorit', 'bobot' => 0.2],
                                        ['key' => 'PROV_HARAPAN', 'nama' => 'Juara Harapan', 'bobot' => 0.1],
                                        ['key' => 'PROV_FINALIS', 'nama' => 'Finalis', 'bobot' => 0.05],
                                    ];
                                    @endphp
                                    @foreach($prestasiProv as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end" 
                                                   name="data[{{ $item['key'] }}][nilai]" 
                                                   value="{{ $existingData[$item['key']]->nilai_input ?? 0 }}" min="0">
                                            <input type="hidden" name="data[{{ $item['key'] }}][bobot]" value="{{ $item['bobot'] }}">
                                        </td>
                                        <td class="text-center">{{ $item['bobot'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Total Mahasiswa -->
                        <div class="alert alert-warning">
                            <strong>⚠️ Penting:</strong> Isi total mahasiswa D & S aktif untuk perhitungan persentase
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label fw-bold">Total Mahasiswa D & S Aktif (t)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" 
                                       name="data[TOTAL_MAHASISWA][nilai]" 
                                       value="{{ $existingData['TOTAL_MAHASISWA']->nilai_input ?? 0 }}"
                                       min="1" required>
                                <input type="hidden" name="data[TOTAL_MAHASISWA][metadata][total_mahasiswa]" value="1">
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
