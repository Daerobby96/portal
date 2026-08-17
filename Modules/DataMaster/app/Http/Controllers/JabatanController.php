<?php

namespace Modules\DataMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DataMaster\Models\Jabatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::withCount('pegawais')->orderBy('level_hirarki')->orderBy('nama');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'ilike', "%{$q}%")
                   ->orWhere('kode', 'ilike', "%{$q}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $jabatans = $query->paginate(15)->withQueryString();

        $stats = [
            'total'       => Jabatan::count(),
            'struktural'  => Jabatan::where('kategori', 'struktural')->count(),
            'fungsional'  => Jabatan::whereIn('kategori', ['fungsional_dosen', 'fungsional_tendik'])->count(),
            'aktif'       => Jabatan::where('is_aktif', true)->count(),
        ];

        return Inertia::render('DataMaster/Jabatan/Index', [
            'jabatans' => $jabatans,
            'stats'    => $stats,
            'filters'  => [
                'search'   => $request->search ?? '',
                'kategori' => $request->kategori ?? '',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'            => 'required|string|max:50|unique:jabatans,kode',
            'nama'            => 'required|string|max:255',
            'kategori'        => 'required|string|max:50',
            'level_hirarki'   => 'required|integer|min:1|max:20',
            'tunjangan_dasar' => 'nullable|numeric|min:0',
            'deskripsi'       => 'nullable|string',
            'is_aktif'        => 'boolean',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        Jabatan::create($validated);

        return back()->with('success', 'Master Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'kode'            => 'required|string|max:50|unique:jabatans,kode,' . $jabatan->id,
            'nama'            => 'required|string|max:255',
            'kategori'        => 'required|string|max:50',
            'level_hirarki'   => 'required|integer|min:1|max:20',
            'tunjangan_dasar' => 'nullable|numeric|min:0',
            'deskripsi'       => 'nullable|string',
            'is_aktif'        => 'boolean',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        $jabatan->update($validated);

        return back()->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->pegawais()->count() > 0) {
            return back()->with('error', "Jabatan tidak dapat dihapus karena masih diemban oleh {$jabatan->pegawais()->count()} pegawai.");
        }

        $jabatan->delete();

        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
