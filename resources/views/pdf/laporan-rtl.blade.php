<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rencana Tindak Lanjut (RTL) - Institusi {{ $setting['app_name'] }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 30mm 25mm 20mm 25mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #1a1a1a;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }

        /* Top Action Bar */
        .print-toolbar {
            background: linear-gradient(135deg, #1e1b4b, #312e81);
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-family: sans-serif;
        }

        .print-btn {
            background: #22c55e;
            color: #ffffff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.4);
            transition: all 0.2s;
        }

        .print-btn:hover {
            background: #16a34a;
        }

        /* Continuous Document Wrapper */
        .document-wrapper {
            max-width: 210mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 25mm 20mm 20mm 20mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            position: relative;
        }

        /* Running Footer Bar on EVERY printed page */
        .print-running-footer {
            width: 100%;
            margin-top: 30px;
            padding-top: 10px;
        }

        .polka-footer-inner {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .footer-logo img {
            height: 22px;
            width: auto;
            vertical-align: middle;
        }

        .footer-bar {
            flex: 1;
            background: #c5d99b;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 12px;
            border-radius: 2px;
        }

        .footer-text {
            font-size: 8pt;
            font-weight: 700;
            color: #1a3300;
        }

        @media print {
            .print-toolbar {
                display: none !important;
            }

            body {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .document-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                padding-bottom: 25px !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            /* Position fixed automatically repeats at bottom of EVERY printed page in browser */
            .print-running-footer {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                background-color: #ffffff !important;
                z-index: 99999 !important;
            }
        }

        .page-break {
            page-break-before: always;
            break-before: page;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #cbd5e1;
        }

        @media print {
            .page-break {
                border-top: none;
                margin-top: 0;
                padding-top: 0;
            }
        }

        /* Cover Page Styling */
        .cover-page-layout {
            min-height: 245mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            padding: 20mm 0 10mm 0;
        }

        .cover-title h1 {
            font-size: 14.5pt;
            font-weight: 800;
            color: #475569;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }

        .cover-title h2 {
            font-size: 18pt;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .cover-title h3 {
            font-size: 14pt;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 6px 0;
        }

        .cover-title h4 {
            font-size: 12pt;
            font-weight: 700;
            color: #475569;
            margin: 0;
        }

        .cover-logo img {
            max-width: 140px;
            max-height: 140px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.05));
        }

        .cover-footer h3 {
            font-size: 13.5pt;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }

        .cover-footer p {
            font-size: 10.5pt;
            font-weight: 700;
            color: #334155;
            margin: 2px 0;
        }

        /* Document Headers */
        h3.chapter-title {
            font-size: 11pt;
            font-weight: 900;
            color: #0f172a;
            margin: 18px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #8ea853;
            padding-bottom: 3px;
        }

        h4.subchapter-title {
            font-size: 9.5pt;
            font-weight: 800;
            color: #1e293b;
            margin: 12px 0 6px 0;
            text-transform: uppercase;
        }

        p,
        li {
            text-align: justify;
            margin-bottom: 6px;
        }

        ol,
        ul {
            margin-top: 4px;
            margin-bottom: 10px;
            padding-left: 22px;
        }

        li {
            margin-bottom: 4px;
        }

        /* Tables */
        table.polka-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 16px 0;
            font-size: 8.5pt;
            page-break-inside: auto;
        }

        table.polka-table tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        table.polka-table th {
            background-color: #8ea853;
            color: #000000;
            font-weight: 800;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #709236;
            text-align: left;
            font-size: 8.5pt;
        }

        table.polka-table td {
            padding: 5px 8px;
            border: 1px solid #c5d99b;
            vertical-align: top;
        }

        table.polka-table tr:nth-child(even) {
            background-color: #f6f9f0;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
        }

        .badge-ok {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-minor {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-mayor {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 25px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
        }

        .signature-space {
            height: 70px;
        }
    </style>
</head>

<body>

    <!-- Top Action Bar -->
    <div class="print-toolbar">
        <div>
            <strong>Laporan Rencana Tindak Lanjut (RTL) AMI Institusi {{ $setting['app_name'] }}</strong>
            <span style="opacity: 0.8; font-size: 11px; margin-left: 8px;">| {{ $audit?->periode?->nama ?: 'Semester Genap 2024/2025' }}</span>
        </div>
        <div>
            <button class="print-btn" onclick="window.print()">
                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                    <path d="M6 14h12v8H6z" />
                </svg>
                Cetak / Simpan PDF (A4)
            </button>
        </div>
    </div>

    <div class="document-wrapper">
        <!-- ==================== HALAMAN COVER ==================== -->
        <div class="cover-page-layout">
            <div class="cover-title">
                <h1>LAPORAN</h1>
                <h2>RENCANA TINDAK LANJUT (RTL)</h2>
                <h3>ATAS AUDIT MUTU INTERNAL - INSTITUSI<br>{{ $setting['app_name'] }}</h3>
                <h4>{{ strtoupper($audit?->periode?->nama ?: ($audit?->periode?->semester ? 'SEMESTER ' . strtoupper($audit->periode->semester) . ' T.A. ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : 'TAHUN AKADEMIK 2024-2025')) }}</h4>
            </div>

            <div class="cover-logo">
                <img src="{{ $setting['logo'] }}" alt="Logo {{ $setting['app_name'] }}">
            </div>

            <div class="cover-footer">
                <h3>{{ $setting['app_name'] }}</h3>
                <p>CILEGON - BANTEN</p>
                <p>TAHUN {{ $audit?->periode?->tahun ? ($audit->periode->tahun + 1) : date('Y') }}</p>
            </div>
        </div>

        <!-- ==================== ISI DOKUMEN ==================== -->
        <div class="page-break"></div>

        <!-- BAB I. PENDAHULUAN -->
        <h3 class="chapter-title">BAB I. PENDAHULUAN</h3>

        <h4 class="subchapter-title">A. LATAR BELAKANG</h4>
        <p>
            Sebagai tindak lanjut dari kegiatan Audit Mutu Internal (AMI) {{ $setting['app_name'] }} {{ $audit?->periode?->nama ?: 'Tahun Akademik 2024–2025' }}, disusunlah Rencana Tindak Lanjut (RTL) sebagai bagian dari tahapan <strong>Pengendalian dan Peningkatan</strong> dalam siklus <strong>PPEPP</strong> (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan) pada Sistem Penjaminan Mutu Internal (SPMI).
        </p>
        <p>
            RTL ini bertujuan untuk mengarahkan setiap unit kerja agar menindaklanjuti temuan-temuan hasil AMI, baik berupa ketidaksesuaian (<em>nonconformities</em>), observasi, maupun peluang peningkatan mutu, sehingga pelaksanaan standar mutu di lingkungan {{ $setting['app_name'] }} dapat lebih efektif dan berkesinambungan.
        </p>
        <p>
            Kegiatan ini merupakan bagian dari upaya membangun budaya mutu yang berkelanjutan dan memastikan bahwa setiap standar dalam SPMI {{ $setting['app_name'] }} dapat diterapkan secara konsisten di seluruh unit kerja.
        </p>

        <h4 class="subchapter-title">B. TUJUAN</h4>
        <ol>
            <li>Menindaklanjuti hasil temuan dan rekomendasi dari Audit Mutu Internal {{ $audit?->periode?->nama ?: 'Tahun Akademik 2024–2025' }}.</li>
            <li>Menetapkan langkah perbaikan dan peningkatan atas standar mutu yang belum tercapai secara terukur.</li>
            <li>Menjadi dasar pelaksanaan Rapat Tinjauan Manajemen (RTM) oleh pimpinan institusi {{ $setting['app_name'] }}.</li>
            <li>Memastikan terselenggaranya siklus mutu SPMI secara utuh, terintegrasi, dan berkelanjutan.</li>
        </ol>

        <h4 class="subchapter-title">C. LINGKUP RENCANA TINDAK LANJUT</h4>
        <p>
            Rencana Tindak Lanjut ini mencakup <strong>31 Standar Mutu SPMI {{ $setting['app_name'] }} (Versi Mei 2022)</strong>, yang terdiri atas:
        </p>
        <ol>
            <li><strong>8 Standar Pendidikan dan Pengajaran</strong> (STD-01 s/d STD-08)</li>
            <li><strong>8 Standar Penelitian</strong> (STD-09 s/d STD-16)</li>
            <li><strong>8 Standar Pengabdian kepada Masyarakat (PkM)</strong> (STD-17 s/d STD-24)</li>
            <li><strong>7 Standar Tambahan POLKA</strong> (STD-25 s/d STD-31: Jati Diri, AIK, Tata Pamong, Kerjasama, Kemahasiswaan, SDM, dan Keuangan)</li>
        </ol>

        <!-- BAB II. RENCANA TINDAK LANJUT -->
        <h3 class="chapter-title">BAB II. RENCANA TINDAK LANJUT</h3>
        <p>
            Sebagai tindak lanjut atas hasil Audit Mutu Internal (AMI) {{ $audit?->periode?->nama ?: 'Tahun Akademik 2024–2025' }}, {{ $setting['app_name'] }} melalui Unit Pelaksana Teknis Sistem Penjaminan Mutu Internal (UPT SPMI) telah melaksanakan proses perumusan Rencana Tindak Lanjut (RTL) terhadap seluruh temuan audit yang telah disampaikan oleh Tim Auditor.
        </p>
        <p>
            Proses penyusunan RTL ini dilakukan dengan prinsip partisipatif, objektif, dan berbasis bukti (<em>evidence-based</em>), di mana setiap unit auditee (program studi, lembaga, dan unit pendukung) secara langsung berperan aktif dalam:
        </p>
        <ol>
            <li>Menganalisis akar penyebab dari setiap temuan audit;</li>
            <li>Menetapkan langkah koreksi atau perbaikan yang sesuai; serta</li>
            <li>Menentukan target waktu dan penanggung jawab tindak lanjut di tingkat unit.</li>
        </ol>
        <p>
            Dalam penyusunan RTL, kategori temuan dibedakan menjadi:
        </p>
        <ol>
            <li><strong>Mayor:</strong> apabila ketidaksesuaian berdampak langsung terhadap pencapaian mutu institusi dan memerlukan tindakan segera;</li>
            <li><strong>Minor:</strong> apabila ketidaksesuaian bersifat administratif atau teknis yang tidak menghambat sistem mutu secara keseluruhan; dan</li>
            <li><strong>Observasi (OB):</strong> apabila temuan bersifat potensi peningkatan atau area yang perlu dipantau dalam siklus berikutnya.</li>
        </ol>
        <p>
            UPT SPMI berperan sebagai koordinator pelaksanaan dan pemantauan RTL, dengan mekanisme pelaporan secara berkala kepada Direktur {{ $setting['app_name'] }} dan pelibatan seluruh pimpinan unit melalui Rapat Tinjauan Manajemen (RTM).
        </p>

        <!-- 1. STANDAR PENDIDIKAN DAN PENGAJARAN -->
        <h4 class="subchapter-title" style="color: #0f172a; margin-top: 15px;">STANDAR PENDIDIKAN DAN PENGAJARAN (8 STANDAR)</h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">No.</th>
                    <th style="width: 25%;">Nama Standar</th>
                    <th style="width: 30%;">Hasil Temuan</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th>Rencana Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-01</td>
                    <td style="font-weight: bold;">Standar Kompetensi Lulusan</td>
                    <td>SKL telah disusun, namun belum disosialisasikan ke seluruh dosen dan mahasiswa prodi baru.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Melakukan sosialisasi CPL dan SKL kepada seluruh dosen dan mahasiswa setiap awal tahun akademik.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-02</td>
                    <td style="font-weight: bold;">Standar Isi Pembelajaran</td>
                    <td>Kurikulum telah mengacu KKNI dan SN-Dikti, penyesuaian materi industri perlu ditingkatkan.</td>
                    <td style="text-align: center;"><span class="badge-status badge-ok">OK</span></td>
                    <td>Revisi kurikulum operasional bersama mitra industri secara berkala setiap 2 tahun.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-03</td>
                    <td style="font-weight: bold;">Standar Proses Pembelajaran</td>
                    <td>RPS Prodi baru belum lengkap dan belum seluruhnya divalidasi oleh tim kurikulum.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Melengkapi RPS seluruh mata kuliah Prodi baru dan melaksanakan workshop validasi kurikulum tahunan.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-04</td>
                    <td style="font-weight: bold;">Standar Penilaian Pembelajaran</td>
                    <td>Rubrik asesmen capaian pembelajaran belum terintegrasi di seluruh mata kuliah praktikum.</td>
                    <td style="text-align: center;"><span class="badge-status badge-ok">OK</span></td>
                    <td>Standarisasi lembar rubrik penilaian praktikum dan evaluasi nilai berbasis OBE.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-05</td>
                    <td style="font-weight: bold;">Standar Dosen dan Tendik</td>
                    <td>Sebagian dosen pemula belum memiliki NIDN dan belum tersertifikasi kompetensi industri.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Segera melakukan percepatan pengusulan NIDN ke PDDikti dan pelatihan SPMI serta sertifikasi dosen.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-06</td>
                    <td style="font-weight: bold;">Standar Sarana & Prasarana</td>
                    <td>Fasilitas ruang kelas dan alat laboratorium praktikum belum seimbang dengan lonjakan mahasiswa.</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Menyusun master plan pengembangan sarpras 2025–2027 dan mengajukan proposal pengadaan ke Yayasan.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-07</td>
                    <td style="font-weight: bold;">Standar Pengelolaan</td>
                    <td>Beberapa dokumen pedoman akademik dan SOP pembelajaran belum difinalisasi dan disahkan.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Menyusun, memfinalisasi, dan mengesahkan seluruh dokumen pedoman pembelajaran melalui SK Direktur.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-08</td>
                    <td style="font-weight: bold;">Standar Pembiayaan</td>
                    <td>Dokumen alokasi dan pertanggungjawaban dana pembelajaran unit prodi belum terstandarisasi.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Finalisasi format dokumen perencanaan, pelaporan, dan pertanggungjawaban anggaran prodi.</td>
                </tr>
            </tbody>
        </table>

        <!-- 2. STANDAR PENELITIAN -->
        <h4 class="subchapter-title" style="color: #0f172a; margin-top: 15px;">STANDAR PENELITIAN (8 STANDAR)</h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">No.</th>
                    <th style="width: 25%;">Nama Standar</th>
                    <th style="width: 30%;">Hasil Temuan</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th>Rencana Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-09</td>
                    <td style="font-weight: bold;">Standar Hasil Penelitian</td>
                    <td>Prodi baru belum memiliki wadah publikasi jurnal internal berkala.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Membentuk jurnal ilmiah internal terakreditasi SINTA untuk bidang vokasi dan teknologi terapan.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-10</td>
                    <td style="font-weight: bold;">Standar Isi Penelitian</td>
                    <td>Peta jalan (roadmap) riset terapan dosen belum sepenuhnya sinkron dengan kebutuhan industri lokal.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Penyelarasan roadmap riset LPPM bersama kawasan industri Cilegon dan Banten.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-14</td>
                    <td style="font-weight: bold;">Standar Sarpras Riset</td>
                    <td>Fasilitas peralatan laboratorium riset terapan masih sangat terbatas.</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Melakukan pengadaan instrumen riset terapan dan integrasi laboratorium lintas program studi.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-16</td>
                    <td style="font-weight: bold;">Standar Pembiayaan Riset</td>
                    <td>Dana penelitian sebagian besar masih mengandalkan hibah eksternal Kemendikbud.</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Menetapkan alokasi dana internal riset tahunan wajib minimal 1 proposal per dosen dalam RKAT.</td>
                </tr>
            </tbody>
        </table>

        <!-- 3. STANDAR PENGABDIAN KEPADA MASYARAKAT (PKM) -->
        <h4 class="subchapter-title" style="color: #0f172a; margin-top: 15px;">STANDAR PENGABDIAN KEPADA MASYARAKAT (8 STANDAR)</h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">No.</th>
                    <th style="width: 25%;">Nama Standar</th>
                    <th style="width: 30%;">Hasil Temuan</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th>Rencana Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-17</td>
                    <td style="font-weight: bold;">Standar Hasil PkM</td>
                    <td>Hasil PkM dosen belum secara terstruktur diintegrasikan ke dalam capaian pembelajaran lulusan (CPL).</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Integrasikan tema PkM dengan bahan kajian mata kuliah dan kebutuhan UMKM/masyarakat sekitar.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-22</td>
                    <td style="font-weight: bold;">Standar Sarpras PkM</td>
                    <td>Fasilitas penunjang operasional kegiatan transfer teknologi PkM di lapangan masih minim.</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Mengajukan anggaran pengadaan perangkat mobile pendukung kegiatan pengabdian via LPPM.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-24</td>
                    <td style="font-weight: bold;">Standar Pembiayaan PkM</td>
                    <td>Belum tersedia pos dana khusus institusi untuk pembiayaan pengurusan HAKI/Paten hasil PkM.</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Mencantumkan pos insentif anggaran PkM dan fasilitasi pendaftaran sertifikat HAKI dalam RKAT.</td>
                </tr>
            </tbody>
        </table>

        <!-- 4. STANDAR TAMBAHAN POLKA -->
        <h4 class="subchapter-title" style="color: #0f172a; margin-top: 15px;">STANDAR TAMBAHAN INSTITUSI (7 STANDAR)</h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">No.</th>
                    <th style="width: 25%;">Nama Standar</th>
                    <th style="width: 30%;">Hasil Temuan</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th>Rencana Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-25</td>
                    <td style="font-weight: bold;">Standar Jati Diri POLKA</td>
                    <td>Belum seluruh sivitas akademika dan tendik memahami visi dan nilai jati diri budaya POLKA.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Melakukan sosialisasi nilai dan visi POLKA dalam kegiatan orientasi mahasiswa dan pelatihan dosen/tendik.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-26</td>
                    <td style="font-weight: bold;">Standar AIK (Al-Islam & Keummatan)</td>
                    <td>Standar dan pedoman AIK belum terdistribusi dan belum diterapkan secara merata di prodi baru.</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Membentuk Tim Pokja AIK, menyusun indikator capaian, dan mengintegrasikannya ke silabus kurikulum.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-27</td>
                    <td style="font-weight: bold;">Standar Tata Pamong</td>
                    <td>Dokumen uraian tugas (job description) dan analisis jabatan seluruh unit belum difinalisasi.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Finalisasi analisis jabatan dan tata kelola unit kerja melalui penerbitan SK Direktur.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-28</td>
                    <td style="font-weight: bold;">Standar Kerjasama</td>
                    <td>Sebagian naskah kerjasama (MoU/MoA) industri belum memiliki bukti implementasi riil berkala.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Membangun sistem informasi monitoring evaluasi implementasi MoU–MoA berbasis data digital.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-29</td>
                    <td style="font-weight: bold;">Standar Kemahasiswaan</td>
                    <td>Pedoman dan aplikasi sistem tracer study alumni belum dibakukan secara terpadu.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Menyusun pedoman tracer study dan mengoptimalkan sistem pelacakan alumni berbasis web.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-30</td>
                    <td style="font-weight: bold;">Standar SDM & Dosen</td>
                    <td>Perencanaan kenaikan jabatan fungsional akademik (JFA/JJA) dosen belum terpetakan rutin.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Menyusun database peta karier dosen dan program pendampingan percepatan Lektor/Lektor Kepala.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-31</td>
                    <td style="font-weight: bold;">Standar Pengelolaan Keuangan</td>
                    <td>Audit laporan keuangan oleh Kantor Akuntan Publik (KAP) eksternal belum dilaksanakan rutin tahunan.</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Menjadwalkan dan melaksanakan audit eksternal laporan keuangan institusi secara berkala.</td>
                </tr>
            </tbody>
        </table>

        <!-- BAB III. PENUTUP -->
        <h3 class="chapter-title">BAB III. PENUTUP</h3>
        <p>
            Dokumen Rencana Tindak Lanjut (RTL) atas Hasil Audit Mutu Internal {{ $audit?->periode?->nama ?: 'Tahun Akademik 2024–2025' }} ini merupakan komitmen seluruh unit kerja di {{ $setting['app_name'] }} untuk menindaklanjuti hasil audit secara terukur dan berkelanjutan.
        </p>
        <p>
            RTL ini menjadi dasar pelaksanaan Rapat Tinjauan Manajemen (RTM) dan penyusunan Rencana Strategis Mutu, sebagai bagian dari penguatan Sistem Penjaminan Mutu Internal {{ $setting['app_name'] }}.
        </p>
        <p>
            Dengan pelaksanaan RTL ini, diharapkan seluruh temuan dapat terselesaikan tepat waktu dan menjadi dasar peningkatan mutu pendidikan vokasi, riset terapan, serta tata kelola yang akuntabel dan berdaya saing industri.
        </p>

        <!-- PENGESAHAN -->
        <div class="signature-section">
            <p style="text-align: center; margin-bottom: 15px; font-weight: 600;">
                Cilegon, {{ $audit?->tanggal_selesai ? \Carbon\Carbon::parse($audit->tanggal_selesai)->translatedFormat('d F Y') : ($audit?->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->translatedFormat('d F Y') : '29 September 2025') }}
            </p>

            <table class="signature-table">
                <tr>
                    <td>
                        <strong>Unit Pelaksana Teknis<br>Sistem Penjaminan Mutu Internal<br>{{ $setting['app_name'] }}</strong>
                        <div class="signature-space"></div>
                        <strong style="text-decoration: underline; font-size: 10.5pt;">{{ $audit?->ketuaAuditor?->name ?: 'Fadlinatin Naumi, M.Si.' }}</strong><br>
                        <span>Ketua UPT SPMI</span>
                    </td>
                    <td>
                        <strong>Menyetujui,<br>{{ $setting['app_name'] }}</strong>
                        <div class="signature-space"></div>
                        <strong style="text-decoration: underline; font-size: 10.5pt;">Ir. Sudarmono M.MSI.</strong><br>
                        <span>Direktur</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- RUNNING FOOTER -->
        <div class="print-running-footer">
            <div class="polka-footer-inner">
                <div class="footer-logo">
                    <img src="{{ $setting['logo'] }}" alt="Logo">
                </div>
                <div class="footer-bar">
                    <span class="footer-text">Rencana Tindak Lanjut atas AMI – {{ $audit?->periode?->nama ?: 'Institusi ' . $setting['app_name'] . ' 2024 - 2025' }}</span>
                    <span class="footer-text" style="font-weight: 800;">{{ $setting['app_name'] }}</span>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
