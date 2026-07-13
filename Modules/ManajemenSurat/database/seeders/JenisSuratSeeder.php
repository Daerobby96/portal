<?php

namespace Modules\ManajemenSurat\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            // Surat Keluar
            [
                'kode' => 'SK-YYS',
                'nama' => 'Surat Keputusan Yayasan',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.sk_yayasan',
                'keterangan' => 'Surat Keputusan yang dikeluarkan oleh Yayasan',
                'is_active' => true,
            ],
            [
                'kode' => 'SK-PT',
                'nama' => 'Surat Keputusan Perguruan Tinggi',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.sk_pt',
                'keterangan' => 'Surat Keputusan yang dikeluarkan oleh Perguruan Tinggi',
                'is_active' => true,
            ],
            [
                'kode' => 'ST',
                'nama' => 'Surat Tugas',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.surat_tugas',
                'keterangan' => 'Surat Tugas untuk penugasan pegawai',
                'is_active' => true,
            ],
            [
                'kode' => 'SU',
                'nama' => 'Surat Undangan',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.surat_undangan',
                'keterangan' => 'Surat Undangan rapat atau acara',
                'is_active' => true,
            ],
            [
                'kode' => 'SKET',
                'nama' => 'Surat Keterangan',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.surat_keterangan',
                'keterangan' => 'Surat Keterangan untuk berbagai keperluan',
                'is_active' => true,
            ],
            [
                'kode' => 'SE',
                'nama' => 'Surat Edaran',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.surat_edaran',
                'keterangan' => 'Surat Edaran internal dan eksternal',
                'is_active' => true,
            ],
            [
                'kode' => 'SP',
                'nama' => 'Surat Pengantar',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.surat_pengantar',
                'keterangan' => 'Surat Pengantar dokumen',
                'is_active' => true,
            ],
            [
                'kode' => 'MOU',
                'nama' => 'Memorandum of Understanding',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.mou',
                'keterangan' => 'Nota Kesepahaman Kerjasama',
                'is_active' => true,
            ],
            [
                'kode' => 'MOA',
                'nama' => 'Memorandum of Agreement',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.moa',
                'keterangan' => 'Perjanjian Kerjasama',
                'is_active' => true,
            ],
            [
                'kode' => 'SREKOM',
                'nama' => 'Surat Rekomendasi',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.surat_rekomendasi',
                'keterangan' => 'Surat Rekomendasi untuk berbagai keperluan',
                'is_active' => true,
            ],
            
            // Surat Masuk
            [
                'kode' => 'SM-UMUM',
                'nama' => 'Surat Masuk Umum',
                'kategori' => 'masuk',
                'template_path' => null,
                'keterangan' => 'Surat masuk kategori umum',
                'is_active' => true,
            ],
            [
                'kode' => 'SM-UNDANGAN',
                'nama' => 'Surat Masuk Undangan',
                'kategori' => 'masuk',
                'template_path' => null,
                'keterangan' => 'Surat masuk berupa undangan',
                'is_active' => true,
            ],
            [
                'kode' => 'SM-PENTING',
                'nama' => 'Surat Masuk Penting',
                'kategori' => 'masuk',
                'template_path' => null,
                'keterangan' => 'Surat masuk dengan prioritas tinggi',
                'is_active' => true,
            ],
        ];

        foreach ($jenisSurat as $jenis) {
            DB::table('jenis_surat')->updateOrInsert(
                ['kode' => $jenis['kode']],
                array_merge($jenis, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
