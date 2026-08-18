<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\Peminjaman;
use Modules\ManajemenAset\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['aset.kategori', 'peminjam', 'approver']);

        // Filter by user role
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan'])) {
            $query->where('peminjam_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('aset', function($sq) use ($q) {
                $sq->where('nama_aset', 'like', "%{$q}%")
                   ->orWhere('kode_aset', 'like', "%{$q}%");
            });
        }

        $peminjamans = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15))
            ->through(fn($pm) => [
                'id'                      => $pm->id,
                'aset_id'                 => $pm->aset_id,
                'aset_kode'               => $pm->aset?->kode_aset,
                'aset_nama'               => $pm->aset?->nama_aset,
                'peminjam_nama'           => $pm->peminjam?->name ?? 'Civitas',
                'keperluan'               => $pm->keperluan,
                'tanggal_pinjam'          => $pm->tanggal_pinjam?->format('d M Y'),
                'tanggal_kembali_rencana' => $pm->tanggal_kembali_rencana?->format('d M Y'),
                'tanggal_kembali_aktual'  => $pm->tanggal_kembali_aktual?->format('d M Y'),
                'status'                  => $pm->status,
                'approver_nama'           => $pm->approver?->name,
                'catatan_approval'        => $pm->catatan_approval,
                'denda'                   => $pm->denda,
                'is_terlambat'            => $pm->isTerlambat(),
                'created_at'              => $pm->created_at?->format('d M Y H:i'),
            ]);

        $stats = [
            'pending'   => Peminjaman::pending()->count(),
            'dipinjam'  => Peminjaman::dipinjam()->count(),
            'terlambat' => Peminjaman::dipinjam()
                ->where('tanggal_kembali_rencana', '<', now())->count(),
        ];

        return Inertia::render('Aset/Peminjaman/Index', [
            'peminjamans' => $peminjamans,
            'stats'       => $stats,
            'filters'     => $request->only(['search', 'status']),
            'canApprove'  => Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan']),
        ]);
    }

    public function create()
    {
        $asets = Aset::where('status', 'aktif')
            ->where('kondisi', 'baik')
            ->orderBy('nama_aset')
            ->get(['id', 'nama_aset', 'kode_aset', 'lokasi']);

        return Inertia::render('Aset/Peminjaman/Create', [
            'asets' => $asets,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_id'                 => 'required|exists:asets,id',
            'keperluan'               => 'required|string|max:255',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan_peminjam'        => 'nullable|string',
        ]);

        $validated['peminjam_id'] = Auth::id();
        $validated['status'] = 'pending';

        Peminjaman::create($validated);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman aset berhasil dikirim. Menunggu verifikasi.');
    }

    public function show(Peminjaman $peminjaman)
    {
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan']) && $peminjaman->peminjam_id !== Auth::id()) {
            abort(403);
        }

        $peminjaman->load(['aset.kategori', 'peminjam', 'approver']);

        return Inertia::render('Aset/Peminjaman/Show', [
            'peminjaman' => [
                'id'                      => $peminjaman->id,
                'aset_id'                 => $peminjaman->aset_id,
                'aset_kode'               => $peminjaman->aset?->kode_aset,
                'aset_nama'               => $peminjaman->aset?->nama_aset,
                'kategori_nama'           => $peminjaman->aset?->kategori?->nama,
                'lokasi_aset'             => $peminjaman->aset?->lokasi,
                'peminjam_id'             => $peminjaman->peminjam_id,
                'peminjam_nama'           => $peminjaman->peminjam?->name ?? 'Civitas',
                'keperluan'               => $peminjaman->keperluan,
                'tanggal_pinjam'          => $peminjaman->tanggal_pinjam?->format('d M Y'),
                'tanggal_kembali_rencana' => $peminjaman->tanggal_kembali_rencana?->format('d M Y'),
                'tanggal_kembali_aktual'  => $peminjaman->tanggal_kembali_aktual?->format('d M Y'),
                'catatan_peminjam'        => $peminjaman->catatan_peminjam,
                'status'                  => $peminjaman->status,
                'approver_nama'           => $peminjaman->approver?->name,
                'catatan_approval'        => $peminjaman->catatan_approval,
                'kondisi_saat_pinjam'     => $peminjaman->kondisi_saat_pinjam,
                'kondisi_saat_kembali'    => $peminjaman->kondisi_saat_kembali,
                'denda'                   => $peminjaman->denda,
                'is_terlambat'            => $peminjaman->isTerlambat(),
                'created_at'              => $peminjaman->created_at?->format('d M Y H:i'),
            ],
            'canApprove' => Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan']),
        ]);
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'catatan_approval'    => 'nullable|string',
            'kondisi_saat_pinjam' => 'nullable|string',
        ]);

        $peminjaman->update([
            'status'              => 'disetujui',
            'approval_by'         => Auth::id(),
            'catatan_approval'    => $validated['catatan_approval'] ?? null,
            'kondisi_saat_pinjam' => $validated['kondisi_saat_pinjam'] ?? 'Baik',
        ]);

        return back()->with('success', 'Peminjaman telah disetujui.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'catatan_approval' => 'required|string',
        ]);

        $peminjaman->update([
            'status'           => 'ditolak',
            'approval_by'      => Auth::id(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return back()->with('success', 'Peminjaman telah ditolak.');
    }

    public function return(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'kondisi_saat_kembali' => 'required|string',
            'denda'                => 'nullable|numeric|min:0',
        ]);

        $peminjaman->update([
            'status'                 => 'dikembalikan',
            'tanggal_kembali_aktual' => now(),
            'kondisi_saat_kembali'   => $validated['kondisi_saat_kembali'],
            'denda'                  => $validated['denda'] ?? 0,
        ]);

        return back()->with('success', 'Pengembalian aset berhasil dicatat.');
    }
}
