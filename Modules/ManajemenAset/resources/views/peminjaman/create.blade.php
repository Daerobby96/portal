@extends('manajemenaset::layouts.master')

@section('title', 'Ajukan Peminjaman Aset')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('peminjaman.index') }}">Peminjaman Aset</a></li>
<li class="breadcrumb-item active">Ajukan Peminjaman</li>
@endsection
@section('page-title', 'Ajukan Peminjaman Aset')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Peminjaman</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label required">Pilih Aset</label>
                                <select name="aset_id" class="form-select @error('aset_id') is-invalid @enderror" required>
                                    <option value="">Pilih Aset yang Tersedia</option>
                                    @foreach($asets as $aset)
                                    <option value="{{ $aset->id }}" {{ old('aset_id') == $aset->id ? 'selected' : '' }}>
                                        {{ $aset->kode_aset }} - {{ $aset->nama_aset }} ({{ $aset->lokasi }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('aset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Hanya aset dalam kondisi baik dan status aktif yang dapat dipinjam</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label required">Keperluan</label>
                                <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                    value="{{ old('keperluan') }}" placeholder="Untuk apa aset ini dipinjam?" required>
                                @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                    value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Rencana Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali_rencana" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                                    value="{{ old('tanggal_kembali_rencana') }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Catatan Peminjam</label>
                                <textarea name="catatan_peminjam" class="form-control @error('catatan_peminjam') is-invalid @enderror" rows="3" 
                                    placeholder="Catatan tambahan (opsional)">{{ old('catatan_peminjam') }}</textarea>
                                @error('catatan_peminjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Catatan:</strong> Pengajuan peminjaman akan diproses oleh admin. Anda akan mendapatkan notifikasi setelah pengajuan disetujui atau ditolak.
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Ajukan Peminjaman
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}
</style>
@endpush
@endsection
