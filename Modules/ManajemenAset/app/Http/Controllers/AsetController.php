<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Aset;
use Modules\ManajemenAset\Models\KategoriAset;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with(['kategori', 'prodi']);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($sq) use ($q) {
                $sq->where('nama_aset', 'like', "%{$q}%")
                   ->orWhere('kode_aset', 'like', "%{$q}%")
                   ->orWhere('lokasi', 'like', "%{$q}%");
            });
        }

        $asets = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15))->through(fn($a) => [
            'id'                => $a->id,
            'kode_aset'         => $a->kode_aset,
            'nama_aset'         => $a->nama_aset,
            'merk'              => $a->merk,
            'tipe'              => $a->tipe,
            'kondisi'           => $a->kondisi,
            'status'            => $a->status,
            'lokasi'            => $a->lokasi,
            'ruangan'           => $a->ruangan,
            'kategori_nama'     => $a->kategori?->nama,
            'kategori_color'    => $a->kategori?->color ?? '#6366f1',
            'kategori_icon'     => $a->kategori?->icon ?? 'bi-box-seam',
            'prodi_nama'        => $a->prodi?->nama,
            'harga_perolehan'   => $a->harga_perolehan,
            'foto'              => $a->foto ? asset('storage/' . $a->foto) : null,
            'tanggal_perolehan' => $a->tanggal_perolehan?->format('d M Y'),
        ]);

        $kategoris = KategoriAset::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'kode', 'color', 'icon']);
        $prodis = ProgramStudi::orderBy('nama')->get(['id', 'nama']);

        $stats = [
            'total'           => Aset::count(),
            'aktif'           => Aset::where('status', 'aktif')->count(),
            'rusak'           => Aset::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->count(),
            'dalam_perbaikan' => Aset::where('status', 'dalam_perbaikan')->count(),
        ];

        return Inertia::render('Aset/Index', [
            'asets'     => $asets,
            'kategoris' => $kategoris,
            'prodis'    => $prodis,
            'stats'     => $stats,
            'filters'   => $request->only(['search', 'kategori_id', 'prodi_id', 'kondisi', 'status']),
        ]);
    }

    public function create()
    {
        $kategoris = KategoriAset::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'kode']);
        $prodis = ProgramStudi::orderBy('nama')->get(['id', 'nama']);

        return Inertia::render('Aset/Create', [
            'kategoris' => $kategoris,
            'prodis'    => $prodis,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'       => 'required|exists:kategori_asets,id',
            'prodi_id'          => 'nullable|exists:program_studis,id',
            'kode_aset'         => 'required|string|max:100|unique:asets',
            'nama_aset'         => 'required|string|max:255',
            'merk'              => 'nullable|string|max:255',
            'tipe'              => 'nullable|string|max:255',
            'nomor_seri'        => 'nullable|string|max:255',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status'            => 'required|in:aktif,non_aktif,dalam_perbaikan,dihapuskan',
            'lokasi'            => 'required|string|max:255',
            'ruangan'           => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'sumber_perolehan'  => 'nullable|string|max:255',
            'harga_perolehan'   => 'nullable|numeric|min:0',
            'umur_ekonomis'     => 'nullable|integer|min:1',
            'penanggung_jawab'  => 'nullable|string|max:255',
            'spesifikasi'       => 'nullable|string',
            'keterangan'        => 'nullable|string',
            'foto'              => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('aset/foto', 'public');
        }

        Aset::create($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Aset baru berhasil didaftarkan.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['kategori', 'prodi', 'pemeliharaans.petugas', 'peminjamans.peminjam']);

        $pemeliharaans = $aset->pemeliharaans->sortByDesc('tanggal_pemeliharaan')->values()->map(fn($p) => [
            'id'                   => $p->id,
            'tanggal_pemeliharaan' => $p->tanggal_pemeliharaan?->format('d M Y'),
            'jenis'                => $p->jenis,
            'deskripsi_kegiatan'   => $p->deskripsi_kegiatan,
            'hasil'                => $p->hasil,
            'biaya'                => $p->biaya,
            'vendor'               => $p->vendor,
            'petugas_nama'         => $p->petugas?->name ?? 'Petugas',
            'bukti_foto'           => $p->bukti_foto ? asset('storage/' . $p->bukti_foto) : null,
        ]);

        $peminjamans = $aset->peminjamans->sortByDesc('tanggal_pinjam')->values()->map(fn($pm) => [
            'id'                      => $pm->id,
            'peminjam_nama'           => $pm->peminjam?->name ?? 'Peminjam',
            'keperluan'               => $pm->keperluan,
            'tanggal_pinjam'          => $pm->tanggal_pinjam?->format('d M Y'),
            'tanggal_kembali_rencana' => $pm->tanggal_kembali_rencana?->format('d M Y'),
            'tanggal_kembali_aktual'  => $pm->tanggal_kembali_aktual?->format('d M Y'),
            'status'                  => $pm->status,
        ]);

        return Inertia::render('Aset/Show', [
            'aset' => [
                'id'                => $aset->id,
                'kategori_id'       => $aset->kategori_id,
                'kategori_nama'     => $aset->kategori?->nama,
                'kategori_color'    => $aset->kategori?->color ?? '#6366f1',
                'kategori_icon'     => $aset->kategori?->icon ?? 'bi-box-seam',
                'prodi_id'          => $aset->prodi_id,
                'prodi_nama'        => $aset->prodi?->nama ?? 'Institusi (Umum)',
                'kode_aset'         => $aset->kode_aset,
                'nama_aset'         => $aset->nama_aset,
                'merk'              => $aset->merk,
                'tipe'              => $aset->tipe,
                'nomor_seri'        => $aset->nomor_seri,
                'kondisi'           => $aset->kondisi,
                'status'            => $aset->status,
                'lokasi'            => $aset->lokasi,
                'ruangan'           => $aset->ruangan,
                'tanggal_perolehan' => $aset->tanggal_perolehan?->format('d M Y'),
                'sumber_perolehan'  => $aset->sumber_perolehan,
                'harga_perolehan'   => $aset->harga_perolehan,
                'umur_ekonomis'     => $aset->umur_ekonomis,
                'penanggung_jawab'  => $aset->penanggung_jawab,
                'spesifikasi'       => $aset->spesifikasi,
                'keterangan'        => $aset->keterangan,
                'foto'              => $aset->foto ? asset('storage/' . $aset->foto) : null,
            ],
            'pemeliharaans' => $pemeliharaans,
            'peminjamans'   => $peminjamans,
        ]);
    }

    public function edit(Aset $aset)
    {
        $kategoris = KategoriAset::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'kode']);
        $prodis = ProgramStudi::orderBy('nama')->get(['id', 'nama']);

        return Inertia::render('Aset/Edit', [
            'aset' => [
                'id'                => $aset->id,
                'kategori_id'       => $aset->kategori_id,
                'prodi_id'          => $aset->prodi_id,
                'kode_aset'         => $aset->kode_aset,
                'nama_aset'         => $aset->nama_aset,
                'merk'              => $aset->merk,
                'tipe'              => $aset->tipe,
                'nomor_seri'        => $aset->nomor_seri,
                'kondisi'           => $aset->kondisi,
                'status'            => $aset->status,
                'lokasi'            => $aset->lokasi,
                'ruangan'           => $aset->ruangan,
                'tanggal_perolehan' => $aset->tanggal_perolehan?->format('Y-m-d'),
                'sumber_perolehan'  => $aset->sumber_perolehan,
                'harga_perolehan'   => $aset->harga_perolehan,
                'umur_ekonomis'     => $aset->umur_ekonomis,
                'penanggung_jawab'  => $aset->penanggung_jawab,
                'spesifikasi'       => $aset->spesifikasi,
                'keterangan'        => $aset->keterangan,
                'foto'              => $aset->foto ? asset('storage/' . $aset->foto) : null,
            ],
            'kategoris' => $kategoris,
            'prodis'    => $prodis,
        ]);
    }

    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'kategori_id'       => 'required|exists:kategori_asets,id',
            'prodi_id'          => 'nullable|exists:program_studis,id',
            'kode_aset'         => 'required|string|max:100|unique:asets,kode_aset,' . $aset->id,
            'nama_aset'         => 'required|string|max:255',
            'merk'              => 'nullable|string|max:255',
            'tipe'              => 'nullable|string|max:255',
            'nomor_seri'        => 'nullable|string|max:255',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'status'            => 'required|in:aktif,non_aktif,dalam_perbaikan,dihapuskan',
            'lokasi'            => 'required|string|max:255',
            'ruangan'           => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'sumber_perolehan'  => 'nullable|string|max:255',
            'harga_perolehan'   => 'nullable|numeric|min:0',
            'umur_ekonomis'     => 'nullable|integer|min:1',
            'penanggung_jawab'  => 'nullable|string|max:255',
            'spesifikasi'       => 'nullable|string',
            'keterangan'        => 'nullable|string',
            'foto'              => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($aset->foto) {
                Storage::disk('public')->delete($aset->foto);
            }
            $validated['foto'] = $request->file('foto')->store('aset/foto', 'public');
        }

        $aset->update($validated);

        return redirect()->route('aset.show', $aset)
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        if ($aset->foto) {
            Storage::disk('public')->delete($aset->foto);
        }

        $aset->delete();

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}
