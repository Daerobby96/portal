@extends('systemadmin::layouts.master')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola pengguna dan hak akses sistem')

@section('breadcrumb')
    <li class="breadcrumb-item active">Manajemen User</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Statistik (Luxury cards with glowing backlights) --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-blue-600">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-blue-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">Total User</span>
                    <h2 class="fw-extrabold text-slate-800 mt-1 mb-0">{{ $stats['total'] }}</h2>
                </div>
                <div class="p-3.5 rounded-2xl bg-blue-50 text-blue-600">
                    <i class="bi bi-people fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-emerald-500">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">User Aktif</span>
                    <h2 class="fw-extrabold text-emerald-600 mt-1 mb-0">{{ $stats['aktif'] }}</h2>
                </div>
                <div class="p-3.5 rounded-2xl bg-emerald-50 text-emerald-600">
                    <i class="bi bi-person-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-100 overflow-hidden relative group border-l-4 border-slate-400">
            <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-slate-500/10 blur-xl transition-all duration-500 group-hover:scale-150"></div>
            <div class="card-body p-4 position-relative z-10 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-xs font-bold text-slate-400 text-uppercase tracking-wider">User Nonaktif</span>
                    <h2 class="fw-extrabold text-slate-600 mt-1 mb-0">{{ $stats['nonaktif'] }}</h2>
                </div>
                <div class="p-3.5 rounded-2xl bg-slate-100 text-slate-600">
                    <i class="bi bi-person-x fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Daftar User --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="card-header bg-white py-4 px-4 border-b border-slate-100 d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <h6 class="mb-0 font-bold text-slate-800 d-flex align-items-center gap-2">
                    <i class="bi bi-people text-blue-600"></i>
                    <span>Daftar User</span>
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary rounded-xl text-xs font-bold px-3 border-slate-200 hover:border-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-file-earmark-excel me-1 text-success"></i>Import Excel
                    </button>
                    <a href="{{ route('users.create') }}" class="btn btn-primary rounded-xl text-xs font-bold px-3 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                {{-- Filter --}}
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-slate-200 border-end-0 rounded-start-xl text-slate-400">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-slate-200 border-start-0 rounded-end-xl text-xs py-2"
                                   placeholder="Cari nama, email, NIP..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select border-slate-200 rounded-xl text-xs py-2 text-slate-700">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ Str::title(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select border-slate-200 rounded-xl text-xs py-2 text-slate-700">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary rounded-xl font-bold text-xs px-3 py-2">
                            Cari
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-xl font-bold text-xs px-3 py-2 border-slate-200">
                            Reset
                        </a>
                    </div>
                </form>

                {{-- Tabel User --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-slate-50/70">
                            <tr>
                                <th class="ps-4 py-3 text-slate-500 font-bold small text-uppercase tracking-wider">User</th>
                                <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">NIP</th>
                                <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Email</th>
                                <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Role</th>
                                <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Status</th>
                                <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center pe-4" style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" 
                                                 class="rounded-circle" width="38" height="38" 
                                                 style="object-fit:cover; border: 2px solid #e2e8f0;">
                                        @else
                                            <div class="avatar-circle-lux">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-slate-800" style="font-size: 13.5px;">{{ $user->name }}</div>
                                            <small class="text-slate-400 font-semibold">{{ $user->jabatan ?? 'User SPMI' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="small px-2 py-1 rounded bg-slate-50 text-slate-600">{{ $user->nip ?? '-' }}</code>
                                </td>
                                <td><span class="small text-slate-600 font-medium">{{ $user->email }}</span></td>
                                <td>
                                    @php
                                        $rName = $user->roles->first()?->name ?? '-';
                                        $bClass = match($rName) {
                                            'super_admin' => 'bg-red-50 text-red-600 border-red-200/50',
                                            'auditor'     => 'bg-blue-50 text-blue-600 border-blue-200/50',
                                            'auditee'     => 'bg-emerald-50 text-emerald-600 border-emerald-200/50',
                                            default       => 'bg-slate-50 text-slate-500 border-slate-200/50'
                                        };
                                    @endphp
                                    <span class="badge {{ $bClass }} border rounded-pill px-2.5 py-1.5 font-bold text-[10px]">
                                        {{ Str::title(str_replace('_', ' ', $rName)) }}
                                    </span>
                                </td>
                                <td><span class="small text-slate-500 font-semibold">{{ $user->unit_kerja ?? '-' }}</span></td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-emerald-50 text-emerald-600 border border-emerald-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px]">
                                            <span class="d-inline-block w-1.5 h-1.5 bg-emerald-500 rounded-full me-1"></span>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-slate-50 text-slate-400 border border-slate-200/50 rounded-pill font-bold px-2.5 py-1.5 text-[10px]">
                                            <span class="d-inline-block w-1.5 h-1.5 bg-slate-300 rounded-full me-1"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-light text-primary rounded-xl" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light text-warning rounded-xl" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-light text-{{ $user->is_active ? 'danger' : 'success' }} rounded-xl"
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="bi bi-{{ $user->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-xl" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-slate-400 py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    <span class="small font-semibold">Tidak ada data user.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                <div class="pagination-wrapper mt-4 pt-3 border-t border-slate-100 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div class="pagination-info small text-slate-400 font-semibold">
                        Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} data
                    </div>
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Import Modal (Modernized) --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-2xl shadow-xl overflow-hidden bg-white">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-b border-slate-100 py-3.5 px-4">
                    <h5 class="modal-title fw-extrabold text-slate-800 mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-cloud-arrow-up text-primary"></i>
                        <span>Import User via Excel</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-xl small d-flex gap-2 mb-4">
                        <i class="bi bi-info-circle-fill text-info-600 mt-0.5"></i>
                        <span>Pastikan berkas Excel memiliki judul kolom: <strong>nama, email, role, nip, unit_kerja, jabatan</strong>.<br>Pilihan role: <code>auditor</code>, <code>auditee</code>, <code>pimpinan</code>, <code>staff</code>.</span>
                    </div>
                    <div class="p-3 bg-slate-50/50 border border-slate-100 rounded-2xl text-center mb-4">
                        <label class="form-label font-bold text-slate-500 small text-uppercase mb-2 d-block">Format File Excel Acuan</label>
                        <a href="{{ route('users.template') }}" class="btn btn-xs btn-outline-primary rounded-pill px-3 font-bold">
                            <i class="bi bi-download me-1.5"></i> Download Template User
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-bold text-slate-700 small mb-2">PILIH FILE EXCEL (.xlsx / .csv)</label>
                        <input type="file" name="file" class="form-control rounded-xl text-xs" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-slate-50 py-3 px-4">
                    <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-xl font-bold text-xs px-4 shadow-sm">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle-lux {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 13.5px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
    color: var(--primary-color);
    border: 2px solid rgba(var(--primary-color-rgb), 0.1);
}
</style>
@endpush
@endsection

