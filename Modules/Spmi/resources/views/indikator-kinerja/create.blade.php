@extends('layouts.app')

@section('title', 'Tambah Indikator Kinerja')
@section('page-title', 'Tambah Indikator Kinerja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('indikator-kinerja.index') }}">Indikator Kinerja</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-bullseye fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Form Tambah Indikator Kinerja</h6>
            </div>
            <div class="p-4">
                <form action="{{ route('indikator-kinerja.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('kode') is-invalid @enderror"
                                value="{{ old('kode') }}"
                                placeholder="IKU-01" style="text-transform:uppercase" required>
                            @error('kode') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-8">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Nama Indikator <span class="text-danger">*</span></label>
                            <input type="text" name="nama"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}"
                                placeholder="Persentase kelulusan mahasiswa tepat waktu" required>
                            @error('nama') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Unit Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="unit_kerja"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('unit_kerja') is-invalid @enderror"
                                value="{{ old('unit_kerja') }}"
                                placeholder="Fakultas Teknik" required>
                            @error('unit_kerja') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Tipe Indikator <span class="text-danger">*</span></label>
                            <select name="tipe" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('tipe') is-invalid @enderror" required>
                                @foreach($tipeOptions as $val => $label)
                                    <option value="{{ $val }}" {{ old('tipe') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('tipe') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Standar Terkait</label>
                            <select name="standar_id" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10">
                                <option value="">Pilih Standar (Opsional)</option>
                                @foreach($standars as $s)
                                    <option value="{{ $s->id }}" {{ old('standar_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->kode }} - {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Target Deskripsi</label>
                            <textarea name="target_deskripsi" rows="2.5"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-3 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('target_deskripsi') is-invalid @enderror"
                                placeholder="Contoh: ≥ 80% atau Memenuhi 3 aspek kriteria...">{{ old('target_deskripsi') }}</textarea>
                            <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Isi kolom ini jika target berupa teks deskriptif sesuai yang tertera pada dokumen standar.</div>
                            @error('target_deskripsi') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Target Angka <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                            <input type="number" name="target_nilai" step="0.01" min="0"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('target_nilai') is-invalid @enderror"
                                value="{{ old('target_nilai') }}" placeholder="80">
                            <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Target kuantitatif untuk visualisasi grafik.</div>
                            @error('target_nilai') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Unit Pengukuran <span class="text-danger">*</span></label>
                            <input type="text" name="unit_pengukuran"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('unit_pengukuran') is-invalid @enderror"
                                value="{{ old('unit_pengukuran') }}"
                                placeholder="%, Orang, Dokumen" required>
                            @error('unit_pengukuran') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Bobot (%) <span class="text-danger">*</span></label>
                            <input type="number" name="bobot" step="0.01" min="0" max="100"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('bobot') is-invalid @enderror"
                                value="{{ old('bobot', '1.00') }}" required>
                            <div class="text-[10px] font-medium text-slate-400 mt-1"><i class="bi bi-info-circle me-1"></i>Digunakan untuk pembobotan nilai mutu.</div>
                            @error('bobot') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 d-block mb-2">Sumber Acuan <span class="text-slate-400 text-[10px]">(Opsional)</span></label>
                            <input type="text" name="sumber"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-700 transition-all focus:border-primary focus:bg-white focus:outline-none focus:ring-4 focus:ring-primary/10 @error('sumber') is-invalid @enderror"
                                value="{{ old('sumber') }}"
                                placeholder="Contoh: Permendikbud No. 53 Tahun 2023 Pasal 12">
                            @error('sumber') <div class="invalid-feedback mt-1 text-xs">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch p-0 d-flex align-items-center gap-2">
                                <input class="form-check-input ms-0" type="checkbox" name="is_aktif" id="isAktif" value="1" style="width: 2.5em; height: 1.25em;" checked>
                                <div>
                                    <label class="text-xs font-bold text-slate-700" for="isAktif">Indikator Aktif</label>
                                    <div class="text-[10px] font-medium text-slate-400">Aktifkan untuk menyertakan indikator ini dalam formulir monitoring aktif</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-3 border-t border-slate-100">
                            <a href="{{ route('indikator-kinerja.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                                <i class="bi bi-arrow-left"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">
                                <i class="bi bi-save"></i>
                                <span>Simpan Indikator</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection