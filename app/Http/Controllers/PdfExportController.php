<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ManajemenRapat\Models\Rapat;
use Modules\Spmi\Models\Audit;
use Modules\Sdm\Models\Pegawai;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Modules\Tridharma\Models\Penelitian;
use Modules\Tridharma\Models\Pengabdian;
use Modules\Tridharma\Models\Publikasi;
use Modules\TracerStudy\Models\TracerStudy;

class PdfExportController extends Controller
{
    public function notulensiRapat($id)
    {
        $rapat = Rapat::with(['peserta.user', 'peserta.pegawai', 'agendas', 'tindakLanjuts.pic'])->findOrFail($id);
        $setting = [
            'app_name'    => Setting::get('app_name', 'POLITEKNIK KAMPUS'),
            'app_tagline' => Setting::get('app_tagline', 'Sistem Informasi Manajemen Terpadu'),
            'logo'        => Setting::get('logo'),
        ];

        return view('pdf.notulensi-rapat', compact('rapat', 'setting'));
    }

    public function beritaAcaraAmi($id)
    {
        $audit = Audit::with([
            'periode',
            'ketuaAuditor',
            'auditors',
            'temuans.checklist.indikator.standar',
        ])->findOrFail($id);

        $audit->setRelation('checklists', $audit->checklists()
            ->leftJoin('indikator_kinerjas', 'audit_checklists.indikator_id', '=', 'indikator_kinerjas.id')
            ->leftJoin('standars', 'indikator_kinerjas.standar_id', '=', 'standars.id')
            ->select('audit_checklists.*')
            ->with('indikator.standar')
            ->orderBy('standars.kode')
            ->orderByRaw("CASE WHEN indikator_kinerjas.tipe = 'IKU' THEN 1 WHEN indikator_kinerjas.tipe = 'IKT' THEN 2 ELSE 3 END")
            ->orderBy('indikator_kinerjas.kode')
            ->get()
        );

        $standars = \Modules\Spmi\Models\Standar::with(['indikators' => function ($q) {
            $q->where('is_aktif', true)->orderByRaw("CASE WHEN tipe = 'IKU' THEN 1 WHEN tipe = 'IKT' THEN 2 ELSE 3 END")->orderBy('kode');
        }])->orderBy('kode')->get();

        $units = [
            ['nama' => 'Direktorat Politeknik Krakatau', 'jenis' => 'Pimpinan Institusi', 'lingkup' => 'Kebijakan mutu, tata pamong, perencanaan strategis, akuntabilitas, dan pelaksanaan SPMI.'],
            ['nama' => 'Senat / Majelis Wali Amanat (MWA)', 'jenis' => 'Badan Normatif / Pengawas', 'lingkup' => 'Kebijakan akademik, persetujuan standar mutu, evaluasi kinerja institusi.'],
            ['nama' => 'Wakil Direktur Bidang Akademik', 'jenis' => 'Bidang Akademik', 'lingkup' => 'Pengelolaan kurikulum, pembelajaran, layanan akademik.'],
            ['nama' => 'Wakil Direktur Bidang Keuangan dan SDM', 'jenis' => 'Bidang Non-Akademik', 'lingkup' => 'Pengelolaan keuangan, SDM, sarana-prasarana, dan tata kelola administrasi.'],
            ['nama' => 'Wakil Direktur Bidang Kemahasiswaan dan Kerjasama Ekternal', 'jenis' => 'Bidang Kemahasiswaan & Kerjasama', 'lingkup' => 'Pengelolaan kemahasiswaan, kerja sama, magang industri, tracer study, hubungan dengan mitra eksternal.'],
            ['nama' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)', 'jenis' => 'Lembaga', 'lingkup' => 'Penelitian, publikasi ilmiah, PkM, pengelolaan dana dan hasil tridharma.'],
            ['nama' => 'UPT Sistem Penjaminan Mutu Internal (SPMI)', 'jenis' => 'Unit Penjaminan Mutu', 'lingkup' => 'Kebijakan mutu, pelaksanaan AMI, monitoring dan evaluasi mutu, tindak lanjut hasil audit.'],
            ['nama' => 'UPT Teknologi Informasi dan Pangkalan Data (TIPD)', 'jenis' => 'Unit Pendukung', 'lingkup' => 'Pengelolaan sistem informasi akademik, PDDikti, dan infrastruktur digital.'],
            ['nama' => 'UPT Perpustakaan dan Laboratorium Terpadu', 'jenis' => 'Unit Penunjang Akademik', 'lingkup' => 'Layanan literasi, fasilitas laboratorium, sarana praktikum, dan dukungan pembelajaran.'],
            ['nama' => 'Program Studi Teknik Mesin', 'jenis' => 'Program Studi', 'lingkup' => 'Kurikulum, pembelajaran, capaian lulusan, dosen, dan mahasiswa.'],
            ['nama' => 'Program Studi Teknik Listrik', 'jenis' => 'Program Studi', 'lingkup' => 'Pembelajaran, penelitian terapan, dan sertifikasi kompetensi.'],
            ['nama' => 'Program Teknologi Rekayasa Perangkat Lunak (TRPL)', 'jenis' => 'Program Studi', 'lingkup' => 'Pengembangan kurikulum berbasis industri, kerja sama magang, dan sarana lab.'],
            ['nama' => 'Program Studi Rekayasa Manufaktur Industri (MRI)', 'jenis' => 'Program Studi Baru', 'lingkup' => 'Standar pembelajaran, RPS, kesiapan sarana, SDM dosen.'],
            ['nama' => 'Program Studi Bisnis Digital (BD)', 'jenis' => 'Program Studi Baru', 'lingkup' => 'Pengelolaan kurikulum, kesiapan dosen, dan sarana pembelajaran.'],
            ['nama' => 'Program Studi Teknologi Rekayasa Elektronika Manufaktur (TREM)', 'jenis' => 'Program Studi Baru', 'lingkup' => 'Standar dosen, sarana prasarana, dan implementasi pembelajaran.'],
            ['nama' => 'Bagian Administrasi Umum & Keuangan (BAUK)', 'jenis' => 'Unit Administrasi', 'lingkup' => 'Pengelolaan anggaran, aset, pengadaan, pelaporan keuangan.'],
            ['nama' => 'Bagian Akademik, Kemahasiswaan, dan Alumni (BAKA)', 'jenis' => 'Unit Administrasi', 'lingkup' => 'Pelayanan akademik, kegiatan kemahasiswaan, dan tracer study alumni.'],
            ['nama' => 'Biro Sumber Daya Manusia (SDM)', 'jenis' => 'Unit Penunjang', 'lingkup' => 'Perencanaan jabatan dosen dan tendik, pengembangan kompetensi, rekrutmen.'],
            ['nama' => 'Pusat Karir dan Inkubator Bisnis (Career Center)', 'jenis' => 'Unit Penunjang', 'lingkup' => 'Penempatan kerja, kerja sama industri, dan pengembangan wirausaha mahasiswa.'],
        ];

        $logoSetting = Setting::get('logo');
        $logoUrl = null;

        if ($logoSetting) {
            $storagePath = storage_path('app/public/' . $logoSetting);
            if (file_exists($storagePath)) {
                $mime = mime_content_type($storagePath) ?: 'image/png';
                $logoUrl = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($storagePath));
            }
        }

        if (!$logoUrl) {
            $fallbackPath = public_path('images/polka-logo.png');
            if (file_exists($fallbackPath)) {
                $mime = mime_content_type($fallbackPath) ?: 'image/png';
                $logoUrl = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fallbackPath));
            } else {
                $logoUrl = asset('images/polka-logo.png');
            }
        }

        $setting = [
            'app_name'    => Setting::get('app_name', 'POLITEKNIK KRAKATAU'),
            'app_tagline' => Setting::get('app_tagline', 'Sistem Informasi Manajemen Terpadu'),
            'logo'        => $logoUrl,
        ];

        return view('pdf.berita-acara-ami', compact('audit', 'standars', 'units', 'setting'));
    }

    public function rekapAkreditasi()
    {
        $setting = [
            'app_name'    => Setting::get('app_name', 'POLITEKNIK KAMPUS'),
            'app_tagline' => Setting::get('app_tagline', 'Sistem Informasi Penjaminan Mutu Internal'),
            'logo'        => Setting::get('logo'),
        ];

        $data = [
            'sdm' => [
                'total_dosen'  => Pegawai::where('jenis_pegawai', 'Dosen')->count(),
                'dosen_aktif'  => Pegawai::where('jenis_pegawai', 'Dosen')->where('is_aktif', true)->count(),
                'dosen_tetap'  => Pegawai::where('jenis_pegawai', 'Dosen')->whereIn('status_kepegawaian', ['PNS', 'PPPK', 'Tetap Yayasan'])->count(),
                'tendik_total' => Pegawai::where('jenis_pegawai', '!=', 'Dosen')->count(),
            ],
            'akademik' => [
                'total_mhs'    => Mahasiswa::count(),
                'mhs_aktif'    => Mahasiswa::where('status', 'aktif')->count(),
                'lulusan'      => Mahasiswa::where('status', 'lulus')->count(),
                'avg_ipk'      => round(Mahasiswa::whereNotNull('ipk')->avg('ipk') ?? 0, 2),
                'tepat_waktu'  => Mahasiswa::where('status', 'lulus')->where('masa_studi_bulan', '<=', 48)->count(),
            ],
            'tridharma' => [
                'penelitian_total' => Penelitian::count(),
                'penelitian_dana'  => Penelitian::sum('jumlah_dana') ?? 0,
                'pengabdian_total' => Pengabdian::count(),
                'pengabdian_dana'  => Pengabdian::sum('jumlah_dana') ?? 0,
                'jurnal_scopus'    => Publikasi::where('jenis', 'ilike', '%Scopus%')->orWhere('jenis', 'ilike', '%Internasional%')->count(),
                'jurnal_sinta'     => Publikasi::whereNotNull('tingkat_sinta')->count(),
            ],
            'tracer' => [
                'total_responden'  => TracerStudy::count(),
                'bekerja'          => TracerStudy::where('status_kerja', 'ilike', 'Bekerja%')->count(),
                'wirausaha'        => TracerStudy::where('status_kerja', 'ilike', '%Wirausaha%')->count(),
                'avg_tunggu'       => round(TracerStudy::where('waktu_tunggu_bulan', '>', 0)->avg('waktu_tunggu_bulan') ?? 0, 1),
                'avg_gaji'         => round(TracerStudy::where('gaji', '>', 0)->avg('gaji') ?? 0),
            ]
        ];

        return view('pdf.rekap-akreditasi', compact('data', 'setting'));
    }

    public function laporanRtm($id = null)
    {
        // Try finding as Rapat, or Audit
        $rapat = null;
        $audit = null;

        if ($id) {
            $rapat = Rapat::with(['peserta.user', 'agendas', 'tindakLanjuts.pic'])->find($id);
            if (!$rapat) {
                $audit = Audit::with(['periode', 'ketuaAuditor', 'auditors', 'temuans.checklist.indikator.standar'])->find($id);
            }
        }

        if (!$rapat && !$audit) {
            $rapat = Rapat::where('jenis', 'RTM')->latest()->first();
            $audit = Audit::with(['periode', 'ketuaAuditor', 'auditors', 'temuans.checklist.indikator.standar'])->latest()->first();
        } elseif ($rapat && !$audit) {
            $audit = Audit::with(['periode', 'ketuaAuditor', 'auditors', 'temuans.checklist.indikator.standar'])->latest()->first();
        }

        // Convert logo to Base64
        $logoSetting = Setting::get('logo');
        $logoUrl = null;
        if ($logoSetting) {
            $cleanPath = ltrim(str_replace('/storage/', '', $logoSetting), '/');
            $fullDiskPath = storage_path('app/public/' . $cleanPath);
            if (file_exists($fullDiskPath)) {
                $type = pathinfo($fullDiskPath, PATHINFO_EXTENSION) ?: 'png';
                $data = file_get_contents($fullDiskPath);
                $logoUrl = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        if (!$logoUrl) {
            $publicEmblem = public_path('images/polka-logo.png');
            if (file_exists($publicEmblem)) {
                $logoUrl = 'data:image/png;base64,' . base64_encode(file_get_contents($publicEmblem));
            }
        }

        $setting = [
            'app_name'    => Setting::get('app_name', 'POLITEKNIK KRAKATAU'),
            'app_tagline' => Setting::get('app_tagline', 'Sistem Penjaminan Mutu Internal'),
            'logo'        => $logoUrl,
        ];

        return view('pdf.laporan-rtm', compact('rapat', 'audit', 'setting'));
    }

    public function laporanRtl($id = null)
    {
        $audit = null;
        if ($id) {
            $audit = Audit::with([
                'periode',
                'ketuaAuditor',
                'auditors',
                'temuans.checklist.indikator.standar',
            ])->find($id);
        }

        if (!$audit) {
            $audit = Audit::with([
                'periode',
                'ketuaAuditor',
                'auditors',
                'temuans.checklist.indikator.standar',
            ])->latest()->first();
        }

        $standars = \Modules\Spmi\Models\Standar::with(['indikators'])->orderBy('kode')->get();

        // Convert logo to Base64
        $logoSetting = Setting::get('logo');
        $logoUrl = null;
        if ($logoSetting) {
            $cleanPath = ltrim(str_replace('/storage/', '', $logoSetting), '/');
            $fullDiskPath = storage_path('app/public/' . $cleanPath);
            if (file_exists($fullDiskPath)) {
                $type = pathinfo($fullDiskPath, PATHINFO_EXTENSION) ?: 'png';
                $data = file_get_contents($fullDiskPath);
                $logoUrl = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        if (!$logoUrl) {
            $publicEmblem = public_path('images/polka-logo.png');
            if (file_exists($publicEmblem)) {
                $logoUrl = 'data:image/png;base64,' . base64_encode(file_get_contents($publicEmblem));
            }
        }

        $setting = [
            'app_name'    => Setting::get('app_name', 'POLITEKNIK KRAKATAU'),
            'app_tagline' => Setting::get('app_tagline', 'Sistem Penjaminan Mutu Internal'),
            'logo'        => $logoUrl,
        ];

        return view('pdf.laporan-rtl', compact('audit', 'standars', 'setting'));
    }
}
