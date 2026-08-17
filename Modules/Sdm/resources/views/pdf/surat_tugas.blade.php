<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas - {{ $suratTugas->nomor_surat }}</title>
    <style>
        @page {
            margin: 50pt 50pt 50pt 50pt;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #111;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header-sub {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .header-address {
            font-size: 8.5pt;
            color: #333;
        }

        .surat-title {
            text-align: center;
            margin-bottom: 22px;
        }

        .surat-title h2 {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0 0 4px 0;
        }

        .surat-title .nomor {
            font-size: 10pt;
            color: #222;
            font-weight: bold;
        }

        .content-block {
            margin-bottom: 12px;
            text-align: justify;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 16px 0;
        }

        .table-data th, .table-data td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 9pt;
        }

        .table-data th {
            background-color: #f1f3f7;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .table-info {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .table-info td {
            vertical-align: top;
            padding: 3px 0;
            font-size: 9.5pt;
        }

        .sign-table {
            width: 100%;
            margin-top: 30px;
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
            height: 65px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- KOP INSTITUSI -->
    <div class="header">
        <div class="header-title">{{ $setting['nama_institusi'] ?? 'POLITEKNIK KAMPUS AKADEMIK' }}</div>
        <div class="header-sub">DIREKTORAT SUMBER DAYA MANUSIA & KEPEGAWAIAN</div>
        <div class="header-address">{{ $setting['alamat_institusi'] ?? 'Jl. Raya Kampus Terpadu, Indonesia' }} | Telp / Email: {{ $setting['email_institusi'] ?? 'info@polka.ac.id' }}</div>
    </div>

    <!-- JUDUL & NOMOR SURAT -->
    <div class="surat-title">
        <h2>SURAT TUGAS</h2>
        <div class="nomor">Nomor: {{ $suratTugas->nomor_surat }}</div>
    </div>

    <!-- PENGANTAR -->
    <div class="content-block">
        Yang bertanda tangan di bawah ini pimpinan <strong>{{ $setting['nama_institusi'] ?? 'Institusi' }}</strong>, dengan ini memberikan tugas kedinasan kepada pegawai di bawah ini:
    </div>

    <!-- TABEL DAFTAR PEGAWAI BERTUGAS -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Pegawai & NIP</th>
                <th>Pangkat / Golongan / Jabatan</th>
                <th>Unit Kerja</th>
                <th style="width: 100px;">Peran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratTugas->pegawais as $index => $pegawai)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $pegawai->nama }}</strong><br>
                    <small style="color: #444;">NIP: {{ $pegawai->nip ?? '-' }}</small>
                </td>
                <td>
                    {{ $pegawai->jabatan_fungsional ?? ($pegawai->jabatan_struktural ?? 'Staf Pegawai') }}<br>
                    <small style="color: #444;">Gol: {{ $pegawai->golongan ?? '-' }}</small>
                </td>
                <td>{{ $pegawai->unit_kerja ?? '-' }}</td>
                <td style="text-align: center; font-weight: bold;">
                    @if($pegawai->pivot->peran === 'ketua')
                        Ketua Tim
                    @elseif($pegawai->pivot->peran === 'penanggung_jawab')
                        Penanggung Jawab
                    @else
                        Anggota
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- RINCIAN TUGAS KEDINASAN -->
    <div class="content-block">
        Untuk melaksanakan tugas dengan ketentuan sebagai berikut:
    </div>

    <table class="table-info">
        <tr>
            <td style="width: 160px;">1. Perihal Tugas</td>
            <td style="width: 15px;">:</td>
            <td style="font-weight: bold;">{{ $suratTugas->perihal }}</td>
        </tr>
        <tr>
            <td>2. Jenis Kegiatan</td>
            <td>:</td>
            <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $suratTugas->jenis) }}</td>
        </tr>
        <tr>
            <td>3. Tempat / Lokasi Tujuan</td>
            <td>:</td>
            <td style="font-weight: bold;">{{ $suratTugas->tempat_tujuan }}</td>
        </tr>
        <tr>
            <td>4. Waktu Pelaksanaan</td>
            <td>:</td>
            <td>
                {{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->translatedFormat('d F Y') }}
                @if($suratTugas->tanggal_selesai != $suratTugas->tanggal_mulai)
                    s.d. {{ \Carbon\Carbon::parse($suratTugas->tanggal_selesai)->translatedFormat('d F Y') }}
                @endif
                ({{ \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($suratTugas->tanggal_selesai)) + 1 }} Hari Kerja)
            </td>
        </tr>
        <tr>
            <td>5. Maksud & Keperluan</td>
            <td>:</td>
            <td>{{ $suratTugas->keperluan }}</td>
        </tr>
        @if($suratTugas->anggaran && $suratTugas->anggaran > 0)
        <tr>
            <td>6. Pembebanan Anggaran</td>
            <td>:</td>
            <td>Rp {{ number_format($suratTugas->anggaran, 0, ',', '.') }} (Sumber: {{ $suratTugas->sumber_dana ?? 'DIPA Institusi' }})</td>
        </tr>
        @endif
    </table>

    <!-- PENUTUP -->
    <div class="content-block">
        Demikian Surat Tugas ini diterbitkan untuk dilaksanakan dengan penuh rasa tanggung jawab dan setelah selesai melaksanakan tugas agar segera menyampaikan laporan tertulis kepada pimpinan.
    </div>

    <!-- TANDA TANGAN -->
    <table class="sign-table">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center;">
                <div>{{ $setting['kota_institusi'] ?? 'Ditetapkan di Tempat' }}, {{ $suratTugas->approved_at ? \Carbon\Carbon::parse($suratTugas->approved_at)->translatedFormat('d F Y') : \Carbon\Carbon::parse($suratTugas->tanggal_mulai)->subDays(1)->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 3px;">a.n. Pimpinan / Direktur Institusi</div>
                <div style="font-size: 8.5pt; color: #444;">Kepala Bagian SDM & Kepegawaian</div>
                <div class="stamp-space"></div>
                <div class="sign-name">{{ $suratTugas->approvedBy->name ?? ($pejabatDefault->name ?? 'Dr. H. Bambang Sudarsono, M.M.') }}</div>
                <div>NIP. {{ $suratTugas->approvedBy->nip ?? ($pejabatDefault->nip ?? '197508142000031002') }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
