<?php

namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\Audit;
use Modules\Spmi\Models\Dokumen;
use Modules\Spmi\Models\Monitoring;
use Modules\Spmi\Models\Temuan;
use Modules\DataMaster\Models\Periode;
use Modules\Spmi\Models\Standar;
use Modules\Spmi\Models\IndikatorKinerja;
use Modules\Spmi\Models\RTM;
use Illuminate\Http\Request;

class PpeppController extends Controller
{
    public function index()
    {
        $periode = Periode::aktif();

        if (!$periode) {
            return redirect()->route('dashboard')->with('error', 'Silakan aktifkan salah satu periode terlebih dahulu di menu Administrasi > Manajemen Periode.');
        }

        $ppeppDetails = $periode->ppepp_progress;

        // ─── P1: Penetapan ───
        $standars = Standar::withCount([
            'dokumens' => function ($q) {
                $q->where('status', 'approved');
            }
        ])->get();
        $totalStandar = $standars->count();
        $totalIndikator = IndikatorKinerja::where('is_aktif', true)->count();

        // ─── P2: Pelaksanaan ───
        $monitorings = Monitoring::where('periode_id', $periode->id)
            ->with(['indikator', 'pelapor'])
            ->get();
        $pelaksanaanProgress = $ppeppDetails['pelaksanaan'];

        // ─── P3: Evaluasi ───
        $audits = Audit::where('periode_id', $periode->id)
            ->with(['ketuaAuditor'])
            ->withCount('checklists')
            ->get();
        $evaluasiProgress = $ppeppDetails['evaluasi'];

        // ─── P4: Pengendalian ───
        $temuans = Temuan::whereHas('audit', function ($q) use ($periode) {
            $q->where('periode_id', $periode->id);
        })
            ->with(['audit', 'auditor'])
            ->get();
        $pengendalianProgress = $ppeppDetails['pengendalian'];

        $temuanStats = [
            'open' => $temuans->where('status', 'open')->count(),
            'closed' => $temuans->where('status', 'closed')->count(),
            'verified' => $temuans->where('status', 'verified')->count(),
            'total' => $temuans->count()
        ];

        // ─── P5: Peningkatan ───
        $rtms = RTM::where('periode_id', $periode->id)->get();
        $peningkatanProgress = $ppeppDetails['peningkatan'];

        return view('spmi::ppepp.index', compact(
            'periode',
            'ppeppDetails',
            'standars',
            'totalStandar',
            'totalIndikator',
            'monitorings',
            'pelaksanaanProgress',
            'audits',
            'evaluasiProgress',
            'temuans',
            'temuanStats',
            'pengendalianProgress',
            'rtms',
            'peningkatanProgress'
        ));
    }
}

