@extends('manajemen-surat::layouts.master')

@section('title', 'Generator Surat Keputusan')
@section('page-title', 'Generator Surat Keputusan (SK)')
@section('page-subtitle', 'Buat dan kelola Surat Keputusan Yayasan & Perguruan Tinggi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Manajemen Surat</a></li>
    <li class="breadcrumb-item active">Surat Keputusan</li>
@endsection

@section('content')
<div class="row g-4">

    {{-- Header Stats --}}
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 rounded-2xl overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-blue-600 to-blue-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white">
                                <i class="bi bi-file-earmark-text fs-4"></i>
                            </div>
                            <div>
                                <div class="text-blue-100 text-xs font-semibold">Total SK Dibuat</div>
                                <div class="text-white text-2xl font-black">{{ $sks->total() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-2xl overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-purple-600 to-purple-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white">
                                <i class="bi bi-building fs-4"></i>
                            </div>
                            <div>
                                <div class="text-purple-100 text-xs font-semibold">SK Yayasan</div>
                                <div class="text-white text-2xl font-black">{{ $sks->where('jenis_sk', 'yayasan')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 rounded-2xl overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] bg-gradient-to-br from-emerald-600 to-emerald-700">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white">
                                <i class="bi bi-mortarboard fs-4"></i>
                            </div>
                            <div>
                                <div class="text-emerald-100 text-xs font-semibold">SK Perguruan Tinggi</div>
                                <div class="text-white text-2xl font-black">{{ $sks->where('jenis_sk', 'pt')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-white border-b border-slate-100 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-bold text-slate-800">Daftar Surat Keputusan</h6>
                        <div class="text-xs text-slate-400">Riwayat SK yang telah dibuat</div>
                    </div>
                </div>
                <a href="{{ route('surat-keputusan.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-md hover:shadow-blue-600/20">
                    <i class="bi bi-plus-lg"></i>
                    <span>Buat SK Baru</span>
                </a>
            </div>

            @if(session('success'))
                <div class="mx-4 mt-3 alert alert-success d-flex align-items-center gap-2 border-0 rounded-xl bg-emerald-50 text-emerald-700 py-3 px-4">
                    <i class="bi bi-check-circle-fill text-emerald-500"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mx-4 mt-3 alert alert-danger d-flex align-items-center gap-2 border-0 rounded-xl bg-rose-50 text-rose-700 py-3 px-4">
                    <i class="bi bi-exclamation-circle-fill text-rose-500"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th width="50" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">#</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Nomor SK</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tentang</th>
                                <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Jenis</th>
                                <th width="130" class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Tanggal</th>
                                <th class="text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5">Penandatangan</th>
                                <th width="120" class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 py-3.5 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-t-0">
                            @forelse($sks as $sk)
                            <tr class="border-b border-slate-100 transition-colors hover:bg-slate-50/40">
                                <td class="text-center text-slate-400 text-sm font-semibold py-3.5">{{ $loop->iteration }}</td>
                                <td class="py-3.5">
                                    <div class="font-bold text-slate-800 text-sm">{{ $sk->nomor_sk }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">Dibuat: {{ $sk->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="py-3.5 text-sm text-slate-600 max-w-xs">
                                    <div class="line-clamp-2">{{ $sk->tentang }}</div>
                                </td>
                                <td class="text-center py-3.5">
                                    @if($sk->jenis_sk === 'yayasan')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 border border-purple-200 text-purple-600 px-2.5 py-1 text-xs font-bold">
                                            <i class="bi bi-building"></i> Yayasan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 px-2.5 py-1 text-xs font-bold">
                                            <i class="bi bi-mortarboard"></i> PT
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-sm text-slate-600">
                                    {{ $sk->tanggal_ditetapkan->format('d M Y') }}
                                </td>
                                <td class="py-3.5">
                                    <div class="text-sm font-semibold text-slate-700">{{ $sk->penandatangan_nama }}</div>
                                    <div class="text-xs text-slate-400">{{ $sk->penandatangan_jabatan }}</div>
                                </td>
                                <td class="text-center py-3.5 pe-4">
                                    <div class="d-flex gap-1.5 justify-content-end">
                                        <a href="{{ route('surat-keputusan.download', $sk) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50/20 text-blue-600 transition-all hover:bg-blue-600 hover:text-white"
                                           title="Download PDF">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="{{ route('surat-keputusan.destroy', $sk) }}" method="POST" class="d-inline m-0"
                                              onsubmit="return confirm('Hapus SK ini? File PDF juga akan dihapus.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50/20 text-rose-500 transition-all hover:bg-rose-500 hover:text-white"
                                                title="Hapus SK">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="d-flex flex-column align-items-center justify-center py-4">
                                        <div class="d-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300 border border-slate-100 mb-3">
                                            <i class="bi bi-file-earmark-text fs-1"></i>
                                        </div>
                                        <h6 class="font-bold text-slate-700 mb-1">Belum Ada Surat Keputusan</h6>
                                        <p class="text-xs font-medium text-slate-400 mb-3">Mulai buat SK pertama untuk Yayasan atau Perguruan Tinggi.</p>
                                        <a href="{{ route('surat-keputusan.create') }}"
                                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-all">
                                            <i class="bi bi-plus-lg"></i> Buat SK Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($sks->hasPages())
            <div class="p-4 border-t border-slate-100 bg-white">
                {{ $sks->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

