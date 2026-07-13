<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IkuResmi;
use Illuminate\Support\Facades\DB;

class IkuResmiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('iku_resmi')->delete();
        
        $ikuData = [
            [
                'nomor_iku' => 'IKU1',
                'nama' => 'Angka Efisiensi Edukasi PT (AEE PT)',
                'sifat' => 'WAJIB',
                'formula' => 'Rata-rata Tingkat Pencapaian AEE seluruh program aktif',
                'satuan' => '%',
                'deskripsi' => 'Mengukur efisiensi lulusan tepat waktu per program pendidikan dibandingkan AEE ideal',
                'sheet_name' => 'IKU1_AEE',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU2',
                'nama' => '% Lulusan D1-S1 bekerja/wirausaha/studi lanjut ≤1 tahun',
                'sifat' => 'WAJIB',
                'formula' => '(∑ nᵢ × kᵢ) / t × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase lulusan yang bekerja, wirausaha, atau melanjutkan studi dalam waktu maksimal 1 tahun setelah lulus',
                'sheet_name' => 'IKU2_Lulusan',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU3',
                'nama' => '% Mahasiswa D & S berkegiatan/berprestasi di luar prodi',
                'sifat' => 'WAJIB',
                'formula' => '(∑ nᵢ × kᵢ) / t × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase mahasiswa yang mengikuti pembelajaran luar kampus atau meraih prestasi di luar program studi',
                'sheet_name' => 'IKU3_Mhs',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU4',
                'nama' => '% Dosen rekognisi internasional / hasil riset diterapkan',
                'sifat' => 'PILIHAN',
                'formula' => '(Jumlah dosen rekognisi / Total dosen) × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase dosen yang memiliki rekognisi internasional atau hasil risetnya diterapkan',
                'sheet_name' => 'IKU4_Dosen',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU5',
                'nama' => '% Luaran hasil kerjasama PT–Industri/Lembaga',
                'sifat' => 'WAJIB',
                'formula' => '(Jumlah luaran / Total kerjasama) × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase kerjasama PT dengan industri/lembaga yang menghasilkan luaran konkret',
                'sheet_name' => 'IKU5_KS',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU6',
                'nama' => '% Publikasi bereputasi internasional (Scopus/WoS)',
                'sifat' => 'WAJIB PTN-BH',
                'formula' => '(∑ nᵢ × kᵢ) / t × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase publikasi terindeks Scopus/WoS dengan bobot berdasarkan kuartil',
                'sheet_name' => 'IKU6_Publikasi',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU7',
                'nama' => '% Keterlibatan PT dalam SDGs',
                'sifat' => 'WAJIB',
                'formula' => '(∑ Program SDG wajib + pilihan) / Total program × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase keterlibatan PT dalam program SDGs (SDG 1, 4, 17 wajib + 2 pilihan)',
                'sheet_name' => 'IKU7_SDGs',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU8',
                'nama' => '% SDM PT terlibat penyusunan kebijakan',
                'sifat' => 'PILIHAN',
                'formula' => '(Jumlah SDM terlibat / Total SDM) × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase SDM PT (dosen/peneliti/perekayasa) yang terlibat dalam penyusunan kebijakan',
                'sheet_name' => 'IKU8_SDM',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU9',
                'nama' => '% Pendapatan non-akademik (non-UKT)',
                'sifat' => 'WAJIB',
                'formula' => '(Total pendapatan non-mahasiswa / Total pendapatan PT) × 100%',
                'satuan' => '%',
                'deskripsi' => 'Persentase pendapatan PT yang berasal dari sumber non-UKT (riset, kerjasama, usaha, dll)',
                'sheet_name' => 'IKU9_Pendapatan',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU10',
                'nama' => 'Zona Integritas (WBK/WBBM) – jumlah unit',
                'sifat' => 'PILIHAN PTN',
                'formula' => 'Jumlah unit WBK + WBBM',
                'satuan' => 'Unit Kerja',
                'deskripsi' => 'Jumlah unit kerja yang mengajukan Zona Integritas baik WBK maupun WBBM',
                'sheet_name' => 'IKU10_ZI',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU11',
                'nama' => 'Hasil Audit LK / SAKIP / Integritas Akademik',
                'sifat' => 'PILIHAN',
                'formula' => 'Composite: Opini Audit, Nilai SAKIP, Pelanggaran, Pencegahan',
                'satuan' => 'Composite',
                'deskripsi' => 'Gabungan hasil audit laporan keuangan, SAKIP, integritas akademik, dan pencegahan (kekerasan, narkoba, korupsi)',
                'sheet_name' => 'IKU11_Audit',
                'is_aktif' => true,
            ],
            [
                'nomor_iku' => 'IKU12',
                'nama' => 'Ketersediaan Renstra Kesejahteraan Dosen',
                'sifat' => 'WAJIB',
                'formula' => '(Jumlah komponen terpenuhi / Total komponen) × 100%',
                'satuan' => '%',
                'deskripsi' => 'Kelengkapan dokumen Renstra/Rencana Induk SDM terkait kesejahteraan dosen (9 komponen)',
                'sheet_name' => 'IKU12_Renstra',
                'is_aktif' => true,
            ],
        ];
        
        foreach ($ikuData as $data) {
            IkuResmi::create($data);
        }
        
        $this->command->info('✅ Data IKU Kepmendikti 358/M/KEP/2025 berhasil di-seed!');
    }
}
