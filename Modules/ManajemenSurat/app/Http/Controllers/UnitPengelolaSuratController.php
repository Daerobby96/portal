<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\ManajemenSurat\Models\UnitPengelolaSurat;

class UnitPengelolaSuratController extends Controller
{
    public function index()
    {
        $units = UnitPengelolaSurat::orderBy('jenis_institusi')->orderBy('nama')->get()
            ->map(fn($u) => [
                'id'             => $u->id,
                'nama'           => $u->nama,
                'kode'           => $u->kode,
                'jenis_institusi'=> $u->jenis_institusi,
                'prefix_format'  => $u->prefix_format,
                'deskripsi'      => $u->deskripsi,
                'pic_nama'       => $u->pic_nama,
                'pic_jabatan'    => $u->pic_jabatan,
                'pic_nip'        => $u->pic_nip,
                'is_active'      => $u->is_active,
                'total_surat'    => $u->suratKeluar()->count(),
            ]);

        return Inertia::render('Surat/UnitPengelola/Index', [
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:100',
            'kode'            => 'required|string|max:20|unique:unit_pengelola_surat,kode',
            'jenis_institusi' => 'required|in:yayasan,perguruan_tinggi',
            'prefix_format'   => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'pic_nama'        => 'nullable|string|max:100',
            'pic_jabatan'     => 'nullable|string|max:100',
            'pic_nip'         => 'nullable|string|max:50',
            'is_active'       => 'boolean',
        ]);

        UnitPengelolaSurat::create($validated);

        return back()->with('success', 'Unit pengelola surat berhasil ditambahkan.');
    }

    public function update(Request $request, UnitPengelolaSurat $unitPengelola)
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:100',
            'kode'            => 'required|string|max:20|unique:unit_pengelola_surat,kode,' . $unitPengelola->id,
            'jenis_institusi' => 'required|in:yayasan,perguruan_tinggi',
            'prefix_format'   => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'pic_nama'        => 'nullable|string|max:100',
            'pic_jabatan'     => 'nullable|string|max:100',
            'pic_nip'         => 'nullable|string|max:50',
            'is_active'       => 'boolean',
        ]);

        $unitPengelola->update($validated);

        return back()->with('success', 'Unit pengelola surat berhasil diperbarui.');
    }

    public function destroy(UnitPengelolaSurat $unitPengelola)
    {
        if ($unitPengelola->suratKeluar()->count() > 0) {
            return back()->with('error', 'Unit tidak dapat dihapus karena memiliki surat terkait.');
        }

        $unitPengelola->delete();

        return back()->with('success', 'Unit pengelola surat berhasil dihapus.');
    }
}
