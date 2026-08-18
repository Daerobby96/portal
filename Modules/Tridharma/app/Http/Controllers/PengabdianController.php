<?php

namespace Modules\Tridharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tridharma\Models\Pengabdian;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengabdian::with(['pegawai', 'prodi'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('judul', 'ilike', "%{$q}%")
                   ->orWhere('mitra', 'ilike', "%{$q}%")
                   ->orWhere('lokasi', 'ilike', "%{$q}%")
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

        $pengabdians = $query->paginate(15)->through(fn($p) => [
            'id'           => $p->id,
            'judul'        => $p->judul,
            'tahun'        => $p->tahun,
            'mitra'        => $p->mitra,
            'lokasi'       => $p->lokasi,
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
            'total'       => Pengabdian::count(),
            'mitra_count' => Pengabdian::whereNotNull('mitra')->where('mitra', '!=', '')->distinct('mitra')->count('mitra'),
            'total_dana'  => Pengabdian::sum('jumlah_dana'),
        ];

        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $tahuns = Pengabdian::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return Inertia::render('Tridharma/Pengabdian/Index', [
            'pengabdians' => $pengabdians,
            'stats'       => $stats,
            'prodis'      => $prodis,
            'tahuns'      => $tahuns,
            'filters'     => $request->only(['search', 'prodi', 'tahun']),
        ]);
    }

    public function create()
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $dosens = Pegawai::where('is_aktif', true)
            ->where('jenis_pegawai', Pegawai::JENIS_DOSEN)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip', 'unit_kerja']);

        return Inertia::render('Tridharma/Pengabdian/Create', [
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
            'mitra'       => 'nullable|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'jumlah_dana' => 'nullable|numeric|min:0',
            'anggota'     => 'nullable|string',
            'prodi_id'    => 'nullable|exists:program_studis,id',
        ]);

        Pengabdian::create($validated);
        return redirect()->route('pengabdian.index')->with('success', 'Data PkM berhasil ditambahkan.');
    }

    public function edit(Pengabdian $pengabdian)
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $dosens = Pegawai::where('is_aktif', true)
            ->where('jenis_pegawai', Pegawai::JENIS_DOSEN)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip', 'unit_kerja']);

        return Inertia::render('Tridharma/Pengabdian/Edit', [
            'pengabdian' => $pengabdian,
            'prodis'     => $prodis,
            'dosens'     => $dosens,
        ]);
    }

    public function update(Request $request, Pengabdian $pengabdian)
    {
        $validated = $request->validate([
            'pegawai_id'  => 'nullable|exists:pegawais,id',
            'judul'       => 'required|string|max:500',
            'tahun'       => 'required|integer|min:2000|max:2100',
            'mitra'       => 'nullable|string|max:255',
            'lokasi'      => 'nullable|string|max:255',
            'sumber_dana' => 'nullable|string|max:255',
            'jumlah_dana' => 'nullable|numeric|min:0',
            'anggota'     => 'nullable|string',
            'prodi_id'    => 'nullable|exists:program_studis,id',
        ]);

        $pengabdian->update($validated);
        return redirect()->route('pengabdian.index')->with('success', 'Data PkM berhasil diperbarui.');
    }

    public function destroy(Pengabdian $pengabdian)
    {
        $pengabdian->delete();
        return redirect()->route('pengabdian.index')->with('success', 'Data PkM berhasil dihapus.');
    }
}

