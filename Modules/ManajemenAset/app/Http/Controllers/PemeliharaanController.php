<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Pemeliharaan;
use Modules\ManajemenAset\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemeliharaan::with(['aset.kategori', 'petugas']);

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('hasil')) {
            $query->where('hasil', $request->hasil);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('aset', function($sq) use ($q) {
                $sq->where('nama_aset', 'like', "%{$q}%")
                   ->orWhere('kode_aset', 'like', "%{$q}%");
            });
        }

        $pemeliharaans = $query->orderBy('tanggal_pemeliharaan', 'desc')
            ->paginate($request->get('per_page', 15))
            ->through(fn($p) => [
                'id'                   => $p->id,
                'aset_id'              => $p->aset_id,
                'aset_kode'            => $p->aset?->kode_aset,
                'aset_nama'            => $p->aset?->nama_aset,
                'kategori_nama'        => $p->aset?->kategori?->nama,
                'tanggal_pemeliharaan' => $p->tanggal_pemeliharaan?->format('d M Y'),
                'jenis'                => $p->jenis,
                'deskripsi_kegiatan'   => $p->deskripsi_kegiatan,
                'hasil'                => $p->hasil,
                'biaya'                => $p->biaya,
                'vendor'               => $p->vendor,
                'petugas_nama'         => $p->petugas?->name ?? 'Petugas',
                'bukti_foto'           => $p->bukti_foto ? asset('storage/' . $p->bukti_foto) : null,
                'tanggal_berikutnya'   => $p->tanggal_berikutnya?->format('d M Y'),
            ]);

        $stats = [
            'total'             => Pemeliharaan::count(),
            'bulan_ini'         => Pemeliharaan::whereMonth('tanggal_pemeliharaan', now()->month)->count(),
            'perlu_perbaikan'   => Pemeliharaan::where('hasil', 'perlu_perbaikan')->count(),
            'perlu_penggantian' => Pemeliharaan::where('hasil', 'perlu_penggantian')->count(),
        ];

        return Inertia::render('Aset/Pemeliharaan/Index', [
            'pemeliharaans' => $pemeliharaans,
            'stats'         => $stats,
            'filters'       => $request->only(['search', 'jenis', 'hasil']),
        ]);
    }

    public function create(Aset $aset)
    {
        return Inertia::render('Aset/Pemeliharaan/Create', [
            'aset' => [
                'id'            => $aset->id,
                'kode_aset'     => $aset->kode_aset,
                'nama_aset'     => $aset->nama_aset,
                'kategori_nama' => $aset->kategori?->nama,
                'lokasi'        => $aset->lokasi,
                'kondisi'       => $aset->kondisi,
            ],
        ]);
    }

    public function store(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis'                => 'required|in:preventif,korektif,kalibrasi,inspeksi',
            'deskripsi_kegiatan'   => 'required|string',
            'temuan'               => 'nullable|string',
            'tindakan'             => 'nullable|string',
            'hasil'                => 'required|in:baik,perlu_perbaikan,perlu_penggantian',
            'biaya'                => 'nullable|numeric|min:0',
            'vendor'               => 'nullable|string|max:255',
            'tanggal_berikutnya'   => 'nullable|date|after:tanggal_pemeliharaan',
            'bukti_foto'           => 'nullable|image|max:2048',
        ]);

        $validated['aset_id'] = $aset->id;
        $validated['petugas_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('pemeliharaan/foto', 'public');
        }

        Pemeliharaan::create($validated);

        return redirect()->route('aset.show', $aset)
            ->with('success', 'Data pemeliharaan berhasil dicatat.');
    }

    public function show(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->load(['aset.kategori', 'petugas']);

        return Inertia::render('Aset/Pemeliharaan/Show', [
            'pemeliharaan' => [
                'id'                   => $pemeliharaan->id,
                'aset_id'              => $pemeliharaan->aset_id,
                'aset_kode'            => $pemeliharaan->aset?->kode_aset,
                'aset_nama'            => $pemeliharaan->aset?->nama_aset,
                'kategori_nama'        => $pemeliharaan->aset?->kategori?->nama,
                'tanggal_pemeliharaan' => $pemeliharaan->tanggal_pemeliharaan?->format('d M Y'),
                'jenis'                => $pemeliharaan->jenis,
                'deskripsi_kegiatan'   => $pemeliharaan->deskripsi_kegiatan,
                'temuan'               => $pemeliharaan->temuan,
                'tindakan'             => $pemeliharaan->tindakan,
                'hasil'                => $pemeliharaan->hasil,
                'biaya'                => $pemeliharaan->biaya,
                'vendor'               => $pemeliharaan->vendor,
                'petugas_nama'         => $pemeliharaan->petugas?->name ?? 'Petugas',
                'bukti_foto'           => $pemeliharaan->bukti_foto ? asset('storage/' . $pemeliharaan->bukti_foto) : null,
                'tanggal_berikutnya'   => $pemeliharaan->tanggal_berikutnya?->format('d M Y'),
            ],
        ]);
    }

    public function edit(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->load('aset');

        return Inertia::render('Aset/Pemeliharaan/Edit', [
            'pemeliharaan' => [
                'id'                   => $pemeliharaan->id,
                'aset_id'              => $pemeliharaan->aset_id,
                'aset_kode'            => $pemeliharaan->aset?->kode_aset,
                'aset_nama'            => $pemeliharaan->aset?->nama_aset,
                'tanggal_pemeliharaan' => $pemeliharaan->tanggal_pemeliharaan?->format('Y-m-d'),
                'jenis'                => $pemeliharaan->jenis,
                'deskripsi_kegiatan'   => $pemeliharaan->deskripsi_kegiatan,
                'temuan'               => $pemeliharaan->temuan,
                'tindakan'             => $pemeliharaan->tindakan,
                'hasil'                => $pemeliharaan->hasil,
                'biaya'                => $pemeliharaan->biaya,
                'vendor'               => $pemeliharaan->vendor,
                'tanggal_berikutnya'   => $pemeliharaan->tanggal_berikutnya?->format('Y-m-d'),
                'bukti_foto'           => $pemeliharaan->bukti_foto ? asset('storage/' . $pemeliharaan->bukti_foto) : null,
            ],
        ]);
    }

    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $validated = $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis'                => 'required|in:preventif,korektif,kalibrasi,inspeksi',
            'deskripsi_kegiatan'   => 'required|string',
            'temuan'               => 'nullable|string',
            'tindakan'             => 'nullable|string',
            'hasil'                => 'required|in:baik,perlu_perbaikan,perlu_penggantian',
            'biaya'                => 'nullable|numeric|min:0',
            'vendor'               => 'nullable|string|max:255',
            'tanggal_berikutnya'   => 'nullable|date|after:tanggal_pemeliharaan',
            'bukti_foto'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('bukti_foto')) {
            if ($pemeliharaan->bukti_foto) {
                Storage::disk('public')->delete($pemeliharaan->bukti_foto);
            }
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('pemeliharaan/foto', 'public');
        }

        $pemeliharaan->update($validated);

        return redirect()->route('pemeliharaan.show', $pemeliharaan)
            ->with('success', 'Data pemeliharaan berhasil diperbarui.');
    }

    public function destroy(Pemeliharaan $pemeliharaan)
    {
        if ($pemeliharaan->bukti_foto) {
            Storage::disk('public')->delete($pemeliharaan->bukti_foto);
        }

        $pemeliharaan->delete();

        return redirect()->route('pemeliharaan.index')
            ->with('success', 'Data pemeliharaan berhasil dihapus.');
    }
}
