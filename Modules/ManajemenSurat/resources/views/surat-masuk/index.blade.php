@extends('manajemen-surat::layouts.master')

@section('title', 'Surat Masuk')
@section('page-title', 'Surat Masuk')
@section('page-subtitle', 'Kelola surat masuk dari eksternal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Surat Masuk</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="jenis_surat_id" class="form-select">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSurat as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="arsip" {{ request('status') == 'arsip' ? 'selected' : '' }}>Arsip</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sifat" class="form-select">
                            <option value="">Semua Sifat</option>
                            <option value="biasa" {{ request('sifat') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                            <option value="segera" {{ request('sifat') == 'segera' ? 'selected' : '' }}>Segera</option>
                            <option value="sangat_segera" {{ request('sifat') == 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                            <option value="rahasia" {{ request('sifat') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search"></i></button>
                        <a href="{{ route('surat-masuk.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 font-bold">Daftar Surat Masuk</h6>
                    <p class="text-xs text-slate-400 mb-0">Total: {{ $suratMasuk->total() }}</p>
                </div>
                <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Catat Surat Baru
                </a>
            </div>

            @if(session('success'))
            <div class="mx-4 mt-3 alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
            @endif

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-3 px-4">#</th>
                                <th class="py-3">No. Agenda</th>
                                <th class="py-3">No. Surat</th>
                                <th class="py-3">Pengirim</th>
                                <th class="py-3">Perihal</th>
                                <th class="py-3">Tgl Terima</th>
                                <th class="py-3 text-center">Sifat</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suratMasuk as $surat)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-3 px-4">{{ $loop->iteration + ($suratMasuk->currentPage() - 1) * $suratMasuk->perPage() }}</td>
                                <td class="py-3 font-semibold">{{ $surat->nomor_agenda }}</td>
                                <td class="py-3">{{ $surat->nomor_surat }}</td>
                                <td class="py-3">{{ Str::limit($surat->pengirim, 30) }}</td>
                                <td class="py-3">{{ Str::limit($surat->perihal, 40) }}</td>
                                <td class="py-3">{{ $surat->tanggal_terima->format('d M Y') }}</td>
                                <td class="py-3 text-center">
                                    @if($surat->sifat === 'sangat_segera')
                                    <span class="badge bg-rose-100 text-rose-600">Sangat Segera</span>
                                    @elseif($surat->sifat === 'segera')
                                    <span class="badge bg-amber-100 text-amber-600">Segera</span>
                                    @elseif($surat->sifat === 'rahasia')
                                    <span class="badge bg-purple-100 text-purple-600">Rahasia</span>
                                    @else
                                    <span class="badge bg-slate-100 text-slate-600">Biasa</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if($surat->status === 'baru')
                                    <span class="badge bg-blue-100 text-blue-600">Baru</span>
                                    @elseif($surat->status === 'proses')
                                    <span class="badge bg-amber-100 text-amber-600">Proses</span>
                                    @elseif($surat->status === 'selesai')
                                    <span class="badge bg-emerald-100 text-emerald-600">Selesai</span>
                                    @else
                                    <span class="badge bg-slate-100 text-slate-600">Arsip</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('surat-masuk.show', $surat) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('surat-masuk.edit', $surat) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                                        @if($surat->file_path)
                                        <a href="{{ route('surat-masuk.download', $surat) }}" class="btn btn-sm btn-light"><i class="bi bi-download"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="py-5 text-center text-slate-400">Belum ada surat masuk</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($suratMasuk->hasPages())
            <div class="card-footer p-4">{{ $suratMasuk->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
