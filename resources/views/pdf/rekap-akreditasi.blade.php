<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Kuantitatif Akreditasi Institusi (LKPT)</title>
    <style>
        @page { size: A4 landscape; margin: 15mm 20mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; color: #111; line-height: 1.35; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 16px; }
        .header h2 { margin: 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; }
        .header h3 { margin: 2px 0 0; font-size: 12pt; font-weight: bold; }
        .header p { margin: 2px 0 0; font-size: 9pt; color: #444; }
        .doc-title { text-align: center; margin-bottom: 16px; }
        .doc-title h4 { margin: 0; font-size: 12.5pt; text-transform: uppercase; text-decoration: underline; }
        .doc-title span { font-size: 10pt; color: #333; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 9.5pt; }
        .data-table th, .data-table td { border: 1px solid #333; padding: 5px 8px; }
        .data-table th { background-color: #f3f4f6; font-weight: bold; text-align: center; }
        .section-head { background-color: #e5e7eb; font-weight: bold; font-size: 10pt; }
        .print-bar { background: #059669; color: #fff; padding: 10px; text-align: center; margin-bottom: 20px; font-family: sans-serif; }
        .print-btn { background: #fff; color: #059669; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        @media print { .print-bar { display: none; } }
    </style>
</head>
<body>
    <div class="print-bar">
        <span>Rekapitulasi Data Kuantitatif Akreditasi (LKPT / LED A4 Landscape) — </span>
        <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h2>{{ $setting['app_name'] }}</h2>
        <h3>PUSAT DATA & PENJAMINAN MUTU PERGURUAN TINGGI</h3>
        <p>{{ $setting['app_tagline'] }} — Agregasi Data Lintas Modul ERP</p>
    </div>

    <div class="doc-title">
        <h4>REKAPITULASI DATA KUANTITATIF AKREDITASI INSTITUSI & PROGRAM STUDI</h4>
        <span>Laporan Otomatis Sistem Terintegrasi PINTAR (Per Tanggal: {{ date('d F Y') }})</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 250px;">Kriteria / Indikator Standar</th>
                <th>Elemen Data Agregasi</th>
                <th style="width: 140px;">Capaian Angka</th>
                <th style="width: 150px;">Sumber Modul</th>
            </tr>
        </thead>
        <tbody>
            <!-- Kriteria SDM -->
            <tr class="section-head">
                <td style="text-align: center;"><strong>A</strong></td>
                <td colspan="4"><strong>SUMBER DAYA MANUSIA (KUALIFIKASI DOSEN & TENDIK)</strong></td>
            </tr>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Total Dosen Perguruan Tinggi</td>
                <td>Total Dosen terdaftar</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['sdm']['total_dosen'] }} Orang</td>
                <td>Modul SDM & Kepegawaian</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Dosen Aktif Mengajar & Melaksanakan Tridharma</td>
                <td>Status kepegawaian aktif</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['sdm']['dosen_aktif'] }} Orang</td>
                <td>Modul SDM & Kepegawaian</td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Dosen Tetap (PNS, PPPK & Tetap Yayasan)</td>
                <td>Status pegawai tetap institusi</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['sdm']['dosen_tetap'] }} Orang</td>
                <td>Modul SDM & Kepegawaian</td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Tenaga Kependidikan & Penunjang Akademik</td>
                <td>Staf administrasi, laboran, dan teknisi</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['sdm']['tendik_total'] }} Orang</td>
                <td>Modul SDM & Kepegawaian</td>
            </tr>

            <!-- Kriteria Mahasiswa -->
            <tr class="section-head">
                <td style="text-align: center;"><strong>B</strong></td>
                <td colspan="4"><strong>KEMAHASISWAAN & PRESTASI</strong></td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Total Mahasiswa Aktif (Body of Students)</td>
                <td>Mahasiswa registrasi aktif periode berjalan</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['akademik']['mhs_aktif'] }} Mahasiswa</td>
                <td>Modul Data Akademik</td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>Rerata Indeks Prestasi Kumulatif (IPK Lulusan)</td>
                <td>Rata-rata IPK skala 4.00</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['akademik']['avg_ipk'] }}</td>
                <td>Modul Data Akademik</td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>Kelulusan Tepat Waktu (<= 4 Tahun / 48 Bulan)</td>
                <td>Persentase lulusan tepat waktu</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['akademik']['tepat_waktu'] }} Orang</td>
                <td>Modul Data Akademik</td>
            </tr>

            <!-- Kriteria Tridharma -->
            <tr class="section-head">
                <td style="text-align: center;"><strong>C</strong></td>
                <td colspan="4"><strong>PENELITIAN, PKM & KARYA ILMIAH DOSEN</strong></td>
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td>Jumlah Judul Penelitian Dosen</td>
                <td>Total hibah internal & eksternal</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['tridharma']['penelitian_total'] }} Judul (Rp {{ number_format($data['tridharma']['penelitian_dana']) }})</td>
                <td>Modul Tridharma Perguruan Tinggi</td>
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td>Jumlah Pengabdian kepada Masyarakat (PkM)</td>
                <td>Kegiatan kemitraan masyarakat & UMKM</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['tridharma']['pengabdian_total'] }} Kegiatan (Rp {{ number_format($data['tridharma']['pengabdian_dana']) }})</td>
                <td>Modul Tridharma Perguruan Tinggi</td>
            </tr>
            <tr>
                <td style="text-align: center;">10</td>
                <td>Publikasi Jurnal Terakreditasi & Scopus</td>
                <td>Jurnal Internasional Scopus & SINTA 1-6</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['tridharma']['jurnal_scopus'] }} Scopus · {{ $data['tridharma']['jurnal_sinta'] }} SINTA</td>
                <td>Modul Tridharma Perguruan Tinggi</td>
            </tr>

            <!-- Kriteria Tracer Study -->
            <tr class="section-head">
                <td style="text-align: center;"><strong>D</strong></td>
                <td colspan="4"><strong>LUARAN & TRACER STUDY ALUMNI</strong></td>
            </tr>
            <tr>
                <td style="text-align: center;">11</td>
                <td>Keterserapan Lulusan di Dunia Kerja</td>
                <td>Lulusan bekerja langsung di industri / instansi</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['tracer']['bekerja'] }} Orang ({{ $data['tracer']['total_responden'] > 0 ? round(($data['tracer']['bekerja'] / $data['tracer']['total_responden']) * 100) : 0 }}%)</td>
                <td>Modul Tracer Study</td>
            </tr>
            <tr>
                <td style="text-align: center;">12</td>
                <td>Rata-rata Masa Tunggu Mendapat Pekerjaan Pertama</td>
                <td>Durasi waktu tunggu pasca wisuda</td>
                <td style="text-align: center; font-weight: bold;">{{ $data['tracer']['avg_tunggu'] }} Bulan</td>
                <td>Modul Tracer Study</td>
            </tr>
            <tr>
                <td style="text-align: center;">13</td>
                <td>Rata-rata Pendapatan Lulusan Pertama Bekerja</td>
                <td>Rerata gaji per bulan</td>
                <td style="text-align: center; font-weight: bold;">Rp {{ number_format($data['tracer']['avg_gaji']) }}</td>
                <td>Modul Tracer Study</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 25px; display: flex; justify-content: flex-end; page-break-inside: avoid;">
        <div style="width: 300px; text-align: center; float: right;">
            Ditetapkan di Kampus Politeknik,<br>
            Pada Tanggal: {{ date('d F Y') }}<br>
            <strong>Direktur / Kepala Pusat Penjaminan Mutu</strong>
            <div style="height: 60px;"></div>
            <strong><u>(............................................................)</u></strong>
        </div>
    </div>
</body>
</html>
