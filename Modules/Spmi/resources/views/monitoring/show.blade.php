@extends('layouts.app')

@section('title', 'Detail Monitoring')
@section('page-title', 'Detail Data Monitoring')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring IKU/IKT</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Utama --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-bar-chart-line-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Informasi Monitoring</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3.5">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode Aktif</span>
                        <span class="text-sm font-bold text-slate-700">{{ $monitoring->periode->nama ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Input</span>
                        <span class="text-sm font-bold text-slate-700">{{ $monitoring->tanggal_input->translatedFormat('d F Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-start py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-1">Indikator Kinerja</span>
                        <span class="text-end">
                            <span class="text-sm font-bold text-slate-800 d-block">{{ $monitoring->indikator->nama ?? '-' }}</span>
                            <span class="text-xs font-mono font-bold text-primary bg-primary-light px-2 py-0.5 rounded-md mt-1 d-inline-block">{{ $monitoring->indikator->kode ?? '-' }}</span>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Kerja Pelaksana</span>
                        <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-xs font-bold">
                            {{ $monitoring->indikator->unit_kerja ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-start py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-1">Standar Acuan</span>
                        <span class="text-xs font-bold text-slate-600 text-end d-block max-w-xs">{{ $monitoring->indikator->standar->nama ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pelapor Sistem</span>
                        <span class="text-xs font-bold text-slate-500 d-flex align-items-center gap-1">
                            <i class="bi bi-person-circle fs-6 text-slate-400"></i>
                            <span>{{ $monitoring->pelapor->name ?? '-' }}</span>
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
                <h6 class="mb-0 font-bold text-slate-800">Evaluasi Capaian</h6>
            </div>
            <div class="p-4 d-flex flex-column justify-content-between h-full" style="min-height: 280px;">
                @php $persen = $monitoring->persentase_capaian; @endphp
                <div class="text-center py-4 relative">
                    <div class="inline-flex h-24 w-24 items-center justify-center rounded-full border-4 {{ $persen >= 100 ? 'border-emerald-500 bg-emerald-50/50 text-emerald-600' : ($persen >= 80 ? 'border-amber-500 bg-amber-50/50 text-amber-600' : 'border-rose-500 bg-rose-50/50 text-rose-600') }} shadow-inner">
                        <span class="fs-3 font-black tracking-tight">{{ number_format($persen, 1) }}%</span>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest d-block mt-3">Persentase Capaian Target</span>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Target</span>
                        <span class="text-sm font-bold text-slate-700">
                            {{ $monitoring->indikator->target_nilai ?? '-' }} {{ $monitoring->indikator->unit_pengukuran ?? '' }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Capaian Riil</span>
                        <span class="text-sm font-bold text-primary bg-primary-light px-2 py-0.5 rounded-lg">
                            {{ $monitoring->nilai_capaian }} {{ $monitoring->indikator->unit_pengukuran ?? '' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Validasi</span>
                        <span>
                            @if($monitoring->status === 'verified')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Verified</span>
                                </span>
                            @elseif($monitoring->status === 'submitted')
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-send-fill"></i>
                                    <span>Submitted</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-pencil-fill"></i>
                                    <span>Draft</span>
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Keterangan & Bukti --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-file-text-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Keterangan & Bukti Dokumen</h6>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-7">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keterangan Tambahan</span>
                        @if($monitoring->keterangan)
                            <p class="text-sm font-semibold text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-100 leading-relaxed mb-0">
                                {{ $monitoring->keterangan }}
                            </p>
                        @else
                            <div class="text-xs font-bold text-slate-400 italic bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                                Tidak ada keterangan tambahan yang diunggah.
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Dokumen Pendukung</span>
                        @if($monitoring->bukti_dokumen)
                            <div class="p-3 bg-emerald-50/40 rounded-xl border border-emerald-100 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="d-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 shadow-inner">
                                        <i class="bi bi-file-earmark-check-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest d-block">Unduh Berkas</span>
                                        <span class="text-sm font-extrabold text-slate-700 mt-0.5 d-block">Bukti Fisik Capaian</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $monitoring->bukti_dokumen) }}" 
                                   target="_blank" class="inline-flex items-center gap-1 rounded-xl bg-emerald-100 text-emerald-700 px-3.5 py-2 text-xs font-bold text-decoration-none transition-colors hover:bg-emerald-200">
                                    <i class="bi bi-download"></i>
                                    <span>Download</span>
                                </a>
                            </div>
                        @else
                            <div class="text-xs font-bold text-slate-400 italic bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                                Tidak ada bukti dokumen pendukung yang disertakan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Evaluasi --}}
    @if($monitoring->evaluasi)
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-emerald-50 border-b border-emerald-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                    <i class="bi bi-check2-circle fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-emerald-800">Catatan & Hasil Evaluasi Mutu</h6>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hasil Akhir</span>
                                <span>
                                    @if($monitoring->evaluasi->hasil === 'tercapai')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Tercapai</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-2.5 py-0.5 text-xs font-bold">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <span>Tidak Tercapai</span>
                                        </span>
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Evaluator</span>
                                <span class="text-xs font-bold text-slate-700 d-flex align-items-center gap-1">
                                    <i class="bi bi-person-badge-fill fs-6 text-slate-400"></i>
                                    <span>{{ $monitoring->evaluasi->evaluator->name ?? '-' }}</span>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Evaluasi</span>
                                <span class="text-xs font-bold text-slate-500">{{ $monitoring->evaluasi->created_at->translatedFormat('d F Y H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Catatan Evaluator</span>
                        <div class="text-sm font-semibold text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-100 leading-relaxed mb-0">
                            {{ $monitoring->evaluasi->catatan ?? 'Tidak ada catatan khusus yang ditambahkan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('monitoring.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('monitoring.edit', $monitoring) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Berkas</span>
            </a>
        </div>
    </div>
</div>
@endsection