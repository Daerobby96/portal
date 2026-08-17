<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas Auditor - {{ $audit->kode_audit }}</title>
    <style>
        @page {
            margin: 60pt 60pt 60pt 60pt !important;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #222;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #222;
            padding-bottom: 8px;
            margin-bottom: 24px;
            position: relative;
        }

        .header-title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .header-sub {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header-address {
            font-size: 8.5pt;
            color: #444;
        }

        .surat-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .surat-title h2 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0 0 4px 0;
        }

        .surat-title .nomor {
            font-size: 9.5pt;
            color: #333;
        }

        .content-block {
            margin-bottom: 14px;
            text-align: justify;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0;
        }

        .table-data th, .table-data td {
            border: 1px solid #333;
            padding: 6px 10px;
            font-size: 9.5pt;
        }

        .table-data th {
            background-color: #f2f4f8;
            font-weight: bold;
            text-align: center;
        }

        .sign-table {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }

        .sign-table td {
            vertical-align: top;
            font-size: 9.5pt;
        }

        .signature-box {
            text-align: center;
            width: 250px;
            float: right;
        }

        .stamp-space {
            height: 70px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title">{{ $setting['nama_institusi'] ?? 'LEMBAGA PENJAMINAN MUTU' }}</div>
        <div class="header-sub">SISTEM PENJAMINAN MUTU INTERNAL (SPMI)</div>
        <div class="header-address">{{ $setting['alamat_institusi'] ?? 'Alamat Kampus' }} - {{ $setting['kota_institusi'] ?? 'Indonesia' }}</div>
    </div>

    <div class="surat-title">
        <h2>SURAT TUGAS AUDITOR INTERNAL</h2>
        <div class="nomor">Nomor: {{ $audit->nomor_surat_tugas ?? ($audit->kode_audit . '/ST-AMI/' . date('Y')) }}</div>
    </div>

    <div class="content-block">
        Yang bertanda tangan di bawah ini:
    </div>

    <table style="width: 100%; margin-bottom: 12px; font-size: 9.5pt;">
        <tr>
            <td style="width: 140px; padding: 2px 0;">Nama</td>
            <td style="width: 15px;">:</td>
            <td style="font-weight: bold;">{{ $audit->penandatangan_surat_tugas ?? ($penandatanganDefault->name ?? 'Kepala Lembaga Penjaminan Mutu') }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0;">Jabatan</td>
            <td>:</td>
            <td>{{ $audit->jabatan_penandatangan ?? 'Ketua Lembaga Penjaminan Mutu' }}</td>
        </tr>
    </table>

    <div class="content-block">
        Dengan ini menugaskan kepada Tim Auditor Mutu Internal di bawah ini:
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Nama Auditor</th>
                <th>Peran dalam Tim</th>
                <th>Unit / Prodi Asal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @if($audit->ketuaAuditor)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td><strong>{{ $audit->ketuaAuditor->name }}</strong><br><small style="color: #666;">NIP: {{ $audit->ketuaAuditor->nip ?? '-' }}</small></td>
                    <td style="text-align: center;"><span style="color: #0d6efd; font-weight: bold;">Ketua Tim Auditor</span></td>
                    <td>{{ $audit->ketuaAuditor->unit_kerja ?? ($audit->ketuaAuditor->prodi->nama ?? '-') }}</td>
                </tr>
            @endif
            @foreach($audit->auditors as $auditor)
                @if($auditor->id !== $audit->ketua_auditor_id)
                    <tr>
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $auditor->name }}<br><small style="color: #666;">NIP: {{ $auditor->nip ?? '-' }}</small></td>
                        <td style="text-align: center;">Anggota Auditor</td>
                        <td>{{ $auditor->unit_kerja ?? ($auditor->prodi->nama ?? '-') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="content-block">
        Untuk melaksanakan kegiatan <strong>Audit Mutu Internal (AMI)</strong> dengan rincian sebagai berikut:
    </div>

    <table style="width: 100%; margin-bottom: 14px; font-size: 9.5pt;">
        <tr>
            <td style="width: 140px; padding: 3px 0;">Kode & Nama Audit</td>
            <td style="width: 15px;">:</td>
            <td><strong>{{ $audit->kode_audit }} - {{ $audit->nama_audit }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Unit yang Diaudit (Auditee)</td>
            <td>:</td>
            <td><strong>{{ $audit->unit_yang_diaudit }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Tahun / Periode</td>
            <td>:</td>
            <td>{{ $audit->periode->nama ?? '-' }} (Tahun {{ $audit->periode->tahun ?? '-' }})</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Tanggal Pelaksanaan</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($audit->tanggal_audit)->translatedFormat('d F Y') }} @if($audit->tanggal_selesai && $audit->tanggal_selesai != $audit->tanggal_audit) s/d {{ \Carbon\Carbon::parse($audit->tanggal_selesai)->translatedFormat('d F Y') }} @endif</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">Ruang Lingkup</td>
            <td>:</td>
            <td>{{ $audit->lingkup_audit ?: 'Audit Kepatuhan & Ketercapaian Standar SPMI' }}</td>
        </tr>
    </table>

    <div class="content-block">
        Demikian Surat Tugas ini diterbitkan untuk dilaksanakan dengan penuh tanggung jawab dan objektivitas, serta menyampaikan laporan hasil audit kepada pimpinan.
    </div>

    <table class="sign-table">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">
                <div>{{ $setting['kota_institusi'] ?? 'Kota' }}, {{ $audit->tgl_surat_tugas ? \Carbon\Carbon::parse($audit->tgl_surat_tugas)->translatedFormat('d F Y') : \Carbon\Carbon::parse($audit->tanggal_audit)->subDays(3)->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 3px;">{{ $audit->jabatan_penandatangan ?? 'Ketua Lembaga Penjaminan Mutu' }}</div>
                <div class="stamp-space"></div>
                <div class="sign-name">{{ $audit->penandatangan_surat_tugas ?? ($penandatanganDefault->name ?? 'Pimpinan Penjaminan Mutu') }}</div>
                <div>NIP. {{ $penandatanganDefault->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
