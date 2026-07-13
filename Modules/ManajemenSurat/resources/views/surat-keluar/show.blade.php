@extends('manajemen-surat::layouts.master')

@section('title', 'Detail Surat Keluar')
@section('page-title', 'Detail Surat Keluar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surat-keluar.index') }}">Surat Keluar</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
.content-html p { margin-bottom: 10px; }
.content-html ol, .content-html ul { margin-left: 20px; margin-bottom: 10px; }
.content-html li { margin-bottom: 5px; }
.content-html table { width: 100%; border-collapse: collapse; margin: 10px 0; }
.content-html table td { padding: 5px; border: 1px solid #ddd; }
.content-html table th { padding: 5px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; }
.content-html strong { font-weight: bold; }
.content-html em { font-style: italic; }
.content-html u { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        {{-- Alert Info untuk Generate PDF --}}
        <div class="alert alert-success border-0 rounded-2xl mb-4 d-flex align-items-start gap-3">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>
                <h6 class="font-bold mb-1">Data Surat Berhasil Disimpan</h6>
                <p class="text-sm mb-2">
                    Metadata surat telah tersimpan. Anda dapat generate PDF kapan saja dengan klik tombol 
                    <strong>"Preview PDF"</strong> atau <strong>"Download PDF"</strong> di atas.
                </p>
                <p class="text-xs text-muted mb-0">
                    <i class="bi bi-info-circle"></i> File PDF tidak disimpan di server, melainkan di-generate on-demand untuk menghemat storage.
                </p>
            </div>
        </div>

        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b border-slate-100 p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 font-bold text-slate-800">{{ $suratKeluar->nomor_surat }}</h6>
                    <p class="text-xs text-slate-400 mb-0">{{ $suratKeluar->jenisSurat->nama }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('surat-keluar.preview-pdf', $suratKeluar) }}" class="btn btn-sm btn-info" target="_blank" title="Generate & preview PDF di browser (tidak disimpan)">
                        <i class="bi bi-file-pdf"></i> Preview PDF
                    </a>
                    <a href="{{ route('surat-keluar.pdf', $suratKeluar) }}" class="btn btn-sm btn-success" title="Generate & download PDF (tidak disimpan di server)">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                    @if($suratKeluar->status === 'pending' && auth()->user()->hasRole(['super_admin', 'pimpinan']))
                    <form method="POST" action="{{ route('surat-keluar.approve', $suratKeluar) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui surat ini?')">
                            <i class="bi bi-check-circle"></i> Setujui
                        </button>
                    </form>
                    <form method="POST" action="{{ route('surat-keluar.reject', $suratKeluar) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Tolak surat ini?')">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                    </form>
                    @endif
                    @if($suratKeluar->isEditable())
                    <a href="{{ route('surat-keluar.edit', $suratKeluar) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="text-xs font-semibold text-slate-400">Nomor Surat</label>
                        <p class="text-slate-800 font-semibold">{{ $suratKeluar->nomor_surat }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-xs font-semibold text-slate-400">Unit Pengelola</label>
                        <p class="text-slate-800">
                            @if($suratKeluar->unit)
                                {{ $suratKeluar->unit->nama }} 
                                <span class="badge bg-slate-100 text-slate-600">{{ $suratKeluar->unit->kode }}</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-xs font-semibold text-slate-400">Tanggal</label>
                        <p class="text-slate-800">{{ $suratKeluar->tanggal_surat->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Perihal</label>
                        <p class="text-slate-800">{{ $suratKeluar->perihal }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Isi Surat</label>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="content-html text-slate-700">{!! $suratKeluar->isi_surat !!}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Tujuan</label>
                        <p class="text-slate-800">{{ $suratKeluar->tujuan }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Alamat Tujuan</label>
                        <p class="text-slate-800">{{ $suratKeluar->alamat_tujuan ?: '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Penandatangan</label>
                        <p class="text-slate-800 font-semibold">{{ $suratKeluar->penandatangan_nama }}</p>
                        <p class="text-sm text-slate-600">{{ $suratKeluar->penandatangan_jabatan }}</p>
                        @if($suratKeluar->penandatangan_nip)
                        <p class="text-xs text-slate-500">NIP: {{ $suratKeluar->penandatangan_nip }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Status</label><br>
                        @if($suratKeluar->status === 'draft')
                        <span class="badge bg-slate-100 text-slate-600">Draft</span>
                        @elseif($suratKeluar->status === 'pending')
                        <span class="badge bg-amber-100 text-amber-600">Pending</span>
                        @elseif($suratKeluar->status === 'approved')
                        <span class="badge bg-emerald-100 text-emerald-600">Approved</span>
                        @elseif($suratKeluar->status === 'published')
                        <span class="badge bg-blue-100 text-blue-600">Published</span>
                        @else
                        <span class="badge bg-rose-100 text-rose-600">Rejected</span>
                        @endif
                    </div>
                    @if($suratKeluar->jumlah_lampiran > 0)
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Lampiran</label>
                        <p class="text-slate-800">{{ $suratKeluar->jumlah_lampiran }} lampiran: {{ $suratKeluar->keterangan_lampiran }}</p>
                    </div>
                    @endif
                    @if($suratKeluar->catatan)
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Catatan</label>
                        <p class="text-slate-600">{{ $suratKeluar->catatan }}</p>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Dibuat Oleh</label>
                        <p class="text-slate-800">{{ $suratKeluar->creator->name }}</p>
                        <p class="text-xs text-slate-500">{{ $suratKeluar->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($suratKeluar->approved_by)
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Disetujui Oleh</label>
                        <p class="text-slate-800">{{ $suratKeluar->approver->name }}</p>
                        <p class="text-xs text-slate-500">{{ $suratKeluar->approved_at->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-slate-50 p-4">
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
