<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\KategoriAset;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KategoriAsetController extends Controller
{
    public function index()
    {
        $kategoris = KategoriAset::withCount('asets')->orderBy('kode')->get()->map(fn($k) => [
            'id'          => $k->id,
            'kode'        => $k->kode,
            'nama'        => $k->nama,
            'keterangan'  => $k->keterangan,
            'icon'        => $k->icon,
            'color'       => $k->color,
            'is_aktif'    => (bool) $k->is_aktif,
            'asets_count' => $k->asets_count,
        ]);

        return Inertia::render('Aset/Kategori/Index', [
            'kategoris' => $kategoris,
        ]);
    }

    public function create()
    {
        return redirect()->route('kategori-aset.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:50|unique:kategori_asets',
            'nama'       => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'icon'       => 'required|string|max:255',
            'color'      => 'required|string|max:20',
            'is_aktif'   => 'boolean',
        ]);

        KategoriAset::create($validated);

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berhasil ditambahkan.');
    }

    public function edit(KategoriAset $kategoriAset)
    {
        return redirect()->route('kategori-aset.index');
    }

    public function update(Request $request, KategoriAset $kategoriAset)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:50|unique:kategori_asets,kode,' . $kategoriAset->id,
            'nama'       => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'icon'       => 'required|string|max:255',
            'color'      => 'required|string|max:20',
            'is_aktif'   => 'boolean',
        ]);

        $kategoriAset->update($validated);

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berhasil diperbarui.');
    }

    public function destroy(KategoriAset $kategoriAset)
    {
        if ($kategoriAset->asets()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki aset terdaftar.');
        }

        $kategoriAset->delete();

        return redirect()->route('kategori-aset.index')
            ->with('success', 'Kategori aset berhasil dihapus.');
    }
}
