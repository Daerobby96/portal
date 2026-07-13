@extends('manajemenrapat::layouts.master')

@section('title', 'Edit Rapat')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('rapat.index') }}">Manajemen Rapat</a></li>
<li class="breadcrumb-item"><a href="{{ route('rapat.show', $rapat) }}">{{ Str::limit($rapat->judul, 30) }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('page-title', 'Edit Rapat')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="bi bi-pencil text-primary me-2"></i>Edit Informasi Rapat</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('rapat.update', $rapat) }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Rapat <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                    value="{{ old('judul', $rapat->judul) }}" maxlength="255" required>
                                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Rapat</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ \Modules\ManajemenRapat\Models\Rapat::jenisOptions()[$rapat->jenis] ?? $rapat->jenis }}" readonly>
                                <small class="text-muted">Jenis rapat tidak dapat diubah setelah dibuat.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Periode</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ $rapat->periode?->nama }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', $rapat->tanggal->format('Y-m-d')) }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror"
                                    value="{{ old('waktu_mulai', substr($rapat->waktu_mulai, 0, 5)) }}" required>
                                @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror"
                                    value="{{ old('waktu_selesai', substr($rapat->waktu_selesai, 0, 5)) }}" required>
                                @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Tempat / Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror"
                                    value="{{ old('tempat', $rapat->tempat) }}" maxlength="255" required>
                                @error('tempat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi / Tujuan</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3" maxlength="2000">{{ old('deskripsi', $rapat->deskripsi) }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Field khusus RTM --}}
                            @if($rapat->jenis === 'RTM')
                            <div class="col-12"><hr><h6 class="text-muted">Input & Output RTM (ISO 9001)</h6></div>
                            @foreach([
                                'input_audit_internal'  => 'Input: Audit Internal',
                                'input_umpan_balik'     => 'Input: Umpan Balik Pelanggan',
                                'input_kinerja_proses'  => 'Input: Kinerja Proses',
                                'input_status_tindakan' => 'Input: Status Tindakan Sebelumnya',
                                'input_perubahan_sistem'=> 'Input: Perubahan yang Mempengaruhi Sistem',
                                'input_rekomendasi'     => 'Input: Rekomendasi Perbaikan',
                                'output_keefektifan'    => 'Output: Keefektifan Sistem Mutu',
                                'output_perbaikan'      => 'Output: Perbaikan Produk/Layanan',
                                'output_sumber_daya'    => 'Output: Kebutuhan Sumber Daya',
                            ] as $field => $label)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">{{ $label }}</label>
                                <textarea name="{{ $field }}" class="form-control form-control-sm"
                                    rows="2">{{ old($field, $rapat->$field) }}</textarea>
                            </div>
                            @endforeach
                            @endif

                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a href="{{ route('rapat.show', $rapat) }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Simpan Perubahan
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


