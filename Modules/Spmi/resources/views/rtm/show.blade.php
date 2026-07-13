@extends('layouts.app')

@section('title', 'Detail RTM')
@section('page-title', 'Detail RTM')
@section('page-subtitle', $rTM->judul_rapat)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rtm.index') }}">RTM</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Utama --}}
    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] sticky-top" style="top: 80px; overflow: hidden;">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Info Umum</h6>
                </div>
                
                @if($rTM->status === 'selesai')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600 border border-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span>Selesai</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-600 border border-amber-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>Draft</span>
                    </span>
                @endif
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Rapat</span>
                        <span class="text-sm font-bold text-slate-700">{{ $rTM->tanggal_rapat->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">File Absensi</span>
                        <span>
                            @if($rTM->file_absensi)
                                <a href="{{ asset('storage/' . $rTM->file_absensi) }}" target="_blank" class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-600 border border-blue-100 transition-colors hover:bg-blue-100 text-decoration-none">
                                    <i class="bi bi-file-earmark-arrow-down-fill text-blue-500"></i>
                                    <span>Lihat File</span>
                                </a>
                            @else
                                <span class="text-xs font-semibold text-slate-400 italic">Belum Diunggah</span>
                            @endif
                        </span>
                    </div>
                </div>

                @if($rTM->agenda)
                <div class="mb-4 p-3 rounded-xl border border-slate-100 bg-slate-50/40 relative overflow-hidden">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 d-block mb-1">Agenda Utama</span>
                    <p class="mb-0 text-slate-700 text-sm leading-relaxed font-medium">{{ $rTM->agenda }}</p>
                </div>
                @endif

                <div class="pt-4 border-t border-slate-100">
                    <h6 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 mb-3"><i class="bi bi-bar-chart-fill me-1 text-indigo-500"></i>Status Temuan Periode Ini</h6>
                    
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-xl bg-rose-50/40 border border-rose-100/50">
                            <span class="text-xs font-bold text-rose-600">Belum Selesai (Open)</span>
                            <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-rose-500 px-2 text-xs font-bold text-white shadow-sm">{{ $findingStats['open'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-xl bg-amber-50/40 border border-amber-100/50">
                            <span class="text-xs font-bold text-amber-600">Dalam Proses</span>
                            <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-amber-500 px-2 text-xs font-bold text-white shadow-sm">{{ $findingStats['in_progress'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-xl bg-emerald-50/40 border border-emerald-100/50">
                            <span class="text-xs font-bold text-emerald-600">Sudah Selesai (Closed)</span>
                            <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-emerald-500 px-2 text-xs font-bold text-white shadow-sm">{{ $findingStats['closed'] }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column gap-2 mt-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('rtm.pdf', $rTM) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50/30 px-4 py-2.5 text-sm font-bold text-rose-600 shadow-sm transition-all hover:bg-rose-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0" target="_blank">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Cetak Laporan RTM</span>
                    </a>
                    <a href="{{ route('rtm.edit', $rTM) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit Data RTM</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Notulensi & Keputusan --}}
    <div class="col-lg-8">
        {{-- Section 1: Inputs --}}
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-journal-arrow-down fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">I. Masukan Tinjauan (Inputs)</h6>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th width="32%" class="text-xs font-bold uppercase tracking-wider text-slate-400 py-3 ps-4">Kategori Tinjauan</th>
                                <th class="text-xs font-bold uppercase tracking-wider text-slate-400 py-3 pe-4">Uraian / Hasil Pembahasan</th>
                            </tr>
                        </thead>
                        <tbody class="border-t-0">
                            <tr class="border-b border-slate-100">
                                <td class="font-bold text-slate-700 text-sm py-3.5 ps-4">1. Hasil Audit Internal</td>
                                <td class="text-slate-600 text-sm py-3.5 pe-4 leading-relaxed font-medium">{{ $rTM->input_audit_internal ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="font-bold text-slate-700 text-sm py-3.5 ps-4">2. Umpan Balik Pelanggan</td>
                                <td class="text-slate-600 text-sm py-3.5 pe-4 leading-relaxed font-medium">{{ $rTM->input_umpan_balik ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="font-bold text-slate-700 text-sm py-3.5 ps-4">3. Kinerja Proses & Kesesuaian</td>
                                <td class="text-slate-600 text-sm py-3.5 pe-4 leading-relaxed font-medium">{{ $rTM->input_kinerja_proses ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="font-bold text-slate-700 text-sm py-3.5 ps-4">4. Status Tindakan Perbaikan</td>
                                <td class="text-slate-600 text-sm py-3.5 pe-4 leading-relaxed font-medium">{{ $rTM->input_status_tindakan ?? '-' }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="font-bold text-slate-700 text-sm py-3.5 ps-4">5. Perubahan Sistem Pengelolaan</td>
                                <td class="text-slate-600 text-sm py-3.5 pe-4 leading-relaxed font-medium">{{ $rTM->input_perubahan_sistem ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold text-slate-700 text-sm py-3.5 ps-4">6. Rekomendasi Peningkatan</td>
                                <td class="text-slate-600 text-sm py-3.5 pe-4 leading-relaxed font-medium">{{ $rTM->input_rekomendasi ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Section 2: Outputs --}}
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-emerald-50/70 border-b border-emerald-100/70 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                    <i class="bi bi-journal-arrow-up fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">II. Keputusan & Hasil (Outputs)</h6>
            </div>
            <div class="p-4 bg-gradient-to-b from-white to-slate-50/20">
                <div class="mb-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400 d-block mb-2">Notulensi Rapat</span>
                    <div class="bg-slate-50 border border-slate-100 p-3.5 rounded-xl text-slate-700 text-sm leading-relaxed font-medium" style="white-space: pre-wrap;">{{ $rTM->notulensi ?? 'Tidak ada catatan.' }}</div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border border-indigo-100/80 rounded-xl bg-indigo-50/20 h-100 relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-indigo-500/5"></div>
                            <h6 class="font-bold text-xs text-indigo-600 mb-2 uppercase tracking-wide"><i class="bi bi-shield-check me-1"></i>Keefektifan SPMI</h6>
                            <p class="mb-0 text-slate-600 text-xs leading-relaxed font-medium">{{ $rTM->output_keefektifan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border border-emerald-100/80 rounded-xl bg-emerald-50/20 h-100 relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-emerald-500/5"></div>
                            <h6 class="font-bold text-xs text-emerald-600 mb-2 uppercase tracking-wide"><i class="bi bi-box-seam me-1"></i>Perbaikan Layanan</h6>
                            <p class="mb-0 text-slate-600 text-xs leading-relaxed font-medium">{{ $rTM->output_perbaikan ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border border-amber-100/80 rounded-xl bg-amber-50/20 h-100 relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 h-12 w-12 rounded-full bg-amber-500/5"></div>
                            <h6 class="font-bold text-xs text-amber-600 mb-2 uppercase tracking-wide"><i class="bi bi-people me-1"></i>Sumber Daya</h6>
                            <p class="mb-0 text-slate-600 text-xs leading-relaxed font-medium">{{ $rTM->output_sumber_daya ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white border-0 rounded-xl shadow-sm relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 h-20 w-20 rounded-full bg-white/5 blur-lg"></div>
                    <h6 class="font-bold text-xs text-white/80 uppercase tracking-widest mb-2"><i class="bi bi-patch-check-fill me-1 text-white"></i>Kesimpulan & Keputusan Manajemen</h6>
                    <div class="text-sm font-medium leading-relaxed" style="white-space: pre-wrap;">{{ $rTM->keputusan_manajemen ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4 text-center">
        <a href="{{ route('rtm.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar RTM</span>
        </a>
    </div>
</div>
@endsection
