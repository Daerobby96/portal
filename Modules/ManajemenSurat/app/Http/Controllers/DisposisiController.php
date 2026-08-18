<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\ManajemenSurat\Models\Disposisi;
use Modules\ManajemenSurat\Models\SuratMasuk;
use App\Models\User;

class DisposisiController extends Controller
{
    public function create(SuratMasuk $suratMasuk)
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email]);

        return Inertia::render('Surat/Disposisi/Create', [
            'suratMasuk' => [
                'id'           => $suratMasuk->id,
                'nomor_agenda' => $suratMasuk->nomor_agenda,
                'nomor_surat'  => $suratMasuk->nomor_surat,
                'pengirim'     => $suratMasuk->pengirim,
                'perihal'      => $suratMasuk->perihal,
                'sifat'        => $suratMasuk->sifat,
                'tanggal_terima' => $suratMasuk->tanggal_terima?->format('d M Y'),
            ],
            'users' => $users,
        ]);
    }

    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'kepada_user_id' => 'required|exists:users,id',
            'isi_disposisi'  => 'required|string',
            'batas_waktu'    => 'nullable|date|after:today',
            'prioritas'      => 'required|in:rendah,sedang,tinggi',
        ]);

        $validated['surat_masuk_id']  = $suratMasuk->id;
        $validated['dari_user_id']    = auth()->id();
        $validated['status']          = 'pending';

        Disposisi::create($validated);

        if ($suratMasuk->status === 'baru') {
            $suratMasuk->update(['status' => 'proses']);
        }

        return redirect()->route('surat-masuk.show', $suratMasuk)
            ->with('success', 'Disposisi berhasil dibuat dan dikirim.');
    }

    public function show(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk.jenisSurat', 'dari', 'kepada']);

        if ($disposisi->kepada_user_id === auth()->id() && !$disposisi->dibaca_at) {
            $disposisi->markAsRead();
        }

        return Inertia::render('Surat/Disposisi/Show', [
            'disposisi' => [
                'id'                    => $disposisi->id,
                'isi_disposisi'         => $disposisi->isi_disposisi,
                'prioritas'             => $disposisi->prioritas,
                'status'                => $disposisi->status,
                'batas_waktu'           => $disposisi->batas_waktu?->format('d M Y'),
                'catatan_tindak_lanjut' => $disposisi->catatan_tindak_lanjut,
                'dibaca_at'             => $disposisi->dibaca_at?->format('d M Y H:i'),
                'selesai_at'            => $disposisi->selesai_at?->format('d M Y H:i'),
                'created_at'            => $disposisi->created_at->format('d M Y H:i'),
                'dari_nama'             => $disposisi->dari?->name,
                'kepada_nama'           => $disposisi->kepada?->name,
                'surat_masuk'           => [
                    'id'             => $disposisi->suratMasuk?->id,
                    'nomor_agenda'   => $disposisi->suratMasuk?->nomor_agenda,
                    'nomor_surat'    => $disposisi->suratMasuk?->nomor_surat,
                    'pengirim'       => $disposisi->suratMasuk?->pengirim,
                    'perihal'        => $disposisi->suratMasuk?->perihal,
                    'sifat'          => $disposisi->suratMasuk?->sifat,
                    'jenis_surat'    => $disposisi->suratMasuk?->jenisSurat?->nama,
                    'tanggal_terima' => $disposisi->suratMasuk?->tanggal_terima?->format('d M Y'),
                ],
            ],
            'canUpdateStatus' => $disposisi->kepada_user_id === auth()->id(),
            'updateStatusUrl' => route('disposisi.update-status', $disposisi),
        ]);
    }

    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        if ($disposisi->kepada_user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui disposisi ini.');
        }

        $validated = $request->validate([
            'status'                => 'required|in:proses,selesai',
            'catatan_tindak_lanjut' => 'nullable|string',
        ]);

        if ($validated['status'] === 'selesai') {
            $disposisi->markAsCompleted($validated['catatan_tindak_lanjut'] ?? null);
        } else {
            $disposisi->update([
                'status'                => $validated['status'],
                'catatan_tindak_lanjut' => $validated['catatan_tindak_lanjut'] ?? $disposisi->catatan_tindak_lanjut,
            ]);
        }

        return redirect()->route('disposisi.show', $disposisi)
            ->with('success', 'Status disposisi berhasil diperbarui.');
    }

    public function myDisposisi(Request $request)
    {
        $query = Disposisi::with(['suratMasuk.jenisSurat', 'dari'])
            ->where('kepada_user_id', auth()->id());

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('overdue') && $request->overdue == '1') {
            $query->where('batas_waktu', '<', now())->whereNotIn('status', ['selesai']);
        }

        $disposisi = $query->latest()->paginate(15)->through(fn ($d) => [
            'id'                    => $d->id,
            'isi_disposisi'         => $d->isi_disposisi,
            'prioritas'             => $d->prioritas,
            'status'                => $d->status,
            'batas_waktu'           => $d->batas_waktu?->format('Y-m-d'),
            'catatan_tindak_lanjut' => $d->catatan_tindak_lanjut,
            'dari_nama'             => $d->dari?->name,
            'created_at'            => $d->created_at->format('d M Y'),
            'perihal'               => $d->suratMasuk?->perihal,
            'surat_masuk_id'        => $d->surat_masuk_id,
            'jenis_surat'           => $d->suratMasuk?->jenisSurat?->nama,
            'is_overdue'            => $d->batas_waktu && $d->batas_waktu->isPast() && $d->status !== 'selesai',
        ]);

        return Inertia::render('Surat/Disposisi/MyDisposisi', [
            'disposisi' => $disposisi,
            'filters'   => $request->only(['status', 'overdue']),
            'stats' => [
                'pending'  => Disposisi::where('kepada_user_id', auth()->id())->whereIn('status', ['pending', 'dibaca'])->count(),
                'proses'   => Disposisi::where('kepada_user_id', auth()->id())->where('status', 'proses')->count(),
                'selesai'  => Disposisi::where('kepada_user_id', auth()->id())->where('status', 'selesai')->count(),
                'overdue'  => Disposisi::where('kepada_user_id', auth()->id())->where('batas_waktu', '<', now())->whereNotIn('status', ['selesai'])->count(),
            ],
        ]);
    }
}
