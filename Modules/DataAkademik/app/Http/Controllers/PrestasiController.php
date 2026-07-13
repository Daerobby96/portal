<?php

namespace Modules\DataAkademik\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Prestasi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestasi::with('mahasiswa.prodi')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_kegiatan', 'ilike', "%{$q}%")
                   ->orWhereHas('mahasiswa', function ($q2) use ($q) {
                       $q2->where('nama', 'ilike', "%{$q}%")
                          ->orWhere('nim', 'ilike', "%{$q}%");
                   });
            });
        }
        if ($request->filled('jenis_prestasi')) {
            $query->where('jenis_prestasi', $request->jenis_prestasi);
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $prestasis = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total' => Prestasi::count(),
            'akademik' => Prestasi::where('jenis_prestasi', 'Akademik')->count(),
            'internasional' => Prestasi::where('tingkat', 'Internasional')->count(),
        ];

        return view('dataakademik::prestasi.index', compact('prestasis', 'stats'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        return view('dataakademik::prestasi.create', compact('mahasiswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'nama_kegiatan'  => 'required|string|max:255',
            'jenis_prestasi' => 'required|string|in:' . implode(',', Prestasi::JENIS_PRESTASI),
            'tingkat'        => 'required|string|in:' . implode(',', Prestasi::TINGKAT),
            'tahun'          => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'penyelenggara'  => 'nullable|string|max:255',
            'peringkat'      => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
            'sertifikat'     => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('sertifikat')) {
            $validated['sertifikat'] = $request->file('sertifikat')->store('prestasi_sertifikat', 'public');
        }

        Prestasi::create($validated);

        return redirect()->route('prestasi.index')->with('success', 'Data Prestasi Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi)
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        return view('dataakademik::prestasi.edit', compact('prestasi', 'mahasiswas'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'nama_kegiatan'  => 'required|string|max:255',
            'jenis_prestasi' => 'required|string|in:' . implode(',', Prestasi::JENIS_PRESTASI),
            'tingkat'        => 'required|string|in:' . implode(',', Prestasi::TINGKAT),
            'tahun'          => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'penyelenggara'  => 'nullable|string|max:255',
            'peringkat'      => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
            'sertifikat'     => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('sertifikat')) {
            if ($prestasi->sertifikat) {
                Storage::disk('public')->delete($prestasi->sertifikat);
            }
            $validated['sertifikat'] = $request->file('sertifikat')->store('prestasi_sertifikat', 'public');
        }

        $prestasi->update($validated);

        return redirect()->route('prestasi.index')->with('success', 'Data Prestasi Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        if ($prestasi->sertifikat) {
            Storage::disk('public')->delete($prestasi->sertifikat);
        }
        $prestasi->delete();
        return redirect()->route('prestasi.index')->with('success', 'Data Prestasi Mahasiswa berhasil dihapus.');
    }
}
