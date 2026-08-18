<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Spmi\Models\KategoriDokumen;
use Modules\Spmi\Models\Dokumen;
use Modules\Spmi\Models\Standar;
use App\Models\User;

class SpmiDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        // 1. Kategori Dokumen SPMI
        $kategoris = [
            [
                'nama'       => 'Buku 1: Kebijakan SPMI',
                'kode'       => 'KBJ',
                'warna'      => '#4F46E5', // Indigo
                'keterangan' => 'Dokumen arah, visi, misi, dan landasan hukum penjaminan mutu Politeknik Krakatau',
            ],
            [
                'nama'       => 'Buku 2: Manual SPMI',
                'kode'       => 'MNL',
                'warna'      => '#0284C7', // Sky
                'keterangan' => 'Panduan teknis pelaksanaan siklus PPEPP untuk setiap standar mutu',
            ],
            [
                'nama'       => 'Buku 3: Standar SPMI',
                'kode'       => 'STD',
                'warna'      => '#059669', // Emerald
                'keterangan' => 'Naskah 31 Standar Mutu Pendidikan, Penelitian, PKM, dan Tambahan Institusi',
            ],
            [
                'nama'       => 'Buku 4: Formulir & Instrumen SPMI',
                'kode'       => 'FRM',
                'warna'      => '#D97706', // Amber
                'keterangan' => 'Katalog lembar instrumen, checklist audit, logbook, rubrik, dan formulir rekam mutu',
            ],
            [
                'nama'       => 'Standar Operasional Prosedur (SOP)',
                'kode'       => 'SOP',
                'warna'      => '#7C3AED', // Purple
                'keterangan' => 'Prosedur operasional baku pelaksanaan tridharma dan tata kelola unit',
            ],
            [
                'nama'       => 'Laporan Audit & RTM',
                'kode'       => 'LAP',
                'warna'      => '#E11D48', // Rose
                'keterangan' => 'Dokumen hasil pelaksanaan AMI, berita acara RTM, dan laporan berkala mutu',
            ],
        ];

        $kategoriMap = [];
        foreach ($kategoris as $k) {
            $kat = KategoriDokumen::updateOrCreate(['kode' => $k['kode']], $k);
            $kategoriMap[$k['kode']] = $kat->id;
        }

        // Get Standar IDs by code
        $standars = Standar::pluck('id', 'kode');

        // 2. Daftar 25 Dokumen & Template Formulir SPMI Resmi Politeknik Krakatau
        $documents = [
            // --- 4 BUKU UTAMA SPMI ---
            [
                'kategori_id'     => $kategoriMap['KBJ'],
                'kode_dokumen'    => 'KBJ-POLKA-01',
                'judul'           => 'Buku 1: Kebijakan Sistem Penjaminan Mutu Internal (SPMI) Politeknik Krakatau',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-10',
                'status'          => 'approved',
                'keterangan'      => 'Memuat filosofi mutu, landasan hukum, struktur organisasi SPMI, dan strategi penjaminan mutu POLKA.',
                'standar_kode'    => 'D.3',
            ],
            [
                'kategori_id'     => $kategoriMap['MNL'],
                'kode_dokumen'    => 'MNL-POLKA-01',
                'judul'           => 'Buku 2: Manual Prosedur Pelaksanaan Siklus PPEPP SPMI',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-10',
                'status'          => 'approved',
                'keterangan'      => 'Panduan operasional penetapan, pelaksanaan, evaluasi, pengendalian, dan peningkatan standar mutu.',
                'standar_kode'    => 'D.3',
            ],
            [
                'kategori_id'     => $kategoriMap['STD'],
                'kode_dokumen'    => 'STD-POLKA-01',
                'judul'           => 'Buku 3: Dokumen 31 Standar Mutu SPMI Politeknik Krakatau',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-10',
                'status'          => 'approved',
                'keterangan'      => 'Memuat 24 Standar SN-Dikti dan 7 Standar Khusus Institusional beserta 156 butir indikator mutu.',
                'standar_kode'    => 'D.1',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-01',
                'judul'           => 'Buku 4: Pedoman & Katalog Formulir Mutu SPMI Politeknik Krakatau',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-10',
                'status'          => 'approved',
                'keterangan'      => 'Kompilasi master template instrumen rekam jejak, checklist audit, dan form survei mutu institusi.',
                'standar_kode'    => 'D.3',
            ],

            // --- PILAR 1: PENETAPAN STANDAR ---
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P1-01',
                'judul'           => 'Formulir Usulan Penyusunan & Pemutakhiran Standar Mutu Baru',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Template pengajuan perumusan standar mutu baru oleh unit kerja/prodi berdasarkan analisis kebutuhan.',
                'standar_kode'    => 'D.3',
            ],

            // --- PILAR 2: PELAKSANAAN PENDIDIKAN & PEMBELAJARAN ---
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-01',
                'judul'           => 'Formulir Review & Validasi Rencana Pembelajaran Semester (RPS OBE)',
                'unit_pemilik'    => 'Program Studi',
                'versi'           => '1.2',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Instrumen verifikasi keselarasan CPL, CPMK, metode penilaian, dan rubrik asesmen mata kuliah.',
                'standar_kode'    => 'A.2',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-02',
                'judul'           => 'Formulir Jurnal Perkuliahan & Presensi Kehadiran Dosen-Mahasiswa',
                'unit_pemilik'    => 'BAAK & Program Studi',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Lembar rekam tatap muka 16 pertemuan, kesesuaian silabus, dan daftar hadir mahasiswa.',
                'standar_kode'    => 'A.3',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-03',
                'judul'           => 'Formulir Logbook Bimbingan & Rubrik Sidang Proyek Akhir (PA/Skripsi)',
                'unit_pemilik'    => 'Program Studi',
                'versi'           => '1.1',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Rekam jejak bimbingan minimal 8 kali dan matriks penilaian kelayakan karya tugas akhir vokasi.',
                'standar_kode'    => 'A.4',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-04',
                'judul'           => 'Formulir Logbook Aktivitas & Evaluasi Magang Industri MBKM',
                'unit_pemilik'    => 'Pusat Karir & Program Studi',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Laporan mingguan mahasiswa magang industri dan lembar penilaian pembimbing lapangan industri.',
                'standar_kode'    => 'A.3',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-05',
                'judul'           => 'Formulir Berita Acara & Rekapitulasi Uji Sertifikasi Kompetensi (LSP)',
                'unit_pemilik'    => 'Lembaga Sertifikasi Profesi (LSP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Dokumen pembuktian capaian sertifikat kompetensi keahlian teknis bagi calon lulusan.',
                'standar_kode'    => 'A.1',
            ],

            // --- PILAR 2: PENELITIAN & PENGABDIAN MASYARAKAT ---
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-06',
                'judul'           => 'Formulir Review & Penilaian Proposal Riset Terapan Dosen',
                'unit_pemilik'    => 'Lembaga Riset & PKM (LPPM)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Rubrik desk evaluation proposal penelitian dosen pemula dan terapan berbasis industri.',
                'standar_kode'    => 'B.3',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P2-07',
                'judul'           => 'Formulir Laporan Kemajuan & Luaran Pengabdian kepada Masyarakat (PKM)',
                'unit_pemilik'    => 'Lembaga Riset & PKM (LPPM)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Format monitoring realisasi teknologi tepat guna dan dampak sosial ekonomi bagi masyarakat mitra.',
                'standar_kode'    => 'C.1',
            ],

            // --- PILAR 3: EVALUASI & SURVEI KEPUASAN ---
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P3-01',
                'judul'           => 'Formulir Evaluasi Dosen oleh Mahasiswa (EDOM Perkuliahan)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Instrumen 4 dimensi: Pedagogik, Profesional, Kepribadian, dan Sosial dosen pengampu.',
                'standar_kode'    => 'A.5',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P3-02',
                'judul'           => 'Formulir Kuesioner Kepuasan Layanan Akademik & Sarana Prasarana',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.5',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Survei kepuasan mahasiswa terhadap fasilitas laboratorium, internet, perpustakaan, dan administrasi.',
                'standar_kode'    => 'A.6',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P3-03',
                'judul'           => 'Formulir Kuesioner Kepuasan Dosen & Tenaga Kependidikan',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Pengukuran kepuasan SDM terhadap manajemen karir, remunerasi, dan tata kelola pimpinan.',
                'standar_kode'    => 'D.6',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P3-04',
                'judul'           => 'Formulir Kuesioner Pelacakan Alumni (Tracer Study Standar Dikti)',
                'unit_pemilik'    => 'Pusat Karir & Tracer Study',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Instrumen pelacakan masa tunggu kerja, pendapatan pertama, dan relevansi kurikulum bagi alumni.',
                'standar_kode'    => 'A.1',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P3-05',
                'judul'           => 'Formulir Survei Kepuasan Pengguna Lulusan (Employer Satisfaction)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Survei integritas, keahlian teknis, kemampuan bahasa asing, dan kerja sama tim alumni di industri.',
                'standar_kode'    => 'A.1',
            ],

            // --- PILAR 4: AUDIT MUTU INTERNAL (AMI) & PENGENDALIAN ---
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P4-01',
                'judul'           => 'Formulir Surat Tugas & Jadwal Pelaksanaan Audit Mutu Internal (AMI)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Penugasan resmi auditor internal dan matriks jadwal visitasi ke seluruh program studi/unit.',
                'standar_kode'    => 'D.3',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P4-02',
                'judul'           => 'Formulir Daftar Tilik (Audit Checklist) Desk Evaluation & Lapangan',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Kuesioner verifikasi bukti fisik dokumen dan wawancara kecukupan standar mutu SPMI.',
                'standar_kode'    => 'A.7',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P4-03',
                'judul'           => 'Formulir Lembar Temuan Audit (LTA / Ketidaksesuaian KTS & Observasi)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Pencatatan gap audit, bukti objektif temuan, klasifikasi mayor/minor, dan akar masalah.',
                'standar_kode'    => 'A.7',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P4-04',
                'judul'           => 'Formulir Permintaan Tindakan Koreksi (PTK / Corrective Action Request)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '2.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Komitmen rencana perbaikan, penanggung jawab aksi korektif, tenggat waktu, dan verifikasi auditor.',
                'standar_kode'    => 'A.7',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P4-05',
                'judul'           => 'Formulir Berita Acara Closing Meeting Audit Mutu Internal',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Pengesahan bersama hasil audit antara Lead Auditor dengan Kaprodi / Kepala Unit Auditi.',
                'standar_kode'    => 'D.3',
            ],

            // --- PILAR 5: RAPAT TINJAUAN MANAJEMEN & PENINGKATAN ---
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P5-01',
                'judul'           => 'Formulir Daftar Hadir & Notulen Rapat Tinjauan Manajemen (RTM)',
                'unit_pemilik'    => 'Direktorat & P4MP',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Dokumentasi kehadiran jajaran pimpinan institusi dan risalah pembahasan laporan hasil audit AMI.',
                'standar_kode'    => 'D.3',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P5-02',
                'judul'           => 'Formulir Matriks Keputusan & Rekomendasi Alokasi Sumber Daya RTM',
                'unit_pemilik'    => 'Direktorat & P4MP',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Instruksi pimpinan terkait alokasi anggaran, sarpras, dan kebijakan institusi tindak lanjut AMI.',
                'standar_kode'    => 'D.7',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P5-03',
                'judul'           => 'Formulir Usulan Peningkatan Standar Mutu (Kaizen Matrix Upgrade)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Form penetapan target baru yang dinaikkan untuk standar yang telah terlampaui pada siklus berjalan.',
                'standar_kode'    => 'D.1',
            ],
            [
                'kategori_id'     => $kategoriMap['FRM'],
                'kode_dokumen'    => 'FRM-POLKA-P5-04',
                'judul'           => 'Formulir Laporan Studi Banding Mutu & Analisis Gap (Benchmarking Report)',
                'unit_pemilik'    => 'Pusat Penjaminan Mutu (P4MP)',
                'versi'           => '1.0',
                'tanggal_terbit'  => '2026-01-15',
                'status'          => 'approved',
                'keterangan'      => 'Instrumen komparasi standar POLKA terhadap kampus rujukan dan rencana adopsi best practice.',
                'standar_kode'    => 'D.4',
            ],
        ];

        foreach ($documents as $doc) {
            $standarCode = $doc['standar_kode'] ?? null;
            unset($doc['standar_kode']);

            $doc['pembuat_id'] = $adminId;
            $doc['is_public']  = true;
            $doc['file_type']  = 'PDF/DOCX';
            $doc['file_size']  = rand(150, 850) * 1024; // Simulated template size
            $doc['download_count'] = rand(5, 45);

            if ($standarCode && isset($standars[$standarCode])) {
                $doc['standar_id'] = $standars[$standarCode];
            }

            Dokumen::updateOrCreate(['kode_dokumen' => $doc['kode_dokumen']], $doc);
        }
    }
}
