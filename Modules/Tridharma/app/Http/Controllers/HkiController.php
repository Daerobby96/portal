<?php

namespace Modules\Tridharma\Http\Controllers;

use Modules\Tridharma\Models\Hki;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class HkiController extends Controller
{
    public function index(Request $request)
    {
        $query = Hki::with('pegawai')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('judul_hki', 'ilike', "%{$q}%")
                   ->orWhereHas('pegawai', function ($q2) use ($q) {
                       $q2->where('nama', 'ilike', "%{$q}%")
                          ->orWhere('nip', 'ilike', "%{$q}%");
                   });
            });
        }
        if ($request->filled('jenis_hki')) {
            $query->where('jenis_hki', $request->jenis_hki);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hkis = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total' => Hki::count(),
            'granted' => Hki::where('status', 'Granted/Sertifikat')->count(),
            'paten' => Hki::whereIn('jenis_hki', ['Paten', 'Paten Sederhana'])->count(),
        ];

        return view('hki.index', compact('hkis', 'stats'));
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return view('hki.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id'       => 'required|exists:pegawais,id',
            'judul_hki'        => 'required|string|max:255',
            'jenis_hki'        => 'required|string|in:' . implode(',', Hki::JENIS_HKI),
            'nomor_pencatatan' => 'nullable|string|max:255',
            'tahun_terbit'     => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status'           => 'required|string|in:' . implode(',', Hki::STATUS),
            'keterangan'       => 'nullable|string',
            'sertifikat'       => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('sertifikat')) {
            $validated['sertifikat'] = $request->file('sertifikat')->store('hki_sertifikat', 'public');
        }

        Hki::create($validated);

        return redirect()->route('hki.index')->with('success', 'Data HKI / Paten berhasil ditambahkan.');
    }

    public function edit(Hki $hki)
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return view('hki.edit', compact('hki', 'pegawais'));
    }

    public function update(Request $request, Hki $hki)
    {
        $validated = $request->validate([
            'pegawai_id'       => 'required|exists:pegawais,id',
            'judul_hki'        => 'required|string|max:255',
            'jenis_hki'        => 'required|string|in:' . implode(',', Hki::JENIS_HKI),
            'nomor_pencatatan' => 'nullable|string|max:255',
            'tahun_terbit'     => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status'           => 'required|string|in:' . implode(',', Hki::STATUS),
            'keterangan'       => 'nullable|string',
            'sertifikat'       => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('sertifikat')) {
            if ($hki->sertifikat) {
                Storage::disk('public')->delete($hki->sertifikat);
            }
            $validated['sertifikat'] = $request->file('sertifikat')->store('hki_sertifikat', 'public');
        }

        $hki->update($validated);

        return redirect()->route('hki.index')->with('success', 'Data HKI / Paten berhasil diperbarui.');
    }

    public function destroy(Hki $hki)
    {
        if ($hki->sertifikat) {
            Storage::disk('public')->delete($hki->sertifikat);
        }
        $hki->delete();
        return redirect()->route('hki.index')->with('success', 'Data HKI / Paten berhasil dihapus.');
    }
}
