<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rapat Tinjauan Manajemen (RTM) - Institusi {{ $setting['app_name'] }}</title>
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
            font-size: 19pt;
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 6px 0;
            letter-spacing: 0.5px;
            line-height: 1.25;
        }

        .cover-title h2 {
            font-size: 15pt;
            font-weight: 800;
            color: #334155;
            margin: 0 0 12px 0;
        }

        .cover-title h3 {
            font-size: 12.5pt;
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
        h2.section-header {
            font-size: 13.5pt;
            font-weight: 900;
            text-align: center;
            color: #0f172a;
            margin: 0 0 18px 0;
            line-height: 1.35;
            border-bottom: 2px solid #8ea853;
            padding-bottom: 8px;
        }

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

        .tembusan {
            margin-top: 25px;
            font-size: 8.5pt;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <!-- Top Action Bar -->
    <div class="print-toolbar">
        <div>
            <strong>Laporan Rapat Tinjauan Manajemen (RTM) Institusi {{ $setting['app_name'] }}</strong>
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
                <h1>RAPAT TINJAUAN MANAJEMEN (RTM)</h1>
                <h2>INSTITUSI {{ $setting['app_name'] }}</h2>
                <h3>{{ strtoupper($audit?->periode?->nama ?: ($audit?->periode?->semester ? 'SEMESTER ' . strtoupper($audit->periode->semester) . ' T.A. ' . $audit->periode->tahun . '/' . ($audit->periode->tahun + 1) : 'TAHUN AJARAN 2024-2025')) }}</h3>
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
            Sebagai bagian dari siklus Penjaminan Mutu Internal (SPMI) {{ $setting['app_name'] }}, Rapat Tinjauan Manajemen (RTM) dilaksanakan untuk meninjau hasil Audit Mutu Internal (AMI) dan Rencana Tindak Lanjut (RTL) {{ $audit?->periode?->nama ?: 'Tahun Akademik 2024–2025' }}.
        </p>
        <p>
            RTM tahun ini diselenggarakan tidak semata-mata untuk meninjau hasil, melainkan juga untuk menetapkan redistribusi tanggung jawab implementasi RTL di antara unit-unit pelaksana dan pimpinan bidang.
        </p>
        <p>
            Langkah ini dilakukan agar pelaksanaan perbaikan mutu lebih cepat, terarah, dan memiliki jalur koordinasi yang jelas sejak awal siklus peningkatan mutu. RTM menjadi forum manajemen mutu tertinggi di tingkat institusi, di mana pimpinan, auditor, dan perwakilan unit kerja bersama-sama:
        </p>
        <ol>
            <li>Meninjau hasil audit mutu internal secara menyeluruh;</li>
            <li>Menyepakati prioritas tindak lanjut dan alokasi sumber daya;</li>
            <li>Menentukan pembagian tanggung jawab pelaksanaan antar unit dan bidang; dan</li>
            <li>Menetapkan langkah monitoring dan evaluasi pasca-audit secara berkala.</li>
        </ol>

        <h4 class="subchapter-title">B. TUJUAN RTM</h4>
        <ol>
            <li>Menetapkan arah kebijakan mutu berdasarkan hasil AMI dan RTL {{ $audit?->periode?->nama ?: 'Tahun Akademik 2024–2025' }}.</li>
            <li>Menyepakati redistribusi tanggung jawab pelaksanaan tindak lanjut hasil audit antar unit dan bidang kerja.</li>
            <li>Menetapkan mekanisme pengawasan dan pelaporan pelaksanaan RTL secara periodik.</li>
            <li>Menyusun rencana penguatan budaya mutu institusi untuk periode akademik berikutnya.</li>
        </ol>

        <h4 class="subchapter-title">C. WAKTU DAN PESERTA</h4>
        <p>
            <strong>Hari/Tanggal:</strong> {{ $rapat?->tanggal ? \Carbon\Carbon::parse($rapat->tanggal)->translatedFormat('l, d F Y') : ($audit?->closing_meeting ? \Carbon\Carbon::parse($audit->closing_meeting)->translatedFormat('l, d F Y') : 'Senin, 14 Oktober 2025') }}<br>
            <strong>Tempat:</strong> {{ $rapat?->tempat ?: 'Ruang Rapat Utama / Smart Room Gedung Rektorat ' . $setting['app_name'] }}
        </p>
        <p><strong>Daftar Unsur Peserta:</strong></p>
        <ol>
            <li>Direktur dan seluruh jajaran Wakil Direktur (Wadir I, Wadir II, Wadir III)</li>
            <li>Ketua Majelis Wali Amanat dan Ketua Komite Kerja</li>
            <li>Ketua dan Anggota Unit Pelaksana Teknis Sistem Penjaminan Mutu Internal (UPT SPMI)</li>
            <li>Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)</li>
            <li>Kepala UPT TIPD, UPT Perpustakaan, dan UPT Laboratorium/Bengkel</li>
            <li>Para Ketua Program Studi (Kaprodi)</li>
            <li>Kepala Bagian Keuangan, SDM, dan Administrasi Akademik</li>
        </ol>

        <!-- BAB II. HASIL TINJAUAN DAN ANALISIS MANAJEMEN -->
        <h3 class="chapter-title">BAB II. HASIL TINJAUAN DAN ANALISIS MANAJEMEN</h3>

        <h4 class="subchapter-title">A. GAMBARAN UMUM HASIL AMI DAN RTL</h4>
        <p>
            Hasil Audit Mutu Internal menunjukkan bahwa seluruh 31 standar SPMI {{ $setting['app_name'] }} (Versi Mei 2022) telah diimplementasikan dengan komitmen tinggi dari setiap unit kerja. Temuan audit terdiri atas:
        </p>
        <ol>
            <li><strong>6 Temuan Mayor:</strong> terutama pada bidang pemenuhan sarana-prasarana laboratorium prodi baru, Standar AIK, dan alokasi rutin dana penelitian/PkM internal;</li>
            <li><strong>23 Temuan Minor:</strong> terkait kelengkapan dokumen RPS kurikulum, SOP tata pamong unit, dan sinkronisasi data antar-unit; serta</li>
            <li><strong>Observasi:</strong> area potensial untuk peningkatan mutu jangka menengah.</li>
        </ol>
        <p>
            UPT SPMI telah menyusun dan mendistribusikan RTL, yang memuat 31 tindakan koreksi beserta jadwal dan penanggung jawab awal. RTM ini difokuskan untuk memastikan setiap tindak lanjut memiliki struktur koordinasi lintas-unit yang jelas sehingga pelaksanaan di lapangan dapat dideploy secara efektif.
        </p>

        <h4 class="subchapter-title">B. PRINSIP REDISTRIBUSI TANGGUNG JAWAB</h4>
        <ol>
            <li><strong>Direktur:</strong> Memberikan keputusan strategis terhadap rekomendasi yang memerlukan dukungan kebijakan atau sumber daya anggaran tambahan.</li>
            <li><strong>Wakil Direktur:</strong> Berperan sebagai koordinator pelaksanaan RTL sesuai lingkup bidang masing-masing.</li>
            <li><strong>Kepala Unit dan Kaprodi:</strong> Bertindak sebagai penanggung jawab operasional pelaksanaan tindakan perbaikan di tingkat unit kerja.</li>
            <li><strong>UPT SPMI:</strong> Berfungsi sebagai <em>monitoring center</em> yang mengawal implementasi, memverifikasi bukti tindak lanjut, dan menyiapkan laporan RTM berkala.</li>
        </ol>

        <h4 class="subchapter-title">C. DISTRIBUSI PELAKSANAAN TINDAK LANJUT</h4>
        <table class="polka-table">
            <thead>
                <tr>
                    <th style="width: 18%;">Bidang</th>
                    <th style="width: 13%;">Standar</th>
                    <th>Fokus Perbaikan / RTL</th>
                    <th style="width: 22%;">Penanggung Jawab</th>
                    <th style="width: 16%;">Koordinator</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold;">Pendidikan & Pengajaran</td>
                    <td style="text-align: center; font-weight: bold;">STD 01–08</td>
                    <td>Finalisasi RPS Prodi baru, validasi kurikulum tahunan, dan perbaikan pedoman pembelajaran vokasi.</td>
                    <td>Kaprodi MRI, BD, TREM, BAKA</td>
                    <td><strong>Wadir I</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Penelitian</td>
                    <td style="text-align: center; font-weight: bold;">STD 09–16</td>
                    <td>Penguatan kapasitas riset dosen, pembentukan jurnal internal terakreditasi, dan penyediaan dana riset internal.</td>
                    <td>Ketua LPPM</td>
                    <td><strong>Wadir II</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">PkM (Pengabdian Masyarakat)</td>
                    <td style="text-align: center; font-weight: bold;">STD 17–24</td>
                    <td>Integrasi PkM dengan CPL, dokumentasi hasil pengabdian industri, dan penganggaran pendaftaran HAKI/Paten.</td>
                    <td>LPPM, Kaprodi, UPT TIPD</td>
                    <td><strong>Wadir III</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">AIK & Jati Diri POLKA</td>
                    <td style="text-align: center; font-weight: bold;">STD 25–26</td>
                    <td>Penyusunan panduan AIK, sosialisasi nilai budaya mutu POLKA, dan implementasi modul AIK di seluruh prodi.</td>
                    <td>Tim AIK & Humas</td>
                    <td><strong>Direktur & Wadir III</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tata Pamong & SDM</td>
                    <td style="text-align: center; font-weight: bold;">STD 27–30</td>
                    <td>Finalisasi analisis jabatan, penguatan pelatihan auditor SPMI, pemetaan karier dosen (JJA), dan NIDN baru.</td>
                    <td>Bagian SDM & LPM</td>
                    <td><strong>Wadir II</strong></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Keuangan & Akuntabilitas</td>
                    <td style="text-align: center; font-weight: bold;">STD 31</td>
                    <td>Pelaksanaan audit eksternal akuntan publik dan pelaporan keuangan berbasis kinerja unit.</td>
                    <td>Bagian Keuangan</td>
                    <td><strong>Wadir II</strong></td>
                </tr>
            </tbody>
        </table>

        <h4 class="subchapter-title">D. STRATEGI DEPLOYMEN RTL</h4>
        <p>Agar RTL dapat dijalankan secara efektif dan berkelanjutan, RTM menetapkan 5 strategi implementasi:</p>
        <ol>
            <li><strong>Integrasi RTL ke dalam RKAT:</strong> Setiap tindakan koreksi yang membutuhkan dukungan dana dimasukkan ke dalam rencana kerja dan anggaran tahunan institusi.</li>
            <li><strong>Timeline Bersiklus:</strong> Pelaksanaan RTL dilakukan maksimal 6 bulan setelah RTM, dengan evaluasi berkala.</li>
            <li><strong>Monitoring dan Pelaporan:</strong> UPT SPMI menyusun laporan perkembangan per dua bulan untuk dilaporkan kepada Direksi.</li>
            <li><strong>Forum Evaluasi Parsial:</strong> Masing-masing Wadir melaksanakan <em>progress review</em> di bidangnya untuk memastikan keterlaksanaan RTL tepat waktu.</li>
            <li><strong>Integrasi SIMUT Digital:</strong> Semua laporan RTL dan bukti tindak lanjut diunggah ke Sistem Informasi Mutu terpadu agar dapat dipantau langsung lintas unit.</li>
        </ol>

        <!-- BAB III. KEPUTUSAN MANAJEMEN -->
        <h3 class="chapter-title">BAB III. KEPUTUSAN MANAJEMEN</h3>
        <ol>
            <li><strong>Pengesahan Dokumen RTL:</strong> Mengesahkan Dokumen Rencana Tindak Lanjut (RTL) sebagai acuan resmi tindakan perbaikan mutu di lingkungan {{ $setting['app_name'] }}.</li>
            <li><strong>Penetapan Koordinator RTL Bidang:</strong>
                <ul>
                    <li>Bidang Akademik & Pembelajaran &rarr; <strong>Wakil Direktur I</strong></li>
                    <li>Bidang Keuangan, Sarpras, & SDM &rarr; <strong>Wakil Direktur II</strong></li>
                    <li>Bidang Kemahasiswaan, Kemitraan, & AIK &rarr; <strong>Wakil Direktur III</strong></li>
                </ul>
            </li>
            <li><strong>Instruksi Pelaporan Unit:</strong> Seluruh unit auditee diinstruksikan untuk menyelesaikan perbaikan dan menyampaikan bukti dukung fisik ke UPT SPMI secara tertulis dan terunggah di sistem.</li>
            <li><strong>Penguatan Kapasitas SPMI:</strong> Pelaksanaan pelatihan berkala bagi auditor internal dan penanggung jawab mutu unit demi menyongsong siklus audit berikutnya.</li>
            <li><strong>Mandat Dashboard Monitoring:</strong> UPT SPMI diberi mandat untuk mengoperasikan Dashboard Monitoring RTL berbasis sistem informasi terintegrasi.</li>
        </ol>

        <!-- BAB IV. PENUTUP & PENGESAHAN -->
        <h3 class="chapter-title">BAB IV. PENUTUP</h3>
        <p>
            Rapat Tinjauan Manajemen (RTM) {{ $setting['app_name'] }} menjadi tonggak penting dalam memperkuat koordinasi pelaksanaan tindak lanjut hasil audit mutu internal.
        </p>
        <p>
            Melalui redistribusi tanggung jawab yang lebih jelas dan penerapan sistem monitoring berbasis data, diharapkan proses deployment RTL dapat berlangsung efektif, transparan, dan berorientasi pada peningkatan mutu berkelanjutan.
        </p>
        <p>
            RTM ini tidak hanya menjadi catatan rapat, tetapi menjadi dokumen kebijakan operasional mutu yang menghubungkan hasil audit, tindak lanjut, dan peningkatan standar menuju <strong>{{ $setting['app_name'] }} Unggul dan Adaptif terhadap Dunia Industri</strong>.
        </p>

        <!-- PENGESAHAN -->
        <div class="signature-section">
            <p style="text-align: center; margin-bottom: 15px; font-weight: 600;">
                Cilegon, {{ $rapat?->tanggal ? \Carbon\Carbon::parse($rapat->tanggal)->translatedFormat('d F Y') : ($audit?->closing_meeting ? \Carbon\Carbon::parse($audit->closing_meeting)->translatedFormat('d F Y') : '14 Oktober 2025') }}
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

            <div class="tembusan">
                <p><strong>Untuk Perhatian dan Tindak Lanjut, Yth:</strong></p>
                <ol style="margin-top: 2px; padding-left: 20px;">
                    <li>Direktur {{ $setting['app_name'] }}</li>
                    <li>Para Wakil Direktur (Wadir I, Wadir II, Wadir III)</li>
                    <li>Para Kepala Biro / Lembaga / UPT</li>
                    <li>Para Ketua Program Studi</li>
                </ol>
            </div>
        </div>

        <!-- RUNNING FOOTER -->
        <div class="print-running-footer">
            <div class="polka-footer-inner">
                <div class="footer-logo">
                    <img src="{{ $setting['logo'] }}" alt="Logo">
                </div>
                <div class="footer-bar">
                    <span class="footer-text">Rapat Tinjauan Manajemen – {{ $audit?->periode?->nama ?: 'Institusi ' . $setting['app_name'] . ' 2024 - 2025' }}</span>
                    <span class="footer-text" style="font-weight: 800;">{{ $setting['app_name'] }}</span>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
