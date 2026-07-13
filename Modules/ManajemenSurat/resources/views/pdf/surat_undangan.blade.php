<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Undangan - {{ $surat->nomor_surat }}</title>
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

        .header-info {
            margin-bottom: 20px;
        }
        .header-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-info td {
            padding: 2px 0;
            border: none;
        }

        .title-block {
            text-align: center;
            margin: 20px 0;
        }
        .title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .content {
            margin-top: 20px;
            text-align: justify;
        }
        .content p {
            margin-bottom: 10px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .content table td {
            padding: 5px 0;
            border: none;
        }

        .ttd-block {
            margin-top: 30px;
        }
        .ttd-cell {
            text-align: left;
            margin-left: 55%;
        }
        .ttd-kota-tanggal {
            margin-bottom: 5px;
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

    {{-- HEADER INFO --}}
    <div class="header-info">
        <table>
            <tr>
                <td style="width: 80px;">Nomor</td>
                <td style="width: 15px;">:</td>
                <td>{{ $surat->nomor_surat }}</td>
            </tr>
            @if($surat->jumlah_lampiran > 0)
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>{{ $surat->jumlah_lampiran }} {{ $surat->keterangan_lampiran ? '(' . $surat->keterangan_lampiran . ')' : '' }}</td>
            </tr>
            @endif
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ $surat->perihal }}</strong></td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <p>Kepada Yth.<br>
        <strong>{{ $surat->tujuan }}</strong><br>
        @if($surat->alamat_tujuan)
        {{ $surat->alamat_tujuan }}<br>
        @endif
        di tempat</p>
    </div>

    {{-- JUDUL --}}
    <div class="title-block">
        <div class="title">Undangan</div>
    </div>

    {{-- ISI --}}
    <div class="content">
        <p>Dengan hormat,</p>
        <p>Sehubungan dengan akan dilaksanakannya kegiatan, bersama ini kami mengundang Bapak/Ibu/Saudara untuk dapat hadir pada:</p>
        {!! $surat->isi_surat !!}
        <p style="margin-top: 15px;">Demikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya kami ucapkan terima kasih.</p>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-block">
        <div class="ttd-cell">
            <div class="ttd-kota-tanggal">{{ $kota }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
            <div class="ttd-jabatan">{{ $surat->penandatangan_jabatan }}</div>
            <div class="ttd-nama">{{ $surat->penandatangan_nama }}</div>
            @if($surat->penandatangan_nip)
            <div class="ttd-nip">NIP. {{ $surat->penandatangan_nip }}</div>
            @endif
        </div>
    </div>

</body>
</html>
