<?php

namespace Modules\Tridharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tridharma\Models\Penelitian;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;

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

        $penelitians = $query->paginate(20)->withQueryString();
        $prodis = ProgramStudi::aktif()->get();
        $tahuns = Penelitian::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('tridharma::penelitian.index', compact('penelitians', 'prodis', 'tahuns'));
    }

    public function create()
    {
        $prodis = ProgramStudi::aktif()->get();
        $dosens = Pegawai::aktif()->where('jenis_pegawai', Pegawai::JENIS_DOSEN)->get();
        return view('tridharma::penelitian.create', compact('prodis', 'dosens'));
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
        $prodis = ProgramStudi::aktif()->get();
        $dosens = Pegawai::aktif()->where('jenis_pegawai', Pegawai::JENIS_DOSEN)->get();
        return view('tridharma::penelitian.edit', compact('penelitian', 'prodis', 'dosens'));
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
