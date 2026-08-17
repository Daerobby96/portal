<?php
namespace Modules\DataMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DataMaster\Models\Periode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeriodeController extends Controller
{
    public function index(Request $request)
    {
        $query = Periode::orderBy('tahun', 'desc')->orderBy('semester', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('tahun', 'like', "%{$s}%");
            });
        }

        $periodes = $query->paginate(10)->withQueryString();

        $stats = [
            'total'   => Periode::count(),
            'aktif'   => Periode::where('is_aktif', true)->count(),
            'ganjil'  => Periode::where('semester', 'ganjil')->count(),
            'genap'   => Periode::where('semester', 'genap')->count(),
        ];

        return Inertia::render('DataMaster/Periode/Index', [
            'periodes' => $periodes,
            'stats'    => $stats,
            'filters'  => [
                'search' => $request->search ?? '',
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('DataMaster/Periode/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'tahun'           => 'required|integer|min:2020|max:2100',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);

        $periode = Periode::create([
            'nama'            => $request->nama,
            'tahun'           => $request->tahun,
            'semester'        => $request->semester,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_aktif'        => false,
            'keterangan'      => $request->keterangan,
        ]);

        if ($request->boolean('is_aktif')) {
            Periode::query()->where('id', '!=', $periode->id)->update(['is_aktif' => false]);
            $periode->update(['is_aktif' => true]);
        }

        return redirect()->route('periode.index')
            ->with('success', 'Periode "' . $request->nama . '" berhasil ditambahkan.');
    }

    public function edit(Periode $periode)
    {
        return Inertia::render('DataMaster/Periode/Edit', [
            'periode' => $periode,
        ]);
    }

    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'tahun'           => 'required|integer|min:2020|max:2100',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);

        $periode->update([
            'nama'            => $request->nama,
            'tahun'           => $request->tahun,
            'semester'        => $request->semester,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => $request->keterangan,
        ]);

        if ($request->boolean('is_aktif')) {
            Periode::query()->where('id', '!=', $periode->id)->update(['is_aktif' => false]);
            $periode->update(['is_aktif' => true]);
        }

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_aktif) {
            return back()->with('error', 'Periode aktif tidak dapat dihapus.');
        }

        if (method_exists($periode, 'audits') && $periode->audits()->count() > 0) {
            return back()->with('error', 'Periode tidak dapat dihapus karena sudah memiliki data audit terkait.');
        }

        $periode->delete();
        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil dihapus.');
    }

    public function activate(Periode $periode)
    {
        Periode::query()->update(['is_aktif' => false]);
        $periode->update(['is_aktif' => true]);
        session(['active_periode_id' => $periode->id]);

        return redirect()->route('periode.index')
            ->with('success', 'Periode "' . $periode->nama . '" telah ditetapkan sebagai Periode Aktif.');
    }
}
