<?php

namespace Modules\DataAkademik\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestasi::with('mahasiswa.prodi')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_kegiatan', 'ilike', "%{$q}%")
                   ->orWhere('penyelenggara', 'ilike', "%{$q}%")
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
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $prestasis = $query->paginate(15)->through(fn($p) => [
            'id'             => $p->id,
            'nama_kegiatan'  => $p->nama_kegiatan,
            'jenis_prestasi' => $p->jenis_prestasi,
            'tingkat'        => $p->tingkat,
            'tahun'          => $p->tahun,
            'penyelenggara'  => $p->penyelenggara,
            'peringkat'      => $p->peringkat,
            'keterangan'     => $p->keterangan,
            'sertifikat_url' => $p->sertifikat ? asset('storage/' . $p->sertifikat) : null,
            'mahasiswa_id'   => $p->mahasiswa_id,
            'mahasiswa_nim'  => $p->mahasiswa?->nim,
            'mahasiswa_nama' => $p->mahasiswa?->nama,
            'prodi_nama'     => $p->mahasiswa?->prodi?->nama,
        ])->withQueryString();
        
        $stats = [
            'total'         => Prestasi::count(),
            'akademik'      => Prestasi::where('jenis_prestasi', 'Akademik')->count(),
            'non_akademik'  => Prestasi::where('jenis_prestasi', 'Non-Akademik')->count(),
            'internasional' => Prestasi::where('tingkat', 'Internasional')->count(),
            'nasional'      => Prestasi::where('tingkat', 'Nasional')->count(),
        ];

        $tahunList = Prestasi::whereNotNull('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return Inertia::render('DataAkademik/Prestasi/Index', [
            'prestasis'      => $prestasis,
            'stats'          => $stats,
            'jenisOptions'   => Prestasi::JENIS_PRESTASI,
            'tingkatOptions' => Prestasi::TINGKAT,
            'tahunList'      => $tahunList,
            'filters'        => $request->only(['search', 'jenis_prestasi', 'tingkat', 'tahun']),
        ]);
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id', 'nim', 'nama', 'prodi_id']);
        
        return Inertia::render('DataAkademik/Prestasi/Create', [
            'mahasiswas'     => $mahasiswas,
            'jenisOptions'   => Prestasi::JENIS_PRESTASI,
            'tingkatOptions' => Prestasi::TINGKAT,
        ]);
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
        $mahasiswas = Mahasiswa::orderBy('nama')->get(['id', 'nim', 'nama', 'prodi_id']);

        return Inertia::render('DataAkademik/Prestasi/Edit', [
            'prestasi' => [
                'id'             => $prestasi->id,
                'mahasiswa_id'   => $prestasi->mahasiswa_id,
                'nama_kegiatan'  => $prestasi->nama_kegiatan,
                'jenis_prestasi' => $prestasi->jenis_prestasi,
                'tingkat'        => $prestasi->tingkat,
                'tahun'          => $prestasi->tahun,
                'penyelenggara'  => $prestasi->penyelenggara,
                'peringkat'      => $prestasi->peringkat,
                'keterangan'     => $prestasi->keterangan,
                'sertifikat_url' => $prestasi->sertifikat ? asset('storage/' . $prestasi->sertifikat) : null,
            ],
            'mahasiswas'     => $mahasiswas,
            'jenisOptions'   => Prestasi::JENIS_PRESTASI,
            'tingkatOptions' => Prestasi::TINGKAT,
        ]);
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

