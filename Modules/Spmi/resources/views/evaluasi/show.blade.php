@extends('layouts.app')

@section('title', 'Detail Evaluasi')
@section('page-title', 'Detail Evaluasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('evaluasi.index') }}">Evaluasi</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Monitoring --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-bar-chart-line-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Data Capaian Monitoring</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3.5">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode Capaian</span>
                        <span class="text-sm font-bold text-slate-700">{{ $evaluasi->monitoring->periode->nama ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Input Capaian</span>
                        <span class="text-sm font-bold text-slate-700">{{ $evaluasi->monitoring->tanggal_input->translatedFormat('d F Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-start py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-1">Indikator Kinerja</span>
                        <span class="text-end">
                            <span class="text-sm font-bold text-slate-800 d-block">{{ $evaluasi->monitoring->indikator->nama ?? '-' }}</span>
                            <span class="text-xs font-mono font-bold text-primary bg-primary-light px-2 py-0.5 rounded-md mt-1 d-inline-block">{{ $evaluasi->monitoring->indikator->kode ?? '-' }}</span>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Pelaksana Kerja</span>
                        <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-xs font-bold">
                            {{ $evaluasi->monitoring->indikator->unit_kerja ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-start py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-1">Standar Acuan</span>
                        <span class="text-xs font-bold text-slate-600 text-end d-block max-w-xs">{{ $evaluasi->monitoring->indikator->standar->nama ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pelapor Capaian</span>
                        <span class="text-xs font-bold text-slate-500 d-flex align-items-center gap-1">
                            <i class="bi bi-person-circle fs-6 text-slate-400"></i>
                            <span>{{ $evaluasi->monitoring->pelapor->name ?? '-' }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Capaian --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-bullseye fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Capaian Target Kuantitatif</h6>
            </div>
            <div class="p-4 d-flex flex-column justify-content-between h-full" style="min-height: 280px;">
                @php $persen = $evaluasi->monitoring->persentase_capaian; @endphp
                <div class="text-center py-4 relative">
                    <div class="inline-flex h-24 w-24 items-center justify-center rounded-full border-4 {{ $persen >= 100 ? 'border-emerald-500 bg-emerald-50/50 text-emerald-600' : ($persen >= 80 ? 'border-amber-500 bg-amber-50/50 text-amber-600' : 'border-rose-500 bg-rose-50/50 text-rose-600') }} shadow-inner">
                        <span class="fs-3 font-black tracking-tight">{{ number_format($persen, 1) }}%</span>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest d-block mt-3">Persentase Target Ketercapaian</span>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Target Standar</span>
                        <span class="text-sm font-bold text-slate-700">
                            {{ $evaluasi->monitoring->indikator->target_nilai ?? '-' }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Capaian Riil</span>
                        <span class="text-sm font-bold text-primary bg-primary-light px-2 py-0.5 rounded-lg">
                            {{ $evaluasi->monitoring->nilai_capaian }} {{ $evaluasi->monitoring->indikator->unit_pengukuran ?? '' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hasil Evaluasi --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <i class="bi bi-clipboard-check-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Hasil & Analisa Evaluasi Mutu</h6>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Evaluasi</span>
                                <span class="text-sm font-bold text-slate-700">{{ $evaluasi->tanggal_evaluasi->translatedFormat('d F Y') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Evaluator Mutu</span>
                                <span class="text-xs font-bold text-slate-700 d-flex align-items-center gap-1">
                                    <i class="bi bi-person-badge-fill fs-6 text-slate-400"></i>
                                    <span>{{ $evaluasi->evaluator->name ?? '-' }}</span>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hasil Penilaian</span>
                                <span>
                                    @if($evaluasi->hasil === 'tercapai')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1 text-xs font-bold">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Tercapai</span>
                                        </span>
                                    @elseif($evaluasi->hasil === 'tidak_tercapai')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-3 py-1 text-xs font-bold">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <span>Tidak Tercapai</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-3 py-1 text-xs font-bold">
                                            <i class="bi bi-shield-fill-exclamation"></i>
                                            <span>Perlu Perhatian</span>
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Analisa Penyebab</span>
                            <div class="text-sm font-semibold text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-100 leading-relaxed">
                                {{ $evaluasi->analisa }}
                            </div>
                        </div>
                        
                        @if($evaluasi->rekomendasi)
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Rekomendasi Tindak Lanjut</span>
                            <div class="text-sm font-semibold text-slate-700 bg-blue-50/30 p-3.5 rounded-xl border border-blue-100/50 leading-relaxed">
                                {{ $evaluasi->rekomendasi }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('evaluasi.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('evaluasi.edit', $evaluasi) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Evaluasi</span>
            </a>
        </div>
    </div>
</div>
@endsection