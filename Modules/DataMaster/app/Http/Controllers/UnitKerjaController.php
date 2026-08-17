<?php

namespace Modules\DataMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DataMaster\Models\UnitKerja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitKerja::withCount('pegawais')->orderBy('tipe')->orderBy('nama');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'ilike', "%{$q}%")
                   ->orWhere('kode', 'ilike', "%{$q}%")
                   ->orWhere('kepala_unit', 'ilike', "%{$q}%")
                   ->orWhere('lokasi', 'ilike', "%{$q}%");
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $unit_kerjas = $query->paginate(15)->withQueryString();

        $stats = [
            'total'   => UnitKerja::count(),
            'aktif'   => UnitKerja::where('is_aktif', true)->count(),
            'jurusan' => UnitKerja::where('tipe', 'jurusan')->count(),
            'upt'     => UnitKerja::whereIn('tipe', ['upt', 'lembaga', 'biro'])->count(),
        ];

        return Inertia::render('DataMaster/UnitKerja/Index', [
            'unit_kerjas' => $unit_kerjas,
            'stats'       => $stats,
            'filters'     => [
                'search' => $request->search ?? '',
                'tipe'   => $request->tipe ?? '',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:50|unique:unit_kerjas,kode',
            'nama'        => 'required|string|max:255',
            'tipe'        => 'required|string|max:50',
            'kepala_unit' => 'nullable|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'is_aktif'    => 'boolean',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        UnitKerja::create($validated);

        return back()->with('success', 'Unit Kerja / Lembaga berhasil ditambahkan.');
    }

    public function update(Request $request, UnitKerja $unit_kerja)
    {
        $validated = $request->validate([
            'kode'        => 'required|string|max:50|unique:unit_kerjas,kode,' . $unit_kerja->id,
            'nama'        => 'required|string|max:255',
            'tipe'        => 'required|string|max:50',
            'kepala_unit' => 'nullable|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'is_aktif'    => 'boolean',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        $unit_kerja->update($validated);

        return back()->with('success', 'Data Unit Kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unit_kerja)
    {
        if ($unit_kerja->pegawais()->count() > 0) {
            return back()->with('error', "Unit Kerja tidak dapat dihapus karena masih menaungi {$unit_kerja->pegawais()->count()} pegawai.");
        }

        $unit_kerja->delete();

        return back()->with('success', 'Unit Kerja berhasil dihapus.');
    }
}
