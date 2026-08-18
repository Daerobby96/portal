<?php

namespace Modules\Tridharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tridharma\Models\Penelitian;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        $query = Penelitian::with(['pegawai', 'prodi'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('judul', 'ilike', "%{$q}%")
                   ->orWhereHas('pegawai', function ($q2) use ($q) {
                       $q2->where('nama', 'ilike', "%{$q}%");
                   });
            });
        }
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penelitians = $query->paginate(15)->through(fn($p) => [
            'id'           => $p->id,
            'judul'        => $p->judul,
            'tahun'        => $p->tahun,
            'tingkat'      => $p->tingkat,
            'status'       => $p->status,
            'sumber_dana'  => $p->sumber_dana,
            'jumlah_dana'  => $p->jumlah_dana,
            'anggota'      => $p->anggota,
            'pegawai_id'   => $p->pegawai_id,
            'pegawai_nama' => $p->pegawai?->nama,
            'pegawai_nip'  => $p->pegawai?->nip,
            'prodi_id'     => $p->prodi_id,
            'prodi_nama'   => $p->prodi?->nama,
        ])->withQueryString();

        $stats = [
            'total'       => Penelitian::count(),
            'usulan'      => Penelitian::where('status', 'Usulan')->count(),
            'berjalan'    => Penelitian::where('status', 'Berjalan')->count(),
            'selesai'     => Penelitian::where('status', 'Selesai')->count(),
            'total_dana'  => Penelitian::sum('jumlah_dana'),
        ];

        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $tahuns = Penelitian::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return Inertia::render('Tridharma/Penelitian/Index', [
            'penelitians' => $penelitians,
            'stats'       => $stats,
            'prodis'      => $prodis,
            'tahuns'      => $tahuns,
            'filters'     => $request->only(['search', 'prodi', 'tahun', 'tingkat', 'status']),
        ]);
    }

    public function create()
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $dosens = Pegawai::where('is_aktif', true)
            ->where('jenis_pegawai', Pegawai::JENIS_DOSEN)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip', 'unit_kerja']);

        return Inertia::render('Tridharma/Penelitian/Create', [
            'prodis' => $prodis,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id'  => 'nullable|exists:pegawais,id',
            'judul'       => 'required|string|max:500',
            'tahun'       => 'required|integer|min:2000|max:2100',
            'sumber_dana' => 'nullable|string|max:255',
            'jumlah_dana' => 'nullable|numeric|min:0',
            'tingkat'     => 'required|in:Lokal,Nasional,Internasional',
            'anggota'     => 'nullable|string',
            'status'      => 'required|in:Usulan,Berjalan,Selesai',
            'prodi_id'    => 'nullable|exists:program_studis,id',
        ]);

        Penelitian::create($validated);
        return redirect()->route('penelitian.index')->with('success', 'Data Penelitian berhasil ditambahkan.');
    }

    public function edit(Penelitian $penelitian)
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $dosens = Pegawai::where('is_aktif', true)
            ->where('jenis_pegawai', Pegawai::JENIS_DOSEN)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip', 'unit_kerja']);

        return Inertia::render('Tridharma/Penelitian/Edit', [
            'penelitian' => $penelitian,
            'prodis'     => $prodis,
            'dosens'     => $dosens,
        ]);
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        $validated = $request->validate([
            'pegawai_id'  => 'nullable|exists:pegawais,id',
            'judul'       => 'required|string|max:500',
            'tahun'       => 'required|integer|min:2000|max:2100',
            'sumber_dana' => 'nullable|string|max:255',
            'jumlah_dana' => 'nullable|numeric|min:0',
            'tingkat'     => 'required|in:Lokal,Nasional,Internasional',
            'anggota'     => 'nullable|string',
            'status'      => 'required|in:Usulan,Berjalan,Selesai',
            'prodi_id'    => 'nullable|exists:program_studis,id',
        ]);

        $penelitian->update($validated);
        return redirect()->route('penelitian.index')->with('success', 'Data Penelitian berhasil diperbarui.');
    }

    public function destroy(Penelitian $penelitian)
    {
        $penelitian->delete();
        return redirect()->route('penelitian.index')->with('success', 'Data Penelitian berhasil dihapus.');
    }
}

