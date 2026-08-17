<?php

namespace Modules\Spmi\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Spmi\Models\Standar;
use Modules\Spmi\Imports\StandarImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class StandarController extends Controller
{
    public function index(Request $request)
    {
        $query = Standar::withCount('dokumens');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('bidang')) {
            $query->where('bidang', $request->bidang);
        }

        $perPage = $request->integer('per_page', 10);
        if (!in_array($perPage, [10, 20, 25, 50, 100])) {
            $perPage = 10;
        }
        $standars = $query->orderBy('kode')->paginate($perPage)->withQueryString();

        $bidangOptions = Standar::bidangOptions();

        $summary = [
            'pendidikan'    => Standar::where('bidang', 'pendidikan')->where('is_aktif', true)->count(),
            'penelitian'    => Standar::where('bidang', 'penelitian')->where('is_aktif', true)->count(),
            'pkm'           => Standar::where('bidang', 'pkm')->where('is_aktif', true)->count(),
            'institusional' => Standar::where('bidang', 'institusional')->where('is_aktif', true)->count(),
        ];

        return Inertia::render('Spmi/Standar/Index', [
            'standars'      => $standars,
            'bidangOptions' => $bidangOptions,
            'summary'       => $summary,
        ]);
    }

    public function create()
    {
        $bidangOptions = Standar::bidangOptions();
        $jenisOptions  = Standar::jenisOptions();

        return Inertia::render('Spmi/Standar/Create', [
            'bidangOptions' => $bidangOptions,
            'jenisOptions'  => $jenisOptions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|max:50|unique:standars,kode',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bidang'    => 'required|in:pendidikan,penelitian,pkm,institusional',
            'jenis'     => 'required|in:inti,tambahan',
            'nomor'     => 'nullable|integer|min:1',
            'is_aktif'  => 'boolean',
        ]);

        Standar::create([
            'kode'      => strtoupper($request->kode),
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'bidang'    => $request->bidang,
            'jenis'     => $request->jenis,
            'nomor'     => $request->nomor,
            'is_aktif'  => $request->boolean('is_aktif', true),
        ]);

        return redirect()->route('standar.index')
            ->with('success', 'Standar "' . $request->nama . '" berhasil ditambahkan.');
    }

    public function show(Standar $standar)
    {
        $standar->load(['dokumens.kategori', 'indikators']);

        return Inertia::render('Spmi/Standar/Show', [
            'standar' => $standar,
        ]);
    }

    public function edit(Standar $standar)
    {
        $bidangOptions = Standar::bidangOptions();
        $jenisOptions  = Standar::jenisOptions();

        return Inertia::render('Spmi/Standar/Edit', [
            'standar'       => $standar,
            'bidangOptions' => $bidangOptions,
            'jenisOptions'  => $jenisOptions,
        ]);
    }

    public function update(Request $request, Standar $standar)
    {
        $request->validate([
            'kode'      => 'required|string|max:50|unique:standars,kode,' . $standar->id,
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bidang'    => 'required|in:pendidikan,penelitian,pkm,institusional',
            'jenis'     => 'required|in:inti,tambahan',
            'nomor'     => 'nullable|integer|min:1',
            'is_aktif'  => 'boolean',
        ]);

        $standar->update([
            'kode'      => strtoupper($request->kode),
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'bidang'    => $request->bidang,
            'jenis'     => $request->jenis,
            'nomor'     => $request->nomor,
            'is_aktif'  => $request->boolean('is_aktif'),
        ]);

        return redirect()->route('standar.index')
            ->with('success', 'Standar "' . $standar->nama . '" berhasil diperbarui.');
    }

    public function destroy(Standar $standar)
    {
        if ($standar->dokumens()->count() > 0) {
            return back()->with('error', 'Standar tidak dapat dihapus karena masih memiliki dokumen terkait.');
        }

        if ($standar->indikators()->count() > 0) {
            return back()->with('error', 'Standar tidak dapat dihapus karena sudah memiliki indikator kinerja terkait.');
        }

        $standar->delete();
        return redirect()->route('standar.index')
            ->with('success', 'Standar berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StandarImport, $request->file('file'));
            return back()->with('success', 'Data standar berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['kode', 'nama', 'bidang', 'jenis', 'nomor', 'deskripsi'];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TemplateExport($headings, 'Template Standar'), 'template-standar.xlsx');
    }
}
