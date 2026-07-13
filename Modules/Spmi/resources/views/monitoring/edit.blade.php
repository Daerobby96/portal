@extends('layouts.app')

@section('title', 'Edit Data Monitoring')
@section('page-title', 'Edit Data Monitoring')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring IKU/IKT</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-pencil-square fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Edit Data Monitoring</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('monitoring.update', $monitoring) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Periode <span class="text-danger">*</span></label>
                            <select name="periode_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('periode_id') is-invalid @enderror" required>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('periode_id', $monitoring->periode_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Input <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_input"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_input') is-invalid @enderror"
                                value="{{ old('tanggal_input', $monitoring->tanggal_input->format('Y-m-d')) }}" required>
                            @error('tanggal_input') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Indikator Kinerja <span class="text-danger">*</span></label>
                            <select name="indikator_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('indikator_id') is-invalid @enderror" required>
                                @foreach($indikators as $i)
                                    <option value="{{ $i->id }}" {{ old('indikator_id', $monitoring->indikator_id) == $i->id ? 'selected' : '' }}>
                                        [{{ $i->kode }}] — {{ $i->nama }} (Target: {{ $i->target_nilai }} {{ $i->unit_pengukuran }})
                                    </option>
                                @endforeach
                            </select>
                            @error('indikator_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nilai Capaian <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_capaian" step="0.01" min="0"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('nilai_capaian') is-invalid @enderror"
                                value="{{ old('nilai_capaian', $monitoring->nilai_capaian) }}" required>
                            @error('nilai_capaian') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Status Validasi <span class="text-danger">*</span></label>
                            <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $monitoring->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ old('status', $monitoring->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="verified" {{ old('status', $monitoring->status) === 'verified' ? 'selected' : '' }}>Verified</option>
                            </select>
                            @error('status') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Bukti Dokumen <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                            @if($monitoring->bukti_dokumen)
                            <div class="p-3 bg-emerald-50/30 rounded-xl border border-emerald-100 d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-check-fill fs-4 text-emerald-600"></i>
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest d-block">Berkas Terunggah</span>
                                        <span class="text-xs font-extrabold text-slate-700 d-block mt-0.5">Bukti Fisik Aktif Saat Ini</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $monitoring->bukti_dokumen) }}" target="_blank" class="inline-flex items-center gap-1 rounded-xl bg-emerald-100 text-emerald-700 px-3 py-1.5 text-xs font-bold text-decoration-none transition-colors hover:bg-emerald-200">
                                    <i class="bi bi-eye"></i>
                                    <span>Lihat Berkas</span>
                                </a>
                            </div>
                            @endif
                            <input type="file" name="bukti_dokumen"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('bukti_dokumen') is-invalid @enderror"
                                accept=".pdf,.jpg,.png,.docx">
                            <div class="text-[10px] font-medium text-slate-400 mt-1.5"><i class="bi bi-info-circle me-1"></i>Format: PDF, JPG, PNG, DOCX (Maksimal 10MB). Kosongkan jika Anda tidak ingin mengubah berkas saat ini.</div>
                            @error('bukti_dokumen') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="3.5" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Catatan tambahan...">{{ old('keterangan', $monitoring->keterangan) }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('monitoring.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection