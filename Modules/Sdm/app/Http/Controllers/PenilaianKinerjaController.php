<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\PenilaianKinerja;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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

        $penilaians = $query->paginate(15)->withQueryString();
        $pegawais   = Pegawai::where('is_aktif', true)->orderBy('nama')->get();

        $stats = [
            'total'       => PenilaianKinerja::count(),
            'tahun_ini'   => PenilaianKinerja::where('tahun', now()->year)->count(),
            'avg_nilai'   => round((float) PenilaianKinerja::where('tahun', now()->year)->avg('nilai_total'), 1),
            'sangat_baik' => PenilaianKinerja::where('predikat', 'sangat_baik')->count(),
        ];

        return Inertia::render('Sdm/PenilaianKinerja/Index', [
            'penilaians' => $penilaians,
            'pegawais'   => $pegawais,
            'stats'      => $stats,
            'filters'    => [
                'tahun'      => $request->tahun ?? '',
                'periode'    => $request->periode ?? '',
                'pegawai_id' => $request->pegawai_id ?? '',
            ],
        ]);
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return Inertia::render('Sdm/PenilaianKinerja/Create', [
            'pegawais' => $pegawais,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'        => 'required|exists:pegawais,id',
            'tahun'             => 'required|integer|min:2020|max:' . (now()->year + 1),
            'periode'           => 'required|in:semester_1,semester_2,tahunan',
            'nilai_disiplin'    => 'required|numeric|min:0|max:100',
            'nilai_kinerja'     => 'required|numeric|min:0|max:100',
            'nilai_loyalitas'   => 'required|numeric|min:0|max:100',
            'nilai_kreativitas' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama'   => 'required|numeric|min:0|max:100',
            'catatan_atasan'    => 'nullable|string|max:2000',
            'file_dokumen'      => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $exists = PenilaianKinerja::where('pegawai_id', $request->pegawai_id)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Penilaian untuk pegawai, tahun, dan periode ini sudah ada.');
        }

        $data = $request->all();
        $data['penilai_id'] = auth()->id();
        $data['status'] = 'draft';

        // Hitung total dan predikat
        $total = ($request->nilai_disiplin + $request->nilai_kinerja + $request->nilai_loyalitas + $request->nilai_kreativitas + $request->nilai_kerjasama) / 5;
        $data['nilai_total'] = round($total, 2);
        $data['predikat'] = $total >= 85 ? 'sangat_baik' : ($total >= 70 ? 'baik' : ($total >= 55 ? 'cukup' : 'kurang'));

        if ($request->hasFile('file_dokumen')) {
            $data['file_dokumen'] = $request->file('file_dokumen')->store('sdm/penilaian', 'public');
        }

        PenilaianKinerja::create($data);

        return redirect('/sdm/penilaian-kinerja')
            ->with('success', 'Penilaian kinerja pegawai berhasil dibuat.');
    }

    public function show(PenilaianKinerja $penilaianKinerja)
    {
        $penilaianKinerja->load(['pegawai', 'penilai']);
        return Inertia::render('Sdm/PenilaianKinerja/Show', [
            'penilaian' => $penilaianKinerja,
        ]);
    }

    public function edit(PenilaianKinerja $penilaianKinerja)
    {
        if ($penilaianKinerja->status === 'verified') {
            return back()->with('error', 'Penilaian yang sudah terverifikasi tidak dapat diedit.');
        }

        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return Inertia::render('Sdm/PenilaianKinerja/Edit', [
            'penilaian' => $penilaianKinerja,
            'pegawais'  => $pegawais,
        ]);
    }

    public function update(Request $request, PenilaianKinerja $penilaianKinerja)
    {
        if ($penilaianKinerja->status === 'verified') {
            return back()->with('error', 'Penilaian yang sudah terverifikasi tidak dapat diedit.');
        }

        $request->validate([
            'nilai_disiplin'    => 'required|numeric|min:0|max:100',
            'nilai_kinerja'     => 'required|numeric|min:0|max:100',
            'nilai_loyalitas'   => 'required|numeric|min:0|max:100',
            'nilai_kreativitas' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama'   => 'required|numeric|min:0|max:100',
            'catatan_atasan'    => 'nullable|string|max:2000',
            'file_dokumen'      => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->all();
        $total = ($request->nilai_disiplin + $request->nilai_kinerja + $request->nilai_loyalitas + $request->nilai_kreativitas + $request->nilai_kerjasama) / 5;
        $data['nilai_total'] = round($total, 2);
        $data['predikat'] = $total >= 85 ? 'sangat_baik' : ($total >= 70 ? 'baik' : ($total >= 55 ? 'cukup' : 'kurang'));

        if ($request->hasFile('file_dokumen')) {
            if ($penilaianKinerja->file_dokumen) {
                Storage::disk('public')->delete($penilaianKinerja->file_dokumen);
            }
            $data['file_dokumen'] = $request->file('file_dokumen')->store('sdm/penilaian', 'public');
        }

        $penilaianKinerja->update($data);

        return redirect('/sdm/penilaian-kinerja')->with('success', 'Penilaian kinerja berhasil diperbarui.');
    }

    public function destroy(PenilaianKinerja $penilaianKinerja)
    {
        if ($penilaianKinerja->file_dokumen) {
            Storage::disk('public')->delete($penilaianKinerja->file_dokumen);
        }
        $penilaianKinerja->delete();
        return redirect('/sdm/penilaian-kinerja')->with('success', 'Data penilaian kinerja berhasil dihapus.');
    }

    public function verify(PenilaianKinerja $penilaianKinerja)
    {
        $penilaianKinerja->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Penilaian kinerja telah diverifikasi resmi.');
    }
}
