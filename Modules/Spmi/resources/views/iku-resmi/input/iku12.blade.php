@extends('layouts.app')

@section('title', 'Input Data IKU 12')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.show', $iku->id) }}">IKU12</a></li>
<li class="breadcrumb-item active">Input Data</li>
@endsection

@section('page-title', 'Input Data IKU 12 - Ketersediaan Renstra Kesejahteraan Dosen')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Form Input Data IKU 12
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                        <p class="mb-0">Centang komponen yang tersedia. Formula: <code>(Komponen Terpenuhi / Total Komponen) × 100%</code></p>
                    </div>
                    
                    <form method="POST" action="{{ route('iku-resmi.store-data', $iku->id) }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                        
                        <h6 class="text-primary mb-3">Checklist Ketersediaan Dokumen</h6>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 70%;">Komponen Dokumen</th>
                                        <th class="text-center" style="width: 30%;">Tersedia?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $komponen = [
                                        ['key' => 'RENSTRA_DITETAPKAN', 'nama' => 'Dokumen Renstra / Rencana Induk SDM ditetapkan pimpinan PT'],
                                        ['key' => 'KEBIJAKAN_FINANSIAL', 'nama' => 'Arah kebijakan & program kesejahteraan finansial dosen (gaji, insentif, tunjangan)'],
                                        ['key' => 'KEBIJAKAN_NONFINANSIAL', 'nama' => 'Arah kebijakan & program kesejahteraan non-finansial (karier, beban kerja, kesehatan)'],
                                        ['key' => 'GAJI_ASISTEN_AHLI', 'nama' => 'Penghasilan Asisten Ahli ≥1,5× UMP'],
                                        ['key' => 'GAJI_LEKTOR', 'nama' => 'Penghasilan Lektor ≥3× UMP'],
                                        ['key' => 'GAJI_LEKTOR_KEPALA', 'nama' => 'Penghasilan Lektor Kepala ≥4× UMP'],
                                        ['key' => 'GAJI_PROFESOR', 'nama' => 'Penghasilan Profesor ≥6× UMP'],
                                        ['key' => 'INDIKATOR_KINERJA', 'nama' => 'Indikator kinerja, target, dan horizon waktu tercantum'],
                                        ['key' => 'INTEGRASI_RENSTRA', 'nama' => 'Terintegrasi dengan Renstra/RKAT institusi'],
                                    ];
                                    @endphp
                                    
                                    @foreach($komponen as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input komponen-check" type="checkbox" 
                                                       name="data[{{ $item['key'] }}][nilai]" value="1"
                                                       {{ ($existingData[$item['key']]->nilai_input ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    <span class="badge bg-success check-label-ya" style="display: {{ ($existingData[$item['key']]->nilai_input ?? 0) == 1 ? 'inline' : 'none' }}">Ya</span>
                                                    <span class="badge bg-secondary check-label-tidak" style="display: {{ ($existingData[$item['key']]->nilai_input ?? 0) == 1 ? 'none' : 'inline' }}">Tidak</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-success mt-3">
                            <strong>📊 Status:</strong> 
                            <span id="total-terpenuhi">0</span> dari 9 komponen terpenuhi 
                            (<span id="persen-terpenuhi">0</span>%)
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
// Update label & count
function updateKomponenCount() {
    const checked = document.querySelectorAll('.komponen-check:checked').length;
    const total = document.querySelectorAll('.komponen-check').length;
    const persen = ((checked / total) * 100).toFixed(2);
    
    document.getElementById('total-terpenuhi').textContent = checked;
    document.getElementById('persen-terpenuhi').textContent = persen;
}

// Toggle badge labels
document.querySelectorAll('.komponen-check').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const row = this.closest('tr');
        const labelYa = row.querySelector('.check-label-ya');
        const labelTidak = row.querySelector('.check-label-tidak');
        
        if (this.checked) {
            labelYa.style.display = 'inline';
            labelTidak.style.display = 'none';
        } else {
            labelYa.style.display = 'none';
            labelTidak.style.display = 'inline';
        }
        
        updateKomponenCount();
    });
});

// Initial count
updateKomponenCount();
</script>
@endpush
