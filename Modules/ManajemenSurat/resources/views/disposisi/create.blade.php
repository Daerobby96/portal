@extends('manajemen-surat::layouts.master')

@section('title', 'Buat Disposisi')
@section('page-title', 'Buat Disposisi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Surat Masuk</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surat-masuk.show', $suratMasuk) }}">Detail Surat</a></li>
    <li class="breadcrumb-item active">Buat Disposisi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl shadow-sm mb-4">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Informasi Surat</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Nomor Agenda</label>
                        <p class="text-slate-800 font-semibold mb-0">{{ $suratMasuk->nomor_agenda }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Tanggal Terima</label>
                        <p class="text-slate-800 mb-0">{{ $suratMasuk->tanggal_terima->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Perihal</label>
                        <p class="text-slate-800 mb-0">{{ $suratMasuk->perihal }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Pengirim</label>
                        <p class="text-slate-800 mb-0">{{ $suratMasuk->pengirim }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Sifat</label><br>
                        @if($suratMasuk->sifat === 'sangat_segera')
                        <span class="badge bg-rose-100 text-rose-600">Sangat Segera</span>
                        @elseif($suratMasuk->sifat === 'segera')
                        <span class="badge bg-amber-100 text-amber-600">Segera</span>
                        @else
                        <span class="badge bg-slate-100 text-slate-600">{{ ucfirst($suratMasuk->sifat) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Form Disposisi</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('surat-masuk.disposisi.store', $suratMasuk) }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label font-semibold">Disposisikan Kepada <span class="text-danger">*</span></label>
                            <select name="kepada_user_id" class="form-select @error('kepada_user_id') is-invalid @enderror" required>
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('kepada_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->roles->first()?->name ?? 'User' }}
                                </option>
                                @endforeach
                            </select>
                            @error('kepada_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label font-semibold">Isi Disposisi <span class="text-danger">*</span></label>
                            <textarea name="isi_disposisi" rows="5" class="form-control @error('isi_disposisi') is-invalid @enderror" placeholder="Tulis instruksi disposisi..." required>{{ old('isi_disposisi') }}</textarea>
                            @error('isi_disposisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Contoh: Mohon ditindaklanjuti sesuai prosedur yang berlaku</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-semibold">Batas Waktu</label>
                            <input type="date" name="batas_waktu" class="form-control @error('batas_waktu') is-invalid @enderror" value="{{ old('batas_waktu') }}" min="{{ date('Y-m-d') }}">
                            @error('batas_waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-semibold">Prioritas <span class="text-danger">*</span></label>
                            <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                <option value="rendah" {{ old('prioritas') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ old('prioritas', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="tinggi" {{ old('prioritas') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                            @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Kirim Disposisi
                                </button>
                                <a href="{{ route('surat-masuk.show', $suratMasuk) }}" class="btn btn-light">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Riwayat Disposisi</h6>
            </div>
            <div class="card-body p-0">
                @forelse($suratMasuk->disposisi as $disposisi)
                <div class="p-3 border-b">
                    <p class="text-sm font-semibold text-slate-800 mb-1">{{ $disposisi->kepada->name }}</p>
                    <p class="text-xs text-slate-600 mb-1">{{ Str::limit($disposisi->isi_disposisi, 60) }}</p>
                    <p class="text-xs text-slate-400 mb-0">{{ $disposisi->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <div class="p-4 text-center text-slate-400 text-sm">Belum ada disposisi</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
