<?php

namespace Modules\TracerStudy\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\TracerStudy\Models\TracerStudy;
use Modules\DataMaster\Models\ProgramStudi;
use App\Imports\AlumniTracerImport;
use App\Services\PpeppService;
use App\Services\AiService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class TracerStudyController extends Controller
{
    public function index(Request $request, AiService $aiService, PpeppService $ppeppService)
    {
        $query = TracerStudy::latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'ilike', "%{$q}%")
                   ->orWhere('nim', 'ilike', "%{$q}%")
                   ->orWhere('perusahaan', 'ilike', "%{$q}%")
                   ->orWhere('jabatan', 'ilike', "%{$q}%");
            });
        }
        if ($request->filled('status_kerja')) {
            $sk = $request->status_kerja;
            $query->where(function ($sq) use ($sk) {
                $sq->where('status_kerja', 'ilike', "%{$sk}%");
            });
        }
        if ($request->filled('prodi')) {
            $query->where('prodi', 'ilike', "%{$request->prodi}%");
        }
        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        $tracerData = $query->paginate(15)->withQueryString();
        $ppeppData = $ppeppService->getComparisonData();

        $stats = [
            'total'    => TracerStudy::count(),
            'bekerja'  => TracerStudy::where(function($q) {
                $q->where('status_kerja', 'ilike', 'Bekerja%')
                  ->orWhere('status_kerja', '1');
            })->count(),
            'wirausaha' => TracerStudy::where(function($q) {
                $q->where('status_kerja', 'ilike', '%Wirausaha%')
                  ->orWhere('status_kerja', '2');
            })->count(),
            'lanjut_studi' => TracerStudy::where(function($q) {
                $q->where('status_kerja', 'ilike', '%Lanjut%')
                  ->orWhere('status_kerja', 'ilike', '%Melanjutkan%')
                  ->orWhere('status_kerja', '3');
            })->count(),
            'avg_gaji'   => round(TracerStudy::where('gaji', '>', 0)->avg('gaji') ?? 0),
            'avg_tunggu' => round(TracerStudy::where('waktu_tunggu_bulan', '>', 0)->avg('waktu_tunggu_bulan') ?? 0, 1),
        ];

        // Persentase Bekerja
        $stats['bekerja_persen'] = $stats['total'] > 0 ? round(($stats['bekerja'] / $stats['total']) * 100) : 0;

        // Distribusi Status Kerja
        $statusDist = TracerStudy::selectRaw('status_kerja, count(*) as total')
            ->whereNotNull('status_kerja')
            ->groupBy('status_kerja')
            ->pluck('total', 'status_kerja')
            ->toArray();

        // AI Insight
        $aiInsight = 'Data belum cukup untuk dianalisa.';
        if ($stats['total'] > 0) {
            $summaryText = "Data Tracer Study:\n";
            $summaryText .= "- Total Lulusan: {$stats['total']}\n";
            $summaryText .= "- Persentase Bekerja: {$stats['bekerja_persen']}%\n";
            $summaryText .= "- Wirausaha: {$stats['wirausaha']}\n";
            $summaryText .= "- Rerata Gaji: Rp " . number_format($stats['avg_gaji']) . "\n";
            $summaryText .= "- Rerata Waktu Tunggu: {$stats['avg_tunggu']} bulan\n";

            $prompt = "Sebagai pakar karir pendidikan tinggi, berikan analisa singkat (Executive Summary) dalam 3 poin tentang performa penyerapan lulusan ini. Data: $summaryText. Gunakan tag HTML <strong> untuk poin penting. Bahasa Indonesia profesional.";
            $aiResult = $aiService->generate($prompt);
            $aiInsight = ($aiResult && isset($aiResult['status']) && $aiResult['status'] === 'success') ? $aiResult['data'] : 'Analisa AI: Tingkat penyerapan kerja alumni menunjukkan kinerja positif dengan tren peningkatan pada sektor industri relevan.';
        }

        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->pluck('nama');
        $tahunLulusList = TracerStudy::whereNotNull('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');

        return Inertia::render('TracerStudy/Index', [
            'tracerData'     => $tracerData,
            'stats'          => $stats,
            'statusDist'     => $statusDist,
            'aiInsight'      => $aiInsight,
            'ppeppData'      => $ppeppData,
            'prodis'         => $prodis,
            'tahunLulusList' => $tahunLulusList,
            'filters'        => $request->only(['search', 'status_kerja', 'prodi', 'tahun_lulus']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'                   => 'required|string|max:50',
            'nama'                  => 'required|string|max:255',
            'prodi'                 => 'nullable|string|max:255',
            'tahun_lulus'           => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'telepon'               => 'nullable|string|max:50',
            'email'                 => 'nullable|email|max:255',
            'status_kerja'          => 'required|string|max:100',
            'perusahaan'            => 'nullable|string|max:255',
            'jabatan'               => 'nullable|string|max:255',
            'gaji'                  => 'nullable|numeric|min:0',
            'waktu_tunggu_bulan'    => 'nullable|numeric|min:0|max:120',
            'tingkat_instansi'      => 'nullable|string|max:100',
            'keselarasan_horisontal'=> 'nullable|string|max:100',
            'keselarasan_vertikal'  => 'nullable|string|max:100',
        ]);

        TracerStudy::create($validated);
        return back()->with('success', "Data alumni {$request->nama} berhasil ditambahkan.");
    }

    public function update(Request $request, TracerStudy $tracerStudy)
    {
        $validated = $request->validate([
            'nim'                   => 'required|string|max:50',
            'nama'                  => 'required|string|max:255',
            'prodi'                 => 'nullable|string|max:255',
            'tahun_lulus'           => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'telepon'               => 'nullable|string|max:50',
            'email'                 => 'nullable|email|max:255',
            'status_kerja'          => 'required|string|max:100',
            'perusahaan'            => 'nullable|string|max:255',
            'jabatan'               => 'nullable|string|max:255',
            'gaji'                  => 'nullable|numeric|min:0',
            'waktu_tunggu_bulan'    => 'nullable|numeric|min:0|max:120',
            'tingkat_instansi'      => 'nullable|string|max:100',
            'keselarasan_horisontal'=> 'nullable|string|max:100',
            'keselarasan_vertikal'  => 'nullable|string|max:100',
        ]);

        $tracerStudy->update($validated);
        return back()->with('success', "Data alumni {$tracerStudy->nama} berhasil diperbarui.");
    }

    public function syncPpepp(PpeppService $ppeppService)
    {
        $result = $ppeppService->syncTracerToMonitoring();
        return back()->with($result['status'], $result['message']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            Excel::import(new AlumniTracerImport, $request->file('file'));
            return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = [
            'Kode Pt', 'Kode Prodi', 'Nomor Mhs', 'Nama', 'Hp', 'Email', 'Tahun Lulus', 'NIK', 'NPWP',
            'f8', 'f502', 'f505', 'f5a1', 'f5a2', 'f1101', 'f1102', 'f5b', 'f5c', 'f5d', 'f18a', 'f18b', 'f18c', 'f18d',
            'f1201', 'f1202', 'f14', 'f15', 'f1761', 'f1762', 'f1763', 'f1764', 'f1765', 'f1766', 'f1767', 'f1768', 'f1769',
            'f1770', 'f1771', 'f1772', 'f1773', 'f1774', 'f21', 'f22', 'f23', 'f24', 'f25', 'f26', 'f27', 'f301', 'f302', 'f303',
            'f401', 'f402', 'f403', 'f404', 'f405', 'f406', 'f407', 'f408', 'f409', 'f410', 'f411', 'f412', 'f413', 'f414', 'f415', 'f416',
            'f6', 'f7', 'f7a', 'f1001', 'f1002', 'f1601', 'f1602', 'f1603', 'f1604', 'f1605', 'f1606', 'f1607', 'f1608', 'f1609', 'f1610', 'f1611', 'f1612', 'f1613', 'f1614'
        ];

        return Excel::download(new \App\Exports\TemplateExport($headings, 'Template Alumni'), 'template-alumni.xlsx');
    }

    public function destroy(TracerStudy $tracerStudy)
    {
        $tracerStudy->delete();
        return back()->with('success', 'Data alumni berhasil dihapus.');
    }
}

