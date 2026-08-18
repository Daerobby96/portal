<?php

namespace Modules\Spmi\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Spmi\Models\Benchmarking;
use Modules\DataMaster\Models\Periode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BenchmarkingController extends Controller
{
    public function index(Request $request)
    {
        $periodeId = $request->periode_id ?? Periode::aktif()?->id ?? Periode::first()?->id;

        $benchmarkings = Benchmarking::with(['periode'])
            ->when($periodeId, fn($q) => $q->where('periode_id', $periodeId))
            ->orderByDesc('tanggal_kegiatan')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'               => Benchmarking::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->count(),
            'diimplementasikan'   => Benchmarking::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->where('status', 'Diimplementasikan')->count(),
            'terlaksana'          => Benchmarking::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->where('status', 'Terlaksana')->count(),
            'internasional'       => Benchmarking::when($periodeId, fn($q) => $q->where('periode_id', $periodeId))->where('tingkat', 'Internasional')->count(),
        ];

        $periodes = Periode::orderByDesc('tahun')->get(['id', 'nama', 'tahun', 'is_aktif']);

        return Inertia::render('Spmi/Benchmarking/Index', [
            'benchmarkings'     => $benchmarkings,
            'stats'             => $stats,
            'periodes'          => $periodes,
            'selectedPeriodeId' => (int)$periodeId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id'            => 'required|exists:periodes,id',
            'nama_mitra'            => 'required|string|max:255',
            'tingkat'               => 'required|in:Lokal,Nasional,Internasional',
            'bidang_standar'        => 'required|string|max:255',
            'tanggal_kegiatan'      => 'required|date',
            'capaian_institusi'     => 'nullable|string',
            'capaian_mitra'         => 'nullable|string',
            'gap_analisis'          => 'nullable|string',
            'best_practice_diadopsi'=> 'nullable|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'status'                => 'required|in:Perencanaan,Terlaksana,Diimplementasikan',
            'pic_nama'              => 'nullable|string|max:255',
        ]);

        Benchmarking::create($validated);

        return redirect()->back()->with('success', 'Kegiatan Benchmarking Mutu berhasil dicatat.');
    }

    public function update(Request $request, Benchmarking $benchmarking)
    {
        $validated = $request->validate([
            'nama_mitra'            => 'required|string|max:255',
            'tingkat'               => 'required|in:Lokal,Nasional,Internasional',
            'bidang_standar'        => 'required|string|max:255',
            'tanggal_kegiatan'      => 'required|date',
            'capaian_institusi'     => 'nullable|string',
            'capaian_mitra'         => 'nullable|string',
            'gap_analisis'          => 'nullable|string',
            'best_practice_diadopsi'=> 'nullable|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'status'                => 'required|in:Perencanaan,Terlaksana,Diimplementasikan',
            'pic_nama'              => 'nullable|string|max:255',
        ]);

        $benchmarking->update($validated);

        return redirect()->back()->with('success', 'Data Benchmarking Mutu berhasil diperbarui.');
    }

    public function destroy(Benchmarking $benchmarking)
    {
        $benchmarking->delete();
        return redirect()->back()->with('success', 'Data Benchmarking Mutu berhasil dihapus.');
    }
}
