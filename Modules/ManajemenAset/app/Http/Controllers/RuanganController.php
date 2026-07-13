<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Ruangan;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::with('prodi');

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_ruangan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_ruangan', 'like', '%' . $request->search . '%')
                  ->orWhere('gedung', 'like', '%' . $request->search . '%');
            });
        }

        $ruangans = $query->orderBy('kode_ruangan')->paginate($request->get('per_page', 15));
        $prodis = ProgramStudi::orderBy('nama')->get();

        $stats = [
            'total' => Ruangan::count(),
            'tersedia' => Ruangan::where('status', 'tersedia')->count(),
            'tidak_tersedia' => Ruangan::where('status', 'tidak_tersedia')->count(),
            'dalam_perbaikan' => Ruangan::where('status', 'dalam_perbaikan')->count(),
        ];

        return view('manajemenaset::ruangan.index', compact('ruangans', 'prodis', 'stats'));
    }

    public function create()
    {
        $prodis = ProgramStudi::orderBy('nama')->get();
        return view('manajemenaset::ruangan.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prodi_id' => 'nullable|exists:program_studis,id',
            'kode_ruangan' => 'required|string|max:50|unique:ruangans',
            'nama_ruangan' => 'required|string|max:255',
            'jenis' => 'required|in:kelas,lab,ruang_rapat,ruang_dosen,perpustakaan,lainnya',
            'gedung' => 'nullable|string|max:255',
            'lantai' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'luas' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:tersedia,tidak_tersedia,dalam_perbaikan',
            'ber_ac' => 'boolean',
            'ber_proyektor' => 'boolean',
            'penanggung_jawab' => 'nullable|string|max:255',
            'fasilitas' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('ruangan/foto', 'public');
        }

        Ruangan::create($validated);

        return redirect()->route('ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Ruangan $ruangan)
    {
        $ruangan->load(['prodi', 'bookings' => function($q) {
            $q->whereIn('status', ['pending', 'disetujui'])->orderBy('tanggal', 'desc');
        }]);
        return view('manajemenaset::ruangan.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan)
    {
        $prodis = ProgramStudi::orderBy('nama')->get();
        return view('manajemenaset::ruangan.edit', compact('ruangan', 'prodis'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'prodi_id' => 'nullable|exists:program_studis,id',
            'kode_ruangan' => 'required|string|max:50|unique:ruangans,kode_ruangan,' . $ruangan->id,
            'nama_ruangan' => 'required|string|max:255',
            'jenis' => 'required|in:kelas,lab,ruang_rapat,ruang_dosen,perpustakaan,lainnya',
            'gedung' => 'nullable|string|max:255',
            'lantai' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'luas' => 'nullable|numeric|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:tersedia,tidak_tersedia,dalam_perbaikan',
            'ber_ac' => 'boolean',
            'ber_proyektor' => 'boolean',
            'penanggung_jawab' => 'nullable|string|max:255',
            'fasilitas' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($ruangan->foto) {
                Storage::disk('public')->delete($ruangan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('ruangan/foto', 'public');
        }

        $ruangan->update($validated);

        return redirect()->route('ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        if ($ruangan->foto) {
            Storage::disk('public')->delete($ruangan->foto);
        }

        $ruangan->delete();

        return redirect()->route('ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
