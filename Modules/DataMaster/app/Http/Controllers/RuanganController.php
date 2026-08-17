<?php

namespace Modules\DataMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DataMaster\Models\Ruangan;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::with('prodi')->orderBy('gedung')->orderBy('kode_ruangan');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_ruangan', 'ilike', "%{$q}%")
                   ->orWhere('kode_ruangan', 'ilike', "%{$q}%")
                   ->orWhere('gedung', 'ilike', "%{$q}%")
                   ->orWhere('lantai', 'ilike', "%{$q}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ruangans = $query->paginate(15)->withQueryString();
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'jenjang']);

        $stats = [
            'total'      => Ruangan::count(),
            'kelas'      => Ruangan::where('jenis', 'kelas')->count(),
            'lab'        => Ruangan::where('jenis', 'lab')->count(),
            'tersedia'   => Ruangan::where('status', 'tersedia')->count(),
        ];

        return Inertia::render('DataMaster/Ruangan/Index', [
            'ruangans' => $ruangans,
            'prodis'   => $prodis,
            'stats'    => $stats,
            'filters'  => [
                'search' => $request->search ?? '',
                'jenis'  => $request->jenis ?? '',
                'status' => $request->status ?? '',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ruangan'     => 'required|string|max:50|unique:ruangan,kode_ruangan',
            'nama_ruangan'     => 'required|string|max:255',
            'jenis'            => 'required|in:kelas,lab,ruang_rapat,ruang_dosen,perpustakaan,lainnya',
            'gedung'           => 'nullable|string|max:100',
            'lantai'           => 'nullable|string|max:50',
            'kapasitas'        => 'nullable|integer|min:1',
            'luas'             => 'nullable|numeric|min:0',
            'kondisi'          => 'required|in:baik,rusak_ringan,rusak_berat',
            'status'           => 'required|in:tersedia,tidak_tersedia,dalam_perbaikan',
            'ber_ac'           => 'boolean',
            'ber_proyektor'    => 'boolean',
            'penanggung_jawab' => 'nullable|string|max:255',
            'fasilitas'        => 'nullable|string',
            'keterangan'       => 'nullable|string',
            'prodi_id'         => 'nullable|exists:program_studis,id',
        ]);

        $validated['ber_ac'] = $request->boolean('ber_ac');
        $validated['ber_proyektor'] = $request->boolean('ber_proyektor');

        Ruangan::create($validated);

        return back()->with('success', 'Master Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'kode_ruangan'     => 'required|string|max:50|unique:ruangan,kode_ruangan,' . $ruangan->id,
            'nama_ruangan'     => 'required|string|max:255',
            'jenis'            => 'required|in:kelas,lab,ruang_rapat,ruang_dosen,perpustakaan,lainnya',
            'gedung'           => 'nullable|string|max:100',
            'lantai'           => 'nullable|string|max:50',
            'kapasitas'        => 'nullable|integer|min:1',
            'luas'             => 'nullable|numeric|min:0',
            'kondisi'          => 'required|in:baik,rusak_ringan,rusak_berat',
            'status'           => 'required|in:tersedia,tidak_tersedia,dalam_perbaikan',
            'ber_ac'           => 'boolean',
            'ber_proyektor'    => 'boolean',
            'penanggung_jawab' => 'nullable|string|max:255',
            'fasilitas'        => 'nullable|string',
            'keterangan'       => 'nullable|string',
            'prodi_id'         => 'nullable|exists:program_studis,id',
        ]);

        $validated['ber_ac'] = $request->boolean('ber_ac');
        $validated['ber_proyektor'] = $request->boolean('ber_proyektor');

        $ruangan->update($validated);

        return back()->with('success', 'Data Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return back()->with('success', 'Ruangan berhasil dihapus.');
    }
}
