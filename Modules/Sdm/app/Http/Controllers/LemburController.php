<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Lembur;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $query = Lembur::with(['pegawai', 'approvedBy'])->latest('tanggal');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        $lemburs = $query->paginate(20)->withQueryString();

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();

        $stats = [
            'total' => Lembur::count(),
            'pending' => Lembur::where('status', 'pending')->count(),
            'approved' => Lembur::where('status', 'approved')->count(),
            'total_jam_bulan_ini' => Lembur::where('status', 'approved')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->sum('jumlah_jam'),
        ];

        return view('sdm::lembur.index', compact('lemburs', 'pegawais', 'stats'));
    }

    public function create()
    {
        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::lembur.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keperluan' => 'required|string|max:2000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        
        // Calculate jumlah_jam
        $mulai = \Carbon\Carbon::parse($request->jam_mulai);
        $selesai = \Carbon\Carbon::parse($request->jam_selesai);
        $data['jumlah_jam'] = $mulai->diffInHours($selesai, true);

        // Upload file if exists
        if ($request->hasFile('file_pendukung')) {
            $data['file_pendukung'] = $request->file('file_pendukung')
                ->store('sdm/lembur', 'public');
        }

        Lembur::create($data);

        return redirect()->route('lembur.index')
            ->with('success', 'Pengajuan lembur berhasil dibuat.');
    }

    public function show(Lembur $lembur)
    {
        $lembur->load(['pegawai', 'approvedBy']);
        return view('sdm::lembur.show', compact('lembur'));
    }

    public function destroy(Lembur $lembur)
    {
        if ($lembur->file_pendukung) {
            Storage::disk('public')->delete($lembur->file_pendukung);
        }
        
        $lembur->delete();
        return back()->with('success', 'Data lembur berhasil dihapus.');
    }

    public function approve(Request $request, Lembur $lembur)
    {
        $request->validate([
            'catatan_approval' => 'nullable|string|max:1000',
        ]);

        $lembur->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Lembur berhasil disetujui.');
    }

    public function reject(Request $request, Lembur $lembur)
    {
        $request->validate([
            'catatan_approval' => 'required|string|max:1000',
        ]);

        $lembur->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Lembur berhasil ditolak.');
    }
}
