<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pelaksanaan Audit (BAPA) - {{ $audit->kode_audit }}</title>
    <style>
        @page {
            margin: 50pt 50pt 50pt 50pt !important;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            line-height: 1.45;
            color: #222;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #222;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }

        .header-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-sub {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .header-address {
            font-size: 8pt;
            color: #555;
        }

        .bapa-title {
            text-align: center;
            margin-bottom: 16px;
        }

        .bapa-title h2 {
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0 0 3px 0;
        }

        .bapa-title .nomor {
            font-size: 9pt;
            color: #444;
        }

        .content-block {
            margin-bottom: 10px;
            text-align: justify;
        }

        .table-info {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 2.5px 0;
            font-size: 9pt;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 14px 0;
        }

        .table-data th, .table-data td {
            border: 1px solid #333;
            padding: 5px 7px;
            font-size: 8.5pt;
        }

        .table-data th {
            background-color: #f1f3f7;
            font-weight: bold;
            text-align: center;
        }

        .badge-kts {
            display: inline-block;
            padding: 2px 5px;
            font-size: 7.5pt;
            font-weight: bold;
            color: #fff;
            border-radius: 3px;
        }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #0dcaf0; color: #000; }

        .sign-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        .sign-table td {
            vertical-align: top;
            font-size: 9pt;
            width: 50%;
            text-align: center;
        }

        .stamp-space {
            height: 55px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .digital-verified {
            display: inline-block;
            border: 1px solid #198754;
            color: #198754;
            font-size: 7.5pt;
            padding: 2px 8px;
            margin-bottom: 4px;
            border-radius: 3px;
            background: #e8f5e9;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title">{{ $setting['nama_institusi'] ?? 'LEMBAGA PENJAMINAN MUTU' }}</div>
        <div class="header-sub">BERITA ACARA PELAKSANAAN AUDIT MUTU INTERNAL (BAPA)</div>
        <div class="header-address">{{ $setting['alamat_institusi'] ?? 'Alamat Kampus' }} - {{ $setting['kota_institusi'] ?? 'Indonesia' }}</div>
    </div>

    <div class="bapa-title">
        <h2>BERITA ACARA CLOSING MEETING & PELAKSANAAN AUDIT</h2>
        <div class="nomor">Nomor: BAPA/{{ $audit->kode_audit }}/{{ date('Y') }}</div>
    </div>

    <div class="content-block">
        Pada hari ini, <strong>{{ \Carbon\Carbon::parse($audit->closing_meeting ?? $audit->tanggal_selesai ?? $audit->tanggal_audit)->translatedFormat('l, d F Y') }}</strong>, telah diselenggarakan Closing Meeting kegiatan Audit Mutu Internal (AMI) Periode <strong>{{ $audit->periode->nama ?? '-' }}</strong> bertempat di unit kerja bersangkutan, antara Tim Auditor dan Pimpinan Auditee:
    </div>

    <table class="table-info">
        <tr>
            <td style="width: 150px;">Unit Kerja / Auditee</td>
            <td style="width: 10px;">:</td>
            <td><strong>{{ $audit->unit_yang_diaudit }}</strong></td>
        </tr>
        <tr>
            <td>Ketua Tim Auditor</td>
            <td>:</td>
            <td><strong>{{ $audit->ketuaAuditor->name ?? '-' }}</strong> (NIP. {{ $audit->ketuaAuditor->nip ?? '-' }})</td>
        </tr>
        <tr>
            <td>Anggota Tim Auditor</td>
            <td>:</td>
            <td>
                @foreach($audit->auditors as $auditor)
                    @if($auditor->id !== $audit->ketua_auditor_id)
                        {{ $auditor->name }} ({{ $auditor->nip ?? '-' }}){{ !$loop->last ? ', ' : '' }}
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Tanggal Audit Dokumen & Lapangan</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($audit->tanggal_audit)->translatedFormat('d F Y') }} @if($audit->tanggal_selesai) s/d {{ \Carbon\Carbon::parse($audit->tanggal_selesai)->translatedFormat('d F Y') }} @endif</td>
        </tr>
        <tr>
            <td>Ruang Lingkup Audit</td>
            <td>:</td>
            <td>{{ $audit->lingkup_audit ?: 'Seluruh Standar SPMI & Indikator Kinerja yang Relevan' }}</td>
        </tr>
    </table>

    <div class="content-block">
        <strong>A. Ringkasan Hasil Pemeriksaan & Temuan Audit:</strong>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Kode Temuan</th>
                <th style="width: 75px;">Kategori</th>
                <th>Uraian Ketidaksesuaian / Temuan</th>
                <th style="width: 110px;">Klausul Standar</th>
                <th style="width: 75px;">Batas Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($audit->temuans as $index => $temuan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $temuan->kode_temuan }}</td>
                    <td style="text-align: center;">
                        @if($temuan->kategori === 'KTS_Mayor')
                            <span class="badge-kts badge-danger">KTS Mayor</span>
                        @elseif($temuan->kategori === 'KTS_Minor')
                            <span class="badge-kts badge-warning">KTS Minor</span>
                        @else
                            <span class="badge-kts badge-info">{{ $temuan->kategori }}</span>
                        @endif
                    </td>
                    <td>{{ $temuan->uraian_temuan }}</td>
                    <td>{{ $temuan->klausul_standar ?: ($temuan->checklist->indikator->standar->kode ?? '-') }}</td>
                    <td style="text-align: center;">{{ $temuan->batas_tindak_lanjut ? \Carbon\Carbon::parse($temuan->batas_tindak_lanjut)->translatedFormat('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #198754; padding: 10px;">
                        <strong>Tidak ditemukan ketidaksesuaian (KTS Mayor/Minor). Seluruh indikator SPMI memenuhi standar.</strong>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="content-block">
        <strong>B. Kesepakatan & Tindak Lanjut:</strong><br>
        1. Pihak Auditee menerima seluruh hasil audit dan berkomitmen untuk menindaklanjuti temuan tersebut sebelum batas waktu yang ditentukan.<br>
        2. Tim Auditor akan melakukan verifikasi efektivitas tindakan perbaikan sesuai mekanisme Permintaan Tindakan Koreksi (PTK).<br>
        @if($audit->bapa_catatan)
            3. Catatan Khusus Closing Meeting: <em>{{ $audit->bapa_catatan }}</em>
        @endif
    </div>

    <table class="sign-table">
        <tr>
            <td>
                <div>Pihak Auditee / Pimpinan Unit,</div>
                <div class="stamp-space">
                    @if($audit->bapa_signed_at_auditee)
                        <div class="digital-verified">✓ Disetujui Secara Digital<br>{{ \Carbon\Carbon::parse($audit->bapa_signed_at_auditee)->translatedFormat('d M Y H:i') }}</div>
                    @endif
                </div>
                <div class="sign-name">{{ $audit->bapaAuditee->name ?? ($auditeeLeader->name ?? 'Pimpinan Unit Kerja') }}</div>
                <div>NIP. {{ $audit->bapaAuditee->nip ?? ($auditeeLeader->nip ?? '-') }}</div>
            </td>
            <td>
                <div>Ketua Tim Auditor,</div>
                <div class="stamp-space">
                    @if($audit->bapa_signed_at_auditor)
                        <div class="digital-verified">✓ Disetujui Secara Digital<br>{{ \Carbon\Carbon::parse($audit->bapa_signed_at_auditor)->translatedFormat('d M Y H:i') }}</div>
                    @endif
                </div>
                <div class="sign-name">{{ $audit->ketuaAuditor->name ?? '-' }}</div>
                <div>NIP. {{ $audit->ketuaAuditor->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
