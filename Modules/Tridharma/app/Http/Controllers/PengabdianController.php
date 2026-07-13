<?php

namespace Modules\Tridharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tridharma\Models\Pengabdian;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;

class PengabdianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengabdian::with(['pegawai', 'prodi'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('judul', 'ilike', "%{$q}%")
                   ->orWhereHas('pegawai', function ($q2) use ($q) {
                       $q2->where('nama', 'ilike', "%{$q}%");
                   });
            });
        }

        $pengabdians = $query->paginate(20)->withQueryString();
        return view('tridharma::pengabdian.index', compact('pengabdians'));
    }

    public function create()
    {
        $prodis = ProgramStudi::aktif()->get();
        $dosens = Pegawai::aktif()->where('jenis_pegawai', Pegawai::JENIS_DOSEN)->get();
        return view('tridharma::pengabdian.create', compact('prodis', 'dosens'));
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
        $prodis = ProgramStudi::aktif()->get();
        $dosens = Pegawai::aktif()->where('jenis_pegawai', Pegawai::JENIS_DOSEN)->get();
        return view('tridharma::pengabdian.edit', compact('pengabdian', 'prodis', 'dosens'));
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
