<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DataMaster\Models\Periode;
use Modules\Spmi\Models\Kuesioner;
use Modules\Spmi\Models\KuesionerPertanyaan;

class SpmiKuesionerSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::where('is_aktif', true)->first() ?? Periode::first();
        $periodeId = $periode?->id;

        // Bersihkan data kuesioner lama untuk memastikan seluruh 13 instrumen terbarui rapi
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE kuesioner_jawaban_details, kuesioner_jawabans, kuesioner_pertanyaans, kuesioners RESTART IDENTITY CASCADE;');

        // ═══════════════════════════════════════════════════════════════════
        // 1. EDOM: Evaluasi Pembelajaran & Dosen (15 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k1 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Evaluasi Pembelajaran & Dosen (EDOM)',
            'deskripsi'    => 'Instrumen komprehensif evaluasi proses belajar mengajar, kedisiplinan, penguasaan materi pedagogik, dan objektivitas evaluasi dosen di kelas.',
            'target_role'  => 'mahasiswa',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k1->id, [
            ['pertanyaan' => 'Dosen menyampaikan Rencana Pembelajaran Semester (RPS), kontrak perkuliahan, dan kriteria penilaian di awal semester.', 'kategori' => 'Perencanaan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen hadir tepat waktu dan memenuhi jumlah pertemuan perkuliahan minimal 16 sesi (termasuk UTS dan UAS).', 'kategori' => 'Kedisiplinan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen menguasai materi perkuliahan dengan mendalam dan mampu menjelaskan konsep secara terstruktur.', 'kategori' => 'Kompetensi Pedagogik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen mengaitkan materi perkuliahan dengan studi kasus nyata, riset mutakhir, dan perkembangan dunia industri.', 'kategori' => 'Kompetensi Pedagogik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen menggunakan metode pembelajaran interaktif (diskusi, problem-based learning, project-based learning) yang memicu keaktifan mahasiswa.', 'kategori' => 'Metode Pembelajaran', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen memanfaatkan media pembelajaran digital, LMS kampus, atau modul ajar yang mudah diakses.', 'kategori' => 'Media Pembelajaran', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen memberikan kesempatan bertanya dan melayani diskusi dengan sabar, jelas, dan santun.', 'kategori' => 'Interaksi Pembelajaran', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen memberikan tugas yang proporsional dan relevan dengan capaian pembelajaran mata kuliah (CPMK).', 'kategori' => 'Tugas & Latihan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen memberikan koreksi dan umpan balik (feedback) atas tugas/kuis mahasiswa sebelum pelaksanaan ujian akhir.', 'kategori' => 'Umpan Balik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Soal ujian (UTS/UAS) sesuai dengan materi yang diajarkan dan mencerminkan tingkat pemahaman yang diharapkan.', 'kategori' => 'Evaluasi Pembelajaran', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen melakukan penilaian secara adil, objektif, dan transparan sesuai kontrak perkuliahan.', 'kategori' => 'Evaluasi Pembelajaran', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen menyerahkan nilai tepat waktu sehingga mahasiswa dapat memantau perkembangan akademik dengan cepat.', 'kategori' => 'Transparansi Nilai', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen menunjukkan sikap profesional, beretika, adil, dan tidak diskriminatif terhadap seluruh mahasiswa.', 'kategori' => 'Kepribadian & Etika', 'tipe' => 'likert'],
            ['pertanyaan' => 'Secara keseluruhan, saya puas dengan kualitas proses pembelajaran yang diberikan oleh dosen pada mata kuliah ini.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tuliskan saran dan masukan konstruktif Anda untuk peningkatan kualitas pembelajaran dosen ini ke depan:', 'kategori' => 'Umpan Balik Kualitatif', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 2. Bimbingan Tugas Akhir / Skripsi (10 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k2 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Pembimbingan Tugas Akhir / Skripsi',
            'deskripsi'    => 'Penilaian mahasiswa tingkat akhir terhadap ketersediaan waktu, arahan metodologi, kecepatan review, dan suasana bimbingan dosen pembimbing tugas akhir.',
            'target_role'  => 'mahasiswa',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k2->id, [
            ['pertanyaan' => 'Kemudahan dalam menghubungi dan menyepakati jadwal bimbingan dengan dosen pembimbing.', 'kategori' => 'Aksesibilitas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Alokasi waktu yang cukup dan fokus yang diberikan dosen saat sesi bimbingan berlangsung.', 'kategori' => 'Alokasi Waktu', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kecepatan dan ketelitian dosen pembimbing dalam memeriksa draft naskah serta memberikan catatan perbaikan.', 'kategori' => 'Responsivitas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kejelasan arahan metodologi penelitian, perumusan masalah, dan kerangka teori yang diberikan dosen.', 'kategori' => 'Kualitas Arahan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Bimbingan dalam pengolahan data, analisis hasil, dan perumusan kesimpulan yang sistematis.', 'kategori' => 'Kualitas Arahan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen pembimbing memberikan motivasi dan mendorong penyelesaian tugas akhir tepat waktu.', 'kategori' => 'Motivasi & Suasana', 'tipe' => 'likert'],
            ['pertanyaan' => 'Suasana bimbingan yang kondusif, apresiatif, dan menghargai ide/gagasan orisinal mahasiswa.', 'kategori' => 'Suasana Akademik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Objektivitas dan keadilan penilaian saat pelaksanaan seminar proposal, hasil, maupun sidang meja hijau.', 'kategori' => 'Penilaian Sidang', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap proses bimbingan tugas akhir/skripsi.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saran Anda untuk perbaikan alur administrasi dan mekanisme pembimbingan tugas akhir di program studi:', 'kategori' => 'Saran Perbaikan', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 3. Pembimbingan Dosen PA / Wali Studi (10 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k3 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Pembimbingan Dosen PA (Wali Studi)',
            'deskripsi'    => 'Pengukuran efektivitas peran Dosen Pembimbing Akademik dalam mengawal rencana studi, kelancaran KRS, perkembangan IPK, dan konsultasi karir.',
            'target_role'  => 'mahasiswa',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k3->id, [
            ['pertanyaan' => 'Kemudahan berkomunikasi dan berkonsultasi dengan Dosen PA menjelang periode pengisian KRS.', 'kategori' => 'Aksesibilitas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketepatan waktu Dosen PA dalam menyetujui (approve) rencana studi KRS mahasiswa.', 'kategori' => 'Layanan KRS', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen PA memberikan arahan yang jelas mengenai pemilihan mata kuliah wajib, pilihan, dan konsentrasi keilmuan.', 'kategori' => 'Arahan Akademik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen PA memantau perkembangan indeks prestasi (IPS/IPK) dan memberikan evaluasi berkala.', 'kategori' => 'Monitoring Prestasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen PA tanggap dan memberikan solusi ketika mahasiswa mengalami kendala akademik atau beban studi.', 'kategori' => 'Pemecahan Masalah', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen PA memberikan motivasi, informasi beasiswa, kegiatan kompetisi, atau peluang program MBKM.', 'kategori' => 'Pengembangan Diri', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keramahan, empati, dan keterbukaan Dosen PA saat mendengarkan keluhan mahasiswa.', 'kategori' => 'Sikap & Komunikasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan waktu Dosen PA untuk konsultasi di luar jam perwalian formal.', 'kategori' => 'Ketersediaan Waktu', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap peran dan pendampingan Dosen Pembimbing Akademik.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Masukan Anda untuk meningkatkan efektivitas sistem bimbingan Dosen PA di kampus:', 'kategori' => 'Saran Perbaikan', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 4. Layanan BAAK & Administrasi Akademik (12 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k4 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Layanan BAAK & Administrasi Akademik',
            'deskripsi'    => 'Pengukuran kepuasan terhadap kejelasan SOP, keramahan staf, kecepatan penerbitan surat akademik, ijazah, dan keandalan sistem SIAKAD.',
            'target_role'  => 'mahasiswa',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k4->id, [
            ['pertanyaan' => 'Kemudahan dan kejelasan alur pengurusan surat izin kuliah, surat aktif, dan transkrip nilai sementara.', 'kategori' => 'Layanan Surat', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kecepatan dan ketepatan waktu staf BAAK dalam memproses berkas permohonan akademik mahasiswa.', 'kategori' => 'Kecepatan Layanan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keramahan, kesopanan, dan sikap solutif staf administrasi akademik saat melayani mahasiswa.', 'kategori' => 'Sikap Staf', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kejelasan informasi jadwal perkuliahan, kalender akademik, ujian, dan yudisium.', 'kategori' => 'Transparansi Informasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemudahan penggunaan, kestabilan, dan kelengkapan fitur Sistem Informasi Akademik (SIAKAD).', 'kategori' => 'Keandalan SIAKAD', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kecepatan tanggapan layanan helpdesk atau customer service akademik saat terjadi kendala sistem.', 'kategori' => 'Layanan Helpdesk', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kejelasan prosedur administrasi cuti akademik, pengunduran diri, maupun pindah program studi.', 'kategori' => 'Prosedur Khusus', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kenyamanan dan kerapian ruang loket pelayanan administrasi akademik di kampus.', 'kategori' => 'Fasilitas Layanan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Transparansi dan keakuratan data rekapitulasi nilai dan presensi mahasiswa di portal sistem.', 'kategori' => 'Akurasi Data', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemudahan proses validasi berkas pendaftaran tugas akhir, yudisium, dan wisuda.', 'kategori' => 'Layanan Kelulusan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap kinerja dan layanan administrasi akademik (BAAK).', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tuliskan kritik atau saran perbaikan untuk layanan administrasi akademik institusi:', 'kategori' => 'Saran Perbaikan', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 5. Layanan Perpustakaan & E-Resources (10 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k5 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Layanan Perpustakaan & E-Resources',
            'deskripsi'    => 'Evaluasi kelengkapan buku, jurnal elektronik terakreditasi, kenyamanan ruang baca, dan kualitas layanan pustakawan.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k5->id, [
            ['pertanyaan' => 'Kelengkapan dan kemutakhiran koleksi buku teks cetak, buku referensi, dan prosiding ilmiah.', 'kategori' => 'Koleksi Cetak', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan dan kemudahan akses jurnal elektronik (e-journal) nasional terakreditasi dan internasional.', 'kategori' => 'E-Resources', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan repository digital karya ilmiah mahasiswa (skripsi, tesis, laporan magang).', 'kategori' => 'Repository Institusi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemudahan pencarian katalog buku melalui sistem OPAC (Online Public Access Catalog).', 'kategori' => 'Sistem Katalog', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kecepatan dan kemudahan prosedur peminjaman, perpanjangan, serta pengembalian buku.', 'kategori' => 'Layanan Sirkulasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keramahan, kesigapan, dan bantuan pustakawan dalam membantu penelusuran referensi.', 'kategori' => 'Kualitas Pustakawan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kenyamanan ruang baca, ketersediaan pendingin ruangan (AC), pencahayaan, dan kebersihan perpustakaan.', 'kategori' => 'Kenyamanan Ruangan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan fasilitas komputer penelusuran, colokan listrik, dan kecepatan Wi-Fi di perpustakaan.', 'kategori' => 'Fasilitas Pendukung', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap layanan perpustakaan kampus.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Sebutkan judul buku, jurnal, atau fasilitas baru yang sangat Anda butuhkan di perpustakaan:', 'kategori' => 'Usulan Pengadaan', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 6. Fasilitas Laboratorium, Studio & Bengkel Kerja (12 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k6 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Laboratorium, Studio & Bengkel Kerja',
            'deskripsi'    => 'Pengukuran kecukupan alat, modul praktikum, kesiapan bahan praktikum, standar K3, dan bantuan laboran teknisi.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k6->id, [
            ['pertanyaan' => 'Kecukupan rasio jumlah peralatan praktikum dengan jumlah mahasiswa dalam satu sesi laboratorium.', 'kategori' => 'Kecukupan Alat', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kondisi peralatan praktikum/komputer berfungsi dengan baik, terawat, dan siap digunakan.', 'kategori' => 'Kesiapan Alat', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan bahan habis pakai praktikum dalam jumlah yang mencukupi untuk seluruh modul.', 'kategori' => 'Ketersediaan Bahan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kejelasan modul penuntun praktikum dan Standard Operating Procedure (SOP) penggunaan alat.', 'kategori' => 'Modul & SOP', 'tipe' => 'likert'],
            ['pertanyaan' => 'Penerapan standar Kesehatan dan Keselamatan Kerja (K3) serta ketersediaan APD dan P3K di lab.', 'kategori' => 'Keselamatan K3', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kebersihan, kenyamanan tata ruang, ventilasi udara, dan penerangan di dalam laboratorium/studio.', 'kategori' => 'Kenyamanan Ruang', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kesiapan dan keramahan laboran/teknisi dalam mendampingi dan mengatasi masalah teknis.', 'kategori' => 'Bantuan Laboran', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemutakhiran teknologi alat praktikum dan software yang digunakan sesuai dengan kebutuhan industri.', 'kategori' => 'Relevansi Industri', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketepatan jadwal pemakaian laboratorium sesuai dengan alokasi jadwal perkuliahan praktikum.', 'kategori' => 'Manajemen Jadwal', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemudahan perizinan peminjaman laboratorium/alat untuk penelitian tugas akhir atau kompetisi.', 'kategori' => 'Layanan Riset', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap fasilitas dan layanan laboratorium kampus.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saran Anda untuk pengadaan alat baru atau perbaikan fasilitas praktikum di laboratorium:', 'kategori' => 'Saran Perbaikan', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 7. Layanan Kemahasiswaan, Beasiswa & Karir (12 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k7 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Layanan Kemahasiswaan, Beasiswa & Karir',
            'deskripsi'    => 'Evaluasi keterbukaan informasi beasiswa, pembinaan organisasi kemahasiswaan (UKM), bimbingan konseling, dan pusat karir magang.',
            'target_role'  => 'mahasiswa',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k7->id, [
            ['pertanyaan' => 'Transparansi dan kejelasan informasi program beasiswa (KIP-K, prestasi, mitra industri).', 'kategori' => 'Layanan Beasiswa', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemudahan dan keadilan proses seleksi penerima beasiswa di lingkungan institusi.', 'kategori' => 'Layanan Beasiswa', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dukungan pendanaan dan perizinan untuk kegiatan Unit Kegiatan Mahasiswa (UKM) dan Himpunan Mahasiswa.', 'kategori' => 'Pembinaan UKM', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dukungan dan pendampingan kampus bagi mahasiswa yang mengikuti kompetisi/lomba tingkat nasional/internasional.', 'kategori' => 'Prestasi Mahasiswa', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan layanan bimbingan konseling psikologi bagi mahasiswa yang membutuhkan bantuan.', 'kategori' => 'Layanan Konseling', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kualitas program pelatihan soft skills, kepemimpinan, dan etika profesional mahasiswa.', 'kategori' => 'Soft Skills', 'tipe' => 'likert'],
            ['pertanyaan' => 'Efektivitas program pembinaan wirausaha mahasiswa (inkubator bisnis, pendanaan modal usaha).', 'kategori' => 'Kewirausahaan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Informasi lowongan kerja, magang industri, dan penyelenggaraan bursa kerja (Job Fair) kampus.', 'kategori' => 'Pusat Karir', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan fasilitas pelayanan kesehatan/klinik kampus yang memadai.', 'kategori' => 'Layanan Kesehatan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keramahan dan kesigapan staf bagian kemahasiswaan dalam merespons kebutuhan mahasiswa.', 'kategori' => 'Sikap Staf', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap layanan bidang kemahasiswaan dan karir.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Program atau fasilitas kemahasiswaan apa yang menurut Anda paling perlu dikembangkan?', 'kategori' => 'Saran Perbaikan', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 8. Exit Survey Calon Wisudawan (14 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k8 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Exit Survey Calon Wisudawan (Evaluasi Akhir Studi)',
            'deskripsi'    => 'Evaluasi menyeluruh oleh lulusan yang baru yudisium mengenai capaian kompetensi, relevansi kurikulum, dan kesiapan bersaing di pasar kerja.',
            'target_role'  => 'mahasiswa',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k8->id, [
            ['pertanyaan' => 'Kurikulum program studi memberikan bekal kompetensi teknis (hard skills) yang kuat dan relevan.', 'kategori' => 'Capaian Kurikulum', 'tipe' => 'likert'],
            ['pertanyaan' => 'Proses perkuliahan melatih kemampuan berpikir kritis, analitis, dan pemecahan masalah (problem solving).', 'kategori' => 'Keterampilan Berpikir', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kampus melatih kemampuan berkomunikasi efektif, presentasi, dan kerjasama dalam tim.', 'kategori' => 'Keterampilan Komunikasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Pengalaman praktikum dan tugas proyek nyata memberikan gambaran kerja yang nyata di industri.', 'kategori' => 'Pengalaman Praktis', 'tipe' => 'likert'],
            ['pertanyaan' => 'Program magang industri / PKL / MBKM yang difasilitasi prodi sangat bermanfaat untuk membangun jaringan profesional.', 'kategori' => 'Pengalaman MBKM', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dosen-dosen di program studi memiliki dedikasi tinggi dan inspiratif dalam membimbing mahasiswa.', 'kategori' => 'Kualitas Dosen', 'tipe' => 'likert'],
            ['pertanyaan' => 'Fasilitas sarana prasarana penunjang akademik telah memenuhi harapan saya selama menempuh studi.', 'kategori' => 'Sarana Kampus', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya merasa percaya diri untuk bersaing di dunia kerja profesional atau merintis wirausaha.', 'kategori' => 'Kesiapan Karir', 'tipe' => 'likert'],
            ['pertanyaan' => 'Waktu penyelesaian studi yang saya tempuh sesuai dengan target rencana awal kelulusan.', 'kategori' => 'Ketepatan Waktu', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya merasa bangga menjadi alumni dari perguruan tinggi dan program studi ini.', 'kategori' => 'Kebanggaan Almamater', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya bersedia merekomendasikan perguruan tinggi ini kepada rekan, keluarga, atau calon mahasiswa baru.', 'kategori' => 'Rekomendasi Kampus', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya bersedia berpartisipasi dalam ikatan alumni dan mengisi survei penelusuran lulusan (Tracer Study).', 'kategori' => 'Komitmen Alumni', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap seluruh pengalaman belajar selama masa kuliah.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tuliskan pesan, kesan, dan saran Anda untuk penyempurnaan kurikulum bagi adik-adik tingkat:', 'kategori' => 'Saran Kurikulum', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 9. Kepuasan Dosen terhadap Tata Kelola & Fasilitas Riset (15 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k9 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Dosen terhadap Tata Kelola & Fasilitas Riset',
            'deskripsi'    => 'Pengukuran kepuasan dosen terkait keadilan beban kerja, transparansi karir Jafung, pendanaan riset/PkM, insentif publikasi, dan kepemimpinan.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k9->id, [
            ['pertanyaan' => 'Keadilan dan proporsionalitas pembagian Beban Kerja Dosen (BKD) dalam pengajaran, penelitian, dan pengabdian.', 'kategori' => 'Beban Kerja Dosen', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dukungan dan kemudahan fasilitas institusi dalam proses pengusulan kenaikan Jabatan Fungsional (Jafung).', 'kategori' => 'Karir Akademik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan dan transparansi alokasi dana hibah penelitian dan pengabdian kepada masyarakat (PkM) internal.', 'kategori' => 'Dana Riset', 'tipe' => 'likert'],
            ['pertanyaan' => 'Pemberian insentif yang memadai dan tepat waktu untuk publikasi artikel jurnal bereputasi (SINTA/Scopus) dan HKI.', 'kategori' => 'Insentif Publikasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemudahan sistem pelaporan, review, dan administrasi hibah riset melalui LPPM.', 'kategori' => 'Layanan LPPM', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kelayakan laboratorium riset, peralatan uji, dan akses database jurnal ilmiah untuk kegiatan riset dosen.', 'kategori' => 'Fasilitas Riset', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kenyamanan ruang kerja dosen, fasilitas komputer, koneksi internet berkecepatan tinggi, dan ruang istirahat.', 'kategori' => 'Sarana Kerja', 'tipe' => 'likert'],
            ['pertanyaan' => 'Transparansi dan ketepatan waktu sistem penggajian, tunjangan jabatan, dan insentif kinerja.', 'kategori' => 'Remunerasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan jaminan kesehatan, asuransi ketenagakerjaan, dan fasilitas kesejahteraan dosen.', 'kategori' => 'Kesejahteraan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Dukungan institusi untuk studi lanjut S3, pelatihan sertifikasi kompetensi, dan keikutsertaan konferensi ilmiah.', 'kategori' => 'Studi Lanjut', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kebebasan mimbar akademik, kebebasan berekspresi ilmiah, dan otonomi keilmuan di kampus.', 'kategori' => 'Kebebasan Akademik', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keterbukaan pimpinan dalam menerima masukan dosen dan penerapan tata pamong yang partisipatif.', 'kategori' => 'Kepemimpinan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Efektivitas sistem penjaminan mutu internal (SPMI) dalam meningkatkan mutu akademik program studi.', 'kategori' => 'Budaya Mutu SPMI', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh sebagai dosen di perguruan tinggi ini.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Masukan Anda untuk peningkatan tata kelola SDM dosen dan iklim riset institusi:', 'kategori' => 'Saran Dosen', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 10. Kepuasan Tenaga Kependidikan terhadap Pengelolaan SDM (12 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k10 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Tenaga Kependidikan (Tendik)',
            'deskripsi'    => 'Evaluasi kepuasan staf tendik mengenai kejelasan uraian tugas, pelatihan kompetensi kerja, kesejahteraan, dan sarana kerja kantor.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k10->id, [
            ['pertanyaan' => 'Kejelasan uraian tugas (job description), pembagian tanggung jawab, dan Standar Operasional Prosedur (SOP) kerja.', 'kategori' => 'Uraian Tugas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kesesuaian penempatan kerja dengan kompetensi, latar belakang pendidikan, dan keahlian pegawai.', 'kategori' => 'Penempatan Kerja', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kesempatan yang adil untuk mengikuti pelatihan teknis, workshop, dan sertifikasi keahlian profesi.', 'kategori' => 'Pelatihan & Karir', 'tipe' => 'likert'],
            ['pertanyaan' => 'Transparansi dan objektivitas sistem penilaian kinerja pegawai (SKP) tahunan.', 'kategori' => 'Penilaian Kinerja', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kejelasan jalur karir, kesempatan rotasi, dan promosi jabatan bagi tenaga kependidikan.', 'kategori' => 'Promosi Jabatan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kelayakan gaji pokok, tunjangan jabatan, uang lembur, dan insentif kerja yang diterima.', 'kategori' => 'Kesejahteraan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketersediaan jaminan kesehatan (BPJS Kesehatan & Ketenagakerjaan) dan bantuan sosial pegawai.', 'kategori' => 'Kesejahteraan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kelengkapan fasilitas kerja (komputer, printer, software resmi, alat tulis kantor, ruang kerja ber-AC).', 'kategori' => 'Sarana Kerja', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kerjasama dan komunikasi kerja yang harmonis antar rekan sejawat dan antar unit kerja.', 'kategori' => 'Kerjasama Tim', 'tipe' => 'likert'],
            ['pertanyaan' => 'Apresiasi dan perlakuan yang adil dari pimpinan unit kerja terhadap dedikasi staf tendik.', 'kategori' => 'Kepemimpinan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh sebagai tenaga kependidikan di institusi ini.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tuliskan usulan Anda untuk perbaikan manajemen SDM dan kenyamanan kerja staf tendik:', 'kategori' => 'Saran Tendik', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 11. Kepuasan Pengguna Lulusan / Industri (15 Butir - 7 Parameter Standar BAN-PT)
        // ═══════════════════════════════════════════════════════════════════
        $k11 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Pengguna Lulusan (Employer Survey)',
            'deskripsi'    => 'Instrumen baku akreditasi nasional BAN-PT/LAM untuk mengukur kepuasan dunia usaha, industri, dan instansi terhadap kinerja alumni di tempat kerja.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k11->id, [
            ['pertanyaan' => 'Integritas, etika profesional, kejujuran, dan moralitas alumni saat menjalankan tugas kerja.', 'kategori' => 'Parameter 1: Etika & Integritas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kedisiplinan, loyalitas, kepatuhan pada aturan perusahaan, dan tanggung jawab terhadap pekerjaan.', 'kategori' => 'Parameter 1: Etika & Integritas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keahlian pada bidang ilmu utama / penguasaan kompetensi teknis (hard skills) yang sesuai dengan posisi kerjanya.', 'kategori' => 'Parameter 2: Keahlian Bidang Ilmu', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kualitas, akurasi, dan ketepatan waktu dalam menyelesaikan tugas-tugas teknis pekerjaan.', 'kategori' => 'Parameter 2: Keahlian Bidang Ilmu', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemampuan berkomunikasi secara aktif dan pasif dalam bahasa Inggris atau bahasa internasional lainnya.', 'kategori' => 'Parameter 3: Kemampuan Bahasa Asing', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keterampilan penggunaan teknologi informasi, aplikasi komputer perkantoran, dan software spesifik industri.', 'kategori' => 'Parameter 4: Penggunaan TI', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemampuan beradaptasi dengan sistem digital, automasi, dan transformasi teknologi di perusahaan.', 'kategori' => 'Parameter 4: Penggunaan TI', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemampuan menyampaikan gagasan, presentasi, dan bernegosiasi secara lisan dengan jelas dan santun.', 'kategori' => 'Parameter 5: Komunikasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemampuan menyusun laporan kerja, korespondensi resmi, dan dokumentasi tertulis secara terstruktur.', 'kategori' => 'Parameter 5: Komunikasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemampuan bekerjasama dalam tim lintas divisi, menghargai perbedaan, dan berkontribusi aktif (teamwork).', 'kategori' => 'Parameter 6: Kerjasama Tim', 'tipe' => 'likert'],
            ['pertanyaan' => 'Potensi kepemimpinan (leadership), inisiatif, dan kemampuan mengambil keputusan di bawah pengawasan minimal.', 'kategori' => 'Parameter 6: Kerjasama Tim', 'tipe' => 'likert'],
            ['pertanyaan' => 'Semangat untuk terus belajar mandiri, meningkatkan kualifikasi diri, dan cepat menyerap hal baru.', 'kategori' => 'Parameter 7: Pengembangan Diri', 'tipe' => 'likert'],
            ['pertanyaan' => 'Ketahanan mental dalam menghadapi tekanan target kerja dan kemampuan menyelesaikan masalah (problem solving).', 'kategori' => 'Parameter 7: Pengembangan Diri', 'tipe' => 'likert'],
            ['pertanyaan' => 'Secara umum, instansi kami merasa puas dengan kinerja lulusan dan bersedia merekrut kembali alumni dari kampus ini.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saran dan rekomendasi Bapak/Ibu untuk penyempurnaan kurikulum agar kompetensi lulusan semakin relevan dengan kebutuhan industri saat ini:', 'kategori' => 'Saran Kurikulum Industri', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 12. Kepuasan Mitra Kerjasama & Program MBKM (12 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k12 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Kepuasan Mitra Kerjasama & Kolaborasi Industri',
            'deskripsi'    => 'Penilaian instansi mitra mengenai kemudahan birokrasi kerjasama, realisasi MoU/MoA, kinerja magang MBKM, dan riset terapan bersama.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k12->id, [
            ['pertanyaan' => 'Kemudahan proses inisiasi, penyusunan, dan penandatanganan naskah kerjasama (MoU / MoA / IA).', 'kategori' => 'Inisiasi Kerjasama', 'tipe' => 'likert'],
            ['pertanyaan' => 'Responsivitas, kejelasan koordinasi, dan keramahan unit pengelola kemitraan institusi.', 'kategori' => 'Koordinasi & Layanan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Realisasi program kerja bersama berjalan tepat waktu dan sesuai dengan klausul yang disepakati.', 'kategori' => 'Realisasi Program', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kesiapan, kedisiplinan, dan etos kerja mahasiswa yang melaksanakan program magang industri / PKL / MBKM.', 'kategori' => 'Kualitas Mahasiswa Magang', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kualitas dan kompetensi dosen pembimbing lapangan dalam mendampingi kegiatan kemitraan.', 'kategori' => 'Kualitas Dosen', 'tipe' => 'likert'],
            ['pertanyaan' => 'Mutu dan kemanfaatan hasil riset kolaborasi atau pengabdian masyarakat yang dilakukan bersama mitra.', 'kategori' => 'Hasil Riset & PkM', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kemanfaatan timbal balik (mutual benefit) yang dirasakan oleh instansi/perusahaan mitra.', 'kategori' => 'Kemanfaatan Bersama', 'tipe' => 'likert'],
            ['pertanyaan' => 'Transparansi dan ketertiban pelaporan kegiatan serta pertanggungjawaban program kerjasama.', 'kategori' => 'Akuntabilitas', 'tipe' => 'likert'],
            ['pertanyaan' => 'Keterbukaan kampus dalam mengadopsi masukan mitra untuk penyesuaian kurikulum praktis.', 'kategori' => 'Keselarasan Kurikulum', 'tipe' => 'likert'],
            ['pertanyaan' => 'Minat dan komitmen instansi/perusahaan Anda untuk memperpanjang dan memperluas kemitraan di masa mendatang.', 'kategori' => 'Keberlanjutan Kemitraan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat kepuasan menyeluruh terhadap kemitraan strategis dengan perguruan tinggi ini.', 'kategori' => 'Kepuasan Menyeluruh', 'tipe' => 'likert'],
            ['pertanyaan' => 'Peluang kolaborasi strategis atau program baru apa yang ingin Anda kembangkan bersama institusi kami ke depan?', 'kategori' => 'Peluang Kolaborasi Baru', 'tipe' => 'text'],
        ]);

        // ═══════════════════════════════════════════════════════════════════
        // 13. Survei Pemahaman Visi, Misi, Tujuan & Sasaran (VMTS) (10 Butir)
        // ═══════════════════════════════════════════════════════════════════
        $k13 = Kuesioner::create([
            'periode_id'   => $periodeId,
            'judul'        => 'Survei Pemahaman Visi, Misi, Tujuan & Sasaran (VMTS)',
            'deskripsi'    => 'Pengukuran efektivitas sosialisasi dan tingkat pemahaman seluruh pemangku kepentingan (Dosen, Tendik, Mahasiswa, Alumni, dan Mitra) terhadap VMTS institusi.',
            'target_role'  => 'all',
            'is_public'    => true,
            'status'       => 'aktif',
        ]);
        $this->seedPertanyaans($k13->id, [
            ['pertanyaan' => 'Kejelasan dan kemudahan akses media sosialisasi Visi, Misi, Tujuan, dan Sasaran (website resmi, media sosial, banner, buku pedoman akademik).', 'kategori' => 'Sosialisasi VMTS', 'tipe' => 'likert'],
            ['pertanyaan' => 'Institusi dan program studi secara konsisten mensosialisasikan Visi Misi pada acara orientasi, rapat kerja, dan perkuliahan.', 'kategori' => 'Intensitas Sosialisasi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya memahami dengan jelas Visi Institusi untuk menjadi perguruan tinggi unggul berdaya saing.', 'kategori' => 'Pemahaman Visi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya memahami Visi Keilmuan program studi dan profil lulusan yang ingin dihasilkan.', 'kategori' => 'Pemahaman Visi Keilmuan', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya memahami Misi institusi dalam penyelenggaraan tridharma perguruan tinggi dan tata kelola.', 'kategori' => 'Pemahaman Misi', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saya memahami Tujuan dan Sasaran Strategis mutu jangka pendek (1 tahun) dan jangka panjang (5 tahun).', 'kategori' => 'Pemahaman Sasaran Mutu', 'tipe' => 'likert'],
            ['pertanyaan' => 'Sivitas akademika dan pemangku kepentingan dilibatkan dalam proses perumusan maupun peninjauan berkala VMTS.', 'kategori' => 'Partisipasi Stakeholder', 'tipe' => 'likert'],
            ['pertanyaan' => 'Kegiatan akademik, program kerja unit, dan pembelajaran sehari-hari telah mencerminkan nilai-nilai VMTS institusi.', 'kategori' => 'Implementasi Harian', 'tipe' => 'likert'],
            ['pertanyaan' => 'Tingkat pemahaman menyeluruh terhadap arah pengembangan Visi Misi institusi.', 'kategori' => 'Tingkat Pemahaman', 'tipe' => 'likert'],
            ['pertanyaan' => 'Saran dan masukan Anda untuk memperkuat pemahaman dan pencapaian sasaran strategis Visi Misi perguruan tinggi:', 'kategori' => 'Saran VMTS', 'tipe' => 'text'],
        ]);
    }

    private function seedPertanyaans(int $kuesionerId, array $items): void
    {
        foreach ($items as $idx => $item) {
            KuesionerPertanyaan::create([
                'kuesioner_id' => $kuesionerId,
                'pertanyaan'   => $item['pertanyaan'],
                'kategori'     => $item['kategori'] ?? 'Umum',
                'tipe'         => $item['tipe'] ?? 'likert',
                'urutan'       => $idx + 1,
            ]);
        }
    }
}
