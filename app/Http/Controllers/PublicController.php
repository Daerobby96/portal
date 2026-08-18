<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Spmi\Models\Audit;
use Modules\Spmi\Models\Dokumen;
use Modules\Spmi\Models\IndikatorKinerja;
use Modules\Spmi\Models\Monitoring;
use Modules\DataMaster\Models\Periode;

class PublicController extends Controller
{
    public function index()
    {
        $periode = Periode::where('is_aktif', true)->first();
        $stats = [];
        
        // Statistik Dokumen
        $stats['total_dokumen'] = Dokumen::where('status', 'approved')->where('is_public', true)->count();
        
        // Statistik Audit
        $auditSelesai = Audit::where('status', 'selesai')->count();
        $totalAudit = Audit::count();
        $stats['audit_progress'] = $totalAudit > 0 ? round(($auditSelesai / $totalAudit) * 100) : 0;
        
        // Capaian IKU (Indikator Kinerja Utama)
        $monitorings = Monitoring::where('periode_id', $periode?->id)->get();
        $avgCapaian = $monitorings->avg(function($m) {
            return $m->persentase_capaian;
        }) ?? 0;
        $stats['avg_capaian'] = round($avgCapaian, 1);
        
        // Data untuk Chart Publik (Radar)
        $capaianPerStandar = [];
        if ($periode) {
            $capaianPerStandar = Monitoring::where('periode_id', $periode->id)
                ->with('indikator.standar')
                ->get()
                ->groupBy('indikator.standar.nama')
                ->map(function ($group) {
                    return round($group->avg(fn($m) => $m->persentase_capaian), 1);
                });
        }
        
        // Rerata Kepuasan Mahasiswa (EDOM)
        $stats['avg_edom'] = round(\Modules\Spmi\Models\DosenKinerja::where('periode_id', $periode?->id)->avg('total_rerata') ?? 0, 2);
        
        // Kuesioner Aktif untuk Publik (Yang ditandai is_public)
        $publicKuesioners = \Modules\Spmi\Models\Kuesioner::where('status', 'aktif')
            ->where('is_public', true)
            ->latest()
            ->take(3)
            ->get();
            
        // Ganti placeholder {periode} pada judul kuesioner dan perbaiki typo umum
        if ($periode) {
            foreach ($publicKuesioners as $k) {
                $k->judul = str_replace('{periode}', $periode->nama, $k->judul);
                // Perbaiki typo umum jika ada di data
                $k->judul = str_ireplace(['surver', 'ganji '], ['Survei', 'Ganjil '], $k->judul);
            }
        }
        
        return \Inertia\Inertia::render('Public/Index', [
            'stats' => $stats,
            'periode' => $periode,
            'capaianPerStandar' => $capaianPerStandar,
            'publicKuesioners' => $publicKuesioners,
        ]);
    }

    public function documents(Request $request)
    {
        $query = Dokumen::where('status', 'approved')
            ->where('is_public', true)
            ->with('kategori', 'standar');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'ilike', '%' . $request->search . '%')
                  ->orWhere('kode_dokumen', 'ilike', '%' . $request->search . '%');
            });
        }
        $documents = $query->latest()->paginate(12)->withQueryString();
        
        return \Inertia\Inertia::render('Public/Documents', [
            'documents' => $documents,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }
}