<?php

namespace Modules\DataMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramStudi::orderBy('jenjang')->orderBy('nama');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'like', "%{$q}%")
                   ->orWhere('kode', 'like', "%{$q}%");
            });
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        $program_studis = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total'   => ProgramStudi::count(),
            'aktif'   => ProgramStudi::where('is_aktif', true)->count(),
            'unggul'  => ProgramStudi::whereIn('akreditasi', ['Unggul', 'A'])->count(),
        ];

        return Inertia::render('DataMaster/ProgramStudi/Index', [
            'program_studis' => $program_studis,
            'stats'          => $stats,
            'filters'        => [
                'search'  => $request->search ?? '',
                'jenjang' => $request->jenjang ?? '',
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('DataMaster/ProgramStudi/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:20|unique:program_studis,kode',
            'nama'       => 'required|string|max:255',
            'jenjang'    => 'required|string|max:50',
            'akreditasi' => 'nullable|string|max:20',
            'deskripsi'  => 'nullable|string',
            'is_aktif'   => 'boolean',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        ProgramStudi::create($validated);

        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $program_studi)
    {
        return Inertia::render('DataMaster/ProgramStudi/Edit', [
            'program_studi' => $program_studi,
        ]);
    }

    public function update(Request $request, ProgramStudi $program_studi)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:20|unique:program_studis,kode,' . $program_studi->id,
            'nama'       => 'required|string|max:255',
            'jenjang'    => 'required|string|max:50',
            'akreditasi' => 'nullable|string|max:20',
            'deskripsi'  => 'nullable|string',
            'is_aktif'   => 'boolean',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        $program_studi->update($validated);

        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $program_studi)
    {
        $program_studi->delete();
        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi berhasil dihapus.');
    }
}
