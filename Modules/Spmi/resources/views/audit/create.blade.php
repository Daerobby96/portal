@extends('layouts.app')

@section('title', 'Buat Audit Baru')
@section('page-title', 'Buat Audit Baru')
@section('page-subtitle', 'Inisiasi pelaksanaan audit mutu internal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Pelaksanaan Audit</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-clipboard2-plus fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Form Pembuatan Audit</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('audit.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        {{-- Info Dasar --}}
                        <div class="col-12">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-flex align-items-center gap-1.5 pb-2 border-b border-slate-100">
                                <i class="bi bi-info-circle text-primary fs-6"></i>
                                <span>Informasi Dasar Audit</span>
                            </span>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kode Registrasi Audit</label>
                            <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm font-mono font-bold text-slate-500" value="{{ $kodeAudit }}" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Periode <span class="text-danger">*</span></label>
                            <select name="periode_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('periode_id') is-invalid @enderror" required>
                                <option value="">Pilih Periode</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Status Audit</label>
                            <span class="d-block w-full text-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm font-bold text-slate-400">
                                Draft
                            </span>
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nama Program Audit <span class="text-danger">*</span></label>
                            <input type="text" name="nama_audit"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('nama_audit') is-invalid @enderror"
                                value="{{ old('nama_audit') }}"
                                placeholder="Contoh: Audit Internal Unit Akademik" required>
                            @error('nama_audit') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Unit Kerja yang Diaudit <span class="text-danger">*</span></label>
                            <input type="text" name="unit_yang_diaudit"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('unit_yang_diaudit') is-invalid @enderror"
                                value="{{ old('unit_yang_diaudit') }}"
                                placeholder="Contoh: Program Studi S1 Informatika / Direktorat Keuangan" required>
                            @error('unit_yang_diaudit') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tim Auditor --}}
                        <div class="col-12 mt-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-flex align-items-center gap-1.5 pb-2 border-b border-slate-100">
                                <i class="bi bi-people-fill text-primary fs-6"></i>
                                <span>Tim Auditor Mutu</span>
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Ketua Auditor <span class="text-danger">*</span></label>
                            <select name="ketua_auditor_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('ketua_auditor_id') is-invalid @enderror" required>
                                <option value="">Pilih Ketua Auditor</option>
                                @foreach($auditors as $a)
                                    <option value="{{ $a->id }}" {{ old('ketua_auditor_id') == $a->id ? 'selected' : '' }}>
                                        {{ $a->name }} — [{{ $a->unit_kerja }}]
                                    </option>
                                @endforeach
                            </select>
                            @error('ketua_auditor_id') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Anggota Auditor <span class="text-slate-400 text-[10px]">(Bisa pilih lebih dari satu)</span></label>
                            <select name="anggota_auditor[]" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10" multiple style="min-height: 100px;">
                                @foreach($auditors as $a)
                                    <option value="{{ $a->id }}" {{ in_array($a->id, old('anggota_auditor', [])) ? 'selected' : '' }}>
                                        {{ $a->name }} — [{{ $a->unit_kerja }}]
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-[10px] font-medium text-slate-400 mt-1.5"><i class="bi bi-info-circle me-1"></i>Tahan tombol <strong>Ctrl</strong> (Windows) atau <strong>Cmd</strong> (Mac) untuk memilih multi-anggota.</div>
                        </div>

                        {{-- Jadwal --}}
                        <div class="col-12 mt-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-flex align-items-center gap-1.5 pb-2 border-b border-slate-100">
                                <i class="bi bi-calendar3 text-primary fs-6"></i>
                                <span>Rencana Jadwal Kegiatan</span>
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_audit"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_audit') is-invalid @enderror"
                                value="{{ old('tanggal_audit') }}" required>
                            @error('tanggal_audit') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tanggal Selesai <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                            <input type="date" name="tanggal_selesai"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        {{-- Detail Tambahan --}}
                        <div class="col-12 mt-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 d-flex align-items-center gap-1.5 pb-2 border-b border-slate-100">
                                <i class="bi bi-file-text-fill text-primary fs-6"></i>
                                <span>Lingkup & Detail Tambahan</span>
                            </span>
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Lingkup Audit</label>
                            <textarea name="lingkup_audit" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Jelaskan ruang lingkup audit... Contoh: Kurikulum, SDM, Sarpras">{{ old('lingkup_audit') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tujuan Audit</label>
                            <textarea name="tujuan_audit" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Tuliskan tujuan diadakannya audit mutu ini...">{{ old('tujuan_audit') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Catatan Tambahan</label>
                            <textarea name="catatan" rows="2.5" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-sm text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10"
                                placeholder="Tuliskan catatan khusus atau deskripsi pelengkap...">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('audit.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Audit</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection