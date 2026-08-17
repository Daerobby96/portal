<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Lembur;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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

        $lemburs = $query->paginate(15)->withQueryString();
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();

        $stats = [
            'total'               => Lembur::count(),
            'pending'             => Lembur::where('status', 'pending')->count(),
            'approved'            => Lembur::where('status', 'approved')->count(),
            'total_jam_bulan_ini' => Lembur::where('status', 'approved')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->sum('jumlah_jam') ?: 0,
        ];

        return Inertia::render('Sdm/Lembur/Index', [
            'lemburs'  => $lemburs,
            'pegawais' => $pegawais,
            'stats'    => $stats,
            'filters'  => [
                'status'     => $request->status ?? '',
                'pegawai_id' => $request->pegawai_id ?? '',
            ],
        ]);
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return Inertia::render('Sdm/Lembur/Create', [
            'pegawais' => $pegawais,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'     => 'required|exists:pegawais,id',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required|string',
            'jam_selesai'    => 'required|string',
            'keperluan'      => 'required|string|max:2000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        
        $mulai = \Carbon\Carbon::parse($request->jam_mulai);
        $selesai = \Carbon\Carbon::parse($request->jam_selesai);
        $data['jumlah_jam'] = max(1, $mulai->diffInHours($selesai, true));
        $data['status'] = 'pending';

        if ($request->hasFile('file_pendukung')) {
            $data['file_pendukung'] = $request->file('file_pendukung')->store('sdm/lembur', 'public');
        }

        Lembur::create($data);

        return redirect('/sdm/lembur')->with('success', 'Pengajuan lembur berhasil dibuat.');
    }

    public function show(Lembur $lembur)
    {
        $lembur->load(['pegawai', 'approvedBy']);
        return Inertia::render('Sdm/Lembur/Show', [
            'lembur' => $lembur,
        ]);
    }

    public function destroy(Lembur $lembur)
    {
        if ($lembur->file_pendukung) {
            Storage::disk('public')->delete($lembur->file_pendukung);
        }
        
        $lembur->delete();
        return redirect('/sdm/lembur')->with('success', 'Data lembur berhasil dihapus.');
    }

    public function approve(Request $request, Lembur $lembur)
    {
        $lembur->update([
            'status'           => 'approved',
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Pengajuan lembur berhasil disetujui.');
    }

    public function reject(Request $request, Lembur $lembur)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $lembur->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Pengajuan lembur ditolak.');
    }
}
