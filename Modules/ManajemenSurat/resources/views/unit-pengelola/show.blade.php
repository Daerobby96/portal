@extends('manajemen-surat::layouts.master')

@section('title', 'Detail Unit Pengelola')
@section('page-title', 'Detail Unit Pengelola')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('unit-pengelola.index') }}">Unit Pengelola</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Card --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 font-bold">{{ $unitPengelola->nama }}</h5>
                    <span class="badge bg-slate-800 text-white font-mono">{{ $unitPengelola->kode }}</span>
                    @if($unitPengelola->jenis_institusi === 'yayasan')
                    <span class="badge bg-purple-100 text-purple-700">Yayasan</span>
                    @else
                    <span class="badge bg-blue-100 text-blue-700">Perguruan Tinggi</span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('unit-pengelola.edit', $unitPengelola) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('unit-pengelola.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Format Nomor Surat</label>
                        <p class="font-mono text-slate-800">{{ $unitPengelola->prefix_format ?: 'Format default' }}</p>
                        <small class="text-muted">Contoh: 001/{{ $unitPengelola->kode }}/ST/01/2025</small>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Kop Surat</label>
                        <p class="text-slate-800">
                            {{ $unitPengelola->jenis_institusi === 'yayasan' ? 'Kop Yayasan (dari pengaturan)' : 'Kop Perguruan Tinggi (dari pengaturan)' }}
                        </p>
                    </div>
                    @if($unitPengelola->deskripsi)
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Deskripsi</label>
                        <p class="text-slate-600">{{ $unitPengelola->deskripsi }}</p>
                    </div>
                    @endif
                    @if($unitPengelola->pic_nama)
                    <div class="col-md-4">
                        <label class="text-xs font-semibold text-slate-400">Person In Charge</label>
                        <p class="text-slate-800 font-semibold">{{ $unitPengelola->pic_nama }}</p>
                        @if($unitPengelola->pic_jabatan)
                        <p class="text-sm text-slate-600">{{ $unitPengelola->pic_jabatan }}</p>
                        @endif
                        @if($unitPengelola->pic_nip)
                        <p class="text-xs text-slate-500">NIP: {{ $unitPengelola->pic_nip }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-blue-600 to-blue-700">
                    <div class="card-body p-4 text-white">
                        <div class="text-blue-100 text-xs font-semibold">Total Surat</div>
                        <div class="text-white text-2xl font-black">{{ $stats['total_surat'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-emerald-600 to-emerald-700">
                    <div class="card-body p-4 text-white">
                        <div class="text-emerald-100 text-xs font-semibold">Bulan Ini</div>
                        <div class="text-white text-2xl font-black">{{ $stats['surat_bulan_ini'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-amber-600 to-amber-700">
                    <div class="card-body p-4 text-white">
                        <div class="text-amber-100 text-xs font-semibold">Draft</div>
                        <div class="text-white text-2xl font-black">{{ $stats['draft'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 rounded-2xl shadow-sm bg-gradient-to-br from-indigo-600 to-indigo-700">
                    <div class="card-body p-4 text-white">
                        <div class="text-indigo-100 text-xs font-semibold">Published</div>
                        <div class="text-white text-2xl font-black">{{ $stats['published'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Surat --}}
    @if($unitPengelola->suratKeluar->count() > 0)
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Surat Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-3 px-4 text-xs">Nomor Surat</th>
                                <th class="py-3 text-xs">Perihal</th>
                                <th class="py-3 text-xs">Tanggal</th>
                                <th class="py-3 text-xs text-center">Status</th>
                                <th class="py-3 px-4 text-xs text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unitPengelola->suratKeluar as $surat)
                            <tr class="border-b">
                                <td class="py-3 px-4 font-mono text-sm">{{ $surat->nomor_surat }}</td>
                                <td class="py-3 text-sm">{{ Str::limit($surat->perihal, 50) }}</td>
                                <td class="py-3 text-sm">{{ $surat->tanggal_surat->format('d M Y') }}</td>
                                <td class="py-3 text-center">
                                    @if($surat->status === 'draft')
                                    <span class="badge bg-slate-100 text-slate-600">Draft</span>
                                    @elseif($surat->status === 'pending')
                                    <span class="badge bg-amber-100 text-amber-600">Pending</span>
                                    @else
                                    <span class="badge bg-blue-100 text-blue-600">Published</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('surat-keluar.show', $surat) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
