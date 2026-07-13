<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MOA - {{ $surat->nomor_surat }}</title>
    <style>
        @page { margin: 2.5cm 2.5cm 2.5cm 3cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11.5px;
            color: #1a1a1a;
            line-height: 1.6;
        }

        .kop-surat {
            width: 100%;
            margin-bottom: 0;
        }
        .kop-surat img {
            width: 100%;
            display: block;
        }
        .kop-divider {
            border: none;
            border-top: 3px solid #000;
            margin: 0 0 20px 0;
        }

        .title-block {
            text-align: center;
            margin: 20px 0;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .nomor {
            font-size: 11px;
            margin-top: 10px;
        }

        .content {
            margin-top: 20px;
            text-align: justify;
        }
        .content p {
            margin-bottom: 10px;
        }
        .content ol, .content ul {
            margin-left: 25px;
            margin-bottom: 10px;
        }
        .content li {
            margin-bottom: 5px;
        }

        .ttd-block {
            margin-top: 40px;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            border: none;
            padding: 0 10px;
        }
        .ttd-jabatan {
            font-weight: bold;
            margin-bottom: 70px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd-nip {
            margin-top: 2px;
        }
    </style>
</head>
<body>

    @php
        $kopPt = \App\Models\Setting::get('kop_surat_pt');
        $namaInstitusi = \App\Models\Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI');
        $alamat = \App\Models\Setting::get('alamat_institusi', 'Alamat Institusi');
        $kota = \App\Models\Setting::get('kota_institusi', 'Kota');
    @endphp

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        @if($kopPt)
            <img src="{{ public_path('storage/' . $kopPt) }}" alt="Kop Surat">
        @else
            <table style="width:100%; padding-bottom:8px; border-bottom: 3px solid #000;">
                <tr>
                    <td style="width:70px; text-align:center; vertical-align:middle;">
                        <table style="width:60px;height:60px;border:2px solid #333; margin:auto;"><tr><td style="text-align:center;font-size:9px;color:#555;vertical-align:middle;">LOGO</td></tr></table>
                    </td>
                    <td style="text-align:center; vertical-align:middle; padding:0 10px;">
                        <div style="font-size:15px; font-weight:bold; text-transform:uppercase; margin:2px 0;">{{ $namaInstitusi }}</div>
                        <div style="font-size:9.5px; color:#444;">{{ $alamat }}</div>
                    </td>
                    <td style="width:70px;"></td>
                </tr>
            </table>
        @endif
    </div>
    @if($kopPt)<hr class="kop-divider">@endif

    {{-- JUDUL --}}
    <div class="title-block">
        <div class="title">Memorandum of Agreement (MOA)</div>
        <div class="subtitle">{{ $surat->perihal }}</div>
        <div class="nomor">Nomor: {{ $surat->nomor_surat }}</div>
    </div>

    {{-- ISI --}}
    <div class="content">
        <p>Pada hari ini, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('l, d F Y') }}, yang bertanda tangan di bawah ini:</p>
        {!! $surat->isi_surat !!}
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-block">
        <p style="text-align: center; margin-bottom: 10px;">{{ $kota }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</p>
        <table class="ttd-table">
            <tr>
                <td>
                    <div style="font-weight: bold; margin-bottom: 5px;">PIHAK PERTAMA</div>
                    <div class="ttd-jabatan">{{ $surat->penandatangan_jabatan }}</div>
                    <div class="ttd-nama">{{ $surat->penandatangan_nama }}</div>
                    @if($surat->penandatangan_nip)
                    <div class="ttd-nip">NIP. {{ $surat->penandatangan_nip }}</div>
                    @endif
                </td>
                <td>
                    <div style="font-weight: bold; margin-bottom: 5px;">PIHAK KEDUA</div>
                    <div class="ttd-jabatan">{{ $surat->tujuan }}</div>
                    <div class="ttd-nama">(...........................)</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
