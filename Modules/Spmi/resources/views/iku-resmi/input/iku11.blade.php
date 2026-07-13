@extends('layouts.app')

@section('title', 'Input Data IKU 11')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU11</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 11 - Hasil Audit LK/SAKIP/Integritas Akademik')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 11
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <p class="mb-0 small">IKU 11 terdiri dari 4 komponen: 11a (Audit LK), 11b (SAKIP), 11c (Integritas Akademik), 11d (Pencegahan)</p>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <!-- 11a: Opini Audit -->
                        <h6 class="text-primary mb-3">11a - Hasil Audit Laporan Keuangan</h6>
                        <div class="row mb-4">
                            <label class="col-sm-5 col-form-label">Opini Audit</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="data[OPINI_AUDIT][keterangan]" required>
                                    <option value="">-- Pilih Opini --</option>
                                    <option value="WTP" {{ ($existingData['OPINI_AUDIT']->keterangan ?? '') == 'WTP' ? 'selected' : '' }}>WTP (Wajar Tanpa Pengecualian)</option>
                                    <option value="WDP" {{ ($existingData['OPINI_AUDIT']->keterangan ?? '') == 'WDP' ? 'selected' : '' }}>WDP (Wajar Dengan Pengecualian)</option>
                                    <option value="TW" {{ ($existingData['OPINI_AUDIT']->keterangan ?? '') == 'TW' ? 'selected' : '' }}>TW (Tidak Wajar)</option>
                                    <option value="TMP" {{ ($existingData['OPINI_AUDIT']->keterangan ?? '') == 'TMP' ? 'selected' : '' }}>TMP (Tidak Memberikan Pendapat)</option>
                                </select>
                                <input type="hidden" name="data[OPINI_AUDIT][nilai]" value="1">
                            </div>
                        </div>
                        
                        <!-- 11b: SAKIP -->
                        <h6 class="text-primary mb-3">11b - SAKIP</h6>
                        <div class="row mb-4">
                            <label class="col-sm-5 col-form-label">Nilai SAKIP (0-100)</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="data[NILAI_SAKIP][nilai]" 
                                       value="{{ $existingData['NILAI_SAKIP']->nilai_input ?? 0 }}" 
                                       min="0" max="100" step="0.01" required>
                                <small class="text-muted">
                                    0-50: CC-C (Kurang), 50-60: BB (Cukup), 60-75: A (Baik), 75-90: AA (Sangat Baik), >90: AAA (Memuaskan)
                                </small>
                            </div>
                        </div>
                        
                        <!-- 11c: Integritas Akademik -->
                        <h6 class="text-primary mb-3">11c - Integritas Akademik (Laporan Pelanggaran)</h6>
                        <div class="row mb-2">
                            <label class="col-sm-5 col-form-label">Jumlah Laporan Pelanggaran Integritas Akademik</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="data[PELANGGARAN_AKADEMIK][nilai]" 
                                       value="{{ $existingData['PELANGGARAN_AKADEMIK']->nilai_input ?? 0 }}" min="0">
                                <small class="text-muted">Semakin rendah semakin baik (0 = ideal)</small>
                            </div>
                        </div>
                        
                        <!-- 11d: Pencegahan -->
                        <h6 class="text-primary mb-3 mt-4">11d - Pencegahan Kekerasan, Narkoba, Korupsi</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Jenis Pencegahan</th>
                                        <th class="text-center" style="width: 25%;">Direncanakan</th>
                                        <th class="text-center" style="width: 25%;">Terlaksana</th>
                                        <th class="text-center" style="width: 15%;">% Capaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $pencegahan = [
                                        ['key' => 'PENCEGAHAN_KEKERASAN', 'nama' => 'Anti Kekerasan (PPKPT)'],
                                        ['key' => 'PENCEGAHAN_NARKOBA', 'nama' => 'Anti Narkoba'],
                                        ['key' => 'PENCEGAHAN_KORUPSI', 'nama' => 'Anti Korupsi'],
                                    ];
                                    @endphp
                                    @foreach($pencegahan as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end pencegahan-plan" 
                                                   data-key="{{ $item['key'] }}"
                                                   name="data[{{ $item['key'] }}][metadata][direncanakan]" 
                                                   value="{{ $existingData[$item['key']]->metadata['direncanakan'] ?? 0 }}" min="0">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-end pencegahan-real" 
                                                   data-key="{{ $item['key'] }}"
                                                   name="data[{{ $item['key'] }}][metadata][terlaksana]" 
                                                   value="{{ $existingData[$item['key']]->metadata['terlaksana'] ?? 0 }}" min="0">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control form-control-sm text-end pencegahan-persen" 
                                                   id="persen-{{ $item['key'] }}" readonly>
                                            <input type="hidden" class="pencegahan-nilai" name="data[{{ $item['key'] }}][nilai]" value="0">
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

@push('scripts')
<script>
// Auto-calculate persentase pencegahan
document.querySelectorAll('.pencegahan-plan, .pencegahan-real').forEach(input => {
    input.addEventListener('input', function() {
        const key = this.dataset.key;
        const plan = parseFloat(document.querySelector(`.pencegahan-plan[data-key="${key}"]`).value) || 0;
        const real = parseFloat(document.querySelector(`.pencegahan-real[data-key="${key}"]`).value) || 0;
        
        const persen = plan > 0 ? (real / plan * 100).toFixed(2) : 0;
        document.getElementById(`persen-${key}`).value = persen + '%';
        document.querySelector(`.pencegahan-nilai[name="data[${key}][nilai]"]`).value = persen;
    });
});

// Trigger on load
document.querySelectorAll('.pencegahan-plan').forEach(input => {
    input.dispatchEvent(new Event('input'));
});
</script>
@endpush
