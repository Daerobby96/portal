@extends('manajemenrapat::layouts.master')

@section('title', 'Buat Rapat')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('rapat.index') }}">Manajemen Rapat</a></li>
<li class="breadcrumb-item active">Buat Rapat</li>
@endsection
@section('page-title', 'Buat Rapat Baru')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="bi bi-calendar2-plus text-primary me-2"></i>Informasi Rapat</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('rapat.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Rapat <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                    value="{{ old('judul') }}" maxlength="255" required>
                                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Rapat <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach(\Modules\ManajemenRapat\Models\Rapat::jenisOptions() as $k => $v)
                                    <option value="{{ $k }}" {{ old('jenis')==$k?'selected':'' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                                <select name="periode_id" class="form-select @error('periode_id') is-invalid @enderror" required>
                                    @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ ($periodeAktif?->id == $p->id || old('periode_id')==$p->id)?'selected':'' }}>
                                        {{ $p->nama }} {{ $p->is_aktif ? '(Aktif)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('periode_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror"
                                    value="{{ old('waktu_mulai', '08:00') }}" required>
                                @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror"
                                    value="{{ old('waktu_selesai', '10:00') }}" required>
                                @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Tempat / Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror"
                                    value="{{ old('tempat') }}" maxlength="255" required placeholder="contoh: Ruang Rapat Lantai 2 / Zoom Meeting">
                                @error('tempat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi / Tujuan</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3" maxlength="2000">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a href="{{ route('rapat.index') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Simpan Rapat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


