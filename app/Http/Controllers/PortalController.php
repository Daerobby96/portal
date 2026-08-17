<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allModules = [
            // Sistem Utama
            [
                'id' => 'spmi',
                'category' => 'Sistem Utama',
                'name' => 'Penjaminan Mutu (SPMI)',
                'desc' => 'Sistem inti Penjaminan Mutu Internal. Kelola Dokumen Standar, Siklus PPEPP, Audit Mutu, dan Evaluasi.',
                'url' => route('dashboard'),
                'tag' => 'Core System',
                'icon' => 'bi-shield-check',
                'color' => 'indigo',
                'allowed' => true,
            ],
            [
                'id' => 'data_master',
                'category' => 'Sistem Utama',
                'name' => 'Data Master & Periode',
                'desc' => 'Pusat kelola Program Studi, Unit Kerja, dan Manajemen Periode aktif.',
                'url' => route('sdm.pegawai.index'),
                'tag' => 'Master Data',
                'icon' => 'bi-database-check',
                'color' => 'sky',
                'allowed' => $user->isSuperAdmin() || $user->isPimpinan(),
            ],

            // Modul Pendukung
            [
                'id' => 'data_akademik',
                'category' => 'Modul Pendukung',
                'name' => 'Data Akademik',
                'desc' => 'Pusat kelola Data Mahasiswa, Prestasi, dan Data Alumni terintegrasi.',
                'url' => route('mahasiswa.index'),
                'tag' => 'Akademik',
                'icon' => 'bi-person-video2',
                'color' => 'blue',
                'allowed' => $user->canAccessModule('data_akademik'),
            ],
            [
                'id' => 'system_admin',
                'category' => 'Modul Pendukung',
                'name' => 'System Admin',
                'desc' => 'Pusat konfigurasi aplikasi, manajemen pengguna (users), hak akses, dan log sistem.',
                'url' => route('settings.index'),
                'tag' => 'Administrasi',
                'icon' => 'bi-gear-fill',
                'color' => 'slate',
                'allowed' => $user->isSuperAdmin(),
            ],
            [
                'id' => 'tracer_study',
                'category' => 'Modul Pendukung',
                'name' => 'Tracer Study',
                'desc' => 'Pelacakan jejak alumni, pengelolaan data lulusan, masa tunggu kerja, dan survei kepuasan.',
                'url' => route('tracer-study.index'),
                'tag' => 'Alumni',
                'icon' => 'bi-mortarboard',
                'color' => 'emerald',
                'allowed' => $user->canAccessModule('tracer_study'),
            ],
            [
                'id' => 'rapat',
                'category' => 'Modul Pendukung',
                'name' => 'Manajemen Rapat',
                'desc' => 'Kelola jadwal rapat, notulensi, absensi kehadiran, dan tindak lanjut hasil rapat.',
                'url' => route('rapat.index'),
                'tag' => 'Administrasi',
                'icon' => 'bi-calendar2-check',
                'color' => 'amber',
                'allowed' => $user->canAccessModule('rapat'),
            ],
            [
                'id' => 'sdm',
                'category' => 'Modul Pendukung',
                'name' => 'SDM & Kepegawaian',
                'desc' => 'Manajemen Pegawai, presensi, cuti, lembur, penilaian kinerja, dan surat tugas.',
                'url' => route('sdm.index'),
                'tag' => 'SDM',
                'icon' => 'bi-people-fill',
                'color' => 'cyan',
                'allowed' => $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'surat',
                'category' => 'Modul Pendukung',
                'name' => 'Manajemen Surat',
                'desc' => 'Mengelola Surat Keluar dan Surat Masuk untuk berbagai keperluan administrasi.',
                'url' => route('surat-keputusan.index'),
                'tag' => 'Administrasi',
                'icon' => 'bi-file-earmark-text',
                'color' => 'violet',
                'allowed' => $user->canAccessModule('system_admin'),
            ],
            [
                'id' => 'kerjasama',
                'category' => 'Modul Pendukung',
                'name' => 'Kerjasama & Mitra',
                'desc' => 'Pendataan MoU, MoA, dan IA dengan mitra dalam negeri maupun luar negeri.',
                'url' => route('kerjasama.index'),
                'tag' => 'Kemitraan',
                'icon' => 'bi-diagram-3',
                'color' => 'pink',
                'allowed' => $user->canAccessModule('kerjasama'),
            ],
            [
                'id' => 'tridharma',
                'category' => 'Modul Pendukung',
                'name' => 'Tridharma Dosen',
                'desc' => 'Sistem rekapitulasi data Penelitian, Pengabdian Masyarakat (PkM), Publikasi Ilmiah, dan HKI.',
                'url' => route('penelitian.index'),
                'tag' => 'Dosen',
                'icon' => 'bi-journal-bookmark-fill',
                'color' => 'rose',
                'allowed' => $user->canAccessModule('tridharma'),
            ],
            [
                'id' => 'aset',
                'category' => 'Modul Pendukung',
                'name' => 'Manajemen Aset',
                'desc' => 'Pengelolaan inventaris aset, pemeliharaan, peminjaman aset, dan booking ruangan institusi.',
                'url' => route('aset.index'),
                'tag' => 'Sarana Prasarana',
                'icon' => 'bi-box-seam',
                'color' => 'teal',
                'allowed' => $user->hasAnyRole(['super_admin', 'staff']),
            ],
        ];

        $accessibleModules = array_values(array_filter($allModules, fn ($m) => $m['allowed']));

        return Inertia::render('Portal/Index', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'modules' => $accessibleModules,
            'appSettings' => [
                'appName' => config('app.name', 'ERP-POLKA'),
            ],
        ]);
    }
}
