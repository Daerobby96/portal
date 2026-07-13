<?php

namespace Modules\DataMaster\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramStudi::latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'ilike', "%{$q}%")
                   ->orWhere('kode', 'ilike', "%{$q}%");
            });
        }

        $program_studis = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total' => ProgramStudi::count(),
            'aktif' => ProgramStudi::where('is_aktif', true)->count(),
        ];

        return view('datamaster::program_studi.index', compact('program_studis', 'stats'));
    }

    public function create()
    {
        return view('datamaster::program_studi.create');
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

        $validated['is_aktif'] = $request->has('is_aktif') ? 1 : 0;

        ProgramStudi::create($validated);

        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $program_studi)
    {
        return view('datamaster::program_studi.edit', compact('program_studi'));
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

        $validated['is_aktif'] = $request->has('is_aktif') ? 1 : 0;

        $program_studi->update($validated);

        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $program_studi)
    {
        $program_studi->delete();
        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi berhasil dihapus.');
    }
}


