<?php

namespace Database\Seeders;

use Modules\Spmi\Models\Standar;
use Illuminate\Database\Seeder;

class SnDiktiStandarSeeder extends Seeder
{
    public function run(): void
    {
        $standars = [
            // ==========================================
            // BIDANG PENDIDIKAN (Permendikbud No 53 2023)
            // ==========================================
            [
                'kode'      => 'SN-DIKTI-PEND-01',
                'nama'      => 'Standar Kompetensi Lulusan',
                'deskripsi' => 'Standar kompetensi lulusan merupakan kriteria minimal tentang kesatuan kompetensi sikap, pengetahuan, dan keterampilan yang menunjukkan capaian pembelajaran lulusan dari program studi.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 1,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-02',
                'nama'      => 'Standar Isi Pembelajaran',
                'deskripsi' => 'Kriteria minimal tingkat kedalaman dan keluasan materi pembelajaran.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 2,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-03',
                'nama'      => 'Standar Proses Pembelajaran',
                'deskripsi' => 'Kriteria minimal tentang pelaksanaan pembelajaran pada program studi untuk memperoleh capaian pembelajaran lulusan.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 3,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-04',
                'nama'      => 'Standar Penilaian Pembelajaran',
                'deskripsi' => 'Kriteria minimal tentang penilaian proses dan hasil belajar mahasiswa dalam rangka pemenuhan capaian pembelajaran lulusan.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 4,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-05',
                'nama'      => 'Standar Dosen dan Tenaga Kependidikan',
                'deskripsi' => 'Kriteria minimal tentang kualifikasi dan kompetensi dosen dan tenaga kependidikan untuk menyelenggarakan pendidikan dalam rangka pemenuhan capaian pembelajaran lulusan.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 5,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-06',
                'nama'      => 'Standar Sarana dan Prasarana Pembelajaran',
                'deskripsi' => 'Kriteria minimal tentang sarana dan prasarana sesuai dengan kebutuhan isi dan proses pembelajaran.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 6,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-07',
                'nama'      => 'Standar Pengelolaan Pembelajaran',
                'deskripsi' => 'Kriteria minimal tentang perencanaan, pelaksanaan, pengendalian, pemantauan dan evaluasi, serta pelaporan kegiatan pembelajaran pada tingkat program studi.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 7,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PEND-08',
                'nama'      => 'Standar Pembiayaan Pembelajaran',
                'deskripsi' => 'Kriteria minimal tentang komponen dan besaran biaya investasi dan biaya operasional yang disusun dalam rangka pemenuhan capaian pembelajaran lulusan.',
                'bidang'    => 'pendidikan',
                'jenis'     => 'inti',
                'nomor'     => 8,
                'is_aktif'  => true,
            ],

            // ==========================================
            // BIDANG PENELITIAN
            // ==========================================
            [
                'kode'      => 'SN-DIKTI-LIT-01',
                'nama'      => 'Standar Hasil Penelitian',
                'deskripsi' => 'Kriteria minimal tentang mutu hasil penelitian.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 1,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-02',
                'nama'      => 'Standar Isi Penelitian',
                'deskripsi' => 'Kriteria minimal tentang kedalaman dan keluasan materi penelitian.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 2,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-03',
                'nama'      => 'Standar Proses Penelitian',
                'deskripsi' => 'Kriteria minimal tentang kegiatan penelitian yang terdiri atas perencanaan, pelaksanaan, dan pelaporan.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 3,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-04',
                'nama'      => 'Standar Penilaian Penelitian',
                'deskripsi' => 'Kriteria minimal tentang penilaian proses dan hasil penelitian.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 4,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-05',
                'nama'      => 'Standar Peneliti',
                'deskripsi' => 'Kriteria minimal tentang kemampuan peneliti untuk melaksanakan penelitian.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 5,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-06',
                'nama'      => 'Standar Sarana dan Prasarana Penelitian',
                'deskripsi' => 'Kriteria minimal tentang sarana dan prasarana yang diperlukan untuk menunjang kebutuhan isi dan proses penelitian dalam rangka memenuhi hasil penelitian.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 6,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-07',
                'nama'      => 'Standar Pengelolaan Penelitian',
                'deskripsi' => 'Kriteria minimal tentang perencanaan, pelaksanaan, pengendalian, pemantauan dan evaluasi, serta pelaporan kegiatan penelitian pada institusi.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 7,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-LIT-08',
                'nama'      => 'Standar Pendanaan dan Pembiayaan Penelitian',
                'deskripsi' => 'Kriteria minimal tentang sumber dan mekanisme pendanaan dan pembiayaan penelitian.',
                'bidang'    => 'penelitian',
                'jenis'     => 'inti',
                'nomor'     => 8,
                'is_aktif'  => true,
            ],

            // ==========================================
            // BIDANG PENGABDIAN KEPADA MASYARAKAT (PkM)
            // ==========================================
            [
                'kode'      => 'SN-DIKTI-PKM-01',
                'nama'      => 'Standar Hasil Pengabdian kepada Masyarakat',
                'deskripsi' => 'Kriteria minimal tentang mutu hasil pengabdian kepada masyarakat.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 1,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-02',
                'nama'      => 'Standar Isi Pengabdian kepada Masyarakat',
                'deskripsi' => 'Kriteria minimal tentang kedalaman dan keluasan materi pengabdian kepada masyarakat.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 2,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-03',
                'nama'      => 'Standar Proses Pengabdian kepada Masyarakat',
                'deskripsi' => 'Kriteria minimal tentang kegiatan pengabdian kepada masyarakat yang terdiri atas perencanaan, pelaksanaan, dan pelaporan.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 3,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-04',
                'nama'      => 'Standar Penilaian Pengabdian kepada Masyarakat',
                'deskripsi' => 'Kriteria minimal tentang penilaian proses dan hasil pengabdian kepada masyarakat.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 4,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-05',
                'nama'      => 'Standar Pelaksana Pengabdian kepada Masyarakat',
                'deskripsi' => 'Kriteria minimal kemampuan pelaksana untuk melaksanakan pengabdian kepada masyarakat.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 5,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-06',
                'nama'      => 'Standar Sarana dan Prasarana PkM',
                'deskripsi' => 'Kriteria minimal tentang sarana dan prasarana yang diperlukan untuk menunjang proses PkM dalam rangka memenuhi hasil PkM.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 6,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-07',
                'nama'      => 'Standar Pengelolaan Pengabdian kepada Masyarakat',
                'deskripsi' => 'Kriteria minimal tentang perencanaan, pelaksanaan, pengendalian, pemantauan dan evaluasi, serta pelaporan kegiatan PkM.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 7,
                'is_aktif'  => true,
            ],
            [
                'kode'      => 'SN-DIKTI-PKM-08',
                'nama'      => 'Standar Pendanaan dan Pembiayaan PkM',
                'deskripsi' => 'Kriteria minimal tentang sumber dan mekanisme pendanaan dan pembiayaan pengabdian kepada masyarakat.',
                'bidang'    => 'pkm',
                'jenis'     => 'inti',
                'nomor'     => 8,
                'is_aktif'  => true,
            ],
        ];

        foreach ($standars as $standar) {
            Standar::firstOrCreate(
                ['kode' => $standar['kode']],
                $standar
            );
        }
    }
}
