@extends('layouts.app')

@section('title', 'Detail Temuan')
@section('page-title', 'Detail Temuan')
@section('page-subtitle', $temuan->kode_temuan)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item"><a href="{{ route('audit.show', $audit) }}">{{ $audit->kode_audit }}</a></li>
    <li class="breadcrumb-item active">Temuan</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Temuan --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Informasi Klasifikasi Temuan</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3.5">
                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Registrasi</span>
                        <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">{{ $temuan->kode_temuan }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori Mutu</span>
                        <span>{!! $temuan->kategori_badge !!}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Acuan Klausul</span>
                        <span class="text-sm font-bold text-slate-700">{{ $temuan->klausul_standar ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Temuan</span>
                        <span>
                            @if($temuan->status === 'open')
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-3 py-1 text-xs font-bold">
                                    🔴 Open
                                </span>
                            @elseif($temuan->status === 'in_progress')
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-3 py-1 text-xs font-bold">
                                    🟡 In Progress
                                </span>
                            @elseif($temuan->status === 'closed')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1 text-xs font-bold">
                                    🟢 Closed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-3 py-1 text-xs font-bold">
                                    🔵 Verified
                                </span>
                            @endif
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Batas Waktu Tindak Lanjut</span>
                        <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2.5 py-0.5 rounded-md">{{ $temuan->batas_tindak_lanjut?->translatedFormat('d F Y') ?? '-' }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Petugas Auditor</span>
                        <span class="text-xs font-bold text-slate-500 d-flex align-items-center gap-1">
                            <i class="bi bi-person-badge text-slate-400"></i>
                            <span>{{ $temuan->auditor->name ?? '-' }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Uraian & Bukti --}}
    <div class="col-lg-6">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-file-text-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Uraian & Bukti Temuan</h6>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Uraian Temuan</span>
                    <div class="text-sm font-semibold text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-100 leading-relaxed">
                        {{ $temuan->uraian_temuan }}
                    </div>
                </div>
                
                @if($temuan->bukti_objektif)
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Bukti Objektif Lapangan</span>
                    <div class="text-sm font-semibold text-slate-600 bg-blue-50/20 p-3.5 rounded-xl border border-blue-100/30 leading-relaxed">
                        {{ $temuan->bukti_objektif }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat Tindak Lanjut --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <i class="bi bi-arrow-repeat fs-5"></i>
                    </div>
                    <h6 class="mb-0 font-bold text-slate-800">Riwayat Penanganan & Tindak Lanjut</h6>
                </div>
                <a href="{{ route('tindak-lanjut.create', ['temuan' => $temuan->id]) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-3 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-primary-dark text-decoration-none">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Tindak Lanjut</span>
                </a>
            </div>
            <div class="card-body p-0">
                @if($temuan->tindakLanjuts->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3">#</th>
                                <th width="150" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3">Tanggal Input</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3">Rencana Tindakan</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3">Penanggung Jawab</th>
                                <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3">Status</th>
                                <th width="100" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-t-0">
                            @foreach($temuan->tindakLanjuts as $tl)
                            <tr class="transition-colors hover:bg-slate-50/30">
                                <td class="text-center text-slate-400 text-sm font-semibold py-3">{{ $loop->iteration }}</td>
                                <td class="text-xs font-bold text-slate-500 py-3">{{ $tl->created_at->translatedFormat('d M Y') }}</td>
                                <td class="py-3 text-sm font-bold text-slate-700">{{ Str::limit($tl->rencana_tindakan, 75) }}</td>
                                <td class="py-3 text-xs font-bold text-slate-600">
                                    <i class="bi bi-person-fill text-slate-400 me-1"></i>
                                    <span>{{ $tl->penanggungJawab->name ?? '-' }}</span>
                                </td>
                                <td class="text-center py-3">
                                    @if($tl->status === 'proses')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-2.5 py-0.5 text-xs font-bold">
                                            Proses
                                        </span>
                                    @elseif($tl->status === 'selesai')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-xs font-bold">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end py-3 pe-4">
                                    <a href="{{ route('tindak-lanjut.show', $tl) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="py-5 text-center">
                    <div class="d-flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3 mx-auto">
                        <i class="bi bi-arrow-repeat fs-2"></i>
                    </div>
                    <h6 class="font-bold text-slate-700 mb-1">Belum Ada Tindak Lanjut</h6>
                    <p class="text-xs font-medium text-slate-400 mb-0">Rencana koreksi tindak lanjut untuk temuan ini belum diunggah.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex gap-2">
            <a href="{{ route('audit.show', $audit) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Audit</span>
            </a>
            
            <a href="{{ route('audit.temuan.edit', [$audit, $temuan]) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Temuan</span>
            </a>
        </div>
    </div>
</div>
@endsection