<?php

namespace Modules\Kerjasama\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Kerjasama\Models\Kerjasama;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class KerjasamaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::with('prodi')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_mitra', 'like', "%{$q}%")
                   ->orWhere('judul_kerjasama', 'like', "%{$q}%");
            });
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }
        if ($request->filled('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kerjasamas = $query->paginate(15)->through(fn($k) => [
            'id'              => $k->id,
            'nama_mitra'      => $k->nama_mitra,
            'jenis_mitra'     => $k->jenis_mitra,
            'tingkat'         => $k->tingkat,
            'judul_kerjasama' => $k->judul_kerjasama,
            'jenis_dokumen'   => $k->jenis_dokumen,
            'tanggal_mulai'   => $k->tanggal_mulai?->format('d M Y'),
            'tanggal_selesai' => $k->tanggal_selesai ? $k->tanggal_selesai->format('d M Y') : 'Seterusnya',
            'dokumen_mou'     => $k->dokumen_mou ? asset('storage/' . $k->dokumen_mou) : null,
            'prodi_id'        => $k->prodi_id,
            'prodi_nama'      => $k->prodi?->nama,
            'status'          => $k->status,
            'keterangan'      => $k->keterangan,
            'is_expiring'     => $k->isExpiring(),
        ]);

        $stats = [
            'total'         => Kerjasama::count(),
            'aktif'         => Kerjasama::where('status', 'Aktif')->count(),
            'internasional' => Kerjasama::where('tingkat', 'Internasional')->count(),
            'nasional'      => Kerjasama::where('tingkat', 'Nasional')->count(),
        ];

        return Inertia::render('Kerjasama/Index', [
            'kerjasamas'   => $kerjasamas,
            'stats'        => $stats,
            'filters'      => $request->only(['search', 'tingkat', 'jenis_dokumen', 'status']),
            'jenisMitra'   => Kerjasama::JENIS_MITRA,
            'tingkatList'  => Kerjasama::TINGKAT,
            'jenisDokumen' => Kerjasama::JENIS_DOKUMEN,
            'statusList'   => Kerjasama::STATUS,
        ]);
    }

    public function show(Kerjasama $kerjasama)
    {
        $kerjasama->load('prodi', 'evaluasiMitras.evaluator');

        $evaluasis = $kerjasama->evaluasiMitras->sortByDesc('tanggal_evaluasi')->values()->map(fn($e) => [
            'id'               => $e->id,
            'tanggal_evaluasi' => $e->tanggal_evaluasi?->format('d M Y'),
            'nilai'            => $e->nilai,
            'catatan'          => $e->catatan,
            'evaluator_name'   => $e->evaluator?->name ?? 'Evaluator',
            'created_at'       => $e->created_at?->translatedFormat('d M Y H:i'),
        ]);

        $avgNilai = $kerjasama->evaluasiMitras->count() > 0
            ? round($kerjasama->evaluasiMitras->avg('nilai'), 1)
            : 0;

        return Inertia::render('Kerjasama/Show', [
            'kerjasama' => [
                'id'              => $kerjasama->id,
                'nama_mitra'      => $kerjasama->nama_mitra,
                'jenis_mitra'     => $kerjasama->jenis_mitra,
                'tingkat'         => $kerjasama->tingkat,
                'judul_kerjasama' => $kerjasama->judul_kerjasama,
                'jenis_dokumen'   => $kerjasama->jenis_dokumen,
                'tanggal_mulai'   => $kerjasama->tanggal_mulai?->format('d M Y'),
                'tanggal_selesai' => $kerjasama->tanggal_selesai ? $kerjasama->tanggal_selesai->format('d M Y') : 'Seterusnya',
                'dokumen_mou'     => $kerjasama->dokumen_mou ? asset('storage/' . $kerjasama->dokumen_mou) : null,
                'prodi_id'        => $kerjasama->prodi_id,
                'prodi_nama'      => $kerjasama->prodi?->nama ?? 'Institusi / Universitas',
                'status'          => $kerjasama->status,
                'keterangan'      => $kerjasama->keterangan,
                'is_expiring'     => $kerjasama->isExpiring(),
                'created_at'      => $kerjasama->created_at?->translatedFormat('d M Y H:i'),
            ],
            'evaluasis' => $evaluasis,
            'avgNilai'  => $avgNilai,
        ]);
    }

    public function create()
    {
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get()
            ->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama]);

        return Inertia::render('Kerjasama/Create', [
            'prodis'       => $prodis,
            'jenisMitra'   => Kerjasama::JENIS_MITRA,
            'tingkatList'  => Kerjasama::TINGKAT,
            'jenisDokumen' => Kerjasama::JENIS_DOKUMEN,
            'statusList'   => Kerjasama::STATUS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mitra'      => 'required|string|max:255',
            'jenis_mitra'     => 'required|string|in:' . implode(',', Kerjasama::JENIS_MITRA),
            'tingkat'         => 'required|string|in:' . implode(',', Kerjasama::TINGKAT),
            'judul_kerjasama' => 'required|string|max:255',
            'jenis_dokumen'   => 'required|string|in:' . implode(',', Kerjasama::JENIS_DOKUMEN),
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prodi_id'        => 'nullable|exists:program_studis,id',
            'status'          => 'required|string|in:' . implode(',', Kerjasama::STATUS),
            'keterangan'      => 'nullable|string',
            'dokumen_mou'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('dokumen_mou')) {
            $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama_mou', 'public');
        }

        $kerjasama = Kerjasama::create($validated);

        return redirect()->route('kerjasama.index')->with('success', 'Data Kerjasama berhasil ditambahkan.');
    }

    public function edit(Kerjasama $kerjasama)
    {
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get()
            ->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama]);

        return Inertia::render('Kerjasama/Edit', [
            'kerjasama' => [
                'id'              => $kerjasama->id,
                'nama_mitra'      => $kerjasama->nama_mitra,
                'jenis_mitra'     => $kerjasama->jenis_mitra,
                'tingkat'         => $kerjasama->tingkat,
                'judul_kerjasama' => $kerjasama->judul_kerjasama,
                'jenis_dokumen'   => $kerjasama->jenis_dokumen,
                'tanggal_mulai'   => $kerjasama->tanggal_mulai?->format('Y-m-d'),
                'tanggal_selesai' => $kerjasama->tanggal_selesai?->format('Y-m-d'),
                'dokumen_mou'     => $kerjasama->dokumen_mou ? asset('storage/' . $kerjasama->dokumen_mou) : null,
                'prodi_id'        => $kerjasama->prodi_id,
                'status'          => $kerjasama->status,
                'keterangan'      => $kerjasama->keterangan,
            ],
            'prodis'       => $prodis,
            'jenisMitra'   => Kerjasama::JENIS_MITRA,
            'tingkatList'  => Kerjasama::TINGKAT,
            'jenisDokumen' => Kerjasama::JENIS_DOKUMEN,
            'statusList'   => Kerjasama::STATUS,
        ]);
    }

    public function update(Request $request, Kerjasama $kerjasama)
    {
        $validated = $request->validate([
            'nama_mitra'      => 'required|string|max:255',
            'jenis_mitra'     => 'required|string|in:' . implode(',', Kerjasama::JENIS_MITRA),
            'tingkat'         => 'required|string|in:' . implode(',', Kerjasama::TINGKAT),
            'judul_kerjasama' => 'required|string|max:255',
            'jenis_dokumen'   => 'required|string|in:' . implode(',', Kerjasama::JENIS_DOKUMEN),
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'prodi_id'        => 'nullable|exists:program_studis,id',
            'status'          => 'required|string|in:' . implode(',', Kerjasama::STATUS),
            'keterangan'      => 'nullable|string',
            'dokumen_mou'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('dokumen_mou')) {
            if ($kerjasama->dokumen_mou) {
                Storage::disk('public')->delete($kerjasama->dokumen_mou);
            }
            $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama_mou', 'public');
        }

        $kerjasama->update($validated);

        return redirect()->route('kerjasama.index')->with('success', 'Data Kerjasama berhasil diperbarui.');
    }

    public function destroy(Kerjasama $kerjasama)
    {
        if ($kerjasama->dokumen_mou) {
            Storage::disk('public')->delete($kerjasama->dokumen_mou);
        }
        $kerjasama->delete();
        return redirect()->route('kerjasama.index')->with('success', 'Data Kerjasama berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\KerjasamaImport, $request->file('file'));
            return back()->with('success', 'Data Kerjasama berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = [
            'nama_mitra', 'jenis_mitra', 'tingkat', 'judul_kerjasama',
            'tanggal_mulai', 'tanggal_selesai', 'prodi', 'status', 'keterangan'
        ];
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\TemplateExport($headings, 'Template Kerjasama'),
            'template-kerjasama.xlsx'
        );
    }
}
