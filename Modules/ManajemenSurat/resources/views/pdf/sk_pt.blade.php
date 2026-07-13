<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keputusan Perguruan Tinggi - {{ $sk->nomor_sk }}</title>
    <style>
        @page { margin: 2.5cm 2.5cm 2.5cm 3cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11.5px;
            color: #1a1a1a;
            line-height: 1.6;
        }

        /* ====== KOP SURAT ====== */
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
            border-top: 4px double #000;
            margin: 0 0 0 0;
        }

        /* ====== JUDUL SK ====== */
        .sk-title-block {
            text-align: center;
            margin: 18px 0 14px 0;
        }
        .sk-title-label {
            font-size: 12.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sk-label-jenis {
            font-size: 10.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .sk-nomor {
            font-size: 11px;
            margin-top: 3px;
            font-weight: bold;
        }
        .sk-tentang-label {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .sk-tentang-value {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ====== ISI SK ====== */
        .sk-content {
            margin-top: 20px;
            margin-bottom: 14px;
            text-align: justify;
        }
        .sk-content p {
            margin-bottom: 8px;
        }
        .sk-content ol, .sk-content ul {
            margin-left: 20px;
            margin-bottom: 8px;
        }
        .sk-content li {
            margin-bottom: 4px;
        }
        .sk-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .sk-content td, .sk-content th {
            border: 1px solid #666;
            padding: 4px;
        }

        /* ====== PENUTUP & TANDA TANGAN ====== */
        .penutup {
            margin-top: 16px;
            margin-bottom: 14px;
        }
        .ttd-block {
            display: table;
            width: 100%;
            margin-top: 22px;
        }
        .ttd-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .ttd-kota-tanggal {
            margin-bottom: 4px;
        }
        .ttd-jabatan {
            font-weight: bold;
            margin-bottom: 60px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .divider {
            border: none;
            border-top: 1px solid #555;
            margin: 12px 0;
        }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    @php
        $namaInstitusi = \App\Models\Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI');
        $alamat        = \App\Models\Setting::get('alamat_institusi', 'Alamat Institusi');
        $kota          = \App\Models\Setting::get('kota_institusi', 'Kota');
        $kopPt         = \App\Models\Setting::get('kop_surat_pt');
    @endphp

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        @if($kopPt)
            <img src="{{ public_path('storage/' . $kopPt) }}" alt="Kop Surat Perguruan Tinggi">
        @else
            {{-- Fallback: kop teks jika gambar belum diupload --}}
            <table style="width:100%; padding-bottom:8px; border-bottom: 4px double #000;">
                <tr>
                    <td style="width:70px; text-align:center; vertical-align:middle;">
                        <table style="width:60px;height:60px;border:2px solid #333; margin:auto;"><tr><td style="text-align:center;font-size:9px;color:#555;vertical-align:middle;">LOGO</td></tr></table>
                    </td>
                    <td style="text-align:center; vertical-align:middle; padding:0 10px;">
                        <div style="font-size:10px;text-transform:uppercase;">Lembaga Penjaminan Mutu Internal</div>
                        <div style="font-size:15px; font-weight:bold; text-transform:uppercase; margin:2px 0;">{{ $namaInstitusi }}</div>
                        <div style="font-size:10px;font-weight:bold;text-transform:uppercase;">Sistem Penjaminan Mutu Internal (SPMI)</div>
                        <div style="font-size:9.5px; color:#444; margin-top:2px;">{{ $alamat }}</div>
                    </td>
                    <td style="width:70px;"></td>
                </tr>
            </table>
        @endif
    </div>
    @if($kopPt)<hr class="kop-divider">@endif

    {{-- JUDUL SK --}}
    <div class="sk-title-block">
        <div class="sk-title-label">Surat Keputusan</div>
        <div class="sk-label-jenis">Rektor / Direktur {{ $namaInstitusi }}</div>
        <div class="sk-nomor">Nomor: {{ $sk->nomor_sk }}</div>
        <div class="sk-tentang-label">Tentang</div>
        <div class="sk-tentang-value">{{ $sk->tentang }}</div>
    </div>

    <hr class="divider">

    {{-- ISI SK --}}
    <div class="sk-content">
        {!! $sk->isi_sk !!}
    </div>

    {{-- PENUTUP --}}
    <div class="penutup">
        <div>Surat Keputusan ini berlaku sejak tanggal ditetapkan, dengan ketentuan apabila dikemudian hari terdapat kekeliruan dalam Surat Keputusan ini, akan diadakan perbaikan sebagaimana mestinya.</div>
    </div>

    {{-- TANDA TANGAN --}}
    <table style="width: 100%; margin-top: 30px; border: none;">
        <tr>
            <td style="width: 55%; border: none;"></td>
            <td style="width: 45%; border: none; text-align: left;">
                <table style="width: 100%; border: none; margin-bottom: 5px;">
                    <tr>
                        <td style="border: none; padding: 0; width: 75px;">Ditetapkan di</td>
                        <td style="border: none; padding: 0; width: 10px;">:</td>
                        <td style="border: none; padding: 0;">{{ $kota }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 0;">Pada tanggal</td>
                        <td style="border: none; padding: 0;">:</td>
                        <td style="border: none; padding: 0;">{{ $sk->tanggal_ditetapkan->translatedFormat('d F Y') }}</td>
                    </tr>
                </table>
                <div class="ttd-jabatan" style="font-weight: bold; margin-bottom: 70px;">{{ $sk->penandatangan_jabatan }}</div>
                <div class="ttd-nama" style="font-weight: bold; text-decoration: underline;">{{ $sk->penandatangan_nama }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
