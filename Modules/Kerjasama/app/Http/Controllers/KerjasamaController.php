<?php

namespace Modules\Kerjasama\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Kerjasama\Models\Kerjasama;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kerjasama::with('prodi')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_mitra', 'ilike', "%{$q}%")
                   ->orWhere('judul_kerjasama', 'ilike', "%{$q}%");
            });
        }
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kerjasamas = $query->paginate(15)->withQueryString();

        $stats = [
            'total'         => Kerjasama::count(),
            'aktif'         => Kerjasama::where('status', 'Aktif')->count(),
            'internasional' => Kerjasama::where('tingkat', 'Internasional')->count(),
        ];

        return view('kerjasama::kerjasama.index', compact('kerjasamas', 'stats'));
    }

    public function show(Kerjasama $kerjasama)
    {
        $kerjasama->load('prodi', 'evaluasiMitras.evaluator');
        return view('kerjasama::kerjasama.show', compact('kerjasama'));
    }

    public function create()
    {
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get();
        return view('kerjasama::kerjasama.create', compact('prodis'));
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

        Kerjasama::create($validated);

        return redirect()->route('kerjasama.index')->with('success', 'Data Kerjasama berhasil ditambahkan.');
    }

    public function edit(Kerjasama $kerjasama)
    {
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get();
        return view('kerjasama::kerjasama.edit', compact('kerjasama', 'prodis'));
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
