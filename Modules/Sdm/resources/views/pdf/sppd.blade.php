<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPPD - {{ $suratTugas->nomor_surat }}</title>
    <style>
        @page {
            margin: 40pt 45pt 40pt 45pt;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5pt;
            line-height: 1.4;
            color: #111;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 16px;
        }

        .header-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header-sub {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-address {
            font-size: 8pt;
            color: #333;
        }

        .sppd-meta {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9pt;
        }

        .sppd-meta td {
            vertical-align: top;
            padding: 1px 0;
        }

        .title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 10px 0 14px 0;
        }

        .table-sppd {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .table-sppd th, .table-sppd td {
            border: 1px solid #000;
            padding: 4.5px 7px;
            font-size: 8.5pt;
            vertical-align: top;
        }

        .sign-container {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .sign-container td {
            vertical-align: top;
            font-size: 8.5pt;
        }

        .stamp-space {
            height: 55px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @foreach($suratTugas->pegawais as $idx => $pegawai)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">
        <!-- KOP -->
        <div class="header">
            <div class="header-title">{{ $setting['nama_institusi'] ?? 'POLITEKNIK KAMPUS AKADEMIK' }}</div>
            <div class="header-sub">SURAT PERINTAH PERJALANAN DINAS (SPPD)</div>
            <div class="header-address">{{ $setting['alamat_institusi'] ?? 'Jl. Raya Kampus Terpadu, Indonesia' }}</div>
        </div>

        <table class="sppd-meta">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    Lembar Ke : {{ $idx + 1 }}<br>
                    Kode No &nbsp;&nbsp;: {{ $suratTugas->nomor_surat }}/SPPD<br>
                    Nomor &nbsp;&nbsp;&nbsp;&nbsp;: SPPD/{{ date('Y') }}/{{ str_pad($suratTugas->id, 4, '0', STR_PAD_LEFT) }}-{{ $idx + 1 }}
                </td>
            </tr>
        </table>

        <div class="title">SURAT PERINTAH PERJALANAN DINAS (SPPD)</div>

        <table class="table-sppd">
            <tr>
                <td style="width: 25px; text-align: center;">1.</td>
                <td style="width: 220px;">Pejabat Pembuat Komitmen / Pemberi Perintah</td>
                <td>{{ $suratTugas->approvedBy->name ?? ($pejabatDefault->name ?? 'Dr. H. Bambang Sudarsono, M.M.') }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">2.</td>
                <td>Nama / NIP Pegawai yang diperintahkan</td>
                <td>
                    <strong>{{ $pegawai->nama }}</strong><br>
                    NIP: {{ $pegawai->nip ?? '-' }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">3.</td>
                <td>
                    a. Pangkat dan Golongan<br>
                    b. Jabatan / Instansi<br>
                    c. Tingkat Biaya Perjalanan Dinas
                </td>
                <td>
                    a. {{ $pegawai->golongan ? 'Penata Muda / ' . $pegawai->golongan : 'Pegawai Tetap' }}<br>
                    b. {{ $pegawai->jabatan_fungsional ?? ($pegawai->jabatan_struktural ?? 'Dosen / Staf Pengajar') }} / {{ $pegawai->unit_kerja }}<br>
                    c. Tingkat B (Kedinasan Institusi)
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">4.</td>
                <td>Maksud Perjalanan Dinas</td>
                <td>{{ $suratTugas->perihal }} - {{ $suratTugas->keperluan }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">5.</td>
                <td>Alat angkutan yang dipergunakan</td>
                <td>Kendaraan Operasional / Transportasi Umum / Pesawat Udara</td>
            </tr>
            <tr>
                <td style="text-align: center;">6.</td>
                <td>
                    a. Tempat berangkat<br>
                    b. Tempat tujuan
                </td>
                <td>
                    a. {{ $setting['kota_institusi'] ?? 'Kampus Pusat' }}<br>
                    b. <strong>{{ $suratTugas->tempat_tujuan }}</strong>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">7.</td>
                <td>
                    a. Lamanya Perjalanan Dinas<br>
                    b. Tanggal berangkat<br>
                    c. Tanggal harus kembali
                </td>
                <td>
                    a. {{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($suratTugas->tanggal_selesai)) + 1 }} ({{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($suratTugas->tanggal_selesai)) + 1 }}) Hari<br>
                    b. {{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->translatedFormat('d F Y') }}<br>
                    c. {{ \Carbon\Carbon::parse($suratTugas->tanggal_selesai)->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">8.</td>
                <td>Pengikut / Tim Rombongan</td>
                <td>
                    @if($suratTugas->pegawais->count() > 1)
                        {{ $suratTugas->pegawais->where('id', '!=', $pegawai->id)->pluck('nama')->join(', ') }}
                    @else
                        - (Tunggal)
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">9.</td>
                <td>
                    Pembebanan Anggaran<br>
                    a. Instansi / Mata Anggaran<br>
                    b. Akun / Sumber Dana
                </td>
                <td>
                    <br>
                    a. {{ $setting['nama_institusi'] ?? 'Institusi' }}<br>
                    b. {{ $suratTugas->sumber_dana ?? 'DIPA / Anggaran Operasional Institusi' }} (Rp {{ number_format($suratTugas->anggaran ?? 0, 0, ',', '.') }})
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">10.</td>
                <td>Keterangan lain-lain</td>
                <td>Berdasarkan Surat Tugas No: {{ $suratTugas->nomor_surat }}</td>
            </tr>
        </table>

        <table class="sign-container">
            <tr>
                <td style="width: 55%;">
                    <div style="font-size: 8pt; color: #555; padding-top: 10px;">
                        <em>Catatan: SPPD ini sah apabila ditandatangani oleh Pejabat Pembuat Komitmen dan Pejabat di tempat tujuan.</em>
                    </div>
                </td>
                <td style="width: 45%; text-align: center;">
                    <div>Dikeluarkan di: {{ $setting['kota_institusi'] ?? 'Indonesia' }}</div>
                    <div>Pada tanggal: {{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->translatedFormat('d F Y') }}</div>
                    <div style="font-weight: bold; margin-top: 4px;">Pejabat Pembuat Komitmen,</div>
                    <div class="stamp-space"></div>
                    <div class="sign-name">{{ $suratTugas->approvedBy->name ?? ($pejabatDefault->name ?? 'Dr. H. Bambang Sudarsono, M.M.') }}</div>
                    <div>NIP. {{ $suratTugas->approvedBy->nip ?? ($pejabatDefault->nip ?? '197508142000031002') }}</div>
                </td>
            </tr>
        </table>
    </div>
    @endforeach

</body>
</html>
