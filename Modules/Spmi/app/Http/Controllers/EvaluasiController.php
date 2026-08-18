<?php
namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\Evaluasi;
use Modules\Spmi\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller
{
    public function index(Request $request)
    {
        $periodeSel = \Modules\DataMaster\Models\Periode::find($request->periode_id) ?? \Modules\DataMaster\Models\Periode::aktif();
        
        $query = Monitoring::where('periode_id', $periodeSel?->id)
            ->whereNotNull('nilai_capaian')
            ->with(['indikator.standar', 'evaluasi', 'pelapor'])
            ->leftJoin('indikator_kinerjas', 'monitorings.indikator_id', '=', 'indikator_kinerjas.id')
            ->leftJoin('standars', 'indikator_kinerjas.standar_id', '=', 'standars.id')
            ->select('monitorings.*')
            ->orderBy('standars.kode')
            ->orderByRaw("CASE WHEN indikator_kinerjas.tipe = 'IKU' THEN 1 WHEN indikator_kinerjas.tipe = 'IKT' THEN 2 ELSE 3 END")
            ->orderBy('indikator_kinerjas.kode');

        if ($request->filled('hasil')) {
            $query->whereHas('evaluasi', fn($q) => $q->where('hasil', $request->hasil));
        }

        $monitorings = $query->get();
        $periodes    = \Modules\DataMaster\Models\Periode::orderByDesc('tahun')->get();

        $stats = [
            'total'           => $monitorings->count(),
            'tercapai'        => $monitorings->filter(fn($m) => $m->evaluasi && $m->evaluasi->hasil === 'tercapai')->count(),
            'tidak_tercapai'  => $monitorings->filter(fn($m) => $m->evaluasi && $m->evaluasi->hasil === 'tidak_tercapai')->count(),
            'perlu_perhatian' => $monitorings->filter(fn($m) => $m->evaluasi && $m->evaluasi->hasil === 'perlu_perhatian')->count(),
            'belum_eval'      => $monitorings->filter(fn($m) => !$m->evaluasi)->count(),
        ];

        return \Inertia\Inertia::render('Spmi/Evaluasi/Index', [
            'monitorings' => $monitorings,
            'stats'       => $stats,
            'periodes'    => $periodes,
            'periodeSel'  => $periodeSel,
        ]);
    }

    public function create(Request $request)
    {
        $monitorings = Monitoring::whereNotNull('nilai_capaian')
            ->doesntHave('evaluasi')
            ->with(['indikator.standar', 'periode'])
            ->leftJoin('indikator_kinerjas', 'monitorings.indikator_id', '=', 'indikator_kinerjas.id')
            ->leftJoin('standars', 'indikator_kinerjas.standar_id', '=', 'standars.id')
            ->select('monitorings.*')
            ->orderBy('standars.kode')
            ->orderByRaw("CASE WHEN indikator_kinerjas.tipe = 'IKU' THEN 1 WHEN indikator_kinerjas.tipe = 'IKT' THEN 2 ELSE 3 END")
            ->orderBy('indikator_kinerjas.kode')
            ->get();

        $selected = $request->filled('monitoring_id')
            ? Monitoring::with('indikator')->find($request->monitoring_id)
            : null;

        return \Inertia\Inertia::render('Spmi/Evaluasi/Create', [
            'monitorings' => $monitorings,
            'selected'    => $selected,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monitoring_id'   => 'required|exists:monitorings,id',
            'analisa'         => 'required|string',
            'rekomendasi'     => 'nullable|string',
            'hasil'           => 'required|in:tercapai,tidak_tercapai,perlu_perhatian',
            'tanggal_evaluasi'=> 'required|date',
        ]);

        if (Evaluasi::where('monitoring_id', $request->monitoring_id)->exists()) {
            return back()->with('error', 'Monitoring ini sudah memiliki evaluasi.');
        }

        Evaluasi::create([
            'monitoring_id'    => $request->monitoring_id,
            'evaluator_id'     => Auth::id(),
            'analisa'          => $request->analisa,
            'rekomendasi'      => $request->rekomendasi,
            'hasil'            => $request->hasil,
            'tanggal_evaluasi' => $request->tanggal_evaluasi,
        ]);

        Monitoring::find($request->monitoring_id)->update(['status' => 'verified']);

        return redirect()->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil disimpan.');
    }

    public function show(Evaluasi $evaluasi)
    {
        $evaluasi->load(['monitoring.indikator.standar', 'monitoring.periode', 'evaluator']);
        return view('spmi::evaluasi.show', compact('evaluasi'));
    }

    public function edit(Evaluasi $evaluasi)
    {
        $evaluasi->load('monitoring.indikator');
        return view('spmi::evaluasi.edit', compact('evaluasi'));
    }

    public function update(Request $request, Evaluasi $evaluasi)
    {
        $request->validate([
            'analisa'          => 'required|string',
            'rekomendasi'      => 'nullable|string',
            'hasil'            => 'required|in:tercapai,tidak_tercapai,perlu_perhatian',
            'tanggal_evaluasi' => 'required|date',
        ]);

        $evaluasi->update($request->only([
            'analisa', 'rekomendasi', 'hasil', 'tanggal_evaluasi',
        ]));

        return redirect()->route('evaluasi.index')
            ->with('success', 'Evaluasi berhasil diperbarui.');
    }

    public function destroy(Evaluasi $evaluasi)
    {
        $evaluasi->monitoring->update(['status' => 'submitted']);
        $evaluasi->delete();
        return back()->with('success', 'Evaluasi berhasil dihapus.');
    }

    public function updateInline(Request $request)
    {
        $request->validate([
            'monitoring_id' => 'required|exists:monitorings,id',
            'field'         => 'required|in:analisa,hasil',
            'value'         => 'required',
        ]);

        $evaluasi = Evaluasi::where('monitoring_id', $request->monitoring_id)->first();
        
        if (!$evaluasi) {
            $evaluasi = new Evaluasi();
            $evaluasi->monitoring_id = $request->monitoring_id;
            $evaluasi->evaluator_id = Auth::id();
            $evaluasi->tanggal_evaluasi = now();
            $evaluasi->hasil = 'perlu_perhatian'; // Default hasil jika baru dibuat lewat analisa
        }
        
        $evaluasi->{$request->field} = $request->value;
        $evaluasi->save();

        $evaluasi->monitoring->update(['status' => 'verified']);

        return response()->json([
            'success' => true,
            'message' => 'Evaluasi berhasil diperbarui.',
        ]);
    }

    public function generateAi(Request $request, \Modules\Spmi\Services\AiEvaluasiService $aiService)
    {
        $request->validate([
            'monitoring_id' => 'required|exists:monitorings,id',
        ]);

        $monitoring = Monitoring::with(['indikator.standar'])->findOrFail($request->monitoring_id);
        $indikator  = $monitoring->indikator;
        $standar    = $indikator?->standar;

        $result = $aiService->generateEvaluation([
            'indikator_kode'   => $indikator?->kode,
            'indikator_nama'   => $indikator?->nama,
            'target_nilai'     => (float)($indikator?->target_nilai ?? 0),
            'target_deskripsi' => $indikator?->target_deskripsi,
            'nilai_capaian'    => (float)($monitoring->nilai_capaian ?? 0),
            'unit_pengukuran'  => $indikator?->unit_pengukuran ?? '%',
            'unit_kerja'       => $indikator?->unit_kerja ?? 'Program Studi',
            'standar_kode'     => $standar?->kode,
            'standar_nama'     => $standar?->nama,
            'bidang'           => $standar?->bidang ?? 'pendidikan',
        ]);

        return response()->json($result);
    }
}
