@extends('sdm::layouts.master')

@section('title', 'Tambah Presensi')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold">Tambah Presensi</h1>
        <p class="text-muted small mb-0">Input data kehadiran pegawai</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Form Presensi</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sdm.presensi.store') }}">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pegawai <span class="text-danger">*</span></label>
                                <select name="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} - {{ $p->unit_kerja }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('pegawai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                    value="{{ old('tanggal', today()->format('Y-m-d')) }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach(\Modules\Sdm\Models\Presensi::statusOptions() as $k => $v)
                                    <option value="{{ $k }}" {{ old('status', 'hadir') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror" 
                                    value="{{ old('jam_masuk') }}">
                                @error('jam_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Jam Keluar</label>
                                <input type="time" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror" 
                                    value="{{ old('jam_keluar') }}">
                                @error('jam_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" 
                                    placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('sdm.presensi.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Status <strong>Hadir</strong> memerlukan jam masuk dan keluar</li>
                        <li class="mb-2">Status <strong>Izin/Sakit</strong> tidak memerlukan jam</li>
                        <li class="mb-2">Status <strong>Cuti</strong> sudah tercatat di menu Cuti</li>
                        <li>Durasi kerja dihitung otomatis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
