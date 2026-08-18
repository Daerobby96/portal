<?php

namespace Modules\ManajemenAset\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenAset\Models\BookingRuangan;
use Modules\ManajemenAset\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookingRuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingRuangan::with(['ruangan', 'pemohon', 'approver']);

        // Filter by user role
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan', 'kaprodi'])) {
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
            ->paginate($request->get('per_page', 15))
            ->through(fn($b) => [
                'id'               => $b->id,
                'ruangan_id'       => $b->ruangan_id,
                'ruangan_nama'     => $b->ruangan?->nama_ruangan,
                'ruangan_kode'     => $b->ruangan?->kode_ruangan,
                'ruangan_gedung'   => $b->ruangan?->gedung,
                'ruangan_lantai'   => $b->ruangan?->lantai,
                'pemohon_nama'     => $b->pemohon?->name ?? 'Civitas',
                'keperluan'        => $b->keperluan,
                'tanggal'          => $b->tanggal?->format('d M Y'),
                'tanggal_raw'      => $b->tanggal?->format('Y-m-d'),
                'jam_mulai'        => $b->jam_mulai ? substr($b->jam_mulai, 0, 5) : '',
                'jam_selesai'      => $b->jam_selesai ? substr($b->jam_selesai, 0, 5) : '',
                'jumlah_peserta'   => $b->jumlah_peserta,
                'status'           => $b->status,
                'approver_nama'    => $b->approver?->name,
                'catatan_approval' => $b->catatan_approval,
                'created_at'       => $b->created_at?->format('d M Y H:i'),
            ]);

        $ruangans = Ruangan::tersedia()->orderBy('nama_ruangan')->get(['id', 'nama_ruangan', 'kode_ruangan', 'kapasitas', 'gedung']);

        $stats = [
            'pending'   => BookingRuangan::pending()->count(),
            'disetujui' => BookingRuangan::disetujui()->count(),
            'hari_ini'  => BookingRuangan::whereDate('tanggal', now())->count(),
        ];

        return Inertia::render('Aset/BookingRuangan/Index', [
            'bookings'   => $bookings,
            'ruangans'   => $ruangans,
            'stats'      => $stats,
            'filters'    => $request->only(['status', 'tanggal', 'ruangan_id']),
            'canApprove' => Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan', 'kaprodi']),
        ]);
    }

    public function create()
    {
        $ruangans = Ruangan::tersedia()->orderBy('nama_ruangan')->get(['id', 'nama_ruangan', 'kode_ruangan', 'kapasitas', 'gedung', 'lantai', 'ber_ac', 'ber_proyektor']);
        return Inertia::render('Aset/BookingRuangan/Create', [
            'ruangans' => $ruangans,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruangan_id'      => 'required|exists:ruangan,id',
            'keperluan'       => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'tanggal'         => 'required|date|after_or_equal:today',
            'jam_mulai'       => 'required|date_format:H:i',
            'jam_selesai'     => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta'  => 'nullable|integer|min:1',
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
            return back()->with('error', 'Ruangan sudah dibooking pada waktu tersebut. Silakan pilih waktu atau ruangan lain.');
        }

        $validated['pemohon_id'] = Auth::id();
        $validated['status'] = 'pending';

        BookingRuangan::create($validated);

        return redirect()->route('booking-ruangan.index')
            ->with('success', 'Pengajuan booking ruangan berhasil dikirim. Menunggu verifikasi.');
    }

    public function show(BookingRuangan $bookingRuangan)
    {
        if (!Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan', 'kaprodi']) 
            && $bookingRuangan->pemohon_id !== Auth::id()) {
            abort(403);
        }

        $bookingRuangan->load(['ruangan', 'pemohon', 'approver']);

        return Inertia::render('Aset/BookingRuangan/Show', [
            'booking' => [
                'id'               => $bookingRuangan->id,
                'ruangan_id'       => $bookingRuangan->ruangan_id,
                'ruangan_nama'     => $bookingRuangan->ruangan?->nama_ruangan,
                'ruangan_kode'     => $bookingRuangan->ruangan?->kode_ruangan,
                'ruangan_gedung'   => $bookingRuangan->ruangan?->gedung,
                'ruangan_lantai'   => $bookingRuangan->ruangan?->lantai,
                'ruangan_kapasitas'=> $bookingRuangan->ruangan?->kapasitas,
                'pemohon_id'       => $bookingRuangan->pemohon_id,
                'pemohon_nama'     => $bookingRuangan->pemohon?->name ?? 'Civitas',
                'keperluan'        => $bookingRuangan->keperluan,
                'deskripsi'        => $bookingRuangan->deskripsi,
                'tanggal'          => $bookingRuangan->tanggal?->format('d M Y'),
                'jam_mulai'        => $bookingRuangan->jam_mulai ? substr($bookingRuangan->jam_mulai, 0, 5) : '',
                'jam_selesai'      => $bookingRuangan->jam_selesai ? substr($bookingRuangan->jam_selesai, 0, 5) : '',
                'jumlah_peserta'   => $bookingRuangan->jumlah_peserta,
                'catatan_pemohon'  => $bookingRuangan->catatan_pemohon,
                'status'           => $bookingRuangan->status,
                'approver_nama'    => $bookingRuangan->approver?->name,
                'catatan_approval' => $bookingRuangan->catatan_approval,
                'created_at'       => $bookingRuangan->created_at?->format('d M Y H:i'),
            ],
            'canApprove' => Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan', 'kaprodi']),
        ]);
    }

    public function approve(Request $request, BookingRuangan $bookingRuangan)
    {
        $validated = $request->validate([
            'catatan_approval' => 'nullable|string',
        ]);

        $bookingRuangan->update([
            'status'           => 'disetujui',
            'approval_by'      => Auth::id(),
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
            'status'           => 'ditolak',
            'approval_by'      => Auth::id(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return back()->with('success', 'Booking ruangan telah ditolak.');
    }

    public function destroy(BookingRuangan $bookingRuangan)
    {
        if ($bookingRuangan->pemohon_id !== Auth::id() 
            && !Auth::user()->hasAnyRole(['super_admin', 'staff', 'pimpinan'])) {
            abort(403);
        }

        $bookingRuangan->update(['status' => 'dibatalkan']);

        return redirect()->route('booking-ruangan.index')
            ->with('success', 'Booking ruangan berhasil dibatalkan.');
    }
}
