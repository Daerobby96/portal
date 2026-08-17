@extends('layouts.app')

@section('title', 'Detail Pelaksanaan Audit - ' . $audit->kode_audit)

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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.js"></script>
@endpush

@section('page-title', 'Detail Pelaksanaan Audit')
@section('page-subtitle', $audit->nama_audit . ' (' . $audit->kode_audit . ')')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item active">{{ $audit->kode_audit }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Header Quick Actions --}}
    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2 pb-2">
        <div class="d-flex align-items-center gap-2">
            <span class="text-xs font-mono font-bold text-primary bg-primary-light px-3 py-1.5 rounded-xl border border-primary/20">
                <i class="bi bi-shield-check me-1"></i>{{ $audit->kode_audit }}
            </span>
            <span class="text-sm font-bold text-slate-700">{{ $audit->unit_yang_diaudit }}</span>
            {!! $audit->status_badge !!}
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('audit.surat-tugas-pdf', $audit) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5" target="_blank">
                <i class="bi bi-file-earmark-person-fill text-primary"></i>
                <span>Surat Tugas (PDF)</span>
            </a>
            <a href="{{ route('audit.bapa-pdf', $audit) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50/40 px-3.5 py-2 text-xs font-bold text-emerald-700 shadow-sm transition-all hover:bg-emerald-50 hover:-translate-y-0.5" target="_blank">
                <i class="bi bi-clipboard2-check-fill text-emerald-600"></i>
                <span>Berita Acara (BAPA)</span>
            </a>
            <a href="{{ route('laporan.export.audit.individual', $audit) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-500 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-rose-600 hover:-translate-y-0.5" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                <span>Laporan Lengkap</span>
            </a>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="col-12">
        <ul class="nav nav-pills gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200/60" id="auditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-xl px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="bi bi-info-circle-fill fs-6"></i>
                    <span>1. Info &amp; Administrasi</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="desk-eval-tab" data-bs-toggle="tab" data-bs-target="#desk-eval" type="button" role="tab">
                    <i class="bi bi-file-earmark-text-fill fs-6 text-amber-500"></i>
                    <span>2. Desk Evaluation (Audit Dokumen)</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="checklist-tab" data-bs-toggle="tab" data-bs-target="#checklist" type="button" role="tab">
                    <i class="bi bi-list-check fs-6 text-primary"></i>
                    <span>3. Visitasi Lapangan (Checklist Kepatuhan)</span>
                    @if($statsChecklist['belum'] > 0)
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white ms-1">{{ $statsChecklist['belum'] }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="temuan-tab" data-bs-toggle="tab" data-bs-target="#temuan" type="button" role="tab">
                    <i class="bi bi-exclamation-triangle-fill fs-6 text-rose-500"></i>
                    <span>4. Daftar Temuan</span>
                    @if($statsTemuan['total'] > 0)
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-700 text-[10px] font-black text-white ms-1">{{ $statsTemuan['total'] }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="bapa-tab" data-bs-toggle="tab" data-bs-target="#bapa" type="button" role="tab">
                    <i class="bi bi-hand-thumbs-up-fill fs-6 text-emerald-500"></i>
                    <span>5. Closing &amp; Berita Acara (BAPA)</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-xl px-3.5 py-2.5 text-xs font-extrabold uppercase tracking-wider d-flex align-items-center gap-1.5 transition-all" id="ai-insight-tab" data-bs-toggle="tab" data-bs-target="#ai-insight" type="button" role="tab">
                    <i class="bi bi-robot fs-6 text-indigo-500"></i>
                    <span>6. Insight AI Executive</span>
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
                                <h6 class="mb-0 font-bold text-slate-800">Informasi &amp; Administrasi</h6>
                            </div>
                            <div class="p-4">
                                <div class="d-flex flex-column gap-3.5 modern-badge-container">
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Audit</span>
                                        <span class="text-xs font-mono font-bold text-primary bg-primary-light px-2.5 py-1 rounded-lg">{{ $audit->kode_audit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-start py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-0.5">Nama Program</span>
                                        <span class="text-sm font-bold text-slate-700 text-end d-block max-w-[180px]">{{ $audit->nama_audit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Kerja Auditee</span>
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 px-2.5 py-0.5 text-xs font-bold">{{ $audit->unit_yang_diaudit }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Surat Tugas</span>
                                        <span class="text-xs font-bold text-slate-600">{{ $audit->nomor_surat_tugas ?? '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Audit</span>
                                        <span class="text-xs font-bold text-slate-500">{{ $audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->translatedFormat('d F Y') : '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Opening Meeting</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $audit->opening_meeting ? $audit->opening_meeting->format('d/m/y H:i') . ' WIB' : 'Belum Diatur' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-b border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Closing Meeting</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $audit->closing_meeting ? $audit->closing_meeting->format('d/m/y H:i') . ' WIB' : 'Belum Diatur' }}</span>
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
                            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                                        <i class="bi bi-people-fill fs-5"></i>
                                    </div>
                                    <h6 class="mb-0 font-bold text-slate-800">Tim Auditor Mutu</h6>
                                </div>
                                <a href="{{ route('audit.surat-tugas-pdf', $audit) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline">
                                    <i class="bi bi-printer"></i>
                                    <span>Cetak SK</span>
                                </a>
                            </div>
                            <div class="p-0">
                                <ul class="list-group list-group-flush border-t-0">
                                    @foreach($audit->auditors as $auditor)
                                    <li class="list-group-item d-flex align-items-center gap-3 py-3 border-slate-100 hover:bg-slate-50/50 transition-colors">
                                        <div class="d-flex h-10 w-10 items-center justify-center rounded-xl {{ $auditor->pivot->peran === 'ketua' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600' }} font-extrabold text-sm shadow-sm">
                                            {{ strtoupper(substr($auditor->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ $auditor->name }}</div>
                                            <div class="text-[11px] text-slate-500">{{ $auditor->unit_kerja ?? ($auditor->prodi->nama ?? 'Auditor Internal') }}</div>
                                            <div class="text-[10px] font-bold uppercase tracking-wider mt-0.5 {{ $auditor->pivot->peran === 'ketua' ? 'text-primary' : 'text-slate-400' }}">
                                                {{ $auditor->pivot->peran === 'ketua' ? '★ Ketua Tim Auditor' : 'Anggota Auditor' }}
                                            </div>
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
                                <h6 class="mb-0 font-bold text-slate-800">Panel Kontrol &amp; Aksi</h6>
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
                                    <input type="hidden" name="tanggal_audit" value="{{ $audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->format('Y-m-d') : date('Y-m-d') }}">
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-emerald-600 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 border-0">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span>Aktifkan Status Pelaksanaan</span>
                                    </button>
                                </form>
                                @endif
                                
                                <a href="{{ route('audit.surat-tugas-pdf', $audit) }}" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 text-decoration-none" target="_blank">
                                    <i class="bi bi-file-earmark-person text-primary"></i>
                                    <span>Download Surat Tugas (PDF)</span>
                                </a>

                                <a href="{{ route('audit.bapa-pdf', $audit) }}" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50/50 px-4 py-2.5 text-sm font-bold text-emerald-700 shadow-sm transition-all hover:bg-emerald-50 hover:-translate-y-0.5 text-decoration-none" target="_blank">
                                    <i class="bi bi-clipboard2-check text-emerald-600"></i>
                                    <span>Download Berita Acara (BAPA)</span>
                                </a>

                                <a href="{{ route('laporan.export.audit.individual', $audit) }}" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-rose-600 hover:-translate-y-0.5 text-decoration-none" target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                    <span>Export Laporan Audit (PDF)</span>
                                </a>
                                
                                <a href="{{ route('audit.edit', $audit) }}" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 text-decoration-none">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Edit Konfigurasi &amp; Data Audit</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Desk Evaluation (Audit Dokumen) --}}
            <div class="tab-pane fade" id="desk-eval" role="tabpanel">
                <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-file-earmark-text-fill me-1.5 text-amber-500"></i>Tahap 1: Desk Evaluation (Audit Dokumen &amp; Kecukupan)</h6>
                            <p class="text-xs font-semibold text-slate-400 mb-0 mt-0.5">Pemeriksaan bukti dokumen &amp; evaluasi diri auditee sebelum visitasi lapangan</p>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr class="bg-slate-50/70 border-b border-slate-100">
                                        <th width="40" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Indikator &amp; Standar Mutu</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5" style="min-width: 220px;">Evaluasi Diri Auditee</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5" style="min-width: 200px;">Dokumen Bukti Pendukung</th>
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5" style="min-width: 220px;">Catatan Telaah Auditor</th>
                                        <th width="80" class="text-end text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
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
                                            <div class="d-flex items-center gap-1.5 mt-1">
                                                <span class="text-xs font-mono font-bold text-primary">{{ $ind->kode }}</span>
                                                <span class="badge bg-slate-100 text-slate-600 border border-slate-200 text-[10px]">{{ $ind->standar->kode ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5">
                                            <div class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200/70">
                                                {{ $item->evaluasi_auditee ?? 'Belum ada catatan evaluasi diri dari auditee.' }}
                                            </div>
                                        </td>
                                        <td class="py-3.5">
                                            @if($item && ($item->bukti_auditee || $item->bukti_objektif))
                                                <a href="{{ $item->bukti_auditee ?: $item->bukti_objektif }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-primary bg-primary-light px-2.5 py-1 rounded-lg hover:underline max-w-[200px] truncate">
                                                    <i class="bi bi-link-45deg"></i>
                                                    <span>{{ Str::limit($item->bukti_auditee ?: $item->bukti_objektif, 25) }}</span>
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 font-semibold italic">Belum disematkan bukti</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5">
                                            <div class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200/70">
                                                {{ $item->catatan ?? 'Belum ada catatan telaah desk audit.' }}
                                            </div>
                                        </td>
                                        <td class="text-end py-3.5 pe-4">
                                            @if($item)
                                            <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50" data-bs-toggle="modal" data-bs-target="#modalDeskEval{{ $item->id }}" title="Edit Desk Evaluation">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Modal Edit Desk Evaluation --}}
                                            <div class="modal fade" id="modalDeskEval{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered text-start">
                                                    <div class="modal-content rounded-2xl border-0 shadow-2xl overflow-hidden">
                                                        <form action="{{ route('audit.update-desk-evaluation', [$audit, $item]) }}" method="POST">
                                                            @csrf @method('PUT')
                                                            <div class="modal-header bg-slate-50 p-4 border-b border-slate-100">
                                                                <h6 class="modal-title font-bold text-slate-800"><i class="bi bi-file-earmark-text me-1 text-amber-500"></i>Desk Evaluation - {{ $ind->kode }}</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4 d-grid gap-3">
                                                                <div>
                                                                    <label class="text-xs font-bold uppercase text-slate-400 mb-1 d-block">Evaluasi Diri Auditee</label>
                                                                    <textarea name="evaluasi_auditee" rows="2" class="w-full text-xs rounded-xl border border-slate-200 p-2.5" placeholder="Penjelasan pencapaian standar dari pihak auditee...">{{ $item->evaluasi_auditee }}</textarea>
                                                                </div>
                                                                <div>
                                                                    <label class="text-xs font-bold uppercase text-slate-400 mb-1 d-block">Link / Tautan Bukti Dokumen</label>
                                                                    <input type="text" name="bukti_auditee" class="w-full text-xs rounded-xl border border-slate-200 p-2.5" value="{{ $item->bukti_auditee }}" placeholder="https://drive.google.com/... atau link dokumen SPMI">
                                                                </div>
                                                                <div>
                                                                    <label class="text-xs font-bold uppercase text-slate-400 mb-1 d-block">Catatan Telaah Dokumen Auditor</label>
                                                                    <textarea name="catatan" rows="2" class="w-full text-xs rounded-xl border border-slate-200 p-2.5" placeholder="Catatan atau klarifikasi yang perlu diverifikasi saat visitasi...">{{ $item->catatan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-slate-50 p-3 border-t border-slate-100">
                                                                <button type="button" class="btn btn-sm btn-light rounded-xl font-bold" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-sm btn-primary rounded-xl font-bold px-3">Simpan Desk Audit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-slate-400">Tidak ada instrumen terkait.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Visitasi Lapangan (Checklist Audit) --}}
            <div class="tab-pane fade" id="checklist" role="tabpanel">
                <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-list-check me-1.5 text-primary"></i>Tahap 2: Visitasi Lapangan (Checklist Kepatuhan Faktual)</h6>
                            <p class="text-xs font-semibold text-slate-400 mb-0 mt-0.5">Verifikasi fisik, kepatuhan indikator, dan konfirmasi bukti objektif di unit kerja</p>
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
                                        <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Catatan Temuan &amp; Bukti Objektif</th>
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
                                        <td colspan="6" class="py-5 text-center text-slate-400">Belum ada indikator acuan aktif.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 4: Daftar Temuan --}}
            <div class="tab-pane fade" id="temuan" role="tabpanel">
                <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 font-bold text-slate-800"><i class="bi bi-exclamation-triangle-fill me-1.5 text-rose-500"></i>Ringkasan Temuan Audit (KTS / OB / Rekomendasi)</h6>
                            <p class="text-xs font-semibold text-slate-400 mb-0 mt-0.5">Daftar temuan formal yang wajib ditindaklanjuti oleh pihak auditee</p>
                        </div>
                        <a href="{{ route('audit.temuan.create', $audit) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-primary-dark text-decoration-none">
                            <i class="bi bi-plus-lg"></i>
                            <span>Input Temuan Formal</span>
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
                                            <span class="text-xs font-mono font-bold text-slate-400 mt-1 d-block">{{ $temuan->kode_temuan }}</span>
                                        </td>
                                        <td class="py-3.5 text-xs font-bold text-slate-500">
                                            <i class="bi bi-bookmarks text-slate-400 fs-6 me-1"></i>
                                            <span>{{ $temuan->klausul_standar ?? '-' }}</span>
                                        </td>
                                        <td class="text-center py-3.5">
                                            @if($temuan->status === 'open') 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-2.5 py-0.5 text-xs font-bold">Open</span>
                                            @elseif($temuan->status === 'in_progress') 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-2.5 py-0.5 text-xs font-bold">In Progress</span>
                                            @else 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-0.5 text-xs font-bold">Closed</span> 
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
                                        <td colspan="6" class="py-5 text-center text-slate-400">
                                            <i class="bi bi-shield-check fs-2 text-emerald-500 d-block mb-1"></i>
                                            <span class="font-bold text-slate-700">Tidak ada temuan ketidaksesuaian.</span>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 5: Closing Meeting & BAPA --}}
            <div class="tab-pane fade" id="bapa" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
                            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                        <i class="bi bi-clipboard2-check fs-5"></i>
                                    </div>
                                    <h6 class="mb-0 font-bold text-slate-800">Berita Acara Pelaksanaan Audit (BAPA)</h6>
                                </div>
                                <a href="{{ route('audit.bapa-pdf', $audit) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-3.5 py-1.5 text-xs font-bold shadow-sm">
                                    <i class="bi bi-printer-fill"></i>
                                    <span>Cetak PDF</span>
                                </a>
                            </div>
                            <div class="p-4">
                                <div class="alert alert-info border-0 rounded-2xl bg-blue-50/60 text-blue-800 text-xs font-semibold d-flex gap-2.5 mb-4">
                                    <i class="bi bi-info-circle-fill fs-5 text-blue-500"></i>
                                    <div>
                                        Berita Acara Pelaksanaan Audit (BAPA) adalah dokumen kesepakatan formal antara Tim Auditor dan Pimpinan Auditee pada sesi Closing Meeting atas seluruh hasil temuan audit dan batas waktu tindak lanjutnya.
                                    </div>
                                </div>

                                <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Catatan Khusus Closing Meeting</h6>
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 mb-4">
                                    {{ $audit->bapa_catatan ?: 'Belum ada catatan khusus closing meeting. Catatan dapat diinput melalui menu Edit Audit.' }}
                                </div>

                                <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Statistik Temuan</h6>
                                <div class="row g-2">
                                    <div class="col-3">
                                        <div class="p-3 bg-rose-50 rounded-xl border border-rose-100 text-center">
                                            <div class="text-lg font-black text-rose-600">{{ $statsTemuan['kts_mayor'] }}</div>
                                            <div class="text-[10px] font-bold uppercase text-rose-500">KTS Mayor</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-center">
                                            <div class="text-lg font-black text-amber-600">{{ $statsTemuan['kts_minor'] }}</div>
                                            <div class="text-[10px] font-bold uppercase text-amber-500">KTS Minor</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="p-3 bg-sky-50 rounded-xl border border-sky-100 text-center">
                                            <div class="text-lg font-black text-sky-600">{{ $statsTemuan['observasi'] }}</div>
                                            <div class="text-[10px] font-bold uppercase text-sky-500">Observasi</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-center">
                                            <div class="text-lg font-black text-emerald-600">{{ $statsTemuan['rekomendasi'] }}</div>
                                            <div class="text-[10px] font-bold uppercase text-emerald-500">Rekomendasi</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden h-100">
                            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <i class="bi bi-pen-fill fs-5"></i>
                                </div>
                                <h6 class="mb-0 font-bold text-slate-800">Tanda Tangan Digital BAPA</h6>
                            </div>
                            <div class="p-4 d-grid gap-3">
                                {{-- Auditor Sign --}}
                                <div class="p-3.5 rounded-2xl border {{ $audit->bapa_signed_at_auditor ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/30' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-xs font-bold text-slate-700">1. Ketua Tim Auditor</span>
                                        @if($audit->bapa_signed_at_auditor)
                                            <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]"><i class="bi bi-check-circle-fill me-1"></i>Telah Disetujui</span>
                                        @else
                                            <span class="badge bg-slate-100 text-slate-500 border border-slate-200 text-[10px]">Menunggu Approval</span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-semibold text-slate-600 mb-2">
                                        {{ $audit->ketuaAuditor->name ?? '-' }}
                                    </div>
                                    @if($audit->bapa_signed_at_auditor)
                                        <div class="text-[10px] text-slate-400">Disetujui pada: {{ \Carbon\Carbon::parse($audit->bapa_signed_at_auditor)->translatedFormat('d F Y, H:i') }} WIB</div>
                                    @else
                                        @if(auth()->user()->id === $audit->ketua_auditor_id || auth()->user()->isSuperAdmin())
                                        <form action="{{ route('audit.sign-bapa', $audit) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="role_sign" value="auditor">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-xl font-bold w-100 text-xs py-2">
                                                <i class="bi bi-pen me-1"></i>Setujui BAPA sebagai Ketua Auditor
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>

                                {{-- Auditee Sign --}}
                                <div class="p-3.5 rounded-2xl border {{ $audit->bapa_signed_at_auditee ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/30' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-xs font-bold text-slate-700">2. Pimpinan Auditee</span>
                                        @if($audit->bapa_signed_at_auditee)
                                            <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]"><i class="bi bi-check-circle-fill me-1"></i>Telah Disetujui</span>
                                        @else
                                            <span class="badge bg-slate-100 text-slate-500 border border-slate-200 text-[10px]">Menunggu Approval</span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-semibold text-slate-600 mb-2">
                                        {{ $audit->bapaAuditee->name ?? ($audit->unit_yang_diaudit) }}
                                    </div>
                                    @if($audit->bapa_signed_at_auditee)
                                        <div class="text-[10px] text-slate-400">Disetujui pada: {{ \Carbon\Carbon::parse($audit->bapa_signed_at_auditee)->translatedFormat('d F Y, H:i') }} WIB oleh {{ $audit->bapaAuditee->name ?? 'Pimpinan' }}</div>
                                    @else
                                        @if(auth()->user()->isAuditee() || auth()->user()->unit_kerja === $audit->unit_yang_diaudit || auth()->user()->isKaprodi() || auth()->user()->isSuperAdmin())
                                        <form action="{{ route('audit.sign-bapa', $audit) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="role_sign" value="auditee">
                                            <button type="submit" class="btn btn-sm btn-emerald text-white rounded-xl font-bold w-100 text-xs py-2" style="background-color: #059669;">
                                                <i class="bi bi-pen me-1"></i>Setujui BAPA sebagai Pimpinan Auditee
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 6: AI Insight --}}
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
                        if (field === 'status') location.reload();
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
@endpush
@endsection