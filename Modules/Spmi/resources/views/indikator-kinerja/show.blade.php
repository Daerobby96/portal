@extends('layouts.app')

@section('title', 'Detail Indikator Kinerja')
@section('page-title', 'Detail Indikator Kinerja')
@section('page-subtitle', $indikator->nama)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('indikator-kinerja.index') }}">Indikator Kinerja</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Informasi Dasar --}}
    <div class="col-lg-5">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Informasi Dasar</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3.5 modern-badge-container">
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Indikator</span>
                        <span class="text-sm font-mono font-bold text-primary bg-primary-light px-2.5 py-1 rounded-lg">
                            {{ $indikator->kode }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-start py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-1">Standar Terkait</span>
                        <span class="text-end">
                            @if($indikator->standar)
                                <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-1 text-xs font-extrabold mb-1">
                                    {{ $indikator->standar->kode }}
                                </span>
                                <div class="text-xs font-semibold text-slate-500 max-w-xs">{{ $indikator->standar->nama }}</div>
                            @else
                                <span class="text-xs font-semibold text-slate-400 italic">Tidak ada standar terkait</span>
                            @endif
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Pengukuran</span>
                        <span class="text-sm font-bold text-slate-700">{{ $indikator->unit_pengukuran }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Target Nilai</span>
                        <span class="text-end">
                            @if($indikator->target_deskripsi)
                                <span class="text-sm font-bold text-slate-800 block">{{ $indikator->target_deskripsi }}</span>
                                @if($indikator->target_nilai)
                                    <small class="text-xs font-semibold text-slate-400 block mt-0.5">({{ $indikator->target_nilai + 0 }} {{ $indikator->unit_pengukuran }})</small>
                                @endif
                            @else
                                <span class="text-sm font-bold text-slate-800">{{ $indikator->target_nilai + 0 }} {{ $indikator->unit_pengukuran }}</span>
                            @endif
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Kerja</span>
                        <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-xs font-bold">
                            {{ $indikator->unit_kerja }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Keaktifan</span>
                        <span>
                            @if($indikator->is_aktif)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Aktif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-dash-circle-fill"></i>
                                    <span>Nonaktif</span>
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historis Capaian --}}
    <div class="col-lg-7">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-graph-up fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Historis Capaian (Monitoring)</h6>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 ps-4">Periode</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Nilai Capaian</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Hasil Evaluasi</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Analisa Penyebab</th>
                            </tr>
                        </thead>
                        <tbody class="border-t-0">
                            @forelse($indikator->monitorings as $mon)
                            <tr class="transition-colors hover:bg-slate-50/40">
                                <td class="py-3.5 ps-4 text-sm font-bold text-slate-700">{{ $mon->periode->nama ?? '-' }}</td>
                                <td class="py-3.5 text-sm font-bold text-primary">{{ $mon->nilai_capaian }} {{ $indikator->unit_pengukuran }}</td>
                                <td class="py-3.5">
                                    @if($mon->evaluasi)
                                        @if($mon->evaluasi->hasil === 'tercapai')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Tercapai</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-0.5 text-xs font-bold">
                                                <i class="bi bi-stars"></i>
                                                <span>Lampaui</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                            <i class="bi bi-clock"></i>
                                            <span>Belum Evaluasi</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 pe-4">
                                    <p class="mb-0 text-xs font-medium text-slate-400 max-w-xs truncate" title="{{ $mon->evaluasi->analisa_penyebab ?? '-' }}">
                                        {{ $mon->evaluasi->analisa_penyebab ?? '—' }}
                                    </p>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-5">
                                    <div class="d-flex flex-column align-items-center justify-center py-4">
                                        <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 text-slate-300 border border-slate-100 mb-2">
                                            <i class="bi bi-graph-down fs-4"></i>
                                        </div>
                                        <h6 class="font-bold text-slate-600 mb-0 text-xs">Belum Ada Riwayat Monitoring</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12 mt-4">
        <div class="d-flex gap-2">
            <a href="{{ route('indikator-kinerja.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('indikator-kinerja.edit', $indikator) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Indikator</span>
            </a>
        </div>
    </div>
</div>
@endsection
