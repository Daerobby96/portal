<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notulensi Rapat - {{ $rapat->judul }}</title>
<style>
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #222; margin: 0; padding: 0; }
    .kop-surat { margin-bottom: 8px; }
    .kop-surat img { width: 100%; max-height: 110px; display: block; }
    .kop-divider { border: 0; border-top: 3px solid #000; margin: 8px 0 16px; }
    .judul-doc { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase;
                 margin: 12px 0 4px; letter-spacing: 1px; }
    .sub-judul  { text-align: center; font-size: 11px; margin-bottom: 16px; }
    table.info  { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    table.info td { padding: 4px 8px; vertical-align: top; }
    table.info td:first-child { width: 140px; font-weight: bold; }
    .section-title { background: #1a3a6b; color: white; padding: 5px 10px; font-size: 11px;
                     font-weight: bold; margin: 14px 0 6px; }
    table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; }
    table.data th { background: #dce6f0; padding: 5px 8px; text-align: left; border: 1px solid #bcd; }
    table.data td { padding: 5px 8px; border: 1px solid #dde; vertical-align: top; }
    .agenda-item { margin-bottom: 12px; border: 1px solid #dde; border-radius: 4px; }
    .agenda-header { background: #f0f4ff; padding: 6px 10px; font-weight: bold; }
    .agenda-body  { padding: 8px 10px; }
    .notulensi-box { background: #fafafa; border-left: 3px solid #1a3a6b; padding: 6px 10px;
                     margin-top: 6px; white-space: pre-wrap; font-size: 10px; }
    .warn-banner { background: #fff3cd; border: 1px solid #ffc107; padding: 6px 10px;
                   margin-bottom: 12px; font-size: 10px; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px;
             font-weight: bold; }
    .badge-hadir       { background:#d4edda; color:#155724; }
    .badge-tidak_hadir { background:#f8d7da; color:#721c24; }
    .badge-izin        { background:#fff3cd; color:#856404; }
    .badge-diundang    { background:#e2e3e5; color:#383d41; }
    .badge-tinggi  { background:#f8d7da; color:#721c24; }
    .badge-sedang  { background:#fff3cd; color:#856404; }
    .badge-rendah  { background:#d4edda; color:#155724; }
    .footer { margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;
              font-size: 9px; color: #888; text-align: center; }
    .tanda-tangan { width: 100%; margin-top: 40px; }
    .tanda-tangan td { text-align: center; vertical-align: top; width: 50%; padding: 0 20px; }
    .ttd-label { font-size: 11px; margin-bottom: 50px; }
    .ttd-nama { font-size: 11px; font-weight: bold; border-top: 1px solid #555; 
                padding-top: 4px; display: inline-block; min-width: 150px; }
</style>
</head>
<body>

@php
    $kopPt = \App\Models\Setting::get('kop_surat_pt');
    $namaInstitusi = \App\Models\Setting::get('nama_institusi', config('app.name'));
    $alamat = \App\Models\Setting::get('alamat_institusi', '');
@endphp

{{-- Kop Surat --}}
<div class="kop-surat">
    @if($kopPt)
        <img src="{{ public_path('storage/' . $kopPt) }}" alt="Kop Surat">
    @else
        <table style="width:100%; padding-bottom:8px; border-bottom: 3px solid #000;">
            <tr>
                <td style="width:80px; text-align:center; vertical-align:middle;">
                    @if($setting && $setting->logo)
                        <img src="{{ public_path('storage/' . $setting->logo) }}" alt="Logo" style="max-height:70px; max-width:70px;">
                    @else
                        <table style="width:70px;height:70px;border:2px solid #333; margin:auto;">
                            <tr><td style="text-align:center;font-size:10px;color:#555;vertical-align:middle;">LOGO</td></tr>
                        </table>
                    @endif
                </td>
                <td style="text-align:center; vertical-align:middle; padding:0 10px;">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase; margin:2px 0;">{{ $namaInstitusi }}</div>
                    <div style="font-size:10px; color:#444;">{{ $alamat }}</div>
                </td>
                <td style="width:80px;"></td>
            </tr>
        </table>
    @endif
</div>
@if($kopPt)<hr class="kop-divider">@endif

{{-- Peringatan jika belum selesai --}}
@if($rapat->status !== 'selesai')
<div class="warn-banner">
    ⚠️ <strong>Notulensi belum lengkap — rapat belum selesai.</strong>
    Dokumen ini bersifat sementara dan dapat berubah.
</div>
@endif

<div class="judul-doc">Notulensi Rapat</div>
<div class="sub-judul">{{ \Modules\ManajemenRapat\Models\Rapat::jenisOptions()[$rapat->jenis] ?? $rapat->jenis }}</div>

{{-- Info Rapat --}}
<table class="info">
    <tr><td>Judul Rapat</td><td>: {{ $rapat->judul }}</td></tr>
    <tr><td>Tanggal</td><td>: {{ $rapat->tanggal->locale('id')->translatedFormat('l, d F Y') }}</td></tr>
    <tr><td>Waktu</td><td>: {{ substr($rapat->waktu_mulai,0,5) }} – {{ substr($rapat->waktu_selesai,0,5) }} WIB</td></tr>
    <tr><td>Tempat</td><td>: {{ $rapat->tempat }}</td></tr>
    <tr><td>Dibuat oleh</td><td>: {{ $rapat->creator?->name ?? '-' }}</td></tr>
</table>

{{-- Peserta --}}
<div class="section-title">DAFTAR PESERTA</div>
<table class="data">
    <thead>
        <tr>
            <th style="width:30px">No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th style="width:70px">Peran</th>
            <th style="width:80px">Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rapat->peserta as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->nama_display }}</td>
            <td>{{ $p->instansi_display ?: '-' }}</td>
            <td>{{ $p->peran }} {{ $p->isEksternal() ? '(Eks.)' : '' }}</td>
            <td><span class="badge badge-{{ $p->status_kehadiran }}">{{ ucfirst(str_replace('_', ' ', $p->status_kehadiran)) }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Agenda & Notulensi --}}
<div class="section-title">AGENDA DAN NOTULENSI</div>
@foreach($rapat->agendas as $agenda)
<div class="agenda-item">
    <div class="agenda-header">{{ $agenda->urutan }}. {{ $agenda->judul }} ({{ $agenda->estimasi_durasi }} menit)</div>
    <div class="agenda-body">
        @if($agenda->deskripsi)<p style="margin:0 0 6px;color:#555">{{ $agenda->deskripsi }}</p>@endif
        @if($agenda->notulensi)
            <strong style="font-size:9px">Notulensi:</strong>
            <div class="notulensi-box">{{ $agenda->notulensi }}</div>
        @else
            <em style="color:#aaa;font-size:9px">Notulensi belum diisi.</em>
        @endif
    </div>
</div>
@endforeach

{{-- Kesimpulan --}}
@if($rapat->kesimpulan)
<div class="section-title">KESIMPULAN RAPAT</div>
<div style="padding:8px 10px;border:1px solid #dde;border-radius:4px;white-space:pre-wrap">{{ $rapat->kesimpulan }}</div>
@endif

{{-- Tindak Lanjut --}}
@if($rapat->tindakLanjuts->isNotEmpty())
<div class="section-title">TINDAK LANJUT</div>
<table class="data">
    <thead>
        <tr>
            <th style="width:30px">No</th>
            <th>Tindakan</th>
            <th style="width:120px">PIC</th>
            <th style="width:80px">Deadline</th>
            <th style="width:60px">Prioritas</th>
            <th style="width:70px">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rapat->tindakLanjuts as $i => $tl)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $tl->deskripsi }}</td>
            <td>{{ $tl->pic?->name ?? '-' }}</td>
            <td>{{ $tl->deadline->format('d M Y') }}</td>
            <td><span class="badge badge-{{ strtolower($tl->prioritas) }}">{{ $tl->prioritas }}</span></td>
            <td>{{ ucfirst(str_replace('_', ' ', $tl->status)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Tanda Tangan --}}
<table class="tanda-tangan">
    <tr>
        <td>
            <div class="ttd-label">Notulis,</div>
            <div class="ttd-nama">
                {{ $rapat->peserta->where('peran','Notulis')->first()?->nama_display ?? '________________________' }}
            </div>
        </td>
        <td>
            <div class="ttd-label">Pimpinan Rapat,</div>
            <div class="ttd-nama">
                {{ $rapat->peserta->where('peran','Ketua')->first()?->nama_display ?? '________________________' }}
            </div>
        </td>
    </tr>
</table>

<div class="footer">
    Dicetak oleh sistem SPMI · {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>

