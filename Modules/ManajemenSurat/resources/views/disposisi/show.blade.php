@extends('manajemen-surat::layouts.master')

@section('title', 'Detail Disposisi')
@section('page-title', 'Detail Disposisi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('manajemen-surat.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('disposisi.my-disposisi') }}">Disposisi Saya</a></li>
    <li class="breadcrumb-item active">Detail</li>
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
                        <p class="text-slate-800 font-semibold">{{ $disposisi->suratMasuk->nomor_agenda }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Nomor Surat</label>
                        <p class="text-slate-800">{{ $disposisi->suratMasuk->nomor_surat }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Perihal</label>
                        <p class="text-slate-800">{{ $disposisi->suratMasuk->perihal }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Pengirim</label>
                        <p class="text-slate-800">{{ $disposisi->suratMasuk->pengirim }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Tanggal Terima</label>
                        <p class="text-slate-800">{{ $disposisi->suratMasuk->tanggal_terima->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Lihat Detail Surat
                        </a>
                        @if($disposisi->suratMasuk->file_path)
                        <a href="{{ route('surat-masuk.download', $disposisi->suratMasuk) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download"></i> Download Surat
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-2xl shadow-sm">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Isi Disposisi</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Dari</label>
                        <p class="text-slate-800 font-semibold">{{ $disposisi->dari->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Kepada</label>
                        <p class="text-slate-800 font-semibold">{{ $disposisi->kepada->name }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-xs font-semibold text-slate-400">Instruksi Disposisi</label>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <p class="text-slate-700 mb-0">{{ $disposisi->isi_disposisi }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Batas Waktu</label>
                        <p class="text-slate-800">
                            {{ $disposisi->batas_waktu ? $disposisi->batas_waktu->locale('id')->translatedFormat('d F Y') : '-' }}
                            @if($disposisi->isOverdue())
                            <span class="badge bg-rose-100 text-rose-600">Overdue</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs font-semibold text-slate-400">Prioritas</label><br>
                        @if($disposisi->prioritas === 'tinggi')
                        <span class="badge bg-rose-100 text-rose-600">Tinggi</span>
                        @elseif($disposisi->prioritas === 'sedang')
                        <span class="badge bg-amber-100 text-amber-600">Sedang</span>
                        @else
                        <span class="badge bg-slate-100 text-slate-600">Rendah</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 rounded-2xl shadow-sm mb-4">
            <div class="card-header bg-white border-b p-4">
                <h6 class="mb-0 font-bold">Status Disposisi</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-xs font-semibold text-slate-400">Status Saat Ini</label><br>
                    @if($disposisi->status === 'pending')
                    <span class="badge bg-amber-100 text-amber-600">Pending</span>
                    @elseif($disposisi->status === 'dibaca')
                    <span class="badge bg-blue-100 text-blue-600">Dibaca</span>
                    @elseif($disposisi->status === 'proses')
                    <span class="badge bg-purple-100 text-purple-600">Proses</span>
                    @else
                    <span class="badge bg-emerald-100 text-emerald-600">Selesai</span>
                    @endif
                </div>

                @if($disposisi->dibaca_at)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-slate-400">Dibaca Pada</label>
                    <p class="text-sm text-slate-700 mb-0">{{ $disposisi->dibaca_at->format('d M Y H:i') }}</p>
                </div>
                @endif

                @if($disposisi->selesai_at)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-slate-400">Selesai Pada</label>
                    <p class="text-sm text-slate-700 mb-0">{{ $disposisi->selesai_at->format('d M Y H:i') }}</p>
                </div>
                @endif

                @if($disposisi->catatan_tindak_lanjut)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-slate-400">Catatan Tindak Lanjut</label>
                    <p class="text-sm text-slate-700">{{ $disposisi->catatan_tindak_lanjut }}</p>
                </div>
                @endif

                @if($disposisi->kepada_user_id === auth()->id() && $disposisi->status !== 'selesai')
                <form method="POST" action="{{ route('disposisi.update-status', $disposisi) }}" class="mt-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label font-semibold">Update Status</label>
                        <select name="status" class="form-select" required>
                            <option value="proses" {{ $disposisi->status === 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Catatan</label>
                        <textarea name="catatan_tindak_lanjut" rows="3" class="form-control" placeholder="Tambahkan catatan...">{{ $disposisi->catatan_tindak_lanjut }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> Update Status
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
