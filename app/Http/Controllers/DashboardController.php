<?php
namespace App\Http\Controllers;

use Modules\Spmi\Models\Audit;
use Modules\Spmi\Models\Dokumen;
use Modules\Spmi\Models\Monitoring;
use Modules\Spmi\Models\Temuan;
use Modules\DataMaster\Models\Periode;
use App\Models\User;
use Modules\Spmi\Models\Standar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $periode = Periode::aktif();
        $unit = $user->unit_kerja;

        // ─── Statistik ────────────────────────────────────────────────
        $stats = [
            'total_audit'         => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
                                        ->count(),
            'audit_selesai'       => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
                                        ->where('status', 'selesai')->count(),
            'audit_aktif'         => Audit::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
                                        ->where('status', 'aktif')->count(),
            'total_temuan'        => Temuan::whereHas('audit', function($q) use ($periode, $user, $unit) {
                                            $q->when($periode, fn($q2) => $q2->where('periode_id', $periode->id))
                                              ->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit));
                                        })->count(),
            'temuan_open'         => Temuan::where('status', 'open')
                                        ->whereHas('audit', fn($q) => $q->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit)))
                                        ->count(),
            'total_dokumen'       => Dokumen::where('status', 'approved')
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_pemilik', $unit))
                                        ->count(),
            'dokumen_kadaluarsa'  => Dokumen::where('tanggal_kadaluarsa', '<=', now()->addMonths(1))
                                        ->where('status', 'approved')
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_pemilik', $unit))
                                        ->count(),
            'total_monitoring'    => Monitoring::when($periode, fn($q) => $q->where('periode_id', $periode->id))
                                        ->when($user->isAuditee(), fn($q) => $q->where('unit_kerja', $unit))
                                        ->count(),
            'total_user'          => User::where('is_active', true)->count(),
        ];

        // ─── 10-Module Executive Overview ─────────────────────────────
        $executiveSummary = [
            'sdm' => [
                'total_pegawai' => \Modules\Sdm\Models\Pegawai::count(),
                'total_dosen'   => \Modules\Sdm\Models\Pegawai::where('jenis_pegawai', 'Dosen')->count(),
                'dosen_s3'      => \Modules\Sdm\Models\Pegawai::where('jenis_pegawai', 'Dosen')->where('is_aktif', true)->count(),
            ],
            'akademik' => [
                'total_mahasiswa' => \App\Models\Mahasiswa::count(),
                'mhs_aktif'       => \App\Models\Mahasiswa::where('status', 'aktif')->count(),
                'avg_ipk'         => round(\App\Models\Mahasiswa::whereNotNull('ipk')->avg('ipk') ?? 0, 2),
                'total_prestasi'  => \App\Models\Prestasi::count(),
            ],
            'tridharma' => [
                'penelitian' => \Modules\Tridharma\Models\Penelitian::count(),
                'pengabdian' => \Modules\Tridharma\Models\Pengabdian::count(),
                'publikasi'  => \Modules\Tridharma\Models\Publikasi::count(),
                'hki'        => \Modules\Tridharma\Models\Hki::count(),
            ],
            'tracer' => [
                'total_alumni'    => \Modules\TracerStudy\Models\TracerStudy::count(),
                'bekerja_persen'  => \Modules\TracerStudy\Models\TracerStudy::count() > 0 
                    ? round((\Modules\TracerStudy\Models\TracerStudy::where('status_kerja', 'ilike', 'Bekerja%')->count() / \Modules\TracerStudy\Models\TracerStudy::count()) * 100) 
                    : 0,
                'avg_tunggu'      => round(\Modules\TracerStudy\Models\TracerStudy::where('waktu_tunggu_bulan', '>', 0)->avg('waktu_tunggu_bulan') ?? 0, 1),
            ],
            'sarpras' => [
                'total_aset'      => class_exists(\Modules\ManajemenAset\Models\Aset::class) ? \Modules\ManajemenAset\Models\Aset::count() : 0,
                'total_rapat'     => class_exists(\Modules\ManajemenRapat\Models\Rapat::class) ? \Modules\ManajemenRapat\Models\Rapat::count() : 0,
                'rapat_selesai'   => class_exists(\Modules\ManajemenRapat\Models\Rapat::class) ? \Modules\ManajemenRapat\Models\Rapat::where('status', 'selesai')->count() : 0,
            ],
            'persuratan' => [
                'surat_masuk'     => class_exists(\Modules\ManajemenSurat\Models\SuratMasuk::class) ? \Modules\ManajemenSurat\Models\SuratMasuk::count() : 0,
                'surat_keluar'    => class_exists(\Modules\ManajemenSurat\Models\SuratKeluar::class) ? \Modules\ManajemenSurat\Models\SuratKeluar::count() : 0,
                'disposisi'       => class_exists(\Modules\ManajemenSurat\Models\Disposisi::class) ? \Modules\ManajemenSurat\Models\Disposisi::where('status', 'Diproses')->count() : 0,
            ],
            'kerjasama' => [
                'total_mitra'     => class_exists(\Modules\Kerjasama\Models\Kerjasama::class) ? \Modules\Kerjasama\Models\Kerjasama::count() : 0,
                'mitra_aktif'     => class_exists(\Modules\Kerjasama\Models\Kerjasama::class) ? \Modules\Kerjasama\Models\Kerjasama::where('status', 'Aktif')->count() : 0,
            ],
        ];

        // ─── Status PPEPP ─────────────────────────────────────────────
        $ppeppStatus = [
            'penetapan'   => false,
            'pelaksanaan' => false,
            'evaluasi'    => false,
            'pengendalian'=> false,
            'peningkatan' => false,
        ];
        $ppeppDetails = [
            'penetapan' => 0,
            'pelaksanaan' => 0,
            'evaluasi' => 0,
            'pengendalian' => 0,
            'peningkatan' => 0,
            'overall' => 0,
            'is_loop_closed' => false,
        ];

        if ($periode) {
            $ppeppDetails = $periode->ppepp_progress;
            $ppeppStatus['penetapan']   = $ppeppDetails['penetapan'] > 0;
            $ppeppStatus['pelaksanaan'] = $ppeppDetails['pelaksanaan'] > 0;
            $ppeppStatus['evaluasi']    = $ppeppDetails['evaluasi'] > 0;
            $ppeppStatus['pengendalian']= $ppeppDetails['pengendalian'] > 0;
            $ppeppStatus['peningkatan'] = $ppeppDetails['peningkatan'] > 0;
        }

        // ─── Data Periode & Radar Chart ──────────────────────────────
        $lastPeriodes = Periode::orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc')
            ->limit(5)
            ->get()
            ->reverse();

        $standars = Standar::with(['indikators.monitorings' => function($q) use ($periode) {
            $q->when($periode, fn($q2) => $q2->where('periode_id', $periode->id));
        }])->get();

        $radarLabels = [];
        $radarData = [];
        $standarProgress = [];

        foreach ($standars as $s) {
            $radarLabels[] = $s->kode;
            $totalPersen = 0;
            $countIndikator = 0;
            foreach ($s->indikators as $ind) {
                $m = $ind->monitorings->first();
                if ($m) {
                    $totalPersen += $m->persentase_capaian;
                    $countIndikator++;
                }
            }
            $avgAchievement = $countIndikator > 0 ? round($totalPersen / $countIndikator, 1) : 0;
            $radarData[] = $avgAchievement;

            // Progress Dokumen per Standar
            $totalDocs = $s->dokumens()->count();
            $approvedDocs = $s->dokumens()->where('status', 'approved')->count();
            $docPercent = $totalDocs > 0 ? round(($approvedDocs / $totalDocs) * 100) : 0;
            $standarProgress[] = [
                'nama' => $s->nama,
                'kode' => $s->kode,
                'total' => $totalDocs,
                'approved' => $approvedDocs,
                'percent' => $docPercent
            ];
        }

        // ─── Graf Tren (Temuan & Performa) ───────────────────────────
        $trenLabels = $lastPeriodes->pluck('nama')->toArray();
        $trenData = []; // Tren Temuan
        $perfTrendData = []; // Tren Performa (Achievement %)

        foreach ($lastPeriodes as $p) {
            // Temuan
            $trenData[] = Temuan::whereHas('audit', function($q) use ($p, $user, $unit) {
                $q->where('periode_id', $p->id)
                  ->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit));
            })->count();

            // Performa
            $avgPerf = Monitoring::where('periode_id', $p->id)
                ->get()
                ->avg(function($m) {
                    return $m->persentase_capaian;
                });
            $perfTrendData[] = round($avgPerf ?? 0, 1);
        }

        // ─── Temuan per Kategori ─────────────────────────────────────
        $temuanPerKategori = Temuan::selectRaw('kategori, COUNT(*) as total')
            ->whereHas('audit', fn($q) => $q->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit)))
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        // ─── Data List Terkait ───────────────────────────────────────
        $auditTerbaru = Audit::with(['periode', 'ketuaAuditor'])
            ->when($user->isAuditee(), fn($q) => $q->where('unit_yang_diaudit', $unit))
            ->latest()
            ->limit(5)
            ->get();

        $temuanDeadline = Temuan::with(['audit'])
            ->where('status', 'open')
            ->whereNotNull('batas_tindak_lanjut')
            ->whereHas('audit', fn($q) => $q->when($user->isAuditee(), fn($q2) => $q2->where('unit_yang_diaudit', $unit)))
            ->orderBy('batas_tindak_lanjut')
            ->limit(5)
            ->get();

        $listDokumenKadaluarsa = Dokumen::where('status', 'approved')
            ->where('tanggal_kadaluarsa', '<=', now()->addMonths(3))
            ->when($user->isAuditee(), fn($q) => $q->where('unit_pemilik', $unit))
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->limit(5)
            ->get();

        $allPeriodes = Periode::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();

        return \Inertia\Inertia::render('Dashboard/Index', [
            'periode' => $periode,
            'allPeriodes' => $allPeriodes,
            'stats' => $stats,
            'temuanPerKategori' => $temuanPerKategori,
            'auditTerbaru' => $auditTerbaru,
            'temuanDeadline' => $temuanDeadline,
            'trenLabels' => $trenLabels,
            'trenData' => $trenData,
            'perfTrendData' => $perfTrendData,
            'listDokumenKadaluarsa' => $listDokumenKadaluarsa,
            'radarLabels' => $radarLabels,
            'radarData' => $radarData,
            'standarProgress' => $standarProgress,
            'ppeppStatus' => $ppeppStatus,
            'ppeppDetails' => $ppeppDetails,
            'executiveSummary' => $executiveSummary,
        ]);
    }

    public function setPeriode(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
        ]);

        Periode::query()->update(['is_aktif' => false]);
        Periode::where('id', $request->periode_id)->update(['is_aktif' => true]);

        return redirect()->back()->with('success', 'Periode mutu aktif berhasil diubah.');
    }
}