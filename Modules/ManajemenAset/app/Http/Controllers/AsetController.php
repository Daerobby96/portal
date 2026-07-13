<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Aset;
use Modules\ManajemenAset\Models\KategoriAset;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kategori', 'prodi']);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_aset', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_aset', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        $asets = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));
        $kategoris = KategoriAset::where('is_aktif', true)->get();
        $prodis = ProgramStudi::orderBy('nama')->get();

        $stats = [
            'total' => Aset::count(),
            'aktif' => Aset::where('status', 'aktif')->count(),
            'rusak' => Aset::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->count(),
            'dalam_perbaikan' => Aset::where('status', 'dalam_perbaikan')->count(),
        ];

        return view('manajemenaset::aset.index', compact('asets', 'kategoris', 'prodis', 'stats'));
    }

    public function create()
    {
        $kategoris = KategoriAset::where('is_aktif', true)->get();
        $prodis = ProgramStudi::orderBy('nama')->get();
        return view('manajemenaset::aset.create', compact('kategoris', 'prodis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_asets,id',
            'prodi_id' => 'nullable|exists:program_studis,id',
            'kode_aset' => 'required|string|max:100|unique:asets',
            'nama_aset' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'nomor_seri' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:aktif,non_aktif,dalam_perbaikan,dihapuskan',
            'lokasi' => 'required|string|max:255',
            'ruangan' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'sumber_perolehan' => 'nullable|string|max:255',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'umur_ekonomis' => 'nullable|integer|min:1',
            'penanggung_jawab' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('aset/foto', 'public');
        }

        Aset::create($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['kategori', 'prodi', 'pemeliharaans.petugas', 'peminjamans.peminjam']);
        return view('manajemenaset::aset.show', compact('aset'));
    }

    public function edit(Aset $aset)
    {
        $kategoris = KategoriAset::where('is_aktif', true)->get();
        $prodis = ProgramStudi::orderBy('nama')->get();
        return view('manajemenaset::aset.edit', compact('aset', 'kategoris', 'prodis'));
    }

    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_asets,id',
            'prodi_id' => 'nullable|exists:program_studis,id',
            'kode_aset' => 'required|string|max:100|unique:asets,kode_aset,' . $aset->id,
            'nama_aset' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'nomor_seri' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status' => 'required|in:aktif,non_aktif,dalam_perbaikan,dihapuskan',
            'lokasi' => 'required|string|max:255',
            'ruangan' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'sumber_perolehan' => 'nullable|string|max:255',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'umur_ekonomis' => 'nullable|integer|min:1',
            'penanggung_jawab' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($aset->foto) {
                Storage::disk('public')->delete($aset->foto);
            }
            $validated['foto'] = $request->file('foto')->store('aset/foto', 'public');
        }

        $aset->update($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        if ($aset->foto) {
            Storage::disk('public')->delete($aset->foto);
        }

        $aset->delete();

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}
