@extends('sdm::layouts.master')

@section('title', 'Manajemen Pegawai')
@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen Pegawai</li>
@endsection

@section('page-title', 'Manajemen Pegawai')
@section('page-subtitle', 'Data seluruh pegawai institusi')

@section('page-actions')
<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('sdm.pegawai.template') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-file-earmark-excel me-1"></i>Template
    </a>
    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
        <i class="bi bi-upload me-1"></i>Import Excel
    </button>
    <a href="{{ route('sdm.pegawai.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Pegawai
    </a>
</div>
@endsection

@push('styles')
<style>
    .premium-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
        border-radius: 4px 0 0 4px;
        transition: all 0.3s ease;
    }
    .premium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06);
    }
    .premium-card:hover::before {
        width: 6px;
    }
    .premium-icon-container {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: var(--bg-color);
        color: var(--accent-color);
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    .premium-card:hover .premium-icon-container {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 15px var(--shadow-color);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php $cards = [
            [
                'label'=>'Total Pegawai',
                'value'=>$stats['total'],
                'icon'=>'bi-people-fill',
                'accent'=>'#4f46e5',
                'bg'=>'rgba(79, 70, 229, 0.08)',
                'shadow'=>'rgba(79, 70, 229, 0.15)'
            ],
            [
                'label'=>'Dosen',
                'value'=>$stats['dosen'],
                'icon'=>'bi-mortarboard-fill',
                'accent'=>'#0ea5e9',
                'bg'=>'rgba(14, 165, 233, 0.08)',
                'shadow'=>'rgba(14, 165, 233, 0.15)'
            ],
            [
                'label'=>'Tendik',
                'value'=>$stats['tendik'],
                'icon'=>'bi-person-badge-fill',
                'accent'=>'#f59e0b',
                'bg'=>'rgba(245, 158, 11, 0.08)',
                'shadow'=>'rgba(245, 158, 11, 0.15)'
            ],
            [
                'label'=>'Aktif',
                'value'=>$stats['aktif'],
                'icon'=>'bi-check-circle-fill',
                'accent'=>'#10b981',
                'bg'=>'rgba(16, 185, 129, 0.08)',
                'shadow'=>'rgba(16, 185, 129, 0.15)'
            ],
        ]; @endphp
        @foreach($cards as $c)
        <div class="col-6 col-md-3">
            <div class="card premium-card" style="--accent-color: {{ $c['accent'] }};">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="premium-icon-container" style="--bg-color: {{ $c['bg'] }}; --accent-color: {{ $c['accent'] }}; --shadow-color: {{ $c['shadow'] }};">
                        <i class="bi {{ $c['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ $c['label'] }}</p>
                        <h3 class="mb-0 fw-bold text-dark mt-1" style="font-size: 1.8rem; line-height: 1.2;">{{ $c['value'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama, NIP, jabatan, unit..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <select name="jenis" class="form-select form-select-sm">
                        <option value="">Semua Jenis</option>
                        @foreach(\Modules\Sdm\Models\Pegawai::jenisOptions() as $k => $v)
                        <option value="{{ $k }}" {{ request('jenis')==$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="unit_kerja" class="form-select form-select-sm">
                        <option value="">Semua Unit</option>
                        @foreach($unitKerjas as $uk)
                        <option value="{{ $uk }}" {{ request('unit_kerja')==$uk?'selected':'' }}>{{ $uk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif"    {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('sdm.pegawai.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Daftar Pegawai</h6>
            <small class="text-muted">{{ $pegawais->total() }} pegawai</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan &amp; Unit</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Akun</th>
                            <th class="text-center" style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawais as $p)
                        <tr>
                            <td class="text-muted small">{{ $pegawais->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold
                                        d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:34px;height:34px;font-size:.8rem">
                                        {{ $p->inisial }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $p->nama }}</div>
                                        @if($p->email)
                                        <div class="text-muted" style="font-size:.73rem">
                                            <i class="bi bi-envelope me-1"></i>{{ $p->email }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small font-monospace">{{ $p->nip ?? '-' }}</td>
                            <td>
                                <div class="small">{{ $p->jabatan ?? '-' }}</div>
                                @if($p->unit_kerja)
                                <div class="text-muted" style="font-size:.73rem">{{ $p->unit_kerja }}</div>
                                @endif
                            </td>
                            <td class="text-center">{!! $p->jenis_badge !!}</td>
                            <td class="text-center">
                                @if($p->is_aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->user_id)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                        title="{{ $p->user?->email }}">
                                        <i class="bi bi-person-check me-1"></i>Terhubung
                                    </span>
                                @elseif($p->email)
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary py-0 px-2"
                                        style="font-size: 0.75rem"
                                        data-pegawai-id="{{ $p->id }}"
                                        data-pegawai-nama="{{ $p->nama }}"
                                        data-pegawai-email="{{ $p->email }}"
                                        data-action="{{ route('sdm.pegawai.create-user', $p) }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCreateUser"
                                        onclick="setupCreateUserModal(this)">
                                        <i class="bi bi-person-plus me-1"></i>Buat Akun
                                    </button>
                                @else
                                    <span class="badge bg-light text-muted border" title="Email pegawai belum diisi">
                                        <i class="bi bi-dash"></i> No Email
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('sdm.pegawai.edit', $p) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('sdm.pegawai.toggle', $p) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-outline-{{ $p->is_aktif ? 'warning' : 'success' }}"
                                            title="{{ $p->is_aktif ? 'Non-aktifkan' : 'Aktifkan' }}"
                                            onclick="return confirm('{{ $p->is_aktif ? 'Non-aktifkan' : 'Aktifkan' }} pegawai ini?')">
                                            <i class="bi bi-{{ $p->is_aktif ? 'pause-circle' : 'play-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('sdm.pegawai.destroy', $p) }}"
                                        onsubmit="return confirm('Hapus {{ addslashes($p->nama) }}? Data peserta rapat terkait tidak akan terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                <p class="mb-2">Belum ada data pegawai.</p>
                                <a href="{{ route('sdm.pegawai.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pegawais->hasPages())
        <div class="card-footer bg-white">
            {{ $pegawais->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Modal Import --}}
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('sdm.pegawai.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upload text-primary me-2"></i>Import Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small py-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Download <a href="{{ route('sdm.pegawai.template') }}">template Excel</a> terlebih dahulu.
                        Kolom yang tersedia: <strong>nip, nama, email, no_hp, jabatan, unit_kerja, jenis_pegawai, status_kepegawaian</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control"
                            accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Buat Akun User --}}
<div class="modal fade" id="modalCreateUser" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCreateUser" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus text-primary me-2"></i>Buat Akun User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small py-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Akun akan dibuat untuk: <strong id="cu_nama"></strong> &mdash; <span id="cu_email" class="text-muted"></span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 karakter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                        </div>
                        <div class="col-12">
                            <hr class="my-1">
                            <label class="form-label fw-bold"><i class="bi bi-shield-lock me-1"></i>Hak Akses & Role</label>
                            <div class="row mt-2">
                                <div class="col-md-6 border-end">
                                    <p class="text-muted small fw-semibold mb-2">Role Utama:</p>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($roles as $role)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="roles[]"
                                                    value="{{ $role->name }}" id="cu_role_{{ $role->id }}">
                                                <label class="form-check-label" for="cu_role_{{ $role->id }}">
                                                    {{ Str::title(str_replace('_', ' ', $role->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small fw-semibold mb-2">Hak Akses Modul:</p>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($permissions as $perm)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $perm->name }}" id="cu_perm_{{ $perm->id }}">
                                                <label class="form-check-label" for="cu_perm_{{ $perm->id }}">
                                                    {{ Str::title(str_replace('_', ' ', str_replace('access_', '', $perm->name))) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-check me-1"></i>Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setupCreateUserModal(btn) {
        var nama   = btn.getAttribute('data-pegawai-nama');
        var email  = btn.getAttribute('data-pegawai-email');
        var action = btn.getAttribute('data-action');

        document.getElementById('cu_nama').textContent  = nama;
        document.getElementById('cu_email').textContent = email;
        document.getElementById('formCreateUser').action = action;

        document.querySelectorAll('#formCreateUser input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.querySelectorAll('#formCreateUser input[type="password"]').forEach(pw => pw.value = '');
    }
</script>

@endsection
