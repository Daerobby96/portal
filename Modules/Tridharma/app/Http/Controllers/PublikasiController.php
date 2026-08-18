<?php

namespace Modules\Tridharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tridharma\Models\Publikasi;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Publikasi::with(['pegawai', 'prodi'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('judul', 'ilike', "%{$q}%")
                   ->orWhere('nama_jurnal_penerbit', 'ilike', "%{$q}%")
                   ->orWhereHas('pegawai', function ($q2) use ($q) {
                       $q2->where('nama', 'ilike', "%{$q}%");
                   });
            });
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('tingkat_sinta')) {
            $query->where('tingkat_sinta', $request->tingkat_sinta);
        }
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $publikasis = $query->paginate(15)->through(fn($p) => [
            'id'                   => $p->id,
            'judul'                => $p->judul,
            'tahun'                => $p->tahun,
            'jenis'                => $p->jenis,
            'nama_jurnal_penerbit' => $p->nama_jurnal_penerbit,
            'volume_nomor'         => $p->volume_nomor,
            'url_tautan'           => $p->url_tautan,
            'tingkat_sinta'        => $p->tingkat_sinta,
            'pegawai_id'           => $p->pegawai_id,
            'pegawai_nama'         => $p->pegawai?->nama,
            'pegawai_nip'          => $p->pegawai?->nip,
            'prodi_id'             => $p->prodi_id,
            'prodi_nama'           => $p->prodi?->nama,
        ])->withQueryString();

        $stats = [
            'total'         => Publikasi::count(),
            'internasional' => Publikasi::where('jenis', 'Jurnal Internasional')->count(),
            'sinta'         => Publikasi::whereNotNull('tingkat_sinta')->where('tingkat_sinta', '!=', '')->count(),
            'buku'          => Publikasi::where('jenis', 'Buku')->count(),
        ];

        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $tahuns = Publikasi::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return Inertia::render('Tridharma/Publikasi/Index', [
            'publikasis' => $publikasis,
            'stats'      => $stats,
            'prodis'     => $prodis,
            'tahuns'     => $tahuns,
            'filters'    => $request->only(['search', 'jenis', 'tingkat_sinta', 'prodi', 'tahun']),
        ]);
    }

    public function create()
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $dosens = Pegawai::where('is_aktif', true)
            ->where('jenis_pegawai', Pegawai::JENIS_DOSEN)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip', 'unit_kerja']);

        return Inertia::render('Tridharma/Publikasi/Create', [
            'prodis' => $prodis,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id'           => 'nullable|exists:pegawais,id',
            'judul'                => 'required|string|max:500',
            'tahun'                => 'required|integer|min:2000|max:2100',
            'jenis'                => 'required|in:Jurnal Nasional,Jurnal Internasional,Prosiding,Buku,HKI,Lainnya',
            'nama_jurnal_penerbit' => 'nullable|string|max:255',
            'volume_nomor'         => 'nullable|string|max:100',
            'url_tautan'           => 'nullable|string|max:500',
            'tingkat_sinta'        => 'nullable|string|max:20',
            'prodi_id'             => 'nullable|exists:program_studis,id',
        ]);

        Publikasi::create($validated);
        return redirect()->route('publikasi.index')->with('success', 'Data Publikasi berhasil ditambahkan.');
    }

    public function edit(Publikasi $publikasi)
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $dosens = Pegawai::where('is_aktif', true)
            ->where('jenis_pegawai', Pegawai::JENIS_DOSEN)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nip', 'unit_kerja']);

        return Inertia::render('Tridharma/Publikasi/Edit', [
            'publikasi' => $publikasi,
            'prodis'    => $prodis,
            'dosens'    => $dosens,
        ]);
    }

    public function update(Request $request, Publikasi $publikasi)
    {
        $validated = $request->validate([
            'pegawai_id'           => 'nullable|exists:pegawais,id',
            'judul'                => 'required|string|max:500',
            'tahun'                => 'required|integer|min:2000|max:2100',
            'jenis'                => 'required|in:Jurnal Nasional,Jurnal Internasional,Prosiding,Buku,HKI,Lainnya',
            'nama_jurnal_penerbit' => 'nullable|string|max:255',
            'volume_nomor'         => 'nullable|string|max:100',
            'url_tautan'           => 'nullable|string|max:500',
            'tingkat_sinta'        => 'nullable|string|max:20',
            'prodi_id'             => 'nullable|exists:program_studis,id',
        ]);

        $publikasi->update($validated);
        return redirect()->route('publikasi.index')->with('success', 'Data Publikasi berhasil diperbarui.');
    }

    public function destroy(Publikasi $publikasi)
    {
        $publikasi->delete();
        return redirect()->route('publikasi.index')->with('success', 'Data Publikasi berhasil dihapus.');
    }
}

