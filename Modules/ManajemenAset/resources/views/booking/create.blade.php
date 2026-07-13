@extends('manajemenaset::layouts.master')

@section('title', 'Booking Ruangan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('booking-ruangan.index') }}">Booking Ruangan</a></li>
<li class="breadcrumb-item active">Booking Baru</li>
@endsection
@section('page-title', 'Booking Ruangan')
@section('page-subtitle', 'Ajukan pemesanan ruangan')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('booking-ruangan.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold">Informasi Booking</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label required">Pilih Ruangan</label>
                                <select name="ruangan_id" class="form-select @error('ruangan_id') is-invalid @enderror" required>
                                    <option value="">Pilih Ruangan</option>
                                    @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}" {{ old('ruangan_id', request('ruangan_id')) == $r->id ? 'selected' : '' }}>
                                        {{ $r->kode_ruangan }} - {{ $r->nama_ruangan }} 
                                        @if($r->kapasitas)(Kapasitas: {{ $r->kapasitas }} orang)@endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('ruangan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Pilih ruangan yang tersedia</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label required">Keperluan</label>
                                <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                    value="{{ old('keperluan') }}" placeholder="Rapat, Kuliah, Seminar, dll" required>
                                @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" 
                                    placeholder="Detail kegiatan (opsional)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label required">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                    value="{{ old('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                    value="{{ old('jam_mulai', '08:00') }}" required>
                                @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                    value="{{ old('jam_selesai', '10:00') }}" required>
                                @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Jumlah Peserta</label>
                                <input type="number" name="jumlah_peserta" class="form-control @error('jumlah_peserta') is-invalid @enderror" 
                                    value="{{ old('jumlah_peserta') }}" placeholder="30" min="1">
                                @error('jumlah_peserta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Opsional, untuk estimasi kebutuhan ruangan</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Catatan Pemohon</label>
                                <textarea name="catatan_pemohon" class="form-control @error('catatan_pemohon') is-invalid @enderror" rows="3" 
                                    placeholder="Catatan atau permintaan khusus">{{ old('catatan_pemohon') }}</textarea>
                                @error('catatan_pemohon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Catatan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Pastikan tidak ada jadwal bentrok pada waktu yang dipilih</li>
                        <li>Booking akan diproses oleh admin/kaprodi</li>
                        <li>Anda akan mendapatkan notifikasi setelah pengajuan disetujui atau ditolak</li>
                    </ul>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Ajukan Booking
                    </button>
                    <a href="{{ route('booking-ruangan.index') }}" class="btn btn-secondary">
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
