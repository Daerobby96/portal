<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Cuti;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $query = Cuti::with(['pegawai', 'approvedBy'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_cuti')) {
            $query->where('jenis_cuti', $request->jenis_cuti);
        }

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        $cutis = $query->paginate(20)->withQueryString();

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();

        $stats = [
            'total' => Cuti::count(),
            'pending' => Cuti::where('status', 'pending')->count(),
            'approved' => Cuti::where('status', 'approved')->count(),
            'rejected' => Cuti::where('status', 'rejected')->count(),
        ];

        return view('sdm::cuti.index', compact('cutis', 'pegawais', 'stats'));
    }

    public function create()
    {
        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::cuti.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'jenis_cuti' => 'required|in:tahunan,sakit,melahirkan,besar,alasan_penting',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:2000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        
        // Calculate jumlah_hari
        $mulai = \Carbon\Carbon::parse($request->tanggal_mulai);
        $selesai = \Carbon\Carbon::parse($request->tanggal_selesai);
        $data['jumlah_hari'] = $mulai->diffInDays($selesai) + 1;

        // Upload file if exists
        if ($request->hasFile('file_pendukung')) {
            $data['file_pendukung'] = $request->file('file_pendukung')
                ->store('sdm/cuti', 'public');
        }

        Cuti::create($data);

        return redirect()->route('cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat.');
    }

    public function show(Cuti $cuti)
    {
        $cuti->load(['pegawai', 'approvedBy']);
        return view('sdm::cuti.show', compact('cuti'));
    }

    public function edit(Cuti $cuti)
    {
        if ($cuti->status !== 'pending') {
            return back()->with('error', 'Hanya cuti berstatus pending yang dapat diedit.');
        }

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::cuti.edit', compact('cuti', 'pegawais'));
    }

    public function update(Request $request, Cuti $cuti)
    {
        if ($cuti->status !== 'pending') {
            return back()->with('error', 'Hanya cuti berstatus pending yang dapat diedit.');
        }

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:2000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        
        // Recalculate jumlah_hari
        $mulai = \Carbon\Carbon::parse($request->tanggal_mulai);
        $selesai = \Carbon\Carbon::parse($request->tanggal_selesai);
        $data['jumlah_hari'] = $mulai->diffInDays($selesai) + 1;

        // Upload new file if exists
        if ($request->hasFile('file_pendukung')) {
            if ($cuti->file_pendukung) {
                Storage::disk('public')->delete($cuti->file_pendukung);
            }
            $data['file_pendukung'] = $request->file('file_pendukung')
                ->store('sdm/cuti', 'public');
        }

        $cuti->update($data);

        return redirect()->route('cuti.index')
            ->with('success', 'Data cuti berhasil diperbarui.');
    }

    public function destroy(Cuti $cuti)
    {
        if ($cuti->file_pendukung) {
            Storage::disk('public')->delete($cuti->file_pendukung);
        }
        
        $cuti->delete();
        return back()->with('success', 'Data cuti berhasil dihapus.');
    }

    public function approve(Request $request, Cuti $cuti)
    {
        $request->validate([
            'catatan_approval' => 'nullable|string|max:1000',
        ]);

        $cuti->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    public function reject(Request $request, Cuti $cuti)
    {
        $request->validate([
            'catatan_approval' => 'required|string|max:1000',
        ]);

        $cuti->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Cuti berhasil ditolak.');
    }
}
