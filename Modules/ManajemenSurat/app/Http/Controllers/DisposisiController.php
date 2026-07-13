<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ManajemenSurat\Models\Disposisi;
use Modules\ManajemenSurat\Models\SuratMasuk;
use App\Models\User;

class DisposisiController extends Controller
{
    public function create(SuratMasuk $suratMasuk)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        
        return view('manajemen-surat::disposisi.create', compact('suratMasuk', 'users'));
    }

    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'kepada_user_id' => 'required|exists:users,id',
            'isi_disposisi' => 'required|string',
            'batas_waktu' => 'nullable|date|after:today',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
        ]);

        $validated['surat_masuk_id'] = $suratMasuk->id;
        $validated['dari_user_id'] = auth()->id();
        $validated['status'] = 'pending';

        $disposisi = Disposisi::create($validated);

        // Update status surat masuk
        if ($suratMasuk->status === 'baru') {
            $suratMasuk->update(['status' => 'proses']);
        }

        // TODO: Send notification to user

        return redirect()
            ->route('surat-masuk.show', $suratMasuk)
            ->with('success', 'Disposisi berhasil dibuat dan dikirim.');
    }

    public function show(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk.jenisSurat', 'dari', 'kepada']);
        
        // Mark as read if current user is the recipient
        if ($disposisi->kepada_user_id === auth()->id() && !$disposisi->dibaca_at) {
            $disposisi->markAsRead();
        }

        return view('manajemen-surat::disposisi.show', compact('disposisi'));
    }

    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        // Only recipient can update status
        if ($disposisi->kepada_user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui disposisi ini.');
        }

        $validated = $request->validate([
            'status' => 'required|in:proses,selesai',
            'catatan_tindak_lanjut' => 'nullable|string',
        ]);

        if ($validated['status'] === 'selesai') {
            $disposisi->markAsCompleted($validated['catatan_tindak_lanjut'] ?? null);
        } else {
            $disposisi->update([
                'status' => $validated['status'],
                'catatan_tindak_lanjut' => $validated['catatan_tindak_lanjut'] ?? $disposisi->catatan_tindak_lanjut,
            ]);
        }

        return redirect()
            ->route('disposisi.show', $disposisi)
            ->with('success', 'Status disposisi berhasil diperbarui.');
    }

    public function myDisposisi(Request $request)
    {
        $query = Disposisi::with(['suratMasuk.jenisSurat', 'dari'])
            ->where('kepada_user_id', auth()->id());

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Show overdue
        if ($request->filled('overdue') && $request->overdue == '1') {
            $query->overdue();
        }

        $disposisi = $query->latest()->paginate(15);

        return view('manajemen-surat::disposisi.my-disposisi', compact('disposisi'));
    }
}
