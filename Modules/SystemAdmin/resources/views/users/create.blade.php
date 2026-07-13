@extends('systemadmin::layouts.master')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')
@section('page-subtitle', 'Buat akun pengguna baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-person-plus me-2 text-primary"></i>
                <h6 class="mb-0">Form Tambah User</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        {{-- Data Akun --}}
                        <div class="col-12">
                            <h6 class="text-muted mb-3"><i class="bi bi-key me-2"></i>Data Akun</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                                                <div class="col-md-12 mb-3 mt-4">
                            <label class="form-label fw-bold"><i class="bi bi-shield-lock me-2"></i>Hak Akses Modul (Permissions) &amp; Role Utama</label>
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <p class="text-muted small mb-2">Pilih Role Utama (Identitas):</p>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($roles as $role)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                                <label class="form-check-label" for="role_{{ $role->id }}">{{ Str::title(str_replace('_', ' ', $role->name)) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-2">Pilih Hak Akses Modul Khusus:</p>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($permissions as $perm)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="perm_{{ $perm->id }}">
                                                <label class="form-check-label" for="perm_{{ $perm->id }}">{{ Str::title(str_replace('_', ' ', str_replace('access_', '', $perm->name))) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                class="form-control" required>
                        </div>

                        {{-- Data Pribadi --}}
                        <div class="col-12 mt-4">
                            <h6 class="text-muted mb-3"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip"
                                class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai">
                            @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" name="unit_kerja"
                                class="form-control @error('unit_kerja') is-invalid @enderror"
                                value="{{ old('unit_kerja') }}" placeholder="Fakultas/Prodi/Unit">
                            @error('unit_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan"
                                class="form-control @error('jabatan') is-invalid @enderror"
                                value="{{ old('jabatan') }}" placeholder="Kepala, Staf, dll">
                            @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp"
                                class="form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="foto"
                                class="form-control @error('foto') is-invalid @enderror"
                                accept="image/jpeg,image/png">
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">JPG/PNG, max 2MB</div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-top mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Role --}}
    <div class="col-lg-4">
        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="bi bi-shield-lock me-2 text-primary"></i>
                <h6 class="mb-0">Keterangan Role</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-danger">Super Admin</span>
                    <p class="small text-muted mb-0">Akses penuh ke semua fitur sistem</p>
                </div>
                <div class="mb-3">
                    <span class="badge bg-primary">Auditor</span>
                    <p class="small text-muted mb-0">Melaksanakan audit, input temuan, verifikasi tindak lanjut</p>
                </div>
                <div class="mb-3">
                    <span class="badge bg-success">Auditee</span>
                    <p class="small text-muted mb-0">Input tindak lanjut temuan, melihat hasil audit</p>
                </div>
                <div class="mb-0">
                    <span class="badge bg-secondary">Staf Dokumen</span>
                    <p class="small text-muted mb-0">Mengelola dokumen mutu dan standar</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
