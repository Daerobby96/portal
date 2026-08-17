<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Cuti;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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

        $cutis = $query->paginate(15)->withQueryString();
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();

        $stats = [
            'total'    => Cuti::count(),
            'pending'  => Cuti::where('status', 'pending')->count(),
            'approved' => Cuti::where('status', 'approved')->count(),
            'rejected' => Cuti::where('status', 'rejected')->count(),
        ];

        return Inertia::render('Sdm/Cuti/Index', [
            'cutis'    => $cutis,
            'pegawais' => $pegawais,
            'stats'    => $stats,
            'filters'  => [
                'status'     => $request->status ?? '',
                'jenis_cuti' => $request->jenis_cuti ?? '',
                'pegawai_id' => $request->pegawai_id ?? '',
            ],
        ]);
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return Inertia::render('Sdm/Cuti/Create', [
            'pegawais' => $pegawais,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'      => 'required|exists:pegawais,id',
            'jenis_cuti'      => 'required|in:tahunan,sakit,melahirkan,besar,alasan_penting',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'          => 'required|string|max:2000',
            'file_pendukung'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        
        $mulai = \Carbon\Carbon::parse($request->tanggal_mulai);
        $selesai = \Carbon\Carbon::parse($request->tanggal_selesai);
        $data['jumlah_hari'] = $mulai->diffInDays($selesai) + 1;
        $data['status'] = 'pending';

        if ($request->hasFile('file_pendukung')) {
            $data['file_pendukung'] = $request->file('file_pendukung')->store('sdm/cuti', 'public');
        }

        Cuti::create($data);

        return redirect('/sdm/cuti')->with('success', 'Pengajuan cuti berhasil diajukan.');
    }

    public function show(Cuti $cuti)
    {
        $cuti->load(['pegawai', 'approvedBy']);
        return Inertia::render('Sdm/Cuti/Show', [
            'cuti' => $cuti,
        ]);
    }

    public function destroy(Cuti $cuti)
    {
        if ($cuti->file_pendukung) {
            Storage::disk('public')->delete($cuti->file_pendukung);
        }
        $cuti->delete();
        return redirect('/sdm/cuti')->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    public function approve(Request $request, Cuti $cuti)
    {
        $cuti->update([
            'status'           => 'approved',
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil disetujui (Approved).');
    }

    public function reject(Request $request, Cuti $cuti)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $cuti->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Pengajuan cuti telah ditolak.');
    }
}
