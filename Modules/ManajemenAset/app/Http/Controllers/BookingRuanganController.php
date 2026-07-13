<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\BookingRuangan;
use Modules\ManajemenAset\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingRuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingRuangan::with(['ruangan', 'pemohon', 'approver']);

        // Filter by user role
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff', 'kaprodi'])) {
            $query->where('pemohon_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $bookings = $query->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate($request->get('per_page', 15));

        $ruangans = Ruangan::tersedia()->orderBy('nama_ruangan')->get();

        $stats = [
            'pending' => BookingRuangan::pending()->count(),
            'disetujui' => BookingRuangan::disetujui()->count(),
            'hari_ini' => BookingRuangan::whereDate('tanggal', now())->count(),
        ];

        return view('manajemenaset::booking.index', compact('bookings', 'ruangans', 'stats'));
    }

    public function create()
    {
        $ruangans = Ruangan::tersedia()->orderBy('nama_ruangan')->get();
        return view('manajemenaset::booking.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
            'keperluan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'catatan_pemohon' => 'nullable|string',
        ]);

        // Check konflik jadwal
        $konflik = BookingRuangan::where('ruangan_id', $request->ruangan_id)
            ->whereDate('tanggal', $request->tanggal)
            ->whereIn('status', ['pending', 'disetujui'])
            ->where(function($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('jam_mulai', '<=', $request->jam_mulai)
                         ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })->exists();

        if ($konflik) {
            return back()->withInput()
                ->with('error', 'Ruangan sudah dibooking pada waktu tersebut.');
        }

        $validated['pemohon_id'] = Auth::id();
        $validated['status'] = 'pending';

        BookingRuangan::create($validated);

        return redirect()->route('booking-ruangan.index')
            ->with('success', 'Booking ruangan berhasil diajukan. Menunggu approval.');
    }

    public function show(BookingRuangan $bookingRuangan)
    {
        // Check access
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff', 'kaprodi']) 
            && $bookingRuangan->pemohon_id !== Auth::id()) {
            abort(403);
        }

        $bookingRuangan->load(['ruangan', 'pemohon', 'approver']);
        return view('manajemenaset::booking.show', compact('bookingRuangan'));
    }

    public function approve(Request $request, BookingRuangan $bookingRuangan)
    {
        $validated = $request->validate([
            'catatan_approval' => 'nullable|string',
        ]);

        $bookingRuangan->update([
            'status' => 'disetujui',
            'approval_by' => Auth::id(),
            'catatan_approval' => $validated['catatan_approval'] ?? null,
        ]);

        return back()->with('success', 'Booking ruangan telah disetujui.');
    }

    public function reject(Request $request, BookingRuangan $bookingRuangan)
    {
        $validated = $request->validate([
            'catatan_approval' => 'required|string',
        ]);

        $bookingRuangan->update([
            'status' => 'ditolak',
            'approval_by' => Auth::id(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return back()->with('success', 'Booking ruangan telah ditolak.');
    }

    public function destroy(BookingRuangan $bookingRuangan)
    {
        // Only pemohon or admin can cancel
        if ($bookingRuangan->pemohon_id !== Auth::id() 
            && !Auth::user()->hasAnyRole(['super_admin', 'staff'])) {
            abort(403);
        }

        $bookingRuangan->update(['status' => 'dibatalkan']);

        return redirect()->route('booking-ruangan.index')
            ->with('success', 'Booking ruangan berhasil dibatalkan.');
    }
}
