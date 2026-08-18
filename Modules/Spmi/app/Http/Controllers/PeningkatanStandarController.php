<?php

namespace Modules\Spmi\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Spmi\Models\PeningkatanStandar;
use Modules\Spmi\Models\Standar;
use Modules\Spmi\Models\IndikatorKinerja;
use Modules\DataMaster\Models\Periode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeningkatanStandarController extends Controller
{
    public function index(Request $request)
    {
        $periodeId = $request->periode_id ?? Periode::aktif()?->id ?? Periode::first()?->id;

        $peningkatans = PeningkatanStandar::with(['standar', 'indikatorKinerja', 'periode', 'approver'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'      => PeningkatanStandar::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->count(),
            'diterapkan' => PeningkatanStandar::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->where('status', 'diterapkan')->count(),
            'disetujui'  => PeningkatanStandar::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->where('status', 'disetujui')->count(),
            'diajukan'   => PeningkatanStandar::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->where('status', 'diajukan')->count(),
        ];

        $standars = Standar::orderBy('kode')->get(['id', 'kode', 'nama', 'jenis', 'bidang']);
        $periodes = Periode::orderByDesc('tahun')->get(['id', 'nama', 'tahun', 'is_aktif']);

        $indikators = IndikatorKinerja::leftJoin('standars', 'indikator_kinerjas.standar_id', '=', 'standars.id')
            ->select('indikator_kinerjas.*')
            ->where('indikator_kinerjas.is_aktif', true)
            ->orderBy('standars.kode')
            ->orderByRaw("CASE WHEN indikator_kinerjas.tipe = 'IKU' THEN 1 WHEN indikator_kinerjas.tipe = 'IKT' THEN 2 ELSE 3 END")
            ->orderBy('indikator_kinerjas.kode')
            ->get()
            ->map(function ($ind) use ($periodeId) {
                $monitoring = \Modules\Spmi\Models\Monitoring::where('indikator_id', $ind->id)
                    ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
                    ->latest('id')
                    ->first();

                $targetText = $ind->target_deskripsi 
                    ?: ($ind->target_nilai ? ($ind->target_nilai . ' ' . ($ind->unit_pengukuran ?? '')) : '-');
                
                $capaianText = $monitoring 
                    ? ($monitoring->nilai_capaian . ' ' . ($ind->unit_pengukuran ?? ''))
                    : ($ind->target_nilai ? ($ind->target_nilai . ' ' . ($ind->unit_pengukuran ?? '')) : '100%');

                return [
                    'id'              => $ind->id,
                    'kode'            => $ind->kode,
                    'nama'            => $ind->nama,
                    'standar_id'      => $ind->standar_id,
                    'target_text'     => trim($targetText),
                    'capaian_text'    => trim($capaianText),
                    'unit_pengukuran' => $ind->unit_pengukuran,
                ];
            });

        return Inertia::render('Spmi/PeningkatanStandar/Index', [
            'peningkatans' => $peningkatans,
            'stats'        => $stats,
            'standars'     => $standars,
            'indikators'   => $indikators,
            'periodes'     => $periodes,
            'selectedPeriodeId' => (int)$periodeId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id'          => 'required|exists:periodes,id',
            'standar_id'          => 'required|exists:standars,id',
            'indikator_kinerja_id'=> 'nullable|exists:indikator_kinerjas,id',
            'target_lama'         => 'required|string|max:255',
            'capaian_saat_ini'    => 'required|string|max:255',
            'target_baru'         => 'required|string|max:255',
            'dasar_pertimbangan'  => 'nullable|string',
            'strategi_pencapaian' => 'nullable|string',
            'status'              => 'required|in:draft,diajukan,disetujui,diterapkan',
            'catatan'             => 'nullable|string',
        ]);

        if (in_array($validated['status'], ['disetujui', 'diterapkan'])) {
            $validated['disetujui_oleh'] = auth()->id();
            $validated['tanggal_persetujuan'] = now();
        }

        PeningkatanStandar::create($validated);

        return redirect()->back()->with('success', 'Usulan Peningkatan Standar Mutu (Kaizen) berhasil ditambahkan.');
    }

    public function update(Request $request, PeningkatanStandar $peningkatanStandar)
    {
        $validated = $request->validate([
            'standar_id'          => 'required|exists:standars,id',
            'indikator_kinerja_id'=> 'nullable|exists:indikator_kinerjas,id',
            'target_lama'         => 'required|string|max:255',
            'capaian_saat_ini'    => 'required|string|max:255',
            'target_baru'         => 'required|string|max:255',
            'dasar_pertimbangan'  => 'nullable|string',
            'strategi_pencapaian' => 'nullable|string',
            'status'              => 'required|in:draft,diajukan,disetujui,diterapkan',
            'catatan'             => 'nullable|string',
        ]);

        if (in_array($validated['status'], ['disetujui', 'diterapkan']) && !$peningkatanStandar->tanggal_persetujuan) {
            $validated['disetujui_oleh'] = auth()->id();
            $validated['tanggal_persetujuan'] = now();
        }

        $peningkatanStandar->update($validated);

        return redirect()->back()->with('success', 'Peningkatan Standar Mutu berhasil diperbarui.');
    }

    public function destroy(PeningkatanStandar $peningkatanStandar)
    {
        $peningkatanStandar->delete();
        return redirect()->back()->with('success', 'Data Peningkatan Standar Mutu berhasil dihapus.');
    }
}
