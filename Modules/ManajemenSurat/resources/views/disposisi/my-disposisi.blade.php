@extends('manajemen-surat::layouts.master')

@section('title', 'Disposisi Saya')
@section('page-title', 'Disposisi Saya')
@section('page-subtitle', 'Daftar disposisi yang perlu ditindaklanjuti')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Disposisi Saya</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="overdue" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ request('overdue') == '1' ? 'selected' : '' }}>Hanya Overdue</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search"></i> Filter</button>
                        <a href="{{ route('disposisi.my-disposisi') }}" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Daftar Disposisi</h6>
                <p class="text-xs text-slate-400 mb-0">Total: {{ $disposisi->total() }}</p>
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
                                <th class="py-3">No. Agenda Surat</th>
                                <th class="py-3">Perihal</th>
                                <th class="py-3">Dari</th>
                                <th class="py-3">Isi Disposisi</th>
                                <th class="py-3">Batas Waktu</th>
                                <th class="py-3 text-center">Prioritas</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disposisi as $item)
                            <tr class="border-b hover:bg-slate-50 {{ $item->isOverdue() ? 'bg-rose-50/30' : '' }}">
                                <td class="py-3 px-4">{{ $loop->iteration + ($disposisi->currentPage() - 1) * $disposisi->perPage() }}</td>
                                <td class="py-3 font-semibold">{{ $item->suratMasuk->nomor_agenda }}</td>
                                <td class="py-3">{{ Str::limit($item->suratMasuk->perihal, 40) }}</td>
                                <td class="py-3">{{ $item->dari->name }}</td>
                                <td class="py-3">{{ Str::limit($item->isi_disposisi, 50) }}</td>
                                <td class="py-3">
                                    @if($item->batas_waktu)
                                    {{ $item->batas_waktu->format('d M Y') }}
                                    @if($item->isOverdue())
                                    <span class="badge bg-rose-100 text-rose-600 text-xs">Overdue</span>
                                    @endif
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if($item->prioritas === 'tinggi')
                                    <span class="badge bg-rose-100 text-rose-600">Tinggi</span>
                                    @elseif($item->prioritas === 'sedang')
                                    <span class="badge bg-amber-100 text-amber-600">Sedang</span>
                                    @else
                                    <span class="badge bg-slate-100 text-slate-600">Rendah</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if($item->status === 'pending')
                                    <span class="badge bg-amber-100 text-amber-600">Pending</span>
                                    @elseif($item->status === 'dibaca')
                                    <span class="badge bg-blue-100 text-blue-600">Dibaca</span>
                                    @elseif($item->status === 'proses')
                                    <span class="badge bg-purple-100 text-purple-600">Proses</span>
                                    @else
                                    <span class="badge bg-emerald-100 text-emerald-600">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('disposisi.show', $item) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="py-5 text-center text-slate-400">Tidak ada disposisi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($disposisi->hasPages())
            <div class="card-footer p-4">{{ $disposisi->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
