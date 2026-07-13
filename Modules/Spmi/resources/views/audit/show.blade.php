@extends('layouts.app')

@section('title', 'Detail Audit')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.css"/>
<style>
.modern-badge-container .badge {
    border-radius: 9999px !important;
    padding: 0.25rem 0.75rem !important;
    font-size: 0.75rem !important;
    font-weight: 700 !important;
    text-transform: capitalize !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    border: 1px solid transparent !important;
}
.modern-badge-container .badge.bg-success {
    background-color: #ecfdf5 !important;
    color: #059669 !important;
    border-color: #d1fae5 !important;
}
.modern-badge-container .badge.bg-warning {
    background-color: #fffbeb !important;
    color: #d97706 !important;
    border-color: #fef3c7 !important;
}
.modern-badge-container .badge.bg-primary {
    background-color: #eff6ff !important;
    color: #2563eb !important;
    border-color: #dbeafe !important;
}
.modern-badge-container .badge.bg-secondary {
    background-color: #f8fafc !important;
    color: #64748b !important;
    border-color: #e2e8f0 !important;
}
.modern-badge-container .badge.bg-danger {
    background-color: #fef2f2 !important;
    color: #dc2626 !important;
    border-color: #fee2e2 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.js"></script>
@endpush

@section('page-title', 'Detail Pelaksanaan Audit')
@section('page-subtitle', $audit->nama_audit)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item active">{{ $audit->kode_audit }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Tabs Navigation --}}
    <div class="col-12">
        <ul class="nav nav-pills gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200/60" id="auditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-xl px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="bi bi-info-circle-fill fs-6"></i>
                    <span>Informasi & Tim</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="checklist-tab" data-bs-toggle="tab" data-bs-target="#checklist" type="button" role="tab">
                    <i class="bi bi-list-check fs-6"></i>
                    <span>Checklist Audit</span>
                    @if($statsChecklist['belum'] > 0)
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white ms-1">{{ $statsChecklist['belum'] }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="temuan-tab" data-bs-toggle="tab" data-bs-target="#temuan" type="button" role="tab">
                    <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                    <span>Daftar Temuan</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="ai-insight-tab" data-bs-toggle="tab" data-bs-target="#ai-insight" type="button" role="tab">
                    <i class="bi bi-robot fs-6"></i>
                    <span>Insight AI Executive</span>
                    @if(!$audit->ai_summary)
                        <span class="relative d-flex h-2 w-2 ms-1">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                    @endif
                </button>
            </li>
        </ul>
    </div>

    <div class="col-12">
        <div class="tab-content" id="auditTabsContent">
            {{-- Tab 1: Informasi & Tim --}}
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
                            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                                    <i class="bi bi-info-circle-fill fs-5"></i>
                                </div>
                                <h6 class="mb-0 font-bold text-slate-800">Informasi Audit</h6>
                            </div>
                            <div class="p-4">
                                <div class="d-flex flex-column gap-3.5 modern-badge-container">
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode</span>
                                        <span class="text-xs font-mono font-bold text-primary bg-primary-light px-2.5 py-1 rounded-lg">{{ $audit->kode_audit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-start py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-0.5">Nama Program</span>
                                        <span class="text-sm font-bold text-slate-700 text-end d-block max-w-[180px]">{{ $audit->nama_audit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Kerja</span>
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-0.5 text-xs font-bold">{{ $audit->unit_yang_diaudit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ketua Auditor</span>
                                        <span class="text-xs font-bold text-slate-600 d-flex align-items-center gap-1">
                                            <i class="bi bi-person-badge text-slate-400"></i>
                                            <span>{{ $audit->ketuaAuditor->name ?? '-' }}</span>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Audit</span>
                                        <span class="text-xs font-bold text-slate-500">{{ $audit->tanggal_audit->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Opening Meeting</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $audit->opening_meeting ? $audit->opening_meeting->format('d/m/y H:i') . ' WIB' : 'Belum Atur' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Closing Meeting</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $audit->closing_meeting ? $audit->closing_meeting->format('d/m/y H:i') . ' WIB' : 'Belum Atur' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Berkas</span>
                                        <span>{!! $audit->status_badge !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
                            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                                    <i class="bi bi-people-fill fs-5"></i>
                                </div>
                                <h6 class="mb-0 font-bold text-slate-800">Tim Auditor Mutu</h6>
                            </div>
                            <div class="p-0">
                                <ul class="list-group list-group-flush border-t-0">
                                    @foreach($audit->auditors as $auditor)
                                    <li class="list-group-item d-flex align-items-center gap-3 py-3 border-slate-100 hover:bg-slate-50/50 transition-colors">
                                        <div class="d-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500 text-white font-extrabold text-sm shadow-[0_4px_12px_rgba(59,130,246,0.2)]">
                                            {{ strtoupper(substr($auditor->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ $auditor->name }}</div>
                                            <div class="text-[10px] font-bold uppercase tracking-wider mt-0.5 {{ $auditor->pivot->peran === 'ketua' ? 'text-primary' : 'text-slate-400' }}">{{ $auditor->pivot->peran === 'ketua' ? 'Ketua Auditor' : 'Anggota Tim' }}</div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
                            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <i class="bi bi-shield-check fs-5"></i>
                                </div>
                                <h6 class="mb-0 font-bold text-slate-800">Panel Kontrol Audit</h6>
                            </div>
                            <div class="p-4 d-grid gap-3">
                                @if($audit->status === 'draft')
                                <form action="{{ route('audit.update', $audit) }}" method="POST" class="m-0 w-100">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="aktif">
                                    <input type="hidden" name="nama_audit" value="{{ $audit->nama_audit }}">
                                    <input type="hidden" name="periode_id" value="{{ $audit->periode_id }}">
                                    <input type="hidden" name="unit_yang_diaudit" value="{{ $audit->unit_yang_diaudit }}">
                                    <input type="hidden" name="ketua_auditor_id" value="{{ $audit->ketua_auditor_id }}">
                                    <input type="hidden" name="tanggal_audit" value="{{ $audit->tanggal_audit->format('Y-m-d') }}">
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-emerald-600 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 border-0">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span>Mulai Program Audit</span>
                                    </button>
                                </form>
                                @endif
                                
                                <a href="{{ route('laporan.export.audit.individual', $audit) }}" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-rose-600 hover:-translate-y-0.5 hover:shadow-md hover:shadow-rose-500/20 active:translate-y-0 text-decoration-none" target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                    <span>Export Laporan PDF</span>
                                </a>
                                
                                <a href="{{ route('audit.edit', $audit) }}" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Edit Konfigurasi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Checklist Audit --}}
            <div class="tab-pane fade" id="checklist" role="tabpanel">
                <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-list-check me-1.5 text-primary"></i>Checklist Instrumen Audit</h6>
                            <p class="text-xs font-semibold text-slate-400 mb-0 mt-0.5">Berdasarkan indikator kinerja unit pelaksana: {{ $audit->unit_yang_diaudit }}</p>
                        </div>
                        <form action="{{ route('audit.generate-checklist', $audit) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50/20 px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50">
                                <i class="bi bi-magic"></i>
                                <span>{{ $audit->checklists->isEmpty() ? 'Generate Checklist' : 'Sync / Refresh Checklist' }}</span>
                            </button>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr class="bg-slate-50/70 border-b border-slate-100">
                                        <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Deskripsi Instrumen / Indikator</th>
                                        <th width="120" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Standar</th>
                                        <th width="160" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Kondisi / Status</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Catatan Temuan & Bukti Objektif</th>
                                        <th width="100" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-t-0">
                                    @forelse($indikators as $ind)
                                    @php 
                                        $item = $audit->checklists->where('indikator_id', $ind->id)->first();
                                    @endphp
                                    <tr class="transition-colors hover:bg-slate-50/40">
                                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                                        <td class="py-3.5">
                                            <span class="text-sm font-bold text-slate-800 d-block leading-snug">{{ $ind->nama }}</span>
                                            <span class="text-xs font-mono font-bold text-primary mt-1 d-block">{{ $ind->kode }}</span>
                                        </td>
                                        <td class="py-3.5">
                                            <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 text-slate-600 px-2.5 py-0.5 text-xs font-bold">
                                                {{ $ind->standar->kode ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3.5">
                                            <select class="w-full rounded-xl border border-slate-200 bg-slate-50/30 px-2.5 py-2 text-xs font-bold text-slate-700 inline-audit-checklist focus:bg-white focus:outline-none" 
                                                    data-ind-id="{{ $ind->id }}" data-audit-id="{{ $audit->id }}" data-field="status"
                                                    style="max-width: 150px; cursor: pointer;">
                                                <option value="belum_diisi" {{ (!$item || $item->status == 'belum_diisi') ? 'selected' : '' }}>⚪ Belum Diisi</option>
                                                <option value="sesuai" {{ ($item && $item->status == 'sesuai') ? 'selected' : '' }}>🟢 Sesuai (Compliant)</option>
                                                <option value="tidak_sesuai" {{ ($item && $item->status == 'tidak_sesuai') ? 'selected' : '' }}>🔴 Tidak Sesuai (KTS)</option>
                                                <option value="observasi" {{ ($item && $item->status == 'observasi') ? 'selected' : '' }}>🟡 Observasi (OB)</option>
                                                <option value="tidak_terkait" {{ ($item && $item->status == 'tidak_terkait') ? 'selected' : '' }}>⚫ Tidak Terkait (N/A)</option>
                                            </select>
                                        </td>
                                        <td class="py-3.5">
                                            <div class="mb-2">
                                                <textarea class="w-full text-xs font-semibold text-slate-600 rounded-xl border border-slate-200 bg-slate-50/30 px-3 py-1.5 inline-audit-checklist focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" 
                                                          data-ind-id="{{ $ind->id }}" data-audit-id="{{ $audit->id }}" data-field="catatan"
                                                          rows="1" placeholder="Catatan temuan audit disini...">{{ $item->catatan ?? '' }}</textarea>
                                            </div>
                                            <input type="text" class="w-full text-xs font-semibold text-slate-600 rounded-xl border border-slate-200 bg-slate-50/30 px-3 py-1.5 inline-audit-checklist focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" 
                                                   data-ind-id="{{ $ind->id }}" data-audit-id="{{ $audit->id }}" data-field="bukti_objektif"
                                                   placeholder="Link bukti fisik / berkas pendukung..." value="{{ $item->bukti_objektif ?? '' }}">
                                        </td>
                                        <td class="text-end py-3.5 pe-4">
                                            <div class="d-flex gap-1.5 justify-content-end">
                                                @if($item && in_array($item->status, ['tidak_sesuai', 'observasi']))
                                                    @if($item->temuans->isEmpty())
                                                    <a href="{{ route('audit.temuan.create', [$audit, 'checklist_id' => $item->id]) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white" title="Buat Temuan Formal">
                                                        <i class="bi bi-exclamation-circle-fill"></i>
                                                    </a>
                                                    @else
                                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100" title="Temuan Tercatat"><i class="bi bi-check-circle-fill"></i></span>
                                                    @endif
                                                @endif
                                                <a href="{{ route('indikator-kinerja.show', $ind) }}" target="_blank" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail Indikator">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-5">
                                            <div class="d-flex flex-column align-items-center justify-center py-5">
                                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                                    <i class="bi bi-clipboard2-x fs-1"></i>
                                                </div>
                                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Indikator Aktif</h6>
                                                <p class="text-xs font-medium text-slate-400 mb-0">Tidak ditemukan indikator acuan aktif untuk unit yang dipilih.</p>
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

            {{-- Tab 3: Daftar Temuan --}}
            <div class="tab-pane fade" id="temuan" role="tabpanel">
                <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-exclamation-triangle-fill me-1.5 text-primary"></i>Ringkasan Temuan Audit</h6>
                            <p class="text-xs font-semibold text-slate-400 mb-0 mt-0.5">Daftar temuan KTS (Ketidaksesuaian) maupun OB (Observasi)</p>
                        </div>
                        <a href="{{ route('audit.temuan.create', $audit) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-primary-dark text-decoration-none">
                            <i class="bi bi-plus-lg"></i>
                            <span>Input Temuan (KTS/OB)</span>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr class="bg-slate-50/70 border-b border-slate-100">
                                        <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                                        <th width="140" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Klasifikasi</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Uraian Ringkasan Temuan</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Acuan Klausul / Standar</th>
                                        <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Status</th>
                                        <th width="100" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-t-0 modern-badge-container">
                                    @forelse($audit->temuans as $temuan)
                                    <tr class="transition-colors hover:bg-slate-50/40">
                                        <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                                        <td class="py-3.5">{!! $temuan->kategori_badge !!}</td>
                                        <td class="py-3.5">
                                            <span class="text-sm font-bold text-slate-800 d-block leading-snug">{{ Str::limit($temuan->uraian_temuan, 110) }}</span>
                                        </td>
                                        <td class="py-3.5 text-xs font-bold text-slate-500">
                                            <i class="bi bi-bookmarks text-slate-400 fs-6 me-1"></i>
                                            <span>{{ $temuan->klausul_standar ?? '-' }}</span>
                                        </td>
                                        <td class="text-center py-3.5">
                                            @if($temuan->status === 'open') 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-2.5 py-0.5 text-xs font-bold">
                                                    Open
                                                </span>
                                            @elseif($temuan->status === 'in_progress') 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-2.5 py-0.5 text-xs font-bold">
                                                    In Progress
                                                </span>
                                            @else 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">
                                                    Closed
                                                </span> 
                                            @endif
                                        </td>
                                        <td class="text-end py-3.5 pe-4">
                                            <a href="{{ route('audit.temuan.show', [$audit, $temuan]) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-700" title="Detail Temuan">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-5">
                                            <div class="d-flex flex-column align-items-center justify-center py-5">
                                                <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                                    <i class="bi bi-shield-slash fs-1"></i>
                                                </div>
                                                <h6 class="font-bold text-slate-700 mb-1">Belum Ada Temuan Formal</h6>
                                                <p class="text-xs font-medium text-slate-400 mb-0">Belum ada temuan formal (KTS/OB) yang dicatatkan pada program audit ini.</p>
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

            {{-- Tab 4: AI Insight --}}
            <div class="tab-pane fade" id="ai-insight" role="tabpanel">
                <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="p-4 bg-gradient-to-r from-blue-500/10 to-indigo-600/10 border-b border-slate-100 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-robot fs-4 text-blue-600 animate-pulse"></i>
                            <h6 class="mb-0 font-extrabold text-slate-800">Executive Briefing (Generasi AI Copilot)</h6>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="inline-flex items-center gap-1 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 px-3 py-1.5 text-xs font-bold" id="btnEditAi" onclick="toggleAiEdit()" style="display: {{ $audit->ai_summary ? 'block' : 'none' }}">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit Manual</span>
                            </button>
                            <button type="button" class="inline-flex items-center gap-1 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 text-xs font-bold border-0" id="btnSaveAi" onclick="saveAiSummary()" style="display: none">
                                <i class="bi bi-check-lg"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                            <button type="button" class="inline-flex items-center gap-1 rounded-xl bg-primary hover:bg-primary-dark text-white px-3.5 py-1.5 text-xs font-bold border-0 shadow-sm" onclick="generateAuditSummary()">
                                <i class="bi bi-magic"></i>
                                <span>Generate Ringkasan Baru</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <textarea id="aiSummaryEditor" style="display: none;"></textarea>
                        <div id="aiSummaryContent" class="p-4">
                            @if($audit->ai_summary)
                                <div class="ai-report-text">
                                    {!! $audit->ai_summary !!}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-500 border border-blue-100/50 mb-3 mx-auto shadow-inner animate-bounce">
                                        <i class="bi bi-robot fs-1"></i>
                                    </div>
                                    <h6 class="font-bold text-slate-700 mb-1">Belum Ada Executive Briefing AI</h6>
                                    <p class="text-xs font-semibold text-slate-400 mb-0">Silakan klik tombol "Generate Ringkasan Baru" diatas untuk menyusun narasi ringkasan eksekutif secara instan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 border-t border-slate-100 text-[10px] font-semibold text-slate-400 text-center">
                        <i class="bi bi-info-circle me-1 text-primary"></i>Analisa cerdas ini disusun oleh AI Engine (Groq Llama). Harap memverifikasi kebenaran dan substansi data sebelum dipublikasikan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab Persistence
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = localStorage.getItem('auditActiveTab');
        if (activeTab) {
            const tabEl = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (tabEl) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }

        const tabBtns = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabBtns.forEach(btn => {
            btn.addEventListener('shown.bs.tab', (e) => {
                localStorage.setItem('auditActiveTab', e.target.getAttribute('data-bs-target'));
            });
        });
    });

    let joditAi = null;

    function toggleAiEdit() {
        const contentDiv = document.getElementById('aiSummaryContent');
        const editorWrapper = document.querySelector('.jodit-container') || document.getElementById('aiSummaryEditor');
        const btnEdit = document.getElementById('btnEditAi');
        const btnSave = document.getElementById('btnSaveAi');

        if (contentDiv.style.display !== 'none') {
            // Start Editing
            contentDiv.style.display = 'none';
            btnEdit.innerHTML = '<i class="bi bi-x-lg me-1"></i>Batal';
            btnSave.style.display = 'block';

            if (!joditAi) {
                joditAi = new Jodit('#aiSummaryEditor', {
                    height: 400,
                    toolbarAdaptive: false,
                    buttons: 'bold,italic,underline,ul,ol,eraser,undo,redo',
                });
            }
            
            // Load current content
            const currentHtml = contentDiv.querySelector('.ai-report-text')?.innerHTML || '';
            joditAi.value = currentHtml;
            document.querySelector('.jodit-container').style.display = 'block';
        } else {
            // Cancel Editing
            contentDiv.style.display = 'block';
            if (document.querySelector('.jodit-container')) {
                document.querySelector('.jodit-container').style.display = 'none';
            }
            btnEdit.innerHTML = '<i class="bi bi-pencil-square me-1"></i>Edit Manual';
            btnSave.style.display = 'none';
        }
    }

    async function saveAiSummary() {
        if (!joditAi) return;
        
        const btn = document.getElementById('btnSaveAi');
        const html = joditAi.value;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width: 12px; height: 12px;"></span>Menyimpan...';

        try {
            const response = await fetch('{{ route("laporan.audit.update-ai-summary", $audit) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ai_summary: html })
            });
            
            const result = await response.json();
            if (result.success) {
                // Update display
                document.getElementById('aiSummaryContent').innerHTML = `<div class="ai-report-text">${html}</div>`;
                toggleAiEdit();
                alert('Analisa AI berhasil diperbarui.');
            }
        } catch (e) {
            console.error(e);
            alert('Gagal menyimpan perubahan.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Perubahan';
        }
    }

    async function generateAuditSummary() {
        const btn = event.currentTarget;
        const container = document.getElementById('aiSummaryContent');
        const auditId = '{{ $audit->id }}';

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width: 12px; height: 12px;"></span>Menganalisa...';
        
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-grow text-primary mb-3" role="status"></div>
                <h6 class="font-bold text-slate-700">Menganalisa {{ $audit->temuans->count() }} Temuan Audit...</h6>
                <p class="text-xs font-semibold text-slate-400">Proses penyusunan ringkasan eksekutif sedang berlangsung.</p>
            </div>
        `;

        try {
            const response = await fetch('{{ route("ai.audit-summary") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ audit_id: auditId })
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                container.innerHTML = `<div class="ai-report-text">${result.data}</div>`;
                document.getElementById('btnEditAi').style.display = 'block';
            } else {
                container.innerHTML = `
                    <div class="alert alert-danger border-0 rounded-2xl bg-rose-50 text-rose-700 text-xs font-semibold d-flex gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>${result.message || 'Gagal menyusun narasi ringkasan.'}</span>
                    </div>
                `;
            }
        } catch (e) {
            console.error(e);
            container.innerHTML = `<div class="alert alert-danger border-0 rounded-2xl bg-rose-50 text-rose-700 text-xs font-semibold d-flex gap-2"><i class="bi bi-exclamation-triangle-fill"></i><span>Gagal menghubungi server AI.</span></div>`;
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic me-1"></i>Generate Ringkasan Baru';
        }
    }

    // Inline Audit Checklist
    document.querySelectorAll('.inline-audit-checklist').forEach(el => {
        el.addEventListener('change', async function() {
            const audit_id = this.dataset.auditId;
            const indikator_id = this.dataset.indId;
            const field = this.dataset.field;
            const value = this.value;
            
            this.style.opacity = '0.5';

            try {
                const response = await fetch('{{ route("audit.checklist-inline") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ audit_id, indikator_id, field, value })
                });
                
                const result = await response.json();
                if (result.success) {
                    this.classList.add('text-success');
                    setTimeout(() => {
                        this.classList.remove('text-success');
                        if (field === 'status') location.reload(); // Reload to show Temuan button if status changes
                    }, 1000);
                }
            } catch (e) {
                console.error(e);
                alert('Gagal menyimpan perubahan.');
            } finally {
                this.style.opacity = '1';
            }
        });
    });
</script>
<style>
    .ai-report-text {
        font-family: inherit;
        line-height: 1.85;
        color: #334155;
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 16px;
        border-left: 4px solid #3b82f6;
        white-space: pre-wrap;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>
@endpush
@endsection