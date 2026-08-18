<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Risalah & Notulensi Rapat - {{ $rapat->nama_rapat }}</title>
    <style>
        @page { size: A4 portrait; margin: 15mm 20mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #111; line-height: 1.4; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 18px; }
        .header h2 { margin: 0; font-size: 15pt; text-transform: uppercase; font-weight: bold; }
        .header h3 { margin: 2px 0 0; font-size: 13pt; font-weight: bold; }
        .header p { margin: 2px 0 0; font-size: 10pt; color: #444; }
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h4 { margin: 0; font-size: 13pt; text-transform: uppercase; text-decoration: underline; }
        .doc-title span { font-size: 11pt; color: #333; }
        .meta-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 11pt; }
        .meta-table td { padding: 4px 6px; vertical-align: top; }
        .meta-table td.label { width: 25%; font-weight: bold; }
        .meta-table td.colon { width: 2%; }
        .data-table th, .data-table td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .section-title { font-size: 12pt; font-weight: bold; margin-top: 16px; margin-bottom: 6px; border-bottom: 1px solid #666; padding-bottom: 2px; }
        .signature-table { width: 100%; margin-top: 35px; page-break-inside: avoid; }
        .signature-table td { width: 50%; text-align: center; vertical-align: top; }
        .print-bar { background: #2563eb; color: #fff; padding: 10px; text-align: center; margin-bottom: 20px; font-family: sans-serif; }
        .print-btn { background: #fff; color: #2563eb; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        @media print { .print-bar { display: none; } }
    </style>
</head>
<body>
    <div class="print-bar">
        <span>Dokumen Siap Cetak (A4) — </span>
        <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h2>{{ $setting['app_name'] }}</h2>
        <h3>SEKRETARIAT & TATA KELOLA MANAJEMEN</h3>
        <p>{{ $setting['app_tagline'] }} — Portal ERP Terpadu</p>
    </div>

    <div class="doc-title">
        <h4>BERITA ACARA & NOTULENSI RAPAT</h4>
        <span>Nomor: RPT/{{ date('Y') }}/{{ str_pad($rapat->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Nama Agenda Rapat</td>
            <td class="colon">:</td>
            <td><strong>{{ $rapat->judul ?? $rapat->nama_rapat }}</strong></td>
        </tr>
        <tr>
            <td class="label">Kategori / Jenis</td>
            <td class="colon">:</td>
            <td>{{ $rapat->jenis ?? $rapat->jenis_rapat }}</td>
        </tr>
        <tr>
            <td class="label">Hari / Tanggal</td>
            <td class="colon">:</td>
            <td>{{ $rapat->tanggal ? date('d F Y', strtotime($rapat->tanggal)) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Waktu & Tempat</td>
            <td class="colon">:</td>
            <td>{{ $rapat->waktu_mulai ?? '-' }} s/d {{ $rapat->waktu_selesai ?? 'Selesai' }} WIB — {{ $rapat->tempat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status Rapat</td>
            <td class="colon">:</td>
            <td>{{ strtoupper($rapat->status) }}</td>
        </tr>
    </table>

    <div class="section-title">I. DAFTAR PESERTA RAPAT</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nama Peserta</th>
                <th>Peran / Jabatan</th>
                <th style="width: 100px;">Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rapat->peserta as $idx => $p)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td>{{ $p->user?->name ?? $p->pegawai?->nama ?? $p->nama_eksternal ?? '-' }}</td>
                <td>{{ $p->peran ?? $p->jabatan_eksternal ?? '-' }}</td>
                <td style="text-align: center;">{{ ucfirst($p->status_kehadiran ?? 'hadir') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #777;">Peserta rapat tercatat dalam lampiran terpisah.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">II. POKOK PEMBAHASAN & NOTULENSI AGENDA</div>
    <div style="font-size: 11pt; padding: 4px 0;">
        @if($rapat->agendas && count($rapat->agendas) > 0)
            @foreach($rapat->agendas as $idx => $ag)
                <div style="margin-bottom: 12px;">
                    <strong>{{ $idx + 1 }}. {{ $ag->judul }}</strong>
                    <div style="margin: 4px 0 0 15px; color: #222;">
                        {!! nl2br(e($ag->notulensi ?? $ag->deskripsi ?? 'Belum ada notulensi rinci untuk agenda ini.')) !!}
                    </div>
                </div>
            @endforeach
        @elseif($rapat->deskripsi)
            <p>{!! nl2br(e($rapat->deskripsi)) !!}</p>
        @else
            <p>Rapat telah dilaksanakan sesuai agenda dengan hasil kesepakatan seluruh peserta.</p>
        @endif

        @if($rapat->kesimpulan)
            <div style="margin-top: 15px; padding: 8px 12px; background-color: #f9fafb; border-left: 3px solid #0d9488;">
                <strong>Kesimpulan Akhir:</strong>
                <p style="margin: 4px 0 0;">{!! nl2br(e($rapat->kesimpulan)) !!}</p>
            </div>
        @endif
    </div>

    @if($rapat->tindakLanjuts && count($rapat->tindakLanjuts) > 0)
    <div class="section-title">III. ACTION ITEMS & TINDAK LANJUT</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Item Tindak Lanjut / Tugas</th>
                <th>Penanggung Jawab (PIC)</th>
                <th>Tenggat Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rapat->tindakLanjuts as $i => $tl)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ $tl->deskripsi ?? $tl->uraian_tindak_lanjut ?? '-' }}</td>
                <td>{{ $tl->pic?->name ?? '-' }}</td>
                <td style="text-align: center;">{{ $tl->deadline ? date('d M Y', strtotime($tl->deadline)) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Pimpinan Rapat</strong>
                <div style="height: 60px;"></div>
                <strong><u>(......................................................)</u></strong>
            </td>
            <td>
                Notulis Rapat,<br>
                <strong>Sekretaris</strong>
                <div style="height: 60px;"></div>
                <strong><u>(......................................................)</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
