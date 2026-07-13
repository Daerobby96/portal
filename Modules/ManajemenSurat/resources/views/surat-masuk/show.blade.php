@extends('manajemen-surat::layouts.master')

@section('title', 'Detail Surat Masuk')
@section('page-title', 'Detail Surat Masuk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Surat Masuk</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl shadow-sm mb-4">
            <div class="card-header bg-white border-b p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 font-bold">{{ $suratMasuk->nomor_agenda }}</h6>
                    <p class="text-xs text-slate-400 mb-0">{{ $suratMasuk->jenisSurat->nama }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('surat-masuk.edit', $suratMasuk) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @if($suratMasuk->file_path)
                    <a href="{{ route('surat-masuk.download', $suratMasuk) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-download"></i> Download
                    </a>
                    @endif
                    @can('create', \Modules\ManajemenSurat\Models\Disposisi::class)
                    <a href="{{ route('surat-masuk.disposisi.create', $suratMasuk) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-send"></i> Disposisi
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Nomor Agenda</label>
                        <p class="text-slate-800 font-semibold">{{ $suratMasuk->nomor_agenda }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Nomor Surat</label>
                        <p class="text-slate-800">{{ $suratMasuk->nomor_surat }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Tanggal Surat</label>
                        <p class="text-slate-800">{{ $suratMasuk->tanggal_surat->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Tanggal Terima</label>
                        <p class="text-slate-800">{{ $suratMasuk->tanggal_terima->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Pengirim</label>
                        <p class="text-slate-800 font-semibold">{{ $suratMasuk->pengirim }}</p>
                        @if($suratMasuk->alamat_pengirim)
                        <p class="text-sm text-slate-600">{{ $suratMasuk->alamat_pengirim }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Status & Sifat</label><br>
                        @if($suratMasuk->status === 'baru')
                        <span class="badge bg-blue-100 text-blue-600">Baru</span>
                        @elseif($suratMasuk->status === 'proses')
                        <span class="badge bg-amber-100 text-amber-600">Proses</span>
                        @elseif($suratMasuk->status === 'selesai')
                        <span class="badge bg-emerald-100 text-emerald-600">Selesai</span>
                        @else
                        <span class="badge bg-slate-100 text-slate-600">Arsip</span>
                        @endif
                        
                        @if($suratMasuk->sifat === 'sangat_segera')
                        <span class="badge bg-rose-100 text-rose-600">Sangat Segera</span>
                        @elseif($suratMasuk->sifat === 'segera')
                        <span class="badge bg-amber-100 text-amber-600">Segera</span>
                        @elseif($suratMasuk->sifat === 'rahasia')
                        <span class="badge bg-purple-100 text-purple-600">Rahasia</span>
                        @else
                        <span class="badge bg-slate-100 text-slate-600">Biasa</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Perihal</label>
                        <p class="text-slate-800">{{ $suratMasuk->perihal }}</p>
                    </div>
                    @if($suratMasuk->jumlah_lampiran > 0)
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Lampiran</label>
                        <p class="text-slate-800">{{ $suratMasuk->jumlah_lampiran }} lampiran: {{ $suratMasuk->keterangan_lampiran }}</p>
                    </div>
                    @endif
                    @if($suratMasuk->catatan)
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Catatan</label>
                        <p class="text-slate-600">{{ $suratMasuk->catatan }}</p>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Diterima Oleh</label>
                        <p class="text-slate-800">{{ $suratMasuk->receiver->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Diinput Oleh</label>
                        <p class="text-slate-800">{{ $suratMasuk->creator->name }}</p>
                        <p class="text-xs text-slate-500">{{ $suratMasuk->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Riwayat Disposisi</h6>
            </div>
            <div class="card-body p-0">
                @forelse($suratMasuk->disposisi as $disposisi)
                <div class="p-3 border-b hover:bg-slate-50">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-sm font-semibold text-slate-800">{{ $disposisi->kepada->name }}</span>
                        @if($disposisi->status === 'pending')
                        <span class="badge bg-amber-100 text-amber-600 text-xs">Pending</span>
                        @elseif($disposisi->status === 'proses')
                        <span class="badge bg-blue-100 text-blue-600 text-xs">Proses</span>
                        @else
                        <span class="badge bg-emerald-100 text-emerald-600 text-xs">Selesai</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600 mb-1">{{ $disposisi->isi_disposisi }}</p>
                    <p class="text-xs text-slate-400 mb-0">
                        Dari: {{ $disposisi->dari->name }} • {{ $disposisi->created_at->diffForHumans() }}
                    </p>
                    @if($disposisi->batas_waktu)
                    <p class="text-xs text-slate-500 mt-1">
                        <i class="bi bi-clock"></i> Deadline: {{ $disposisi->batas_waktu->format('d M Y') }}
                    </p>
                    @endif
                </div>
                @empty
                <div class="p-4 text-center text-slate-400 text-sm">Belum ada disposisi</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
