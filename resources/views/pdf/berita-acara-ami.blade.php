<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Audit Mutu Internal - Institusi {{ $setting['app_name'] }} 2024-2025</title>
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
            line-height: 1.4;
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
        }

        .no-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Cover Page Layout */
        .cover-page-layout {
            text-align: center;
            min-height: 85vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 30px 0;
            page-break-after: always;
            break-after: page;
        }

        .cover-title h1 {
            font-size: 20pt;
            font-weight: 900;
            margin: 0;
            letter-spacing: 0.5px;
            color: #111;
        }

        .cover-title h2 {
            font-size: 17pt;
            font-weight: 800;
            margin: 6px 0 0 0;
            color: #111;
        }

        .cover-title h3 {
            font-size: 14pt;
            font-weight: 700;
            margin: 6px 0 0 0;
            color: #222;
        }

        .cover-logo {
            margin: 40px 0;
        }

        .cover-logo img {
            width: 210px;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }

        .cover-footer h3 {
            font-size: 14pt;
            font-weight: 800;
            margin: 0;
            color: #111;
        }

        .cover-footer p {
            font-size: 11pt;
            font-weight: 600;
            margin: 3px 0 0 0;
            color: #333;
        }

        /* Document Header on Page 2 */
        h2.section-header {
            font-size: 13pt;
            font-weight: 800;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #111;
            line-height: 1.35;
        }

        h3.section-subtitle {
            font-size: 10.5pt;
            font-weight: 800;
            margin-top: 18px;
            margin-bottom: 6px;
            color: #111;
            text-transform: uppercase;
            border-bottom: 1.5px solid #8ea853;
            padding-bottom: 3px;
        }

        p,
        li {
            text-align: justify;
            margin-bottom: 4px;
        }

        ol,
        ul {
            margin-top: 3px;
            margin-bottom: 8px;
            padding-left: 22px;
        }

        li {
            margin-bottom: 3px;
        }

        /* Tables */
        table.polka-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 14px 0;
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
            padding: 5px 7px;
            border: 1px solid #709236;
            text-align: left;
            font-size: 8.5pt;
        }

        table.polka-table td {
            padding: 4px 7px;
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
            border: 1px solid #fca5a5;
        }

        .badge-ob {
            background-color: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }

        /* Running Footer Bar for Page Bottoms */
        .polka-footer {
            width: 100%;
            margin-top: 25px;
            padding-top: 6px;
            page-break-inside: avoid;
            break-inside: avoid;
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

        /* Signatures */
        .signature-section {
            margin-top: 30px;
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
            vertical-align: top;
            text-align: center;
            padding: 10px;
        }

        .signature-space {
            height: 75px;
        }

        .tembusan {
            margin-top: 25px;
            font-size: 9pt;
            color: #333;
        }
    </style>
</head>

<body>

    <!-- Print Action Bar -->
    <div class="print-toolbar">
        <div>
            <strong>Laporan Audit Mutu Internal (AMI) Institusi {{ $setting['app_name'] }}</strong>
            <span style="opacity: 0.8; font-size: 11px; margin-left: 8px;">| Tahun Akademik
                {{ $audit->periode?->tahun ? $audit->periode->tahun . '-' . ($audit->periode->tahun + 1) : '2024-2025' }}</span>
        </div>
        <div>
            <button class="print-btn" onclick="window.print()">
                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
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
                <h1>AUDIT MUTU INTERNAL (AMI)</h1>
                <h2>{{ $setting['app_name'] }}</h2>
                <h3>{{ strtoupper($audit->periode?->nama ?: ($audit->periode?->semester ? 'SEMESTER ' . strtoupper($audit->periode->semester) . ' T.A. ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : 'SEMESTER GANJIL/GENAP 2024/2025')) }}</h3>
            </div>

            <div class="cover-logo">
                <img src="{{ $setting['logo'] }}" alt="Logo {{ $setting['app_name'] }}">
            </div>

            <div class="cover-footer">
                <h3>{{ $setting['app_name'] }}</h3>
                <p>CILEGON - BANTEN</p>
                <p>TAHUN {{ $audit->periode?->tahun ? ($audit->periode->tahun + 1) : date('Y') }}</p>
            </div>
        </div>

        <!-- ==================== ISI DOKUMEN (MENGALIR PADAT & RAPI) ==================== -->
        <h2 class="section-header">
            LAPORAN AUDIT MUTU INTERNAL (AMI)<br>
            {{ strtoupper($audit->periode?->nama ?: ($audit->periode?->semester ? 'SEMESTER ' . strtoupper($audit->periode->semester) . ' T.A. ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : 'TAHUN AKADEMIK 2024/2025')) }}<br>
            {{ $setting['app_name'] }}
        </h2>

        <!-- 1. LATAR BELAKANG -->
        <h3 class="section-subtitle">1. LATAR BELAKANG</h3>
        <p>
            Audit Mutu Internal (AMI) merupakan bagian dari Sistem Penjaminan Mutu Internal (SPMI) yang diselenggarakan secara berkala per semester untuk menilai kinerja institusi dalam menerapkan standar mutu pada aspek pendidikan, penelitian, dan pengabdian kepada masyarakat. Laporan ini menyajikan hasil audit siklus semesteran yang dilakukan pada Program Studi dan Unit-Unit di {{ $setting['app_name'] }} untuk {{ $audit->periode?->nama ?: ($audit->periode?->semester ? 'Semester ' . ucfirst($audit->periode->semester) . ' Tahun Akademik ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : 'Tahun Akademik 2024/2025') }}.
        </p>
        <p>
            Audit ini dilakukan untuk memastikan bahwa pelaksanaan kegiatan akademik, administratif, dan pelayanan telah sesuai dengan standar yang ditetapkan dan untuk memberikan rekomendasi perbaikan demi peningkatan kualitas institusi.
        </p>

        <!-- 2. IDENTITAS AUDITOR -->
        <h3 class="section-subtitle">2. IDENTITAS AUDITOR</h3>
        <table class="polka-table">
            <tr>
                <td style="width: 25%; font-weight: bold; background-color: #f2f7e8;">Nama Unit</td>
                <td colspan="2"><strong>{{ $audit->unit_yang_diaudit ?: 'Institusi ' . $setting['app_name'] }}</strong>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; background-color: #f2f7e8;">Periode / Semester</td>
                <td colspan="2"><strong>{{ $audit->periode?->nama ?: ($audit->periode?->semester ? 'Semester ' . ucfirst($audit->periode->semester) . ' T.A. ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : '-') }}</strong>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; background-color: #f2f7e8;">Jadwal Audit</td>
                <td colspan="2">
                    {{ $audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->translatedFormat('d F Y') : 'September 2025' }}
                    @if($audit->tanggal_selesai)
                        s/d {{ \Carbon\Carbon::parse($audit->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            @php
                $auditorMembers = $audit->auditors->filter(function($item) use ($audit) {
                    return $item->id !== $audit->ketua_auditor_id;
                });
                $totalTimRows = max(1, 1 + $auditorMembers->count());
            @endphp
            <tr>
                <td rowspan="{{ $totalTimRows }}" style="font-weight: bold; background-color: #f2f7e8;">Tim Audit</td>
                <td style="width: 38%; font-weight: bold;">
                    1. {{ $audit->ketuaAuditor?->name ?: 'Fadlinatin Naumi, M.Si.' }}
                </td>
                <td>Ketua Tim Auditor Mutu Internal (SPMI)</td>
            </tr>
            @foreach($auditorMembers as $idx => $aud)
            <tr>
                <td style="font-weight: bold;">
                    {{ $loop->iteration + 1 }}. {{ $aud->name }}
                </td>
                <td>{{ $aud->pivot->peran ? ucfirst($aud->pivot->peran) . ' Tim Auditor' : 'Anggota Tim Auditor Mutu Internal' }}</td>
            </tr>
            @endforeach
        </table>

        <!-- 3. DASAR HUKUM -->
        <h3 class="section-subtitle">3. DASAR HUKUM & ACUAN AUDIT</h3>
        <p>
            Pelaksanaan Audit Mutu Internal {{ $setting['app_name'] }} Tahun Akademik
            {{ $audit->periode?->tahun ? $audit->periode->tahun . '–' . ($audit->periode->tahun + 1) : '2024–2025' }}
            dilaksanakan berdasarkan peraturan perundang-undangan dan pedoman penjaminan mutu pendidikan tinggi yang
            berlaku:
        </p>
        <ol>
            <li>Undang-Undang Nomor 12 Tahun 2012 tentang Pendidikan Tinggi.</li>
            <li>Peraturan Pemerintah Nomor 4 Tahun 2014 tentang Penyelenggaraan Pendidikan Tinggi dan Pengelolaan
                Perguruan Tinggi.</li>
            <li>Permendikbudristek Nomor 53 Tahun 2023 tentang Penjaminan Mutu Pendidikan Tinggi.</li>
            <li>Peraturan BAN-PT Nomor 1 Tahun 2022 tentang Mekanisme Akreditasi.</li>
            <li>Peraturan BAN-PT Nomor 14 Tahun 2023 tentang Kebijakan Penyusunan Instrumen Akreditasi.</li>
            <li>Dokumen SPMI {{ $setting['app_name'] }} (Versi Mei 2022).</li>
            <li>Keputusan Direktur {{ $setting['app_name'] }} tentang Penetapan Tim Auditor dan Pelaksanaan AMI Tahun
                Akademik
                {{ $audit->periode?->tahun ? $audit->periode->tahun . '–' . ($audit->periode->tahun + 1) : '2024–2025' }}.
            </li>
        </ol>

        <!-- 4. DAFTAR UNIT YANG DIAUDIT -->
        <h3 class="section-subtitle">4. DAFTAR UNIT YANG DIAUDIT</h3>
        <p>
            Audit Mutu Internal dilaksanakan terhadap seluruh unit akademik dan non-akademik di lingkungan
            {{ $setting['app_name'] }}, mencakup pelaksanaan tridharma perguruan tinggi, tata kelola, dan layanan
            penunjang:
        </p>

        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 25px; text-align: center;">No.</th>
                    <th style="width: 32%;">Nama Unit / Pihak yang Diaudit</th>
                    <th style="width: 24%;">Jenis Unit</th>
                    <th>Ruang Lingkup Audit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $idx => $u)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                        <td style="font-weight: bold; color: #111;">{{ $u['nama'] }}</td>
                        <td>{{ $u['jenis'] }}</td>
                        <td>{{ $u['lingkup'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 5. TUJUAN AUDIT -->
        <h3 class="section-subtitle">5. TUJUAN AUDIT</h3>
        <p>
            Pelaksanaan Audit Mutu Internal di {{ $setting['app_name'] }} bertujuan untuk memastikan keterlaksanaan dan
            ketercapaian standar mutu yang telah ditetapkan dalam Sistem Penjaminan Mutu Internal (SPMI), serta
            memberikan masukan perbaikan berkelanjutan (<em>continuous improvement</em>) bagi seluruh unit kerja di
            lingkungan {{ $setting['app_name'] }}.
        </p>
        <p>Secara khusus, audit ini bertujuan untuk:</p>
        <ol>
            <li>Memastikan kesesuaian pelaksanaan kegiatan tridharma perguruan tinggi dengan standar mutu dalam dokumen
                SPMI {{ $setting['app_name'] }}.</li>
            <li>Menilai kelengkapan dan efektivitas penerapan dokumen mutu (kebijakan, manual, standar, SOP, dan
                formulir).</li>
            <li>Menilai tingkat ketercapaian indikator kinerja standar mutu (input, proses, output, outcome).</li>
            <li>Mengidentifikasi ketidaksesuaian (<em>nonconformities</em>) dan kelemahan untuk perumusan tindak lanjut.
            </li>
            <li>Mengevaluasi efektivitas pelaksanaan SPMI secara menyeluruh sebagai dasar keputusan berbasis data mutu.
            </li>
            <li>Mendorong budaya mutu dan <em>continuous improvement</em> di lingkungan sivitas akademika
                {{ $setting['app_name'] }}.
            </li>
            <li>Memberikan rekomendasi perbaikan dan pengembangan sistem mutu untuk kesiapan akreditasi (BAN-PT/LAM).
            </li>
        </ol>

        <!-- 6. RUANG LINGKUP -->
        <h3 class="section-subtitle">6. RUANG LINGKUP AUDIT</h3>
        <p>
            Audit Mutu Internal (AMI) {{ $setting['app_name'] }} Tahun Akademik
            {{ $audit->periode?->tahun ? $audit->periode->tahun . '–' . ($audit->periode->tahun + 1) : '2024–2025' }}
            mencakup 6 bidang:
        </p>
        <ol>
            <li><strong>Bidang Pendidikan dan Pengajaran:</strong> Perencanaan, RPS, kualifikasi dosen, sarpras, dan
                capaian lulusan (CPL).</li>
            <li><strong>Bidang Penelitian:</strong> Pelaksanaan riset, publikasi ilmiah, dana riset internal/eksternal,
                dan keterlibatan mahasiswa.</li>
            <li><strong>Bidang Pengabdian kepada Masyarakat (PkM):</strong> Perencanaan, luaran PkM, dan relevansi
                dengan kebutuhan masyarakat.</li>
            <li><strong>Bidang Tata Kelola dan Manajemen Institusi:</strong> Kebijakan mutu, tata pamong, akuntabilitas
                keuangan, dan kepatuhan.</li>
            <li><strong>Bidang Sumber Daya dan Layanan Penunjang:</strong> Pengelolaan SDM dosen/tendik, perpustakaan,
                IT, dan laboratorium.</li>
            <li><strong>Bidang Kerjasama dan Inovasi:</strong> Kemitraan industri, program magang, dan tracer study
                alumni.</li>
        </ol>

        <!-- 7. JADWAL AUDIT -->
        <h3 class="section-subtitle">7. JADWAL KEGIATAN AUDIT</h3>
        <table class="polka-table" style="width: 85%;">
            <thead>
                <tr>
                    <th style="width: 30%;">Waktu Mulai</th>
                    <th style="width: 30%;">Waktu Berakhir</th>
                    <th>Nama Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->format('d/m/Y') : '15/09/2025' }}</td>
                    <td>{{ $audit->tanggal_selesai ? \Carbon\Carbon::parse($audit->tanggal_selesai)->format('d/m/Y') : '26/09/2025' }}</td>
                    <td>Pengumpulan Data & Dokumen Mutu</td>
                </tr>
                <tr>
                    <td>{{ $audit->opening_meeting ? \Carbon\Carbon::parse($audit->opening_meeting)->format('d/m/Y') : ($audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->addDays(5)->format('d/m/Y') : '20/09/2025') }}</td>
                    <td>{{ $audit->closing_meeting ? \Carbon\Carbon::parse($audit->closing_meeting)->format('d/m/Y') : ($audit->tanggal_selesai ? \Carbon\Carbon::parse($audit->tanggal_selesai)->subDays(1)->format('d/m/Y') : '25/09/2025') }}</td>
                    <td>Observasi & Wawancara Lapangan</td>
                </tr>
                <tr>
                    <td>{{ $audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->addDays(7)->format('d/m/Y') : '22/09/2025' }}</td>
                    <td>{{ $audit->tanggal_selesai ? \Carbon\Carbon::parse($audit->tanggal_selesai)->format('d/m/Y') : '26/09/2025' }}</td>
                    <td>Evaluasi Dokumen & Perumusan Laporan</td>
                </tr>
            </tbody>
        </table>

        <!-- 8. METODE AUDIT -->
        <h3 class="section-subtitle">8. METODE AUDIT</h3>
        <p>
            Audit Mutu Internal {{ $setting['app_name'] }} dilaksanakan dengan menggunakan metode audit yang sistematis,
            objektif, dan berbasis bukti (<em>evidence based audit</em>) melalui 6 tahapan:
        </p>
        <ol>
            <li>
                <strong>Pemeriksaan Dokumen (<em>Document Review</em>):</strong> Memeriksa kebijakan, manual, standar,
                SOP, RPS, laporan tridharma, notulen rapat, dan berita acara.
            </li>
            <li>
                <strong>Wawancara (<em>Interview</em>):</strong> Mengonfirmasi kesesuaian faktual bersama pimpinan unit,
                dosen, tendik, dan mahasiswa.
            </li>
            <li>
                <strong>Observasi Lapangan (<em>Field Observation</em>):</strong> Verifikasi fisik ruang kelas,
                laboratorium riset, bengkel praktik, perpustakaan, dan fasilitas penunjang.
            </li>
            <li>
                <strong>Verifikasi dan Analisis Temuan:</strong> Mengklasifikasikan temuan ke dalam Mayor, Minor, dan
                Observasi.
            </li>
            <li>
                <strong>Rapat Pembukaan dan Penutupan (<em>Opening & Closing Meeting</em>):</strong> Menyepakati tata
                cara dan memaparkan hasil audit sementara untuk klarifikasi auditee.
            </li>
            <li>
                <strong>Penyusunan dan Pelaporan Hasil Audit:</strong> Menyusun LAMI tertulis untuk ditindaklanjuti
                dalam bentuk RTK oleh masing-masing unit.
            </li>
        </ol>

        <!-- 9. TEMUAN AUDIT (31 STANDAR) -->
        <h3 class="section-subtitle">9. TEMUAN AUDIT</h3>
        <p>
            Pelaksanaan Audit Mutu Internal (AMI) {{ $setting['app_name'] }} Tahun Akademik
            {{ $audit->periode?->tahun ? $audit->periode->tahun . '–' . ($audit->periode->tahun + 1) : '2024–2025' }}
            menghasilkan temuan terhadap pelaksanaan 31 Standar Mutu Pendidikan Tinggi:
        </p>

        <!-- STANDAR PENDIDIKAN -->
        <h4 style="font-weight: 800; font-size: 9pt; color: #274e13; margin-top: 10px; margin-bottom: 3px;">
            A. STANDAR PENDIDIKAN DAN PENGAJARAN (8 Standar)
        </h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 65px; text-align: center;">NOMOR</th>
                    <th style="width: 25%;">NAMA STANDAR</th>
                    <th style="width: 75px; text-align: center;">HASIL TEMUAN</th>
                    <th>CATATAN AUDITOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-01</td>
                    <td style="font-weight: bold;">Standar Kompetensi Lulusan</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>SKL sudah disusun dan disahkan, tetapi belum seluruh dosen memahami keterkaitannya dengan CPL
                        Prodi.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-02</td>
                    <td style="font-weight: bold;">Standar Isi Pembelajaran</td>
                    <td style="text-align: center;"><span class="badge-status badge-ok">OK</span></td>
                    <td>Kurikulum seluruh prodi sudah sesuai KKNI dan SN-Dikti, telah disahkan oleh Senat POLKA.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-03</td>
                    <td style="font-weight: bold;">Standar Proses Pembelajaran</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>RPS pada Prodi baru (MRI, BD, TREM) belum lengkap dan belum seluruhnya divalidasi.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-04</td>
                    <td style="font-weight: bold;">Standar Penilaian Pembelajaran</td>
                    <td style="text-align: center;"><span class="badge-status badge-ok">OK</span></td>
                    <td>Mekanisme penilaian telah sesuai pedoman akademik, namun belum semua dosen menyertakan rubrik
                        penilaian.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-05</td>
                    <td style="font-weight: bold;">Standar Dosen & Tendik</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Sebagian dosen Prodi baru belum memiliki NIDN dan belum ikut pelatihan SPMI.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-06</td>
                    <td style="font-weight: bold;">Standar Sarana & Prasarana</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Fasilitas ruang belajar dan laboratorium belum seimbang dengan pertambahan mahasiswa baru.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-07</td>
                    <td style="font-weight: bold;">Standar Pengelolaan</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Beberapa pedoman pembelajaran belum difinalisasi dan belum disahkan melalui SK Direktur.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-08</td>
                    <td style="font-weight: bold;">Standar Pembiayaan</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Belum tersedia dokumen lengkap tentang pengalokasian, pelaporan, dan pertanggungjawaban biaya
                        pembelajaran.</td>
                </tr>
            </tbody>
        </table>

        <!-- STANDAR PENELITIAN -->
        <h4 style="font-weight: 800; font-size: 9pt; color: #274e13; margin-top: 10px; margin-bottom: 3px;">
            B. STANDAR PENELITIAN (8 Standar)
        </h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 65px; text-align: center;">NOMOR</th>
                    <th style="width: 25%;">NAMA STANDAR</th>
                    <th style="width: 75px; text-align: center;">HASIL TEMUAN</th>
                    <th>CATATAN AUDITOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-09</td>
                    <td style="font-weight: bold;">Standar Hasil Penelitian</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Program Studi baru belum memiliki publikasi internal atau jurnal penelitian aktif.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-10</td>
                    <td style="font-weight: bold;">Standar Isi Penelitian</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Dosen baru belum memahami pedoman isi penelitian sesuai standar POLKA.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-11</td>
                    <td style="font-weight: bold;">Standar Proses Penelitian</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Mekanisme penyusunan proposal dan laporan penelitian belum terintegrasi sistem digital.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-12</td>
                    <td style="font-weight: bold;">Standar Penilaian Riset</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Evaluasi hasil penelitian belum dilakukan secara berkala oleh LPPM.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-13</td>
                    <td style="font-weight: bold;">Standar Peneliti</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Perlu peningkatan pelatihan metodologi penelitian bagi dosen tetap baru.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-14</td>
                    <td style="font-weight: bold;">Standar Sarpras Riset</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Fasilitas laboratorium riset masih terbatas untuk mendukung roadmap penelitian prodi.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-15</td>
                    <td style="font-weight: bold;">Standar Pengelolaan Riset</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>SOP penelitian sudah tersedia tetapi belum semua prodi menerapkannya secara konsisten.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-16</td>
                    <td style="font-weight: bold;">Standar Pembiayaan Riset</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Dana penelitian sebagian besar masih mengandalkan hibah eksternal; dana internal belum
                        dialokasikan rutin.</td>
                </tr>
            </tbody>
        </table>

        <!-- STANDAR PKM -->
        <h4 style="font-weight: 800; font-size: 9pt; color: #274e13; margin-top: 10px; margin-bottom: 3px;">
            C. STANDAR PENGABDIAN KEPADA MASYARAKAT (8 Standar)
        </h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 65px; text-align: center;">NOMOR</th>
                    <th style="width: 25%;">NAMA STANDAR</th>
                    <th style="width: 75px; text-align: center;">HASIL TEMUAN</th>
                    <th>CATATAN AUDITOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-17</td>
                    <td style="font-weight: bold;">Standar Hasil PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Hasil PkM belum terhubung langsung dengan capaian pembelajaran lulusan.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-18</td>
                    <td style="font-weight: bold;">Standar Isi PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Belum ada PkM lintas disiplin dan belum melibatkan mahasiswa secara optimal.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-19</td>
                    <td style="font-weight: bold;">Standar Proses PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Proses perencanaan dan pelaporan kegiatan belum terdokumentasi lengkap.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-20</td>
                    <td style="font-weight: bold;">Standar Penilaian PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Belum tersedia instrumen penilaian hasil dan dampak kegiatan PkM.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-21</td>
                    <td style="font-weight: bold;">Standar Pelaksana PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Kegiatan PkM belum sepenuhnya sesuai bidang keahlian dosen pelaksana.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-22</td>
                    <td style="font-weight: bold;">Standar Sarpras PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Sarana penunjang PkM sangat terbatas, terutama untuk Prodi baru.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-23</td>
                    <td style="font-weight: bold;">Standar Pengelolaan PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>LPPM belum melakukan monitoring dan evaluasi PkM secara berkala.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-24</td>
                    <td style="font-weight: bold;">Standar Pembiayaan PkM</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Belum tersedia anggaran rutin untuk kegiatan PkM dan pendaftaran HAKI/paten hasil PkM.</td>
                </tr>
            </tbody>
        </table>

        <!-- STANDAR TAMBAHAN POLKA -->
        <h4 style="font-weight: 800; font-size: 9pt; color: #274e13; margin-top: 10px; margin-bottom: 3px;">
            D. STANDAR TAMBAHAN POLKA (7 Standar)
        </h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 65px; text-align: center;">NOMOR</th>
                    <th style="width: 25%;">NAMA STANDAR</th>
                    <th style="width: 75px; text-align: center;">HASIL TEMUAN</th>
                    <th>CATATAN AUDITOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-25</td>
                    <td style="font-weight: bold;">Standar Jati Diri POLKA</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Banyak sivitas akademika belum memahami nilai dan jati diri POLKA secara utuh.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-26</td>
                    <td style="font-weight: bold;">Standar AIK</td>
                    <td style="text-align: center;"><span class="badge-status badge-mayor">Mayor</span></td>
                    <td>Standar AIK belum terdefinisi dalam dokumen akademik dan belum diterapkan di prodi baru.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-27</td>
                    <td style="font-weight: bold;">Standar Tata Pamong</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Analisis jabatan, uraian tugas, dan laporan kinerja belum disahkan secara formal.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-28</td>
                    <td style="font-weight: bold;">Standar Kerjasama</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>MoU dan MoA telah banyak dibuat, tetapi tindak lanjut kegiatan kerja sama masih terbatas.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-29</td>
                    <td style="font-weight: bold;">Standar Kemahasiswaan</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Belum dilakukan pembakuan pedoman tracer study dan kegiatan pengembangan karakter.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-30</td>
                    <td style="font-weight: bold;">Standar SDM & Dosen</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Perencanaan jabatan akademik dosen belum terdokumentasi dan belum ada database pengembangan
                        karier.</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">STD-31</td>
                    <td style="font-weight: bold;">Standar Keuangan</td>
                    <td style="text-align: center;"><span class="badge-status badge-minor">Minor</span></td>
                    <td>Mekanisme perencanaan dan pelaporan keuangan belum baku dan audit eksternal belum rutin.</td>
                </tr>
            </tbody>
        </table>

        <p
            style="background: #f8fafc; border-left: 4px solid #8ea853; padding: 6px 10px; margin: 8px 0; font-size: 9pt;">
            <strong>Rekapitulasi Temuan:</strong> Ditemukan <strong>6 temuan mayor</strong>, <strong>23 temuan
                minor</strong>, dan <strong>2 kesesuaian penuh (OK)</strong>. Temuan mayor terutama terdapat pada bidang
            sarana-prasarana, pembiayaan penelitian dan PkM, serta penerapan Standar AIK.
        </p>

        <!-- 10. KESIMPULAN AUDIT -->
        <h3 class="section-subtitle">10. KESIMPULAN AUDIT</h3>
        <p>
            Berdasarkan hasil pelaksanaan Audit Mutu Internal (AMI) terhadap seluruh unit kerja di lingkungan
            {{ $setting['app_name'] }}, dapat disimpulkan:
        </p>
        <ol>
            <li>Pelaksanaan SPMI di {{ $setting['app_name'] }} telah berjalan baik berdasarkan 31 Standar SPMI POLKA
                (Versi Mei 2022) mengacu pada siklus PPEPP.</li>
            <li>Ditemukan 6 temuan mayor (sarpras, dana riset/PkM, AIK) dan 23 temuan minor (kurikulum/RPS, tata pamong,
                SDM, kemahasiswaan).</li>
            <li>Bidang Pendidikan umumnya sesuai standar, namun Prodi baru (MRI, BD, TREM) memerlukan penyempurnaan RPS
                dan fasilitas lab.</li>
            <li>Bidang Riset & PkM memerlukan penguatan alokasi dana internal dan fasilitasi publikasi jurnal.</li>
            <li>Standar AIK dan Jati Diri POLKA perlu segera disosialisasikan dan diintegrasikan ke seluruh prodi.</li>
            <li>Sarana dan prasarana laboratorium/bengkel praktik menjadi prioritas utama penambahan fasilitas.</li>
            <li>Hasil AMI 2024–2025 menjadi dasar penyusunan Rencana Tindakan Koreksi (RTK) untuk dilaporkan dalam RTM.
            </li>
            <li>Secara keseluruhan, {{ $setting['app_name'] }} menunjukkan arah perbaikan positif menuju perguruan
                tinggi vokasi unggul dan adaptif.</li>
        </ol>

        <!-- 11. REKOMENDASI AUDIT -->
        <h3 class="section-subtitle">11. REKOMENDASI AUDIT MUTU INTERNAL (AMI)</h3>

        <h4 style="font-weight: 800; font-size: 9pt; color: #111; margin-top: 8px; margin-bottom: 3px;">A. Rekomendasi
            Umum</h4>
        <ol>
            <li><strong>Budaya Mutu:</strong> Pelatihan rutin SPMI/PPEPP dan penunjukan PIC Mutu di setiap unit.</li>
            <li><strong>Digitalisasi Mutu:</strong> Penerapan Sistem Informasi Mutu Internal (SIMUT POLKA) terintegrasi
                PDDikti.</li>
            <li><strong>Sarpras:</strong> Penambahan ruang kuliah, laboratorium, dan bengkel praktik bagi Prodi baru.
            </li>
            <li><strong>Dana Riset/PkM:</strong> Alokasi dana internal rutin minimal satu kali per tahun untuk seluruh
                dosen.</li>
            <li><strong>Integrasi Renstra:</strong> Temuan AMI dan RTK wajib menjadi dasar penyusunan RKAT dan Renstra
                {{ $setting['app_name'] }}.
            </li>
        </ol>

        <h4 style="font-weight: 800; font-size: 9pt; color: #111; margin-top: 10px; margin-bottom: 3px;">B. Rekomendasi
            Spesifik per Bidang</h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 22%;">Bidang</th>
                    <th style="width: 28%;">Fokus Perbaikan</th>
                    <th>Rekomendasi Spesifik</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold;">Pendidikan</td>
                    <td>Mutu proses pembelajaran</td>
                    <td>Lengkapi RPS seluruh mata kuliah; validasi kurikulum tahunan; pelatihan pembelajaran industri.
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Penelitian</td>
                    <td>Penguatan riset & publikasi</td>
                    <td>Aktifkan jurnal internal terakreditasi; tingkatkan hibah riset; siapkan insentif publikasi
                        industri.</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">PkM</td>
                    <td>Relevansi & keberlanjutan</td>
                    <td>Integrasikan PkM dengan prodi; alokasikan dana tahunan; daftarkan HAKI hasil PkM unggulan.</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">SDM</td>
                    <td>Pengembangan dosen/tendik</td>
                    <td>Lengkapi NIDN dosen baru; pemetaan kompetensi; program capacity building SPMI tahunan.</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sarpras</td>
                    <td>Fasilitas prodi baru</td>
                    <td>Lengkapi laboratorium/bengkel praktik; tingkatkan utilitas ruang belajar dan bandwidth internet.
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tata Kelola</td>
                    <td>Efektivitas kemitraan</td>
                    <td>Evaluasi seluruh MoU/MoA; buat sistem data kemitraan; finalisasi SOP tata pamong unit.</td>
                </tr>
            </tbody>
        </table>

        <h4 style="font-weight: 800; font-size: 9pt; color: #111; margin-top: 10px; margin-bottom: 3px;">C. Rencana
            Tindak Lanjut (RTL)</h4>
        <ol>
            <li>Setiap unit auditee wajib menyusun Rencana Tindakan Koreksi (RTK) maksimal 30 hari kalender.</li>
            <li>UPT SPMI melakukan verifikasi bukti perbaikan dan melaporkannya kepada Direktur dalam RTM.</li>
        </ol>

        <!-- PENGESAHAN & TANDA TANGAN -->
        <div class="signature-section">
            <p style="text-align: center; margin-bottom: 15px; font-weight: 600;">
                Cilegon, {{ $audit->tanggal_selesai ? \Carbon\Carbon::parse($audit->tanggal_selesai)->translatedFormat('d F Y') : ($audit->tanggal_audit ? \Carbon\Carbon::parse($audit->tanggal_audit)->translatedFormat('d F Y') : '29 September 2025') }}
            </p>

            <table class="signature-table">
                <tr>
                    <td>
                        <strong>Unit Pelaksana Teknis<br>Sistem Penjaminan Mutu
                            Internal<br>{{ $setting['app_name'] }}</strong>
                        <div class="signature-space"></div>
                        <strong
                            style="text-decoration: underline; font-size: 10.5pt;">{{ $audit->ketuaAuditor?->name ?: 'Fadlinatin Naumi, M.Si.' }}</strong><br>
                        <span>Ketua UPT SPMI</span>
                    </td>
                    <td>
                        <strong>Menyetujui,<br>Majelis Wali Amanat<br>{{ $setting['app_name'] }}</strong>
                        <div class="signature-space"></div>
                        <strong style="text-decoration: underline; font-size: 10.5pt;">Hasan Basri, S.Mn.</strong><br>
                        <span>Ketua MWA</span>
                    </td>
                </tr>
            </table>

            <div class="tembusan">
                <p><strong>Untuk Perhatian dan Tindak Lanjut, Yth:</strong></p>
                <ol style="margin-top: 2px; padding-left: 20px;">
                    <li>Direktur {{ $setting['app_name'] }} untuk dikoordinasikan pada semua jajaran terkait.</li>
                </ol>
            </div>
        </div>

        <div class="print-running-footer">
            <div class="polka-footer-inner">
                <div class="footer-logo">
                    <img src="{{ $setting['logo'] }}" alt="Logo">
                </div>
                <div class="footer-bar">
                    <span class="footer-text">Audit Mutu Internal – {{ $audit->periode?->nama ?: ($audit->periode?->semester ? 'Semester ' . ucfirst($audit->periode->semester) . ' ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : 'Institusi ' . $setting['app_name']) }}</span>
                    <span class="footer-text" style="font-weight: 800;">{{ $setting['app_name'] }}</span>
                </div>
            </div>
        </div>
    </div>

</body>

</html>