@extends('datamaster::layouts.master')

@section('title', 'Manajemen Periode')
@section('page-title', 'Manajemen Periode')
@section('page-subtitle', 'Kelola periode/semester untuk data SPMI')

@section('page-actions')
    <a href="{{ route('periode.create') }}" class="btn btn-primary rounded-xl text-xs font-bold px-4 shadow-sm">
        <i class="bi bi-plus-lg me-1.5"></i>Tambah Periode
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen Periode</li>
@endsection

@section('content')
<div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="ps-4 py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 80px">#</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Nama Periode</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 150px">Tahun</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 150px">Semester</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center" style="width: 150px">Status</th>
                        <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center pe-4" style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodes as $periode)
                    <tr>
                        <td class="ps-4"><span class="small font-bold text-slate-400">{{ $loop->iteration }}</span></td>
                        <td>
                            <span class="fw-bold text-slate-800" style="font-size: 13.5px;">{{ $periode->nama }}</span>
                        </td>
                        <td>
                            <code class="small px-2 py-1 rounded bg-slate-50 text-slate-600 font-semibold">{{ $periode->tahun }}</code>
                        </td>
                        <td>
                            @php
                                $sem = strtolower($periode->semester ?? '');
                                $sClass = $sem === 'ganjil' ? 'bg-blue-50 text-blue-600 border-blue-200/50' : 'bg-cyan-50 text-cyan-600 border-cyan-200/50';
                            @endphp
                            <span class="badge {{ $sClass }} border rounded-pill px-2.5 py-1.5 font-bold text-[10px]">
                                {{ ucfirst($periode->semester) }}
                            </span>
                        </td>
                        <td><span class="small text-slate-600 font-medium">{{ $periode->tanggal_mulai->translatedFormat('d F Y') }}</span></td>
                        <td><span class="small text-slate-600 font-medium">{{ $periode->tanggal_selesai->translatedFormat('d F Y') }}</span></td>
                        <td class="text-center">
                            @if($periode->is_aktif)
                                <span class="badge bg-emerald-50 text-emerald-600 border border-emerald-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px] d-inline-flex align-items-center gap-1 shadow-sm">
                                    <span class="relative d-flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span>Aktif</span>
                                </span>
                            @else
                                <span class="badge bg-slate-50 text-slate-400 border border-slate-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px]">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                @if(!$periode->is_aktif)
                                <form action="{{ route('periode.activate', $periode) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light text-success rounded-xl" 
                                            title="Aktifkan periode ini">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('periode.edit', $periode) }}"
                                   class="btn btn-sm btn-light text-primary rounded-xl" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('periode.destroy', $periode) }}" method="POST"
                                      onsubmit="return confirm('Hapus periode ini?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-xl" 
                                            title="Hapus" {{ $periode->is_aktif ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-slate-400 py-5">
                            <i class="bi bi-calendar-x d-block fs-2 mb-3 opacity-25"></i>
                            <span class="small font-semibold">Belum ada data periode.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($periodes->hasPages())
    <div class="pagination-wrapper p-4 border-t border-slate-100 d-flex flex-wrap gap-2 justify-content-between align-items-center bg-white">
        <div class="pagination-info small text-slate-400 font-semibold">
            Menampilkan {{ $periodes->firstItem() }}-{{ $periodes->lastItem() }} dari {{ $periodes->total() }} data
        </div>
        {{ $periodes->links() }}
    </div>
    @endif
</div>
@endsection

