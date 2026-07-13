@extends('sdm::layouts.master')

@section('title', 'Edit Presensi')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold">Edit Presensi</h1>
        <p class="text-muted small mb-0">Edit data kehadiran pegawai</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Form Presensi</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sdm.presensi.update', $presensi) }}">
                        @csrf @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pegawai</label>
                                <input type="text" class="form-control" value="{{ $presensi->pegawai->nama }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal</label>
                                <input type="text" class="form-control" value="{{ $presensi->tanggal->format('d M Y') }}" disabled>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach(\Modules\Sdm\Models\Presensi::statusOptions() as $k => $v)
                                    <option value="{{ $k }}" {{ old('status', $presensi->status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror" 
                                    value="{{ old('jam_masuk', $presensi->jam_masuk ? substr($presensi->jam_masuk, 0, 5) : '') }}">
                                @error('jam_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Jam Keluar</label>
                                <input type="time" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror" 
                                    value="{{ old('jam_keluar', $presensi->jam_keluar ? substr($presensi->jam_keluar, 0, 5) : '') }}">
                                @error('jam_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" 
                                    placeholder="Keterangan tambahan...">{{ old('keterangan', $presensi->keterangan) }}</textarea>
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('sdm.presensi.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
