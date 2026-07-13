<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ManajemenSurat\Models\UnitPengelolaSurat;
use Illuminate\Support\Facades\Storage;

class UnitPengelolaSuratController extends Controller
{
    public function index()
    {
        $units = UnitPengelolaSurat::orderBy('jenis_institusi')->orderBy('nama')->get();
        
        return view('manajemen-surat::unit-pengelola.index', compact('units'));
    }

    public function create()
    {
        return view('manajemen-surat::unit-pengelola.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:unit_pengelola_surat,kode',
            'jenis_institusi' => 'required|in:yayasan,perguruan_tinggi',
            'prefix_format' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_jabatan' => 'nullable|string|max:100',
            'pic_nip' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        UnitPengelolaSurat::create($validated);

        return redirect()
            ->route('unit-pengelola.index')
            ->with('success', 'Unit pengelola surat berhasil ditambahkan.');
    }

    public function show(UnitPengelolaSurat $unitPengelola)
    {
        $unitPengelola->load(['suratKeluar' => function($query) {
            $query->latest()->limit(10);
        }]);

        // Statistik
        $stats = [
            'total_surat' => $unitPengelola->suratKeluar()->count(),
            'surat_bulan_ini' => $unitPengelola->suratKeluar()->whereMonth('tanggal_surat', now()->month)->count(),
            'draft' => $unitPengelola->suratKeluar()->where('status', 'draft')->count(),
            'published' => $unitPengelola->suratKeluar()->where('status', 'published')->count(),
        ];

        return view('manajemen-surat::unit-pengelola.show', compact('unitPengelola', 'stats'));
    }

    public function edit(UnitPengelolaSurat $unitPengelola)
    {
        return view('manajemen-surat::unit-pengelola.edit', compact('unitPengelola'));
    }

    public function update(Request $request, UnitPengelolaSurat $unitPengelola)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:unit_pengelola_surat,kode,' . $unitPengelola->id,
            'jenis_institusi' => 'required|in:yayasan,perguruan_tinggi',
            'prefix_format' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'pic_nama' => 'nullable|string|max:100',
            'pic_jabatan' => 'nullable|string|max:100',
            'pic_nip' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $unitPengelola->update($validated);

        return redirect()
            ->route('unit-pengelola.show', $unitPengelola)
            ->with('success', 'Unit pengelola surat berhasil diperbarui.');
    }

    public function destroy(UnitPengelolaSurat $unitPengelola)
    {
        // Check if unit has any surat
        if ($unitPengelola->suratKeluar()->count() > 0) {
            return back()->with('error', 'Unit tidak dapat dihapus karena memiliki surat terkait.');
        }

        $unitPengelola->delete();

        return redirect()
            ->route('unit-pengelola.index')
            ->with('success', 'Unit pengelola surat berhasil dihapus.');
    }
}
