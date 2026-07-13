<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Pemeliharaan;
use Modules\ManajemenAset\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemeliharaan::with(['aset.kategori', 'petugas']);

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('hasil')) {
            $query->where('hasil', $request->hasil);
        }

        if ($request->filled('search')) {
            $query->whereHas('aset', function($q) use ($request) {
                $q->where('nama_aset', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_aset', 'like', '%' . $request->search . '%');
            });
        }

        $pemeliharaans = $query->orderBy('tanggal_pemeliharaan', 'desc')
            ->paginate($request->get('per_page', 15));

        $stats = [
            'total' => Pemeliharaan::count(),
            'bulan_ini' => Pemeliharaan::whereMonth('tanggal_pemeliharaan', now()->month)->count(),
            'perlu_perbaikan' => Pemeliharaan::where('hasil', 'perlu_perbaikan')->count(),
            'perlu_penggantian' => Pemeliharaan::where('hasil', 'perlu_penggantian')->count(),
        ];

        return view('manajemenaset::pemeliharaan.index', compact('pemeliharaans', 'stats'));
    }

    public function create(Aset $aset)
    {
        return view('manajemenaset::pemeliharaan.create', compact('aset'));
    }

    public function store(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis' => 'required|in:preventif,korektif,kalibrasi,inspeksi',
            'deskripsi_kegiatan' => 'required|string',
            'temuan' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'hasil' => 'required|in:baik,perlu_perbaikan,perlu_penggantian',
            'biaya' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'tanggal_berikutnya' => 'nullable|date|after:tanggal_pemeliharaan',
            'bukti_foto' => 'nullable|image|max:2048',
        ]);

        $validated['aset_id'] = $aset->id;
        $validated['petugas_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('pemeliharaan/foto', 'public');
        }

        Pemeliharaan::create($validated);

        return redirect()->route('aset.show', $aset)
            ->with('success', 'Data pemeliharaan berhasil ditambahkan.');
    }

    public function show(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->load(['aset.kategori', 'petugas']);
        return view('manajemenaset::pemeliharaan.show', compact('pemeliharaan'));
    }

    public function edit(Pemeliharaan $pemeliharaan)
    {
        return view('manajemenaset::pemeliharaan.edit', compact('pemeliharaan'));
    }

    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $validated = $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis' => 'required|in:preventif,korektif,kalibrasi,inspeksi',
            'deskripsi_kegiatan' => 'required|string',
            'temuan' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'hasil' => 'required|in:baik,perlu_perbaikan,perlu_penggantian',
            'biaya' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'tanggal_berikutnya' => 'nullable|date|after:tanggal_pemeliharaan',
            'bukti_foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('bukti_foto')) {
            if ($pemeliharaan->bukti_foto) {
                Storage::disk('public')->delete($pemeliharaan->bukti_foto);
            }
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('pemeliharaan/foto', 'public');
        }

        $pemeliharaan->update($validated);

        return redirect()->route('pemeliharaan.index')
            ->with('success', 'Data pemeliharaan berhasil diperbarui.');
    }

    public function destroy(Pemeliharaan $pemeliharaan)
    {
        if ($pemeliharaan->bukti_foto) {
            Storage::disk('public')->delete($pemeliharaan->bukti_foto);
        }

        $pemeliharaan->delete();

        return redirect()->route('pemeliharaan.index')
            ->with('success', 'Data pemeliharaan berhasil dihapus.');
    }
}
