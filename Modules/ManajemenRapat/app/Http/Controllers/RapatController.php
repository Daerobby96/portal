<?php

namespace Modules\ManajemenRapat\Http\Controllers;

use Modules\ManajemenRapat\Models\Rapat;
use Modules\ManajemenRapat\Models\RapatAgenda;
use Modules\ManajemenRapat\Models\RapatPeserta;
use Modules\ManajemenRapat\Models\RapatTindakLanjut;
use Modules\ManajemenRapat\Models\RapatLampiran;
use Modules\DataMaster\Models\Periode;
use App\Models\User;
use App\Notifications\RapatUndanganNotification;
use App\Notifications\RapatTindakLanjutNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RapatController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes     = Periode::orderBy('tahun', 'desc')->get();
        $periodeId    = $request->get('periode_id', $periodeAktif?->id);

        $query = Rapat::with(['creator', 'peserta'])
            ->byPeriode($periodeId)
            ->orderBy('tanggal', 'desc');

        // Filter tambahan
        if ($request->filled('jenis'))  $query->where('jenis', $request->jenis);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('dari'))   $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $request->sampai);

        $rapats = $query->get();

        // Statistik dashboard
        $stats = [
            'total'       => $rapats->count(),
            'draft'       => $rapats->where('status', Rapat::STATUS_DRAFT)->count(),
            'terjadwal'   => $rapats->where('status', Rapat::STATUS_TERJADWAL)->count(),
            'berlangsung' => $rapats->where('status', Rapat::STATUS_BERLANGSUNG)->count(),
            'selesai'     => $rapats->where('status', Rapat::STATUS_SELESAI)->count(),
        ];

        // Rapat mendatang (30 hari ke depan)
        $mendatang = Rapat::byPeriode($periodeId)
            ->where('status', Rapat::STATUS_TERJADWAL)
            ->whereDate('tanggal', '>=', today())
            ->whereDate('tanggal', '<=', today()->addDays(30))
            ->orderBy('tanggal')
            ->get();

        // Tindak lanjut overdue
        $overdueActions = RapatTindakLanjut::with(['rapat', 'pic'])
            ->whereHas('rapat', fn($q) => $q->where('periode_id', $periodeId))
            ->whereIn('status', [RapatTindakLanjut::STATUS_BELUM_MULAI, RapatTindakLanjut::STATUS_DALAM_PROSES])
            ->whereDate('deadline', '<', today())
            ->get();

        // Data grafik per bulan
        $chartData = $this->getChartData($periodeId);

        return view('manajemenrapat::index', compact(
            'rapats', 'periodes', 'periodeId', 'stats',
            'mendatang', 'overdueActions', 'chartData'
        ));
    }

    // ── Create ────────────────────────────────────────────────────
    public function create()
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes     = Periode::orderBy('tahun', 'desc')->get();
        $users        = User::where('is_active', true)->with('roles')->orderBy('name')->get();

        return view('manajemenrapat::create', compact('periodeAktif', 'periodes', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'jenis'         => 'required|in:RTM,Koordinasi,Evaluasi,Audit,Khusus',
            'tanggal'       => 'required|date|after_or_equal:today',
            'waktu_mulai'   => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'tempat'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string|max:2000',
            'periode_id'    => 'required|exists:periodes,id',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status']     = Rapat::STATUS_DRAFT;

        $rapat = Rapat::create($validated);

        return redirect()->route('rapat.show', $rapat)
            ->with('success', 'Rapat berhasil dibuat. Tambahkan agenda dan peserta sebelum menjadwalkan.');
    }

    // ── Show / Detail ─────────────────────────────────────────────
    public function show(Rapat $rapat)
    {
        $rapat->load([
            'creator',
            'agendas.notulensiUpdatedBy',
            'peserta.user.role',
            'tindakLanjuts.pic.role',
            'lampirans.uploader',
        ]);

        $ringkasanKehadiran = [
            'hadir'       => $rapat->peserta->where('status_kehadiran', 'hadir')->count(),
            'tidak_hadir' => $rapat->peserta->where('status_kehadiran', 'tidak_hadir')->count(),
            'izin'        => $rapat->peserta->where('status_kehadiran', 'izin')->count(),
            'diundang'    => $rapat->peserta->where('status_kehadiran', 'diundang')->count(),
        ];

        $ringkasanTL = [
            'belum_mulai'  => $rapat->tindakLanjuts->where('status', 'belum_mulai')->count(),
            'dalam_proses' => $rapat->tindakLanjuts->where('status', 'dalam_proses')->count(),
            'selesai'      => $rapat->tindakLanjuts->where('status', 'selesai')->count(),
            'dibatalkan'   => $rapat->tindakLanjuts->where('status', 'dibatalkan')->count(),
        ];

        // Load users aktif untuk form peserta & tindak lanjut
        $users = User::where('is_active', true)->with('roles')->orderBy('name')->get();

        return view('manajemenrapat::show', compact('rapat', 'ringkasanKehadiran', 'ringkasanTL', 'users'));
    }

    // ── Edit ─────────────────────────────────────────────────────
    public function edit(Rapat $rapat)
    {
        if ($rapat->isLocked()) {
            return back()->with('error', 'Rapat yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $users    = User::where('is_active', true)->orderBy('name')->get();

        return view('manajemenrapat::edit', compact('rapat', 'periodes', 'users'));
    }

    public function update(Request $request, Rapat $rapat)
    {
        if ($rapat->isLocked()) {
            return back()->with('error', 'Rapat yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'jenis'         => 'required|in:RTM,Koordinasi,Evaluasi,Audit,Khusus',
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'tempat'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string|max:2000',
        ]);

        $oldTanggal   = $rapat->tanggal->format('Y-m-d');
        $oldWaktuMulai = $rapat->waktu_mulai;

        // Tambahan field RTM
        if ($rapat->jenis === Rapat::JENIS_RTM) {
            $rtmFields = $request->only([
                'input_audit_internal', 'input_umpan_balik', 'input_kinerja_proses',
                'input_status_tindakan', 'input_perubahan_sistem', 'input_rekomendasi',
                'output_keefektifan', 'output_perbaikan', 'output_sumber_daya',
            ]);
            $validated = array_merge($validated, $rtmFields);
        }

        $rapat->update($validated);

        // Kirim notifikasi jika jadwal berubah dan status terjadwal
        $jadwalBerubah = $oldTanggal !== $rapat->fresh()->tanggal->format('Y-m-d')
                      || $oldWaktuMulai !== $rapat->fresh()->waktu_mulai;

        if ($jadwalBerubah && $rapat->status === Rapat::STATUS_TERJADWAL) {
            $this->sendNotifikasiPerubahanJadwal($rapat);
        }

        return redirect()->route('rapat.show', $rapat)
            ->with('success', 'Rapat berhasil diperbarui.');
    }

    // ── Delete ────────────────────────────────────────────────────
    public function destroy(Rapat $rapat)
    {
        // Hapus file lampiran dari storage
        foreach ($rapat->lampirans as $lampiran) {
            Storage::disk('public')->delete($lampiran->path);
        }

        $rapat->delete();

        return redirect()->route('rapat.index')
            ->with('success', 'Rapat berhasil dihapus.');
    }

    // ── Ubah Status ───────────────────────────────────────────────
    public function ubahStatus(Request $request, Rapat $rapat)
    {
        $request->validate([
            'status'             => 'required|in:draft,terjadwal,berlangsung,selesai,dibatalkan',
            'alasan_pembatalan'  => 'required_if:status,dibatalkan|nullable|string|max:500',
            'kesimpulan'         => 'nullable|string',
        ]);

        $newStatus = $request->status;

        // Validasi transisi ke terjadwal
        if ($newStatus === Rapat::STATUS_TERJADWAL) {
            if (!$rapat->tanggal || !$rapat->waktu_mulai || !$rapat->waktu_selesai) {
                return back()->with('error', 'Lengkapi tanggal dan waktu rapat sebelum menjadwalkan.');
            }
            if ($rapat->agendas->isEmpty()) {
                return back()->with('error', 'Rapat harus memiliki minimal satu agenda sebelum dijadwalkan.');
            }
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === Rapat::STATUS_DIBATALKAN) {
            $updateData['alasan_pembatalan'] = $request->alasan_pembatalan;
        }

        if ($newStatus === Rapat::STATUS_SELESAI && $request->filled('kesimpulan')) {
            $updateData['kesimpulan'] = $request->kesimpulan;
        }

        $rapat->update($updateData);

        // Notifikasi peserta
        if ($newStatus === Rapat::STATUS_TERJADWAL) {
            $this->sendUndangan($rapat, $rapat->peserta->pluck('user'));
        }

        if ($newStatus === Rapat::STATUS_DIBATALKAN) {
            $this->sendNotifikasiPembatalan($rapat);
        }

        $label = Rapat::statusOptions()[$newStatus];
        return back()->with('success', "Status rapat berubah menjadi \"{$label}\".");
    }

    // ── Agenda CRUD ───────────────────────────────────────────────
    public function storeAgenda(Request $request, Rapat $rapat)
    {
        if (in_array($rapat->status, [Rapat::STATUS_SELESAI, Rapat::STATUS_DIBATALKAN])) {
            return back()->with('error', 'Agenda tidak dapat ditambahkan pada rapat yang sudah selesai atau dibatalkan.');
        }

        $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'estimasi_durasi' => 'required|integer|min:1|max:999',
        ]);

        // Query langsung ke DB untuk menghindari stale collection
        $maxUrutan = RapatAgenda::where('rapat_id', $rapat->id)->max('urutan') ?? 0;

        RapatAgenda::create([
            'rapat_id'        => $rapat->id,
            'urutan'          => $maxUrutan + 1,
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'estimasi_durasi' => $request->estimasi_durasi,
        ]);

        return back()->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function destroyAgenda(Rapat $rapat, RapatAgenda $agenda)
    {
        if (in_array($rapat->status, [Rapat::STATUS_SELESAI, Rapat::STATUS_DIBATALKAN])) {
            return back()->with('error', 'Agenda tidak dapat dihapus pada rapat yang sudah selesai atau dibatalkan.');
        }

        $agenda->delete();

        // Renumber agenda dari DB langsung
        RapatAgenda::where('rapat_id', $rapat->id)
            ->orderBy('urutan')
            ->get()
            ->each(function ($item, $index) {
                $item->update(['urutan' => $index + 1]);
            });

        return back()->with('success', 'Agenda berhasil dihapus.');
    }

    public function updateNotulensi(Request $request, Rapat $rapat, RapatAgenda $agenda)
    {
        if ($rapat->status === Rapat::STATUS_SELESAI && !Auth::user()->isSuperAdmin()) {
            return back()->with('error', 'Notulensi rapat yang sudah selesai hanya dapat diubah oleh Super Admin.');
        }

        $request->validate(['notulensi' => 'nullable|string']);

        $agenda->update([
            'notulensi'              => $request->notulensi,
            'notulensi_updated_by'   => Auth::id(),
            'notulensi_updated_at'   => now(),
        ]);

        return back()->with('success', 'Notulensi berhasil disimpan.');
    }

    // ── Search Peserta (AJAX) ─────────────────────────────────────
    public function searchPeserta(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'ilike', "%{$q}%")
                      ->orWhere('nip', 'ilike', "%{$q}%")
                      ->orWhere('email', 'ilike', "%{$q}%")
                      ->orWhere('unit_kerja', 'ilike', "%{$q}%")
                      ->orWhere('jabatan', 'ilike', "%{$q}%");
            })
            ->with('roles')
            ->orderBy('name')
            ->limit(15)
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'nip'        => $u->nip,
                'email'      => $u->email,
                'jabatan'    => $u->jabatan,
                'unit_kerja' => $u->unit_kerja,
                'role'       => $u->roles->first()?->display_name ?? $u->roles->first()?->name,
                'tipe'       => 'internal',
            ]);

        return response()->json($users);
    }
    // ── Peserta CRUD ──────────────────────────────────────────────
    public function storePeserta(Request $request, Rapat $rapat)
    {
        $tipe = $request->input('tipe_peserta', 'eksternal');
        $statusAwal = in_array($request->status_kehadiran_awal, ['diundang','hadir'])
            ? $request->status_kehadiran_awal
            : RapatPeserta::STATUS_DIUNDANG;

        if ($tipe === 'internal' && $request->filled('user_id')) {
            // ── Peserta dari user sistem ──────────────────────────
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'peran'   => 'required|in:Ketua,Notulis,Peserta',
            ]);

            $user = User::findOrFail($request->user_id);

            if (!$user->is_active) {
                return back()->with('error', 'Pengguna tidak ditemukan atau tidak aktif.');
            }
            if ($rapat->hasUserAsPeserta($request->user_id)) {
                return back()->with('error', "{$user->name} sudah terdaftar sebagai peserta rapat ini.");
            }

            // Cari apakah user ini juga terdaftar sebagai pegawai
            $pegawai = \Modules\Sdm\Models\Pegawai::where('user_id', $user->id)->first();

            RapatPeserta::create([
                'rapat_id'         => $rapat->id,
                'user_id'          => $user->id,
                'pegawai_id'       => $pegawai?->id,
                'peran'            => $request->peran,
                'keterangan'       => $request->keterangan,
                'status_kehadiran' => $statusAwal,
            ]);

            // Kirim notifikasi jika rapat sudah terjadwal
            if ($rapat->status === Rapat::STATUS_TERJADWAL) {
                try { $user->notify(new RapatUndanganNotification($rapat)); }
                catch (\Exception $e) { \Log::warning("Gagal kirim notif ke user {$user->id}: " . $e->getMessage()); }
            }

            return back()->with('success', "Peserta \"{$user->name}\" berhasil ditambahkan.");
        }

        // ── Peserta eksternal / dari data pegawai ─────────────────
        $request->validate([
            'nama_eksternal'    => 'required|string|max:255',
            'instansi'          => 'nullable|string|max:255',
            'jabatan_eksternal' => 'nullable|string|max:255',
            'email_eksternal'   => 'nullable|email|max:255',
            'no_hp_eksternal'   => 'nullable|string|max:50',
            'peran'             => 'required|in:Ketua,Notulis,Peserta',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        // Jika dipilih dari data pegawai
        $linkedUserId = null;
        $linkedPegawaiId = null;
        if ($request->filled('pegawai_id')) {
            $pegawai = \Modules\Sdm\Models\Pegawai::find($request->pegawai_id);
            if ($pegawai) {
                $linkedPegawaiId = $pegawai->id;
                if ($pegawai->user_id) {
                    $linkedUserId = $pegawai->user_id;
                    if ($rapat->hasUserAsPeserta($linkedUserId)) {
                        return back()->with('error', "{$request->nama_eksternal} sudah terdaftar sebagai peserta rapat ini.");
                    }
                } else {
                    if ($rapat->peserta()->where('pegawai_id', $linkedPegawaiId)->exists()) {
                        return back()->with('error', "{$request->nama_eksternal} sudah terdaftar sebagai peserta rapat ini.");
                    }
                }
            }
        }

        RapatPeserta::create([
            'rapat_id'          => $rapat->id,
            'user_id'           => $linkedUserId,
            'pegawai_id'        => $linkedPegawaiId,
            'nama_eksternal'    => $request->nama_eksternal,
            'instansi'          => $request->instansi,
            'jabatan_eksternal' => $request->jabatan_eksternal,
            'email_eksternal'   => $request->email_eksternal,
            'no_hp_eksternal'   => $request->no_hp_eksternal,
            'peran'             => $request->peran,
            'keterangan'        => $request->keterangan,
            'status_kehadiran'  => $statusAwal,
        ]);

        return back()->with('success', "Peserta \"{$request->nama_eksternal}\" berhasil ditambahkan.");
    }

    public function updateKehadiran(Request $request, Rapat $rapat, RapatPeserta $peserta)
    {
        $request->validate([
            'status_kehadiran' => 'required|in:diundang,hadir,tidak_hadir,izin',
        ]);

        $currentUser = Auth::user();

        // Peserta internal bisa update diri sendiri; eksternal hanya bisa diupdate penyelenggara
        $canUpdate = $currentUser->hasAnyRole(["super_admin", "pimpinan"])
                  || (!$peserta->isEksternal() && $peserta->user_id === $currentUser->id);

        if (!$canUpdate) {
            abort(403);
        }

        $peserta->update([
            'status_kehadiran'     => $request->status_kehadiran,
            'kehadiran_updated_at' => now(),
        ]);

        return back()->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    public function destroyPeserta(Rapat $rapat, RapatPeserta $peserta)
    {
        $peserta->delete();
        return back()->with('success', 'Peserta berhasil dihapus dari rapat.');
    }

    // ── Tindak Lanjut CRUD ────────────────────────────────────────
    public function storeTindakLanjut(Request $request, Rapat $rapat)
    {
        $request->validate([
            'deskripsi'  => 'required|string|max:1000',
            'pic_id'     => 'required|exists:users,id',
            'deadline'   => 'required|date',
            'prioritas'  => 'required|in:Tinggi,Sedang,Rendah',
        ]);

        $tl = RapatTindakLanjut::create([
            'rapat_id'  => $rapat->id,
            'pic_id'    => $request->pic_id,
            'deskripsi' => $request->deskripsi,
            'deadline'  => $request->deadline,
            'prioritas' => $request->prioritas,
            'status'    => RapatTindakLanjut::STATUS_BELUM_MULAI,
        ]);

        // Notifikasi ke PIC
        try {
            $tl->pic->notify(new RapatTindakLanjutNotification($tl, 'assigned'));
        } catch (\Exception $e) {
            \Log::warning("Gagal kirim notifikasi TL rapat ke PIC {$tl->pic_id}: " . $e->getMessage());
        }

        return back()->with('success', 'Tindak lanjut berhasil ditambahkan.');
    }

    public function updateTindakLanjut(Request $request, Rapat $rapat, RapatTindakLanjut $tindakLanjut)
    {
        $request->validate([
            'status'          => 'required|in:belum_mulai,dalam_proses,selesai,dibatalkan',
            'catatan_progres' => 'nullable|string|max:500',
        ]);

        $currentUser  = Auth::user();
        $canUpdate    = $currentUser->hasAnyRole(["super_admin", "pimpinan"])
                     || $tindakLanjut->pic_id === $currentUser->id;

        if (!$canUpdate) {
            abort(403);
        }

        $updateData = [
            'status'          => $request->status,
            'catatan_progres' => $request->catatan_progres ?? $tindakLanjut->catatan_progres,
        ];

        if ($request->status === RapatTindakLanjut::STATUS_SELESAI) {
            $updateData['tanggal_selesai_aktual'] = today();
            $updateData['completed_by']           = Auth::id();
        }

        $tindakLanjut->update($updateData);

        return back()->with('success', 'Status tindak lanjut berhasil diperbarui.');
    }

    // ── Lampiran ─────────────────────────────────────────────────
    public function storeLampiran(Request $request, Rapat $rapat)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,docx,xlsx,pptx',
        ]);

        if ($rapat->lampirans->count() >= 20) {
            return back()->with('error', 'Maksimal 20 lampiran per rapat sudah tercapai.');
        }

        $file   = $request->file('file');
        $path   = $file->store("rapat/{$rapat->id}/lampiran", 'public');

        RapatLampiran::create([
            'rapat_id'    => $rapat->id,
            'uploaded_by' => Auth::id(),
            'nama_asli'   => $file->getClientOriginalName(),
            'path'        => $path,
            'mime_type'   => $file->getMimeType(),
            'ukuran'      => $file->getSize(),
        ]);

        return back()->with('success', 'Lampiran berhasil diunggah.');
    }

    public function destroyLampiran(Rapat $rapat, RapatLampiran $lampiran)
    {
        Storage::disk('public')->delete($lampiran->path);
        $lampiran->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }

    public function downloadLampiran(Rapat $rapat, RapatLampiran $lampiran)
    {
        if (!Storage::disk('public')->exists($lampiran->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($lampiran->path, $lampiran->nama_asli);
    }

    // ── Export PDF Notulensi ──────────────────────────────────────
    public function exportPdf(Rapat $rapat)
    {
        $rapat->load(['agendas', 'peserta.user', 'tindakLanjuts.pic', 'creator']);
        $setting = \App\Models\Setting::first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('manajemenrapat::pdf-notulensi', compact('rapat', 'setting'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Notulensi-' . str_replace(' ', '-', $rapat->judul) . '-' . $rapat->tanggal->format('Y-m-d') . '.pdf';

        // Preview di browser (inline) alih-alih download langsung
        return $pdf->stream($filename);
    }

    // ── Private helpers ───────────────────────────────────────────
    private function sendUndangan(Rapat $rapat, $users)
    {
        foreach ($users as $user) {
            try {
                $user->notify(new RapatUndanganNotification($rapat));
            } catch (\Exception $e) {
                \Log::warning("Gagal kirim undangan rapat ke user {$user->id}: " . $e->getMessage());
            }
        }
    }

    private function sendNotifikasiPembatalan(Rapat $rapat)
    {
        $rapat->load('peserta.user');
        foreach ($rapat->peserta as $p) {
            try {
                $p->user->notify(new \App\Notifications\RapatPembatalanNotification($rapat));
            } catch (\Exception $e) {
                \Log::warning("Gagal kirim notifikasi pembatalan ke user {$p->user_id}: " . $e->getMessage());
            }
        }
    }

    private function sendNotifikasiPerubahanJadwal(Rapat $rapat)
    {
        $rapat->load('peserta.user');
        foreach ($rapat->peserta as $p) {
            try {
                $p->user->notify(new \App\Notifications\RapatPerubahanJadwalNotification($rapat));
            } catch (\Exception $e) {
                \Log::warning("Gagal kirim notifikasi perubahan jadwal ke user {$p->user_id}: " . $e->getMessage());
            }
        }
    }

    private function getChartData(int $periodeId): array
    {
        $data = Rapat::byPeriode($periodeId)
            ->selectRaw("TO_CHAR(tanggal, 'Mon') as bulan, EXTRACT(MONTH FROM tanggal) as bulan_num, COUNT(*) as total")
            ->groupBy('bulan', 'bulan_num')
            ->orderBy('bulan_num')
            ->get();

        return [
            'labels' => $data->pluck('bulan')->toArray(),
            'values' => $data->pluck('total')->toArray(),
        ];
    }
}

