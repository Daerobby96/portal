<?php

namespace Modules\Tridharma\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tridharma\Models\Publikasi;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Publikasi::with(['pegawai', 'prodi'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('judul', 'ilike', "%{$q}%")
                   ->orWhereHas('pegawai', function ($q2) use ($q) {
                       $q2->where('nama', 'ilike', "%{$q}%");
                   });
            });
        }

        $publikasis = $query->paginate(20)->withQueryString();
        return view('tridharma::publikasi.index', compact('publikasis'));
    }

    public function create()
    {
        $prodis = ProgramStudi::aktif()->get();
        $dosens = Pegawai::aktif()->where('jenis_pegawai', Pegawai::JENIS_DOSEN)->get();
        return view('tridharma::publikasi.create', compact('prodis', 'dosens'));
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
        $prodis = ProgramStudi::aktif()->get();
        $dosens = Pegawai::aktif()->where('jenis_pegawai', Pegawai::JENIS_DOSEN)->get();
        return view('tridharma::publikasi.edit', compact('publikasi', 'prodis', 'dosens'));
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
