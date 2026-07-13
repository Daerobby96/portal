<?php

namespace Modules\ManajemenSurat\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ManajemenSurat\Models\UnitPengelolaSurat;

class UnitPengelolaSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'nama' => 'Yayasan Pendidikan',
                'kode' => 'YYS',
                'jenis_institusi' => 'yayasan',
                'prefix_format' => '{nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}',
                'deskripsi' => 'Unit pengelola surat untuk tingkat yayasan',
                'is_active' => true,
            ],
            [
                'nama' => 'Perguruan Tinggi',
                'kode' => 'STMIK',
                'jenis_institusi' => 'perguruan_tinggi',
                'prefix_format' => '{nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}',
                'deskripsi' => 'Unit pengelola surat untuk tingkat perguruan tinggi',
                'is_active' => true,
            ],
            [
                'nama' => 'Program Studi Teknik Informatika',
                'kode' => 'TI',
                'jenis_institusi' => 'perguruan_tinggi',
                'prefix_format' => '{nomor}/{kode_jenis}/PRODI-{kode_unit}/{bulan}/{tahun}',
                'deskripsi' => 'Unit pengelola surat untuk Program Studi Teknik Informatika',
                'is_active' => true,
            ],
            [
                'nama' => 'Program Studi Sistem Informasi',
                'kode' => 'SI',
                'jenis_institusi' => 'perguruan_tinggi',
                'prefix_format' => '{nomor}/{kode_jenis}/PRODI-{kode_unit}/{bulan}/{tahun}',
                'deskripsi' => 'Unit pengelola surat untuk Program Studi Sistem Informasi',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            UnitPengelolaSurat::firstOrCreate(
                ['kode' => $unit['kode']],
                $unit
            );
        }

        $this->command->info('✓ Unit Pengelola Surat seeded successfully');
    }
}
