<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DataMaster\Models\UnitKerja;
use Modules\DataMaster\Models\Jabatan;

class UnitKerjaJabatanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Standar Unit Kerja Kampus
        $unitKerjas = [
            ['kode' => 'DIREKTORAT', 'nama' => 'Direktorat & Pimpinan Kampus', 'tipe' => 'pimpinan', 'kepala_unit' => 'Direktur', 'lokasi' => 'Gedung Pusat Rektorat Lt. 2'],
            ['kode' => 'JUR-TI',     'nama' => 'Jurusan Teknologi Informasi', 'tipe' => 'jurusan', 'kepala_unit' => 'Ketua Jurusan TI', 'lokasi' => 'Gedung Terpadu A Lt. 1'],
            ['kode' => 'JUR-MEK',    'nama' => 'Jurusan Teknik Mesin', 'tipe' => 'jurusan', 'kepala_unit' => 'Ketua Jurusan Mesin', 'lokasi' => 'Gedung Workshop Lt. 1'],
            ['kode' => 'JUR-ELK',    'nama' => 'Jurusan Teknik Elektro', 'tipe' => 'jurusan', 'kepala_unit' => 'Ketua Jurusan Elektro', 'lokasi' => 'Gedung Terpadu B Lt. 2'],
            ['kode' => 'JUR-AKN',    'nama' => 'Jurusan Akuntansi & Bisnis', 'tipe' => 'jurusan', 'kepala_unit' => 'Ketua Jurusan Akuntansi', 'lokasi' => 'Gedung Kuliah Terpadu C'],
            ['kode' => 'BAAK',       'nama' => 'Biro Administrasi Akademik & Kemahasiswaan (BAAK)', 'tipe' => 'biro', 'kepala_unit' => 'Kepala BAAK', 'lokasi' => 'Gedung Pusat Lt. 1'],
            ['kode' => 'BAUK',       'nama' => 'Biro Administrasi Umum & Keuangan (BAUK)', 'tipe' => 'biro', 'kepala_unit' => 'Kepala BAUK', 'lokasi' => 'Gedung Pusat Lt. 1'],
            ['kode' => 'LPPM',       'nama' => 'Lembaga Penelitian & Pengabdian Masyarakat (LPPM)', 'tipe' => 'lembaga', 'kepala_unit' => 'Ketua LPPM', 'lokasi' => 'Gedung Riset Lt. 2'],
            ['kode' => 'SPMI',       'nama' => 'Pusat Penjaminan Mutu & Pengembangan Pendidikan (P4MP)', 'tipe' => 'lembaga', 'kepala_unit' => 'Kepala Pusat Mutu', 'lokasi' => 'Gedung Pusat Lt. 2'],
            ['kode' => 'UPT-TIK',    'nama' => 'UPT Teknologi Informasi & Komunikasi', 'tipe' => 'upt', 'kepala_unit' => 'Kepala UPT Komputer', 'lokasi' => 'Gedung Lab Komputer'],
            ['kode' => 'UPT-PERPUS', 'nama' => 'UPT Perpustakaan Terpadu', 'tipe' => 'upt', 'kepala_unit' => 'Kepala Perpustakaan', 'lokasi' => 'Gedung Perpustakaan Pusat'],
        ];

        foreach ($unitKerjas as $u) {
            \Illuminate\Support\Facades\DB::table('unit_kerjas')->updateOrInsert(
                ['kode' => $u['kode']],
                array_merge($u, ['is_aktif' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // 2. Data Standar Jabatan Kampus
        $jabatans = [
            ['kode' => 'DIR',        'nama' => 'Direktur Politeknik', 'kategori' => 'struktural', 'level_hirarki' => 1, 'tunjangan_dasar' => 10000000],
            ['kode' => 'WADIR_1',    'nama' => 'Wakil Direktur I (Bidang Akademik)', 'kategori' => 'struktural', 'level_hirarki' => 2, 'tunjangan_dasar' => 7500000],
            ['kode' => 'WADIR_2',    'nama' => 'Wakil Direktur II (Bidang Umum & Keuangan)', 'kategori' => 'struktural', 'level_hirarki' => 2, 'tunjangan_dasar' => 7500000],
            ['kode' => 'WADIR_3',    'nama' => 'Wakil Direktur III (Bidang Kemahasiswaan & Kerjasama)', 'kategori' => 'struktural', 'level_hirarki' => 2, 'tunjangan_dasar' => 7500000],
            ['kode' => 'KA_JURUSAN', 'nama' => 'Ketua Jurusan', 'kategori' => 'struktural', 'level_hirarki' => 3, 'tunjangan_dasar' => 5000000],
            ['kode' => 'KAPRODI',    'nama' => 'Koordinator Program Studi', 'kategori' => 'struktural', 'level_hirarki' => 3, 'tunjangan_dasar' => 4000000],
            ['kode' => 'KA_UPT',     'nama' => 'Kepala UPT / Pusat Lembaga', 'kategori' => 'struktural', 'level_hirarki' => 3, 'tunjangan_dasar' => 3500000],
            ['kode' => 'KA_BAGIAN',  'nama' => 'Kepala Bagian / Kasubag', 'kategori' => 'struktural', 'level_hirarki' => 4, 'tunjangan_dasar' => 2500000],
            ['kode' => 'DOSEN_GB',   'nama' => 'Guru Besar / Profesor', 'kategori' => 'fungsional_dosen', 'level_hirarki' => 4, 'tunjangan_dasar' => 6000000],
            ['kode' => 'DOSEN_LK',   'nama' => 'Dosen Lektor Kepala', 'kategori' => 'fungsional_dosen', 'level_hirarki' => 5, 'tunjangan_dasar' => 4500000],
            ['kode' => 'DOSEN_L',    'nama' => 'Dosen Lektor', 'kategori' => 'fungsional_dosen', 'level_hirarki' => 6, 'tunjangan_dasar' => 3000000],
            ['kode' => 'DOSEN_AA',   'nama' => 'Dosen Asisten Ahli', 'kategori' => 'fungsional_dosen', 'level_hirarki' => 7, 'tunjangan_dasar' => 2000000],
            ['kode' => 'DOSEN_PENG', 'nama' => 'Dosen Pengajar / Non-Jafung', 'kategori' => 'fungsional_dosen', 'level_hirarki' => 8, 'tunjangan_dasar' => 1000000],
            ['kode' => 'PRANATA_KOM','nama' => 'Pranata Komputer / IT Support', 'kategori' => 'fungsional_tendik', 'level_hirarki' => 8, 'tunjangan_dasar' => 1500000],
            ['kode' => 'LABORAN',    'nama' => 'Pranata Laboratorium Pendidikan (Laboran)', 'kategori' => 'fungsional_tendik', 'level_hirarki' => 8, 'tunjangan_dasar' => 1500000],
            ['kode' => 'PUSTAKAWAN', 'nama' => 'Pustakawan', 'kategori' => 'fungsional_tendik', 'level_hirarki' => 8, 'tunjangan_dasar' => 1500000],
            ['kode' => 'STAF_ADM',   'nama' => 'Staf Administrasi & Tata Usaha', 'kategori' => 'pelaksana', 'level_hirarki' => 9, 'tunjangan_dasar' => 500000],
        ];

        foreach ($jabatans as $j) {
            \Illuminate\Support\Facades\DB::table('jabatans')->updateOrInsert(
                ['kode' => $j['kode']],
                array_merge($j, ['is_aktif' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
