<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DataMaster\Models\Periode;
use Modules\Spmi\Models\Standar;
use Modules\Spmi\Models\IndikatorKinerja;
use Modules\Spmi\Models\PeningkatanStandar;
use Modules\Spmi\Models\Benchmarking;

class SpmiPeningkatanSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::where('is_aktif', true)->first() ?? Periode::first();
        if (!$periode) return;

        $standars = Standar::all();
        $standarPendidikan = $standars->firstWhere('kategori', 'pendidikan') ?? $standars->first();
        $standarPenelitian = $standars->firstWhere('kategori', 'penelitian') ?? $standars->first();
        $standarPkM = $standars->firstWhere('kategori', 'pengabdian') ?? $standars->first();

        // 1. Seed Peningkatan Standar (Kaizen Upgrades)
        PeningkatanStandar::truncate();

        if ($standarPendidikan) {
            PeningkatanStandar::create([
                'periode_id'          => $periode->id,
                'standar_id'          => $standarPendidikan->id,
                'target_lama'         => 'Rata-rata IPK Lulusan >= 3.25',
                'capaian_saat_ini'    => '3.42 (Tercapai 105%)',
                'target_baru'         => 'Rata-rata IPK Lulusan >= 3.50 dengan 40% Cumlaude',
                'dasar_pertimbangan'  => 'Hasil Rapat Tinjauan Manajemen (RTM) Siklus Lalu: Target mutu pendidikan telah konsisten terlampaui selama 2 tahun berturut-turut.',
                'strategi_pencapaian' => 'Penguatan modul ajar interaktif, tutorial intensif, dan bimbingan akademik terstruktur.',
                'status'              => 'disetujui',
                'disetujui_oleh'      => 1,
                'tanggal_persetujuan' => now()->subDays(15),
                'catatan'             => 'Disahkan pada RTM Semester Gasal untuk diberlakukan pada siklus berikutnya.',
            ]);

            PeningkatanStandar::create([
                'periode_id'          => $periode->id,
                'standar_id'          => $standarPendidikan->id,
                'target_lama'         => 'Persentase Dosen Berkualifikasi S3 (Doktor) minimal 30%',
                'capaian_saat_ini'    => '38% (Tercapai 126%)',
                'target_baru'         => 'Persentase Dosen Berkualifikasi S3 (Doktor) minimal 50%',
                'dasar_pertimbangan'  => 'Rekomendasi Akreditasi LAM: Peningkatan kualifikasi dosen doktor untuk persiapan pembukaan program studi magister terapan.',
                'strategi_pencapaian' => 'Penyediaan beasiswa tugas belajar institusi dan kemitraan dengan universitas luar negeri.',
                'status'              => 'diterapkan',
                'disetujui_oleh'      => 1,
                'tanggal_persetujuan' => now()->subDays(30),
                'catatan'             => 'Telah dimasukkan ke dalam Rencana Strategis (Renstra) Mutu.',
            ]);
        }

        if ($standarPenelitian) {
            PeningkatanStandar::create([
                'periode_id'          => $periode->id,
                'standar_id'          => $standarPenelitian->id,
                'target_lama'         => 'Jumlah Publikasi Internasional Bereputasi (Scopus/WoS) 15 Artikel/Tahun',
                'capaian_saat_ini'    => '22 Artikel/Tahun (Tercapai 146%)',
                'target_baru'         => 'Jumlah Publikasi Internasional Bereputasi 35 Artikel/Tahun dengan Sitasi >= 100',
                'dasar_pertimbangan'  => 'Target IKU 5 Kemdiktisaintek: Optimalisasi hasil riset terapan dosen menjadi luaran bereputasi global.',
                'strategi_pencapaian' => 'Kenaikan insentif publikasi Q1/Q2 dan pendampingan *manuscript clinic* oleh LPPM.',
                'status'              => 'diajukan',
                'disetujui_oleh'      => null,
                'tanggal_persetujuan' => null,
                'catatan'             => 'Menunggu persetujuan final Senat Akademik dan Direktur.',
            ]);
        }

        // 2. Seed Benchmarking Mutu
        Benchmarking::truncate();

        Benchmarking::create([
            'periode_id'            => $periode->id,
            'nama_mitra'            => 'Politeknik Negeri Bandung (POLBAN)',
            'tingkat'               => 'Nasional',
            'bidang_standar'        => 'Standar Pembelajaran Berbasis Teaching Factory & Kurikulum OBE',
            'tanggal_kegiatan'      => now()->subMonths(2),
            'capaian_institusi'     => 'Implementasi Teaching Factory masih bersifat pilot project di 2 prodi.',
            'capaian_mitra'         => 'Seluruh 12 prodi telah terintegrasi Teaching Factory dengan omzet industri mandiri.',
            'gap_analisis'          => 'Kemitraan industri untuk serapan produk TEFA kita masih terbatas pada level regional.',
            'best_practice_diadopsi'=> 'SOP tata kelola TEFA mandiri, skema insentif teknisi lab, dan integrasi kurikulum blok industri.',
            'rencana_tindak_lanjut' => 'Penyusunan Pedoman TEFA Institusi dan piloting di 3 program studi baru pada TA 2026/2027.',
            'status'                => 'Diimplementasikan',
            'pic_nama'              => 'Dr. Ir. Hendra Gunawan, M.T. (Ketua LPM)',
        ]);

        Benchmarking::create([
            'periode_id'            => $periode->id,
            'nama_mitra'            => 'Universitas Gadjah Mada (UGM)',
            'tingkat'               => 'Nasional',
            'bidang_standar'        => 'Standar Penjaminan Mutu Internal (SPMI) Siklus PPEPP & Audit Berbasis Risiko',
            'tanggal_kegiatan'      => now()->subMonths(1),
            'capaian_institusi'     => 'Audit AMI masih berfokus pada kepatuhan checklist administratif dokumen.',
            'capaian_mitra'         => 'Audit Mutu Internal telah berbasis asesmen risiko (Risk-Based Internal Audit) & KPI terbobot.',
            'gap_analisis'          => 'Auditor kita belum seluruhnya tersertifikasi Lead Auditor ISO 21001:2018.',
            'best_practice_diadopsi'=> 'Instrumen AMI berbasis matriks risiko, scoring otomatis IKU, dan integrasi digital PTK online.',
            'rencana_tindak_lanjut' => 'Pelatihan sertifikasi 20 Auditor Mutu Internal bersertifikat BNSP / ISO 21001 pada kuartal depan.',
            'status'                => 'Terlaksana',
            'pic_nama'              => 'Dewi Ratnasari, M.Kom. (Sekretaris SPMI)',
        ]);

        Benchmarking::create([
            'periode_id'            => $periode->id,
            'nama_mitra'            => 'Nanyang Polytechnic (NYP Singapore)',
            'tingkat'               => 'Internasional',
            'bidang_standar'        => 'Standar Kemitraan Industri Global & Applied Research Hub',
            'tanggal_kegiatan'      => now()->addMonths(1),
            'capaian_institusi'     => 'Kerjasama internasional baru sebatas pertukaran mahasiswa tingkat ASEAN.',
            'capaian_mitra'         => 'Memiliki Applied Research Group bersama multinasional corp (Apple, Bosch, Siemens).',
            'gap_analisis'          => 'Perlunya inkubator teknologi terapan bersama mitra multinasional.',
            'best_practice_diadopsi'=> 'Model Industry Joint Lab dan magang dosen di industri internasional.',
            'rencana_tindak_lanjut' => 'Inisiasi MoU Joint Applied Lab di bidang Otomasi & AI Terapan.',
            'status'                => 'Perencanaan',
            'pic_nama'              => 'Direktur & Tim Urusan Internasional',
        ]);
    }
}
