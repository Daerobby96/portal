@extends(auth()->check() ? 'layouts.app' : 'layouts.public')

@section('title', 'Detail Dokumen')

@section('page-title', $dokumen->judul)
@section('page-subtitle', $dokumen->kode_dokumen)

@section('page-actions')
    @auth
    <div class="d-flex gap-2">
        @if($dokumen->file_path)
        <a href="{{ route('dokumen.download', $dokumen) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50/30 px-4 py-2.5 text-sm font-bold text-emerald-600 shadow-sm transition-all hover:bg-emerald-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
            <i class="bi bi-file-earmark-arrow-down-fill"></i>
            <span>Download</span>
        </a>
        @endif
        <a href="{{ route('dokumen.edit', $dokumen) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0 text-decoration-none">
            <i class="bi bi-pencil-square"></i>
            <span>Edit Dokumen</span>
        </a>
    </div>
    @endauth
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dokumen.index') }}">Dokumen Mutu</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
@guest
<section class="py-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl shadow-lg mb-4 p-4 text-center">
    <div class="container py-2">
        <h1 class="font-extrabold tracking-tight mb-2">{{ $dokumen->judul }}</h1>
        <p class="text-white/80 lead font-semibold">{{ $dokumen->kode_dokumen }}</p>
    </div>
</section>
@endguest

<div class="{{ auth()->check() ? '' : 'container pb-5' }}">
<div class="row g-4">

    {{-- Detail Utama --}}
    <div class="col-lg-8">
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-file-earmark-text-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Informasi Dokumen</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3.5">
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Dokumen</span>
                        <span class="text-sm font-mono font-bold text-primary bg-primary-light px-2.5 py-1 rounded-lg">
                            {{ $dokumen->kode_dokumen }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul Dokumen</span>
                        <span class="text-sm font-bold text-slate-800 text-end max-w-sm">{{ $dokumen->judul }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</span>
                        <span class="d-flex align-items-center gap-2">
                            @if($dokumen->kategori)
                                <span class="inline-flex items-center rounded-lg bg-{{ $dokumen->kategori->warna ?? 'secondary' }}-50 border border-{{ $dokumen->kategori->warna ?? 'secondary' }}-200 text-{{ $dokumen->kategori->warna ?? 'secondary' }}-600 px-2.5 py-1 text-xs font-extrabold">
                                    {{ $dokumen->kategori->kode }}
                                </span>
                                <span class="text-sm font-bold text-slate-700">{{ $dokumen->kategori->nama }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-start py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider pt-1.5">Standar Mutu</span>
                        <span class="text-end">
                            @if($dokumen->standars->count() > 0)
                                <div class="d-flex flex-column gap-1.5 justify-content-end align-items-end">
                                    @foreach($dokumen->standars as $standar)
                                        <div class="d-flex align-items-center gap-2 p-1.5 px-3 rounded-xl border border-slate-100 bg-slate-50">
                                            <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 px-2 py-0.5 text-[10px] font-extrabold">{{ $standar->kode }}</span>
                                            <span class="text-xs font-semibold text-slate-600">{{ $standar->nama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Pemilik</span>
                        <span class="text-sm font-bold text-slate-700">{{ $dokumen->unit_pemilik }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Versi Dokumen</span>
                        <span class="inline-flex items-center rounded-full bg-slate-50 border border-slate-200 text-slate-500 px-2.5 py-0.5 text-xs font-bold">
                            v{{ $dokumen->versi }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Dokumen</span>
                        <span>
                            @php
                                $statusStyleMap = [
                                    'draft' => 'bg-slate-50 border-slate-200 text-slate-500',
                                    'review' => 'bg-amber-50 border-amber-200 text-amber-600',
                                    'approved' => 'bg-emerald-50 border-emerald-200 text-emerald-600',
                                    'obsolete' => 'bg-dark border-dark text-white',
                                ];
                                $labelMap = [
                                    'draft' => 'Draft',
                                    'review' => 'Review',
                                    'approved' => 'Approved',
                                    'obsolete' => 'Obsolete',
                                ];
                                $curStyle = $statusStyleMap[$dokumen->status] ?? 'bg-slate-50 border-slate-200 text-slate-500';
                                $curLabel = $labelMap[$dokumen->status] ?? $dokumen->status;
                            @endphp
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $curStyle }}">
                                {{ $curLabel }}
                            </span>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Visibilitas Akses</span>
                        <span class="d-flex align-items-center gap-2">
                            @if($dokumen->is_public)
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-globe"></i>
                                    <span>Publik</span>
                                </span>
                                <small class="text-xs font-semibold text-slate-400">(Dapat diakses publik)</small>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 border border-slate-100 text-slate-400 px-2.5 py-0.5 text-xs font-bold">
                                    <i class="bi bi-lock-fill"></i>
                                    <span>Internal</span>
                                </span>
                                <small class="text-xs font-semibold text-slate-400">(Hanya pengguna terdaftar)</small>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Terbit</span>
                        <span class="text-sm font-bold text-slate-700">{{ $dokumen->tanggal_terbit->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Masa Berlaku</span>
                        <span>
                            @if($dokumen->tanggal_kadaluarsa)
                                <span class="d-flex align-items-center gap-2 text-sm font-bold {{ $dokumen->tanggal_kadaluarsa <= now() ? 'text-rose-500' : 'text-slate-700' }}">
                                    <span>{{ $dokumen->tanggal_kadaluarsa->translatedFormat('d F Y') }}</span>
                                    @if($dokumen->tanggal_kadaluarsa <= now())
                                        <span class="inline-flex items-center rounded-full bg-rose-50 border border-rose-100 text-rose-600 px-2 py-0.5 text-[10px] font-extrabold">KADALUARSA</span>
                                    @elseif($dokumen->tanggal_kadaluarsa <= now()->addDays(30))
                                        <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-100 text-amber-600 px-2 py-0.5 text-[10px] font-extrabold">SEGERA</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-xs font-semibold text-slate-400 italic">Tidak Ada Masa Berlaku</span>
                            @endif
                        </span>
                    </div>
                    @if($dokumen->keterangan)
                    <div class="py-2.5">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider d-block mb-1">Keterangan</span>
                        <p class="mb-0 text-slate-600 text-sm leading-relaxed font-medium bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $dokumen->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4">

        {{-- File Dokumen --}}
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-paperclip fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">File Dokumen</h6>
            </div>
            <div class="p-4 text-center">
                @if($dokumen->file_path)
                    @php
                        $iconClassMap = [
                            'pdf' => 'text-rose-500 bg-rose-50 border-rose-100',
                            'docx' => 'text-blue-500 bg-blue-50 border-blue-100',
                            'doc' => 'text-blue-500 bg-blue-50 border-blue-100',
                            'xlsx' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
                            'xls' => 'text-emerald-500 bg-emerald-50 border-emerald-100',
                            'pptx' => 'text-orange-500 bg-orange-50 border-orange-100',
                            'ppt' => 'text-orange-500 bg-orange-50 border-orange-100',
                        ];
                        $curClass = $iconClassMap[$dokumen->file_type] ?? 'text-slate-500 bg-slate-50 border-slate-100';
                    @endphp
                    <div class="d-inline-flex h-16 w-16 items-center justify-center rounded-2xl border shadow-inner mb-3 {{ $curClass }}">
                        @switch($dokumen->file_type)
                            @case('pdf')   <i class="bi bi-file-earmark-pdf-fill fs-1"></i>  @break
                            @case('docx')
                            @case('doc')   <i class="bi bi-file-earmark-word-fill fs-1"></i> @break
                            @case('xlsx')
                            @case('xls')   <i class="bi bi-file-earmark-excel-fill fs-1"></i>@break
                            @default       <i class="bi bi-file-earmark-fill fs-1"></i>
                        @endswitch
                    </div>
                    <h6 class="font-bold text-slate-800 mb-1">{{ strtoupper($dokumen->file_type) }} File</h6>
                    <p class="text-xs font-semibold text-slate-400 mb-4">{{ $dokumen->file_size_formatted }}</p>
                    <a href="{{ route('dokumen.download', $dokumen) }}"
                       class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-emerald-600 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 text-decoration-none">
                        <i class="bi bi-download"></i>
                        <span>Download File</span>
                    </a>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mt-2 mb-0">
                        Diunduh {{ $dokumen->download_count }} kali
                    </p>
                @else
                    <div class="d-inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-150 bg-slate-50 text-slate-300 mb-3">
                        <i class="bi bi-file-earmark-x-fill fs-1"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-400 mb-3">Belum ada file dokumen terunggah.</p>
                    <a href="{{ route('dokumen.edit', $dokumen) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50/20 px-3.5 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 text-decoration-none">
                        <i class="bi bi-upload"></i>
                        <span>Upload File</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Verifikasi Digital QR Code --}}
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden mb-4 relative" style="background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);">
            <div class="p-4 bg-transparent pb-0 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">
                    <i class="bi bi-patch-check-fill fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Verifikasi Digital</h6>
            </div>
            <div class="p-4 text-center">
                <div class="mb-4">
                    <div class="p-3 bg-white rounded-2xl shadow-sm d-inline-block border border-slate-100">
                        @php
                            $qr = QrCode::size(150)
                                ->format('svg')
                                ->style('round')
                                ->eye('circle')
                                ->color(30, 27, 75);
                        @endphp
                        {!! $qr->generate(route('dokumen.show', $dokumen)) !!}
                    </div>
                </div>
                
                <div class="mb-3">
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 px-3 py-1 text-xs font-bold">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Official Document</span>
                    </span>
                </div>
                
                <p class="text-xs font-medium text-slate-400 px-3 mb-0">Pindai kode QR di atas untuk memverifikasi dokumen secara <i>real-time</i> pada portal validasi.</p>
                
                <div class="d-grid mt-4">
                    <button onclick="window.print()" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 shadow-sm transition-all hover:bg-slate-50 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0">
                        <i class="bi bi-printer-fill"></i>
                        <span>Cetak Label Validasi</span>
                    </button>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 h-20 w-20 rounded-full bg-indigo-500/5 blur-lg"></div>
        </div>

        {{-- Meta History --}}
        <div class="card border-0 rounded-2xl bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 d-flex align-items-center gap-2">
                <div class="d-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <h6 class="mb-0 font-bold text-slate-800">Riwayat Dokumen</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center pb-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400">Dibuat oleh</span>
                        <span class="text-xs font-bold text-slate-700">{{ $dokumen->pembuat->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2.5 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400">Tanggal dibuat</span>
                        <span class="text-xs font-bold text-slate-600">{{ $dokumen->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs font-semibold text-slate-400">Diperbarui</span>
                        <span class="text-xs font-bold text-slate-600">{{ $dokumen->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection