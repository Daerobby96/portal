@extends('systemadmin::layouts.master')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('page-subtitle', 'Pantau aktivitas pengguna dan perubahan sistem secara real-time.')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activity Log</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Filter Log --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="card-body p-4">
                <form action="{{ route('activity-log.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label font-bold text-slate-700 small mb-2">CARI AKTIVITAS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-slate-200 border-end-0 rounded-start-xl text-slate-400">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-slate-200 border-start-0 rounded-end-xl text-xs py-2" 
                                   placeholder="Cari deskripsi, aksi, atau user..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-bold text-slate-700 small mb-2">PENGGUNA</label>
                        <select name="user_id" class="form-select border-slate-200 rounded-xl text-xs py-2 text-slate-700">
                            <option value="">Semua User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-bold text-slate-700 small mb-2">AKSI</label>
                        <select name="action" class="form-select border-slate-200 rounded-xl text-xs py-2 text-slate-700">
                            <option value="">Semua Aksi</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary rounded-xl font-bold text-xs px-4 py-2">
                            Filter
                        </button>
                        <a href="{{ route('activity-log.index') }}" class="btn btn-outline-secondary rounded-xl font-bold text-xs px-4 py-2 border-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Log (Console Log aesthetics) --}}
    <div class="col-12">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-slate-50/70">
                        <tr>
                            <th class="ps-4 py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Timestamp</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Pengguna</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 120px">Aksi</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider">Deskripsi</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider" style="width: 150px">IP Address</th>
                            <th class="py-3 text-slate-500 font-bold small text-uppercase tracking-wider text-center pe-4" style="width: 120px">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-slate-800 small">{{ $log->created_at->translatedFormat('d F Y') }}</div>
                                    <span class="text-slate-400 font-semibold text-[10px]">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td>
                                    @if($log->user)
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar-sm-log">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-slate-800 small">{{ $log->user->name }}</div>
                                                <div class="text-slate-400 font-semibold" style="font-size: 10px;">{{ Str::title(str_replace("_", " ", $log->user->roles->first()?->name ?? "-")) ?? '-' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="avatar-sm-log system">
                                                S
                                            </div>
                                            <div>
                                                <span class="text-slate-400 italic font-semibold small">Sistem Otomatis</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($log->action) {
                                            'created' => 'bg-emerald-50 text-emerald-600 border-emerald-200/50',
                                            'updated' => 'bg-blue-50 text-blue-600 border-blue-200/50',
                                            'deleted' => 'bg-red-50 text-red-600 border-red-200/50',
                                            'login', 'logout' => 'bg-amber-50 text-amber-600 border-amber-200/50',
                                            default   => 'bg-slate-50 text-slate-500 border-slate-200/50'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} border rounded px-2.5 py-1.5 font-bold text-[10px]">
                                        {{ strtoupper($log->action) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-slate-700 small font-medium">{{ $log->description }}</div>
                                    @if($log->model_type)
                                        <div class="text-slate-400 font-semibold mt-1" style="font-size: 10px;">
                                            <code>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</code>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <code class="small px-2 py-1 rounded bg-slate-50 text-slate-600 font-semibold">{{ $log->ip_address }}</code>
                                </td>
                                <td class="text-center pe-4">
                                    @if($log->properties)
                                        <button type="button" class="btn btn-sm btn-light text-primary rounded-xl font-bold text-xs" 
                                                data-bs-toggle="modal" data-bs-target="#modal-log-{{ $log->id }}">
                                            View Data
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="modal-log-{{ $log->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-centered text-start">
                                                <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden bg-white">
                                                    <div class="modal-header border-b border-slate-100 py-3.5 px-4">
                                                        <h5 class="modal-title fw-extrabold text-slate-800 mb-0 d-flex align-items-center gap-2">
                                                            <i class="bi bi-info-circle text-primary"></i>
                                                            <span>Rincian Rekam Aktivitas</span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4 bg-slate-50/30">
                                                        <div class="row g-4">
                                                            <div class="col-md-5">
                                                                <h6 class="fw-bold mb-3 pb-2 text-primary border-b border-slate-100 d-flex align-items-center gap-1.5">
                                                                    <span class="d-inline-block w-2 h-4 bg-primary rounded-pill"></span>
                                                                    Metadata Log
                                                                </h6>
                                                                <table class="table table-sm table-borderless small bg-white rounded-xl border border-slate-100 p-3 mb-0">
                                                                    <tr><th class="ps-3 pt-3 text-slate-400 font-bold text-uppercase text-[10px]">User</th><td class="pt-3 text-slate-700 font-semibold">{{ $log->user->name ?? 'System' }}</td></tr>
                                                                    <tr><th class="ps-3 text-slate-400 font-bold text-uppercase text-[10px]">Action</th><td><span class="badge {{ $badgeClass }} border rounded px-2 py-1 text-[9px] font-bold">{{ strtoupper($log->action) }}</span></td></tr>
                                                                    <tr><th class="ps-3 text-slate-400 font-bold text-uppercase text-[10px]">Model Type</th><td><code class="small">{{ class_basename($log->model_type) }}</code></td></tr>
                                                                    <tr><th class="ps-3 text-slate-400 font-bold text-uppercase text-[10px]">IP Address</th><td><code class="small">{{ $log->ip_address }}</code></td></tr>
                                                                    <tr><th class="ps-3 pb-3 text-slate-400 font-bold text-uppercase text-[10px]">Browser</th><td class="pb-3 text-slate-500 font-medium text-wrap" style="max-width: 150px; font-size: 11px;">{{ $log->user_agent }}</td></tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <h6 class="fw-bold mb-3 pb-2 text-primary border-b border-slate-100 d-flex align-items-center gap-1.5">
                                                                    <span class="d-inline-block w-2 h-4 bg-primary rounded-pill"></span>
                                                                    Perubahan Data (JSON)
                                                                </h6>
                                                                <pre class="bg-slate-900 text-slate-200 p-3.5 rounded-2xl border border-slate-800 font-monospace small mb-0 overflow-auto" style="max-height: 280px; font-size: 11px; line-height: 1.5;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 bg-slate-50 py-3 px-4">
                                                        <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-300 small font-semibold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-400 py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    <span class="small font-semibold">Tidak ada log aktivitas ditemukan.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="pagination-wrapper p-4 border-t border-slate-100 d-flex flex-wrap gap-2 justify-content-between align-items-center bg-white">
                    <div class="pagination-info small text-slate-400 font-semibold">
                        Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }} data
                    </div>
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-sm-log {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 12px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    color: var(--primary-color);
    border: 2px solid rgba(var(--primary-color-rgb), 0.08);
}
.avatar-sm-log.system {
    background: linear-gradient(135deg, rgba(148, 163, 184, 0.1) 0%, rgba(100, 116, 139, 0.1) 100%);
    color: #475569;
    border-color: rgba(148, 163, 184, 0.1);
}
</style>
@endpush
@endsection
