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
            // ─── Sistem Utama (Core Systems) ────────────────────────────────
            [
                'id' => 'spmi',
                'category' => 'Sistem Utama',
                'name' => 'Penjaminan Mutu (SPMI PPEPP)',
                'desc' => 'Sistem inti Penjaminan Mutu Internal. Kelola Dokumen Standar, Siklus PPEPP, Audit Mutu Internal (AMI), Evaluasi & RTM.',
                'url' => route('dashboard'),
                'tag' => 'Core System',
                'icon' => 'bi-shield-check',
                'color' => 'indigo',
                'allowed' => true,
            ],
            [
                'id' => 'sdm',
                'category' => 'Sistem Utama',
                'name' => 'SDM & Data Master',
                'desc' => 'Pusat Kepegawaian Dosen & Tendik, Data Master Periode & Prodi, Presensi Harian, Cuti, Lembur, SKP, dan Surat Tugas Kedinasan.',
                'url' => route('sdm.index'),
                'tag' => 'Kepegawaian & Master',
                'icon' => 'bi-people-fill',
                'color' => 'purple',
                'allowed' => true,
            ],

            // ─── Modul Pendukung (Supporting Modules) ────────────────────────
            [
                'id' => 'data_akademik',
                'category' => 'Modul Pendukung',
                'name' => 'Data Akademik & Mahasiswa',
                'desc' => 'Pusat kelola Data Mahasiswa, Prestasi, dan integrasi pangkalan data tridharma.',
                'url' => route('mahasiswa.index'),
                'tag' => 'Akademik',
                'icon' => 'bi-mortarboard',
                'color' => 'blue',
                'allowed' => $user->canAccessModule('data_akademik') || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'tridharma',
                'category' => 'Modul Pendukung',
                'name' => 'Tridharma Dosen',
                'desc' => 'Sistem rekapitulasi data Penelitian, Pengabdian Masyarakat (PkM), Publikasi Jurnal, dan HKI/Paten Dosen.',
                'url' => route('penelitian.index'),
                'tag' => 'Tridharma',
                'icon' => 'bi-journal-bookmark-fill',
                'color' => 'rose',
                'allowed' => $user->canAccessModule('tridharma') || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'tracer_study',
                'category' => 'Modul Pendukung',
                'name' => 'Tracer Study & Alumni',
                'desc' => 'Pelacakan jejak alumni, masa tunggu kerja, sebaran lulusan, dan survei kepuasan pengguna.',
                'url' => route('tracer-study.index'),
                'tag' => 'Alumni',
                'icon' => 'bi-person-check-fill',
                'color' => 'emerald',
                'allowed' => $user->canAccessModule('tracer_study') || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'kerjasama',
                'category' => 'Modul Pendukung',
                'name' => 'Kerjasama & Kemitraan',
                'desc' => 'Pendataan MoU, MoA, Implementation Agreement (IA) mitra dalam & luar negeri, serta evaluasi kemitraan.',
                'url' => route('kerjasama.index'),
                'tag' => 'Kemitraan',
                'icon' => 'bi-diagram-3-fill',
                'color' => 'pink',
                'allowed' => $user->canAccessModule('kerjasama') || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'rapat',
                'category' => 'Modul Pendukung',
                'name' => 'Manajemen Rapat & Notulensi',
                'desc' => 'Jadwal rapat pimpinan/prodi, daftar hadir presensi, notulensi rapat, dan tindak lanjut hasil rapat.',
                'url' => route('rapat.index'),
                'tag' => 'Administrasi',
                'icon' => 'bi-calendar2-check-fill',
                'color' => 'amber',
                'allowed' => $user->canAccessModule('rapat') || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'surat',
                'category' => 'Modul Pendukung',
                'name' => 'Tata Kelola Persuratan',
                'desc' => 'Pencatatan Surat Masuk, Surat Keluar, Surat Keputusan (SK), dan lembar disposisi digital.',
                'url' => route('surat-masuk.index'),
                'tag' => 'Persuratan',
                'icon' => 'bi-file-earmark-text-fill',
                'color' => 'violet',
                'allowed' => $user->canAccessModule('system_admin') || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'aset',
                'category' => 'Modul Pendukung',
                'name' => 'Manajemen Aset & Sarpras',
                'desc' => 'Inventaris sarana & prasarana institusi, pemeliharaan aset, peminjaman barang, dan pemanfaatan ruangan.',
                'url' => route('aset.index'),
                'tag' => 'Sarpras',
                'icon' => 'bi-box-seam-fill',
                'color' => 'teal',
                'allowed' => $user->hasAnyRole(['super_admin', 'staff']) || $user->isSuperAdmin() || $user->isPimpinan(),
            ],
            [
                'id' => 'system_admin',
                'category' => 'Modul Pendukung',
                'name' => 'Pengaturan & System Admin',
                'desc' => 'Pusat konfigurasi institusi, manajemen akun pengguna (users), hak akses peran, dan log audit sistem.',
                'url' => route('settings.index'),
                'tag' => 'Sistem',
                'icon' => 'bi-gear-fill',
                'color' => 'slate',
                'allowed' => $user->isSuperAdmin(),
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
