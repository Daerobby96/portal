@extends('datamaster::layouts.master')

@section('title', 'Program Studi')
@section('page-title', 'Data Program Studi')

@section('content')
<div class="container-fluid px-4">


@push('styles')
<style>
    .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative; overflow: hidden; z-index: 1;
    }
    .premium-card::before {
        content:''; position:absolute; top:0; left:0; width:4px; height:100%;
        background:var(--accent-color);
    }
    .premium-card:hover { transform:translateY(-5px); box-shadow:0 15px 35px rgba(0,0,0,0.05); }
    .premium-icon-container {
        width:48px; height:48px; display:flex; align-items:center; justify-content:center;
        border-radius:14px; background:var(--bg-color); color:var(--accent-color); font-size:1.5rem;
        transition:all 0.3s ease;
    }
    .premium-card:hover .premium-icon-container { transform:scale(1.1) rotate(5deg); box-shadow:0 6px 15px var(--shadow-color); }
</style>
@endpush

    <div class="row mb-4 g-3">
        @php $cards = [
            ['label'=>'Total Program Studi','value'=>$stats['total'],'icon'=>'bi-building','accent'=>'#4f46e5','bg'=>'rgba(79,70,229,0.08)','shadow'=>'rgba(79,70,229,0.15)'],
            ['label'=>'Prodi Aktif','value'=>$stats['aktif'],'icon'=>'bi-check-circle-fill','accent'=>'#10b981','bg'=>'rgba(16,185,129,0.08)','shadow'=>'rgba(16,185,129,0.15)'],
        ]; @endphp
        @foreach($cards as $c)
        <div class="col-md-3">
            <div class="card premium-card h-100" style="--accent-color:{{ $c['accent'] }};">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="premium-icon-container" style="--bg-color:{{ $c['bg'] }};--accent-color:{{ $c['accent'] }};--shadow-color:{{ $c['shadow'] }};">
                        <i class="bi {{ $c['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size:0.72rem;letter-spacing:0.5px;">{{ $c['label'] }}</p>
                        <h3 class="mb-0 fw-bold text-dark mt-1" style="font-size:1.8rem;line-height:1.2;">{{ $c['value'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-secondary mb-0">Daftar Program Studi</h5>
            <a href="{{ route('program-studi.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Prodi
            </a>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('program-studi.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode prodi..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill shadow-sm"><i class="bi bi-funnel me-1"></i>Cari</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="rounded-start-3" width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama Program Studi</th>
                            <th>Jenjang</th>
                            <th>Akreditasi</th>
                            <th>Status</th>
                            <th class="rounded-end-3 text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($program_studis as $i => $ps)
                        <tr>
                            <td class="text-muted text-center">{{ $program_studis->firstItem() + $i }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fw-bold">{{ $ps->kode }}</span></td>
                            <td class="fw-semibold">{{ $ps->nama }}</td>
                            <td>
                                <span class="badge {{ $ps->jenjang == 'S1' ? 'bg-info' : ($ps->jenjang == 'S2' ? 'bg-purple' : 'bg-secondary') }} bg-opacity-10 text-dark border">{{ $ps->jenjang }}</span>
                            </td>
                            <td>{{ $ps->akreditasi ?? '-' }}</td>
                            <td>
                                @if($ps->is_aktif)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border"><i class="bi bi-x-circle me-1"></i>Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('program-studi.edit', $ps) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('program-studi.destroy', $ps) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-3 opacity-50"></i>
                                Belum ada data Program Studi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $program_studis->links() }}</div>
        </div>
    </div>
</div>
@endsection

