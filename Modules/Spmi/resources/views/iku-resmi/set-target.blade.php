@extends('layouts.app')

@section('title', 'Set Target IKU')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('iku-resmi.index') }}">IKU Kemdiktisaintek</a></li>
<li class="breadcrumb-item active">Set Target</li>
@endsection

@section('page-title', 'Set Target IKU per Periode')
@section('page-subtitle', 'Tentukan target capaian untuk setiap IKU')

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('iku-resmi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <a href="{{ route('iku-resmi.analytics', ['periode_id' => $periodeId]) }}" class="btn btn-info btn-sm">
        <i class="bi bi-graph-up me-1"></i> Analisa Kinerja
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    
    <div class="row">
        <div class="col-lg-11 mx-auto">
            
            <!-- Alert Info -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Petunjuk</h6>
                <ul class="mb-0 small">
                    <li>Set target capaian untuk setiap IKU sesuai dengan rencana strategis PT</li>
                    <li>Target dapat berbeda per periode akademik</li>
                    <li>Status capaian akan otomatis diupdate saat perhitungan IKU dilakukan</li>
                    <li>Kosongkan field jika tidak ingin set target untuk IKU tertentu</li>
                </ul>
            </div>
            
            <!-- Form Set Target -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-bullseye me-2"></i>Set Target IKU
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-calendar-event me-1"></i> Periode: 
                                {{ $periodes->firstWhere('id', $periodeId)?->nama ?? 'Pilih Periode' }}
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($periodes as $p)
                                <li>
                                    <a class="dropdown-item {{ $p->id == $periodeId ? 'active' : '' }}" 
                                       href="?periode_id={{ $p->id }}">
                                        {{ $p->nama }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <form method="POST" action="{{ route('iku-resmi.store-target') }}">
                        @csrf
                        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">No. IKU</th>
                                        <th>Nama Indikator</th>
                                        <th class="text-center" style="width: 120px;">Satuan</th>
                                        <th class="text-center" style="width: 150px;">Target</th>
                                        <th class="text-center" style="width: 150px;">Nilai Saat Ini</th>
                                        <th class="text-center" style="width: 120px;">Capaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ikuList as $iku)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">
                                                {{ $iku->nomor_iku }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $iku->nama }}</strong>
                                                @if($iku->deskripsi_target)
                                                <p class="text-muted small mb-0">{{ $iku->deskripsi_target }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $iku->satuan }}</span>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control form-control-sm text-end" 
                                                   name="targets[{{ $iku->id }}]" 
                                                   value="{{ $iku->target }}"
                                                   min="0" 
                                                   step="0.01"
                                                   placeholder="0">
                                        </td>
                                        <td class="text-center">
                                            <strong class="fs-6">
                                                {{ number_format($iku->nilai_hasil, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            @if($iku->persentase_capaian > 0)
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-{{ $iku->persentase_capaian >= 100 ? 'success' : ($iku->persentase_capaian >= 80 ? 'info' : ($iku->persentase_capaian >= 60 ? 'warning' : 'danger')) }}" 
                                                     style="width: {{ min($iku->persentase_capaian, 100) }}%">
                                                    <strong>{{ number_format($iku->persentase_capaian, 1) }}%</strong>
                                                </div>
                                            </div>
                                            @else
                                            <span class="text-muted small">Belum dihitung</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('iku-resmi.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Simpan Target
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
