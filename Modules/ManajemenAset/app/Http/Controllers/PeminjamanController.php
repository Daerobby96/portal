<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Peminjaman;
use Modules\ManajemenAset\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['aset.kategori', 'peminjam', 'approver']);

        // Filter by user role
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff'])) {
            $query->where('peminjam_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('aset', function($q) use ($request) {
                $q->where('nama_aset', 'like', '%' . $request->search . '%');
            });
        }

        $peminjamans = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $stats = [
            'pending' => Peminjaman::pending()->count(),
            'dipinjam' => Peminjaman::dipinjam()->count(),
            'terlambat' => Peminjaman::dipinjam()
                ->where('tanggal_kembali_rencana', '<', now())->count(),
        ];

        return view('manajemenaset::peminjaman.index', compact('peminjamans', 'stats'));
    }

    public function create()
    {
        $asets = Aset::where('status', 'aktif')
            ->where('kondisi', 'baik')
            ->orderBy('nama_aset')
            ->get();
        return view('manajemenaset::peminjaman.create', compact('asets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'keperluan' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan_peminjam' => 'nullable|string',
        ]);

        $validated['peminjam_id'] = Auth::id();
        $validated['status'] = 'pending';

        Peminjaman::create($validated);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil diajukan. Menunggu approval.');
    }

    public function show(Peminjaman $peminjaman)
    {
        // Check access
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff']) && $peminjaman->peminjam_id !== Auth::id()) {
            abort(403);
        }

        $peminjaman->load(['aset.kategori', 'peminjam', 'approver']);
        return view('manajemenaset::peminjaman.show', compact('peminjaman'));
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'catatan_approval' => 'nullable|string',
            'kondisi_saat_pinjam' => 'nullable|string',
        ]);

        $peminjaman->update([
            'status' => 'disetujui',
            'approval_by' => Auth::id(),
            'catatan_approval' => $validated['catatan_approval'] ?? null,
            'kondisi_saat_pinjam' => $validated['kondisi_saat_pinjam'] ?? null,
        ]);

        return back()->with('success', 'Peminjaman telah disetujui.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'catatan_approval' => 'required|string',
        ]);

        $peminjaman->update([
            'status' => 'ditolak',
            'approval_by' => Auth::id(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return back()->with('success', 'Peminjaman telah ditolak.');
    }

    public function return(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'kondisi_saat_kembali' => 'required|string',
            'denda' => 'nullable|numeric|min:0',
        ]);

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali_aktual' => now(),
            'kondisi_saat_kembali' => $validated['kondisi_saat_kembali'],
            'denda' => $validated['denda'] ?? 0,
        ]);

        return back()->with('success', 'Aset telah dikembalikan.');
    }
}
