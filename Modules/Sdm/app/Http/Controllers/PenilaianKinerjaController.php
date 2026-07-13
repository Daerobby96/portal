<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\PenilaianKinerja;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenilaianKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = PenilaianKinerja::with(['pegawai', 'penilai'])->latest('tahun')->latest('periode');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        $penilaians = $query->paginate(20)->withQueryString();

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();

        $stats = [
            'total' => PenilaianKinerja::count(),
            'tahun_ini' => PenilaianKinerja::where('tahun', now()->year)->count(),
            'avg_nilai' => PenilaianKinerja::where('tahun', now()->year)->avg('nilai_total'),
            'sangat_baik' => PenilaianKinerja::where('predikat', 'sangat_baik')->count(),
        ];

        return view('sdm::penilaian-kinerja.index', compact('penilaians', 'pegawais', 'stats'));
    }

    public function create()
    {
        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::penilaian-kinerja.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tahun' => 'required|integer|min:2020|max:' . (now()->year + 1),
            'periode' => 'required|in:semester_1,semester_2,tahunan',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kinerja' => 'required|numeric|min:0|max:100',
            'nilai_loyalitas' => 'required|numeric|min:0|max:100',
            'nilai_kreativitas' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama' => 'required|numeric|min:0|max:100',
            'catatan_atasan' => 'nullable|string|max:2000',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Check duplicate
        $exists = PenilaianKinerja::where('pegawai_id', $request->pegawai_id)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Penilaian untuk pegawai, tahun, dan periode ini sudah ada.');
        }

        $data = $request->all();
        $data['penilai_id'] = auth()->id();

        // Upload file if exists
        if ($request->hasFile('file_dokumen')) {
            $data['file_dokumen'] = $request->file('file_dokumen')
                ->store('sdm/penilaian', 'public');
        }

        PenilaianKinerja::create($data);

        return redirect()->route('penilaian-kinerja.index')
            ->with('success', 'Penilaian kinerja berhasil dibuat.');
    }

    public function show(PenilaianKinerja $penilaianKinerja)
    {
        $penilaianKinerja->load(['pegawai', 'penilai']);
        return view('sdm::penilaian-kinerja.show', compact('penilaianKinerja'));
    }

    public function edit(PenilaianKinerja $penilaianKinerja)
    {
        if ($penilaianKinerja->status === 'verified') {
            return back()->with('error', 'Penilaian yang sudah terverifikasi tidak dapat diedit.');
        }

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::penilaian-kinerja.edit', compact('penilaianKinerja', 'pegawais'));
    }

    public function update(Request $request, PenilaianKinerja $penilaianKinerja)
    {
        if ($penilaianKinerja->status === 'verified') {
            return back()->with('error', 'Penilaian yang sudah terverifikasi tidak dapat diedit.');
        }

        $request->validate([
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kinerja' => 'required|numeric|min:0|max:100',
            'nilai_loyalitas' => 'required|numeric|min:0|max:100',
            'nilai_kreativitas' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama' => 'required|numeric|min:0|max:100',
            'catatan_atasan' => 'nullable|string|max:2000',
            'catatan_pegawai' => 'nullable|string|max:2000',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->all();

        // Upload new file if exists
        if ($request->hasFile('file_dokumen')) {
            if ($penilaianKinerja->file_dokumen) {
                Storage::disk('public')->delete($penilaianKinerja->file_dokumen);
            }
            $data['file_dokumen'] = $request->file('file_dokumen')
                ->store('sdm/penilaian', 'public');
        }

        $penilaianKinerja->update($data);

        return redirect()->route('penilaian-kinerja.index')
            ->with('success', 'Penilaian kinerja berhasil diperbarui.');
    }

    public function destroy(PenilaianKinerja $penilaianKinerja)
    {
        if ($penilaianKinerja->file_dokumen) {
            Storage::disk('public')->delete($penilaianKinerja->file_dokumen);
        }
        
        $penilaianKinerja->delete();
        return back()->with('success', 'Penilaian kinerja berhasil dihapus.');
    }

    public function submit(PenilaianKinerja $penilaianKinerja)
    {
        $penilaianKinerja->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Penilaian kinerja berhasil diajukan.');
    }

    public function verify(PenilaianKinerja $penilaianKinerja)
    {
        $penilaianKinerja->update([
            'status' => 'verified',
        ]);

        return back()->with('success', 'Penilaian kinerja berhasil diverifikasi.');
    }
}
