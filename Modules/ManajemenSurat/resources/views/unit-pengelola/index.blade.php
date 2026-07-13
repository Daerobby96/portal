@extends('manajemen-surat::layouts.master')

@section('title', 'Unit Pengelola Surat')
@section('page-title', 'Unit Pengelola Surat')
@section('page-subtitle', 'Kelola unit pengelola surat dan format nomor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Unit Pengelola</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Info Alert --}}
    <div class="col-12">
        <div class="alert alert-info border-0 rounded-2xl d-flex align-items-start gap-3 bg-blue-50">
            <i class="bi bi-info-circle-fill text-blue-600 fs-5"></i>
            <div>
                <h6 class="font-bold text-blue-800 mb-1">Tentang Unit Pengelola Surat</h6>
                <p class="text-sm text-blue-700 mb-2">
                    Setiap unit dapat memiliki <strong>format nomor surat sendiri</strong>. 
                    Format default: <code>{nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}</code>
                </p>
                <p class="text-sm text-blue-700 mb-0">
                    <strong>Kop Surat:</strong> Otomatis menggunakan kop dari pengaturan aplikasi berdasarkan jenis institusi:
                </p>
                <ul class="text-sm text-blue-700 mb-0 mt-1">
                    <li><strong>Yayasan</strong> → Kop Surat Yayasan (dari pengaturan)</li>
                    <li><strong>Perguruan Tinggi</strong> → Kop Surat PT (dari pengaturan)</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b border-slate-100 p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-xl bg-indigo-50 p-2">
                        <i class="bi bi-building text-indigo-600"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-bold text-slate-800">Daftar Unit Pengelola</h6>
                        <p class="text-xs text-slate-400 mb-0">Total: {{ $units->count() }} unit</p>
                    </div>
                </div>
                <a href="{{ route('unit-pengelola.create') }}" class="btn btn-primary px-4">
                    <i class="bi bi-plus-lg"></i> Tambah Unit
                </a>
            </div>

            @if(session('success'))
            <div class="mx-4 mt-3 alert alert-success border-0 rounded-xl">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mx-4 mt-3 alert alert-danger border-0 rounded-xl">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
            @endif

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-xs font-bold uppercase text-slate-400">#</th>
                                <th class="py-3 text-xs font-bold uppercase text-slate-400">Nama Unit</th>
                                <th class="py-3 text-xs font-bold uppercase text-slate-400">Kode</th>
                                <th class="py-3 text-xs font-bold uppercase text-slate-400">Jenis Institusi</th>
                                <th class="py-3 text-xs font-bold uppercase text-slate-400">Format Nomor</th>
                                <th class="py-3 text-xs font-bold uppercase text-slate-400 text-center">Status</th>
                                <th class="py-3 px-4 text-xs font-bold uppercase text-slate-400 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-400 font-semibold">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div class="font-bold text-sm text-slate-800">{{ $unit->nama }}</div>
                                    @if($unit->pic_nama)
                                    <div class="text-xs text-slate-400">PIC: {{ $unit->pic_nama }}</div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-slate-800 text-white font-mono">{{ $unit->kode }}</span>
                                </td>
                                <td class="py-3">
                                    @if($unit->jenis_institusi === 'yayasan')
                                    <span class="badge bg-purple-100 text-purple-700">Yayasan</span>
                                    @else
                                    <span class="badge bg-blue-100 text-blue-700">Perguruan Tinggi</span>
                                    @endif
                                </td>
                                <td class="py-3 font-mono text-xs text-slate-600">
                                    {{ $unit->prefix_format ?: 'Format default' }}
                                </td>
                                <td class="py-3 text-center">
                                    @if($unit->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('unit-pengelola.show', $unit) }}" class="btn btn-sm btn-light" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('unit-pengelola.edit', $unit) }}" class="btn btn-sm btn-light" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('unit-pengelola.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus unit ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center">
                                    <div class="text-center">
                                        <i class="bi bi-inbox text-slate-300 fs-1"></i>
                                        <h6 class="font-bold text-slate-700 mt-3">Belum Ada Unit</h6>
                                        <p class="text-sm text-slate-400 mb-3">Tambah unit pengelola surat</p>
                                        <a href="{{ route('unit-pengelola.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-lg"></i> Tambah Unit
                                        </a>
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
</div>
@endsection
