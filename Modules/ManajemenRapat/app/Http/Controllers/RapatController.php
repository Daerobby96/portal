<?php

namespace Modules\ManajemenRapat\Http\Controllers;

use Modules\ManajemenRapat\Models\Rapat;
use Modules\ManajemenRapat\Models\RapatAgenda;
use Modules\ManajemenRapat\Models\RapatPeserta;
use Modules\ManajemenRapat\Models\RapatTindakLanjut;
use Modules\ManajemenRapat\Models\RapatLampiran;
use Modules\DataMaster\Models\Periode;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Notifications\RapatUndanganNotification;
use App\Notifications\RapatTindakLanjutNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;

class RapatController extends Controller
{
    // ── Dashboard / Index ─────────────────────────────────────────
    public function index(Request $request)
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes     = Periode::orderBy('tahun', 'desc')->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'tahun'    => $p->tahun,
                'semester' => $p->semester ?? null,
                'nama'     => $p->nama ?? ($p->tahun . ($p->semester ? ' (' . $p->semester . ')' : '')),
                'is_aktif' => (bool)$p->is_aktif,
            ]);

        $periodeId = $request->get('periode_id', $periodeAktif?->id ?? ($periodes->first()['id'] ?? null));

        $query = Rapat::with(['creator', 'peserta', 'agendas', 'tindakLanjuts', 'periode'])
            ->when($periodeId, fn($q) => $q->byPeriode($periodeId))
            ->orderBy('tanggal', 'desc');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('judul', 'like', "%{$search}%")
                ->orWhere('tempat', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
            );
        }

        // Filter tambahan
        if ($request->filled('jenis'))  $query->where('jenis', $request->jenis);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('dari'))   $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $request->sampai);

        $rapatsPaginated = $query->paginate(15)->through(fn($r) => [
            'id'             => $r->id,
            'judul'          => $r->judul,
            'jenis'          => $r->jenis,
            'tanggal'        => $r->tanggal?->format('Y-m-d'),
            'tanggal_display'=> $r->tanggal?->translatedFormat('d M Y'),
            'waktu_mulai'    => $r->waktu_mulai ? substr($r->waktu_mulai, 0, 5) : null,
            'waktu_selesai'  => $r->waktu_selesai ? substr($r->waktu_selesai, 0, 5) : null,
            'tempat'         => $r->tempat,
            'status'         => $r->status,
            'periode_id'     => $r->periode_id,
            'periode_nama'   => $r->periode?->nama ?? $r->periode?->tahun,
            'creator_name'   => $r->creator?->name,
            'total_peserta'  => $r->peserta->count(),
            'total_agenda'   => $r->agendas->count(),
            'total_tl'       => $r->tindakLanjuts->count(),
            'tl_selesai'     => $r->tindakLanjuts->where('status', 'selesai')->count(),
        ]);

        // Stats query based on selected period
        $baseRapat = Rapat::when($periodeId, fn($q) => $q->byPeriode($periodeId))->get();

        $stats = [
            'total'       => $baseRapat->count(),
            'draft'       => $baseRapat->where('status', Rapat::STATUS_DRAFT)->count(),
            'terjadwal'   => $baseRapat->where('status', Rapat::STATUS_TERJADWAL)->count(),
            'berlangsung' => $baseRapat->where('status', Rapat::STATUS_BERLANGSUNG)->count(),
            'selesai'     => $baseRapat->where('status', Rapat::STATUS_SELESAI)->count(),
            'dibatalkan'  => $baseRapat->where('status', Rapat::STATUS_DIBATALKAN)->count(),
        ];

        // Rapat mendatang (30 hari ke depan)
        $mendatang = Rapat::when($periodeId, fn($q) => $q->byPeriode($periodeId))
            ->where('status', Rapat::STATUS_TERJADWAL)
            ->whereDate('tanggal', '>=', today())
            ->whereDate('tanggal', '<=', today()->addDays(30))
            ->orderBy('tanggal')
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'id'              => $r->id,
                'judul'           => $r->judul,
                'jenis'           => $r->jenis,
                'tanggal_display' => $r->tanggal?->translatedFormat('d M Y'),
                'waktu_mulai'     => substr($r->waktu_mulai, 0, 5),
                'tempat'          => $r->tempat,
                'total_peserta'   => $r->peserta()->count(),
            ]);

        // Tindak lanjut overdue
        $overdueActions = RapatTindakLanjut::with(['rapat', 'pic'])
            ->when($periodeId, fn($q) => $q->whereHas('rapat', fn($rq) => $rq->where('periode_id', $periodeId)))
            ->whereIn('status', [RapatTindakLanjut::STATUS_BELUM_MULAI, RapatTindakLanjut::STATUS_DALAM_PROSES])
            ->whereDate('deadline', '<', today())
            ->take(5)
            ->get()
            ->map(fn($tl) => [
                'id'          => $tl->id,
                'rapat_id'    => $tl->rapat_id,
                'rapat_judul' => $tl->rapat?->judul,
                'deskripsi'   => $tl->deskripsi,
                'pic_name'    => $tl->pic?->name,
                'deadline'    => $tl->deadline?->format('d M Y'),
                'prioritas'   => $tl->prioritas,
                'status'      => $tl->status,
            ]);

        // Data grafik per bulan
        $chartData = $this->getChartData($periodeId ?: 0);

        return Inertia::render('Rapat/Index', [
            'rapats'         => $rapatsPaginated,
            'periodes'       => $periodes,
            'periodeId'      => $periodeId,
            'stats'          => $stats,
            'mendatang'      => $mendatang,
            'overdueActions' => $overdueActions,
            'chartData'      => $chartData,
            'filters'        => $request->only(['search', 'jenis', 'status', 'dari', 'sampai', 'periode_id']),
            'jenisOptions'   => Rapat::jenisOptions(),
            'statusOptions'  => Rapat::statusOptions(),
        ]);
    }

    // ── Create ────────────────────────────────────────────────────
    public function create()
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes     = Periode::orderBy('tahun', 'desc')->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'tahun'    => $p->tahun,
                'semester' => $p->semester ?? null,
                'nama'     => $p->nama ?? ($p->tahun . ($p->semester ? ' (' . $p->semester . ')' : '')),
                'is_aktif' => (bool)$p->is_aktif,
            ]);

        $users = User::where('is_active', true)->with('roles')->orderBy('name')->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'nip'        => $u->nip ?? null,
                'unit_kerja' => $u->unit_kerja ?? null,
                'jabatan'    => $u->jabatan ?? null,
                'role'       => $u->roles->first()?->display_name ?? $u->roles->first()?->name,
            ]);

        return Inertia::render('Rapat/Create', [
            'periodeAktifId' => $periodeAktif?->id ?? ($periodes->first()['id'] ?? null),
            'periodes'       => $periodes,
            'users'          => $users,
            'jenisOptions'   => Rapat::jenisOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'jenis'         => 'required|in:RTM,Koordinasi,Evaluasi,Audit,Khusus',
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
            'tempat'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string|max:2000',
            'periode_id'    => 'required|exists:periodes,id',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status']     = Rapat::STATUS_DRAFT;

        $rapat = Rapat::create($validated);

        return redirect()->route('rapat.show', $rapat)
            ->with('success', 'Rapat berhasil dibuat. Silakan tambahkan agenda dan peserta sebelum menjadwalkan.');
    }

    // ── Show / Detail ─────────────────────────────────────────────
    public function show(Rapat $rapat)
    {
        $rapat->load([
            'creator',
            'periode',
            'agendas.notulensiUpdatedBy',
            'peserta.user.roles',
            'peserta.pegawai',
            'tindakLanjuts.pic.roles',
            'lampirans.uploader',
        ]);

        $ringkasanKehadiran = [
            'total'       => $rapat->peserta->count(),
            'hadir'       => $rapat->peserta->where('status_kehadiran', 'hadir')->count(),
            'tidak_hadir' => $rapat->peserta->where('status_kehadiran', 'tidak_hadir')->count(),
            'izin'        => $rapat->peserta->where('status_kehadiran', 'izin')->count(),
            'diundang'    => $rapat->peserta->where('status_kehadiran', 'diundang')->count(),
        ];

        $ringkasanTL = [
            'total'        => $rapat->tindakLanjuts->count(),
            'belum_mulai'  => $rapat->tindakLanjuts->where('status', 'belum_mulai')->count(),
            'dalam_proses' => $rapat->tindakLanjuts->where('status', 'dalam_proses')->count(),
            'selesai'      => $rapat->tindakLanjuts->where('status', 'selesai')->count(),
            'dibatalkan'   => $rapat->tindakLanjuts->where('status', 'dibatalkan')->count(),
        ];

        $users = User::where('is_active', true)->with('roles')->orderBy('name')->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'nip'        => $u->nip ?? null,
                'unit_kerja' => $u->unit_kerja ?? null,
                'jabatan'    => $u->jabatan ?? null,
                'role'       => $u->roles->first()?->display_name ?? $u->roles->first()?->name,
            ]);

        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get()
            ->map(fn($p) => [
                'id'         => $p->id,
                'nama'       => $p->nama,
                'nip'        => $p->nip ?? $p->nidn ?? null,
                'email'      => $p->email ?? null,
                'unit_kerja' => $p->unit_kerja ?? null,
                'jabatan'    => $p->jabatan ?? null,
                'user_id'    => $p->user_id,
            ]);

        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->hasRole('super_admin');
        $isPimpinan   = $currentUser->hasRole('pimpinan');

        return Inertia::render('Rapat/Show', [
            'rapat' => [
                'id'                    => $rapat->id,
                'judul'                 => $rapat->judul,
                'jenis'                 => $rapat->jenis,
                'tanggal'               => $rapat->tanggal?->format('Y-m-d'),
                'tanggal_display'       => $rapat->tanggal?->translatedFormat('l, d F Y'),
                'waktu_mulai'           => $rapat->waktu_mulai ? substr($r_mulai = $rapat->waktu_mulai, 0, 5) : null,
                'waktu_selesai'         => $rapat->waktu_selesai ? substr($r_selesai = $rapat->waktu_selesai, 0, 5) : null,
                'tempat'                => $rapat->tempat,
                'deskripsi'             => $rapat->deskripsi,
                'kesimpulan'            => $rapat->kesimpulan,
                'status'                => $rapat->status,
                'alasan_pembatalan'     => $rapat->alasan_pembatalan,
                'created_by'            => $rapat->created_by,
                'creator_name'          => $rapat->creator?->name,
                'created_at'            => $rapat->created_at?->translatedFormat('d M Y H:i'),
                'periode_id'            => $rapat->periode_id,
                'periode_nama'          => $rapat->periode?->nama ?? $rapat->periode?->tahun,
                // RTM specific fields
                'input_audit_internal'  => $rapat->input_audit_internal,
                'input_umpan_balik'     => $rapat->input_umpan_balik,
                'input_kinerja_proses'  => $rapat->input_kinerja_proses,
                'input_status_tindakan' => $rapat->input_status_tindakan,
                'input_perubahan_sistem'=> $rapat->input_perubahan_sistem,
                'input_rekomendasi'     => $rapat->input_rekomendasi,
                'output_keefektifan'    => $rapat->output_keefektifan,
                'output_perbaikan'      => $rapat->output_perbaikan,
                'output_sumber_daya'    => $rapat->output_sumber_daya,
                // Relations
                'agendas'               => $rapat->agendas->map(fn($a) => [
                    'id'                     => $a->id,
                    'urutan'                 => $a->urutan,
                    'judul'                  => $a->judul,
                    'deskripsi'              => $a->deskripsi,
                    'estimasi_durasi'        => $a->estimasi_durasi,
                    'notulensi'              => $a->notulensi,
                    'notulensi_updated_by'   => $a->notulensiUpdatedBy?->name,
                    'notulensi_updated_at'   => $a->notulensi_updated_at?->translatedFormat('d M Y H:i'),
                ]),
                'peserta'               => $rapat->peserta->map(fn($p) => [
                    'id'                   => $p->id,
                    'user_id'              => $p->user_id,
                    'pegawai_id'           => $p->pegawai_id,
                    'nama'                 => $p->nama_display,
                    'instansi'             => $p->instansi_display,
                    'email'                => $p->email_display,
                    'no_hp'                => $p->no_hp_display,
                    'peran'                => $p->peran,
                    'keterangan'           => $p->keterangan,
                    'status_kehadiran'     => $p->status_kehadiran,
                    'kehadiran_updated_at' => $p->kehadiran_updated_at?->translatedFormat('d M Y H:i'),
                    'is_eksternal'         => $p->isEksternal(),
                    'avatar_inisial'       => $p->inisial,
                ]),
                'tindak_lanjuts'        => $rapat->tindakLanjuts->map(fn($tl) => [
                    'id'                     => $tl->id,
                    'pic_id'                 => $tl->pic_id,
                    'pic_name'               => $tl->pic?->name,
                    'deskripsi'              => $tl->deskripsi,
                    'deadline'               => $tl->deadline?->format('Y-m-d'),
                    'deadline_display'       => $tl->deadline?->translatedFormat('d M Y'),
                    'prioritas'              => $tl->prioritas,
                    'status'                 => $tl->status,
                    'catatan_progres'        => $tl->catatan_progres,
                    'tanggal_selesai_aktual' => $tl->tanggal_selesai_aktual?->format('d M Y'),
                    'is_overdue'             => in_array($tl->status, ['belum_mulai', 'dalam_proses']) && $tl->deadline && $tl->deadline->isPast(),
                ]),
                'lampirans'             => $rapat->lampirans->map(fn($l) => [
                    'id'            => $l->id,
                    'nama_asli'     => $l->nama_asli,
                    'ukuran'        => $l->ukuran_formatted ?? (round($l->ukuran / 1024, 1) . ' KB'),
                    'mime_type'     => $l->mime_type,
                    'uploader_name' => $l->uploader?->name,
                    'created_at'    => $l->created_at?->translatedFormat('d M Y H:i'),
                    'download_url'  => route('rapat.lampiran.download', [$rapat, $l]),
                ]),
            ],
            'ringkasanKehadiran' => $ringkasanKehadiran,
            'ringkasanTL'        => $ringkasanTL,
            'users'              => $users,
            'pegawais'           => $pegawais,
            'canEdit'            => $isSuperAdmin || $isPimpinan || $rapat->created_by === $currentUser->id,
            'isLocked'           => $rapat->isLocked(),
            'currentUserId'      => $currentUser->id,
            'isSuperAdmin'       => $isSuperAdmin,
            'exportPdfUrl'       => route('rapat.export-pdf', $rapat),
        ]);
    }

    // ── Edit ─────────────────────────────────────────────────────
    public function edit(Rapat $rapat)
    {
        if ($rapat->isLocked()) {
            return redirect()->route('rapat.show', $rapat)
                ->with('error', 'Rapat yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $periodes = Periode::orderBy('tahun', 'desc')->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'tahun'    => $p->tahun,
                'semester' => $p->semester ?? null,
                'nama'     => $p->nama ?? ($p->tahun . ($p->semester ? ' (' . $p->semester . ')' : '')),
                'is_aktif' => (bool)$p->is_aktif,
            ]);

        return Inertia::render('Rapat/Edit', [
            'rapat' => [
                'id'                    => $rapat->id,
                'judul'                 => $rapat->judul,
                'jenis'                 => $rapat->jenis,
                'tanggal'               => $rapat->tanggal?->format('Y-m-d'),
                'waktu_mulai'           => $rapat->waktu_mulai ? substr($rapat->waktu_mulai, 0, 5) : '',
                'waktu_selesai'         => $rapat->waktu_selesai ? substr($rapat->waktu_selesai, 0, 5) : '',
                'tempat'                => $rapat->tempat,
                'deskripsi'             => $rapat->deskripsi,
                'periode_id'            => $rapat->periode_id,
                // RTM fields
                'input_audit_internal'  => $rapat->input_audit_internal,
                'input_umpan_balik'     => $rapat->input_umpan_balik,
                'input_kinerja_proses'  => $rapat->input_kinerja_proses,
                'input_status_tindakan' => $rapat->input_status_tindakan,
                'input_perubahan_sistem'=> $rapat->input_perubahan_sistem,
                'input_rekomendasi'     => $rapat->input_rekomendasi,
                'output_keefektifan'    => $rapat->output_keefektifan,
                'output_perbaikan'      => $rapat->output_perbaikan,
                'output_sumber_daya'    => $rapat->output_sumber_daya,
            ],
            'periodes'     => $periodes,
            'jenisOptions' => Rapat::jenisOptions(),
        ]);
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
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
            'tempat'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string|max:2000',
            'periode_id'    => 'required|exists:periodes,id',
        ]);

        $oldTanggal    = $rapat->tanggal?->format('Y-m-d');
        $oldWaktuMulai = $rapat->waktu_mulai;

        // Tambahan field RTM jika jenis RTM
        if ($validated['jenis'] === Rapat::JENIS_RTM) {
            $rtmFields = $request->only([
                'input_audit_internal', 'input_umpan_balik', 'input_kinerja_proses',
                'input_status_tindakan', 'input_perubahan_sistem', 'input_rekomendasi',
                'output_keefektifan', 'output_perbaikan', 'output_sumber_daya',
            ]);
            $validated = array_merge($validated, $rtmFields);
        }

        $rapat->update($validated);

        // Kirim notifikasi jika jadwal berubah dan status terjadwal
        $jadwalBerubah = $oldTanggal !== $rapat->fresh()->tanggal?->format('Y-m-d')
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
                return back()->with('error', 'Rapat harus memiliki minimal satu susunan agenda sebelum dijadwalkan.');
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
            $this->sendUndangan($rapat, $rapat->peserta->pluck('user')->filter());
        }

        if ($newStatus === Rapat::STATUS_DIBATALKAN) {
            $this->sendNotifikasiPembatalan($rapat);
        }

        $label = Rapat::statusOptions()[$newStatus] ?? $newStatus;
        return back()->with('success', "Status rapat berhasil diubah menjadi \"{$label}\".");
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

        // Renumber urutan
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
        if ($rapat->status === Rapat::STATUS_SELESAI && !Auth::user()->hasRole('super_admin')) {
            return back()->with('error', 'Notulensi rapat yang sudah selesai hanya dapat diubah oleh Super Admin.');
        }

        $request->validate(['notulensi' => 'nullable|string']);

        $agenda->update([
            'notulensi'            => $request->notulensi,
            'notulensi_updated_by' => Auth::id(),
            'notulensi_updated_at' => now(),
        ]);

        return back()->with('success', 'Notulensi agenda berhasil disimpan.');
    }

    // ── Search Peserta (AJAX / JSON) ──────────────────────────────
    public function searchPeserta(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('nip', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('unit_kerja', 'like', "%{$q}%")
                      ->orWhere('jabatan', 'like', "%{$q}%");
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
        $tipe = $request->input('tipe_peserta', 'internal');
        $statusAwal = in_array($request->status_kehadiran_awal, ['diundang', 'hadir'])
            ? $request->status_kehadiran_awal
            : RapatPeserta::STATUS_DIUNDANG;

        // Multiple internal users (Batch / Checkbox)
        if ($tipe === 'internal' && $request->filled('user_ids') && is_array($request->user_ids)) {
            $request->validate([
                'user_ids'   => 'required|array|min:1',
                'user_ids.*' => 'exists:users,id',
                'peran'      => 'required|in:Ketua,Notulis,Peserta',
            ]);

            $addedCount = 0;
            $skippedNames = [];

            foreach ($request->user_ids as $uid) {
                if ($rapat->hasUserAsPeserta($uid)) {
                    $u = User::find($uid);
                    if ($u) $skippedNames[] = $u->name;
                    continue;
                }

                $user = User::find($uid);
                if (!$user) continue;

                $pegawai = Pegawai::where('user_id', $user->id)->first();

                RapatPeserta::create([
                    'rapat_id'         => $rapat->id,
                    'user_id'          => $user->id,
                    'pegawai_id'       => $pegawai?->id,
                    'peran'            => $request->peran,
                    'keterangan'       => $request->keterangan,
                    'status_kehadiran' => $statusAwal,
                ]);

                $addedCount++;

                if ($rapat->status === Rapat::STATUS_TERJADWAL) {
                    try { $user->notify(new RapatUndanganNotification($rapat)); }
                    catch (\Exception $e) { \Log::warning("Gagal kirim notif rapat ke user {$user->id}: " . $e->getMessage()); }
                }
            }

            $msg = "{$addedCount} peserta berhasil ditambahkan sekaligus.";
            if (count($skippedNames) > 0) {
                $msg .= " (" . count($skippedNames) . " peserta dilewati karena sudah terdaftar).";
            }

            return back()->with('success', $msg);
        }

        // Single internal user
        if ($tipe === 'internal' && $request->filled('user_id')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'peran'   => 'required|in:Ketua,Notulis,Peserta',
            ]);

            $user = User::findOrFail($request->user_id);

            if ($rapat->hasUserAsPeserta($request->user_id)) {
                return back()->with('error', "{$user->name} sudah terdaftar sebagai peserta rapat ini.");
            }

            $pegawai = Pegawai::where('user_id', $user->id)->first();

            RapatPeserta::create([
                'rapat_id'         => $rapat->id,
                'user_id'          => $user->id,
                'pegawai_id'       => $pegawai?->id,
                'peran'            => $request->peran,
                'keterangan'       => $request->keterangan,
                'status_kehadiran' => $statusAwal,
            ]);

            if ($rapat->status === Rapat::STATUS_TERJADWAL) {
                try { $user->notify(new RapatUndanganNotification($rapat)); }
                catch (\Exception $e) { \Log::warning("Gagal kirim notif rapat ke user {$user->id}: " . $e->getMessage()); }
            }

            return back()->with('success', "Peserta \"{$user->name}\" berhasil ditambahkan.");
        }

        // Peserta eksternal / pegawai
        $request->validate([
            'nama_eksternal'    => 'required|string|max:255',
            'instansi'          => 'nullable|string|max:255',
            'jabatan_eksternal' => 'nullable|string|max:255',
            'email_eksternal'   => 'nullable|email|max:255',
            'no_hp_eksternal'   => 'nullable|string|max:50',
            'peran'             => 'required|in:Ketua,Notulis,Peserta',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        $linkedUserId = null;
        $linkedPegawaiId = null;
        if ($request->filled('pegawai_id')) {
            $pegawai = Pegawai::find($request->pegawai_id);
            if ($pegawai) {
                $linkedPegawaiId = $pegawai->id;
                if ($pegawai->user_id) {
                    $linkedUserId = $pegawai->user_id;
                    if ($rapat->hasUserAsPeserta($linkedUserId)) {
                        return back()->with('error', "{$request->nama_eksternal} sudah terdaftar sebagai peserta rapat.");
                    }
                } else {
                    if ($rapat->peserta()->where('pegawai_id', $linkedPegawaiId)->exists()) {
                        return back()->with('error', "{$request->nama_eksternal} sudah terdaftar sebagai peserta rapat.");
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
        $canUpdate = $currentUser->hasAnyRole(['super_admin', 'pimpinan'])
                  || (!$peserta->isEksternal() && $peserta->user_id === $currentUser->id);

        if (!$canUpdate) {
            abort(403, 'Anda tidak memiliki hak untuk mengubah status kehadiran peserta ini.');
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
            'deskripsi' => 'required|string|max:1000',
            'pic_id'    => 'required|exists:users,id',
            'deadline'  => 'required|date',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
        ]);

        $tl = RapatTindakLanjut::create([
            'rapat_id'  => $rapat->id,
            'pic_id'    => $request->pic_id,
            'deskripsi' => $request->deskripsi,
            'deadline'  => $request->deadline,
            'prioritas' => $request->prioritas,
            'status'    => RapatTindakLanjut::STATUS_BELUM_MULAI,
        ]);

        try {
            $tl->pic?->notify(new RapatTindakLanjutNotification($tl, 'assigned'));
        } catch (\Exception $e) {
            \Log::warning("Gagal kirim notifikasi TL rapat ke PIC {$tl->pic_id}: " . $e->getMessage());
        }

        return back()->with('success', 'Tindak lanjut (action item) berhasil ditambahkan.');
    }

    public function updateTindakLanjut(Request $request, Rapat $rapat, RapatTindakLanjut $tindakLanjut)
    {
        $request->validate([
            'status'          => 'required|in:belum_mulai,dalam_proses,selesai,dibatalkan',
            'catatan_progres' => 'nullable|string|max:500',
        ]);

        $currentUser = Auth::user();
        $canUpdate   = $currentUser->hasAnyRole(['super_admin', 'pimpinan'])
                    || $tindakLanjut->pic_id === $currentUser->id;

        if (!$canUpdate) {
            abort(403, 'Hanya PIC atau Pimpinan yang dapat memperbarui tindak lanjut ini.');
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

        $file = $request->file('file');
        $path = $file->store("rapat/{$rapat->id}/lampiran", 'public');

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
            abort(404, 'File lampiran tidak ditemukan.');
        }

        return Storage::disk('public')->download($lampiran->path, $lampiran->nama_asli);
    }

    // ── Export PDF Notulensi ──────────────────────────────────────
    public function exportPdf(Rapat $rapat)
    {
        $rapat->load(['agendas', 'peserta.user', 'peserta.pegawai', 'tindakLanjuts.pic', 'creator']);
        $setting = \App\Models\Setting::first();

        $view = view()->exists('manajemenrapat::pdf-notulensi')
            ? 'manajemenrapat::pdf-notulensi'
            : 'manajemen-rapat::pdf-notulensi';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, compact('rapat', 'setting'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Notulensi-' . \Illuminate\Support\Str::slug($rapat->judul) . '-' . $rapat->tanggal?->format('Y-m-d') . '.pdf';

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
            if ($p->user) {
                try {
                    $p->user->notify(new \App\Notifications\RapatPembatalanNotification($rapat));
                } catch (\Exception $e) {
                    \Log::warning("Gagal kirim notifikasi pembatalan ke user {$p->user_id}: " . $e->getMessage());
                }
            }
        }
    }

    private function sendNotifikasiPerubahanJadwal(Rapat $rapat)
    {
        $rapat->load('peserta.user');
        foreach ($rapat->peserta as $p) {
            if ($p->user) {
                try {
                    $p->user->notify(new \App\Notifications\RapatPerubahanJadwalNotification($rapat));
                } catch (\Exception $e) {
                    \Log::warning("Gagal kirim notifikasi perubahan jadwal ke user {$p->user_id}: " . $e->getMessage());
                }
            }
        }
    }

    private function getChartData(int $periodeId): array
    {
        $months = [];
        $totals = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $count = Rapat::when($periodeId, fn($q) => $q->byPeriode($periodeId))
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->count();

            $totals[] = $count;
        }

        return [
            'labels' => $months,
            'values' => $totals,
        ];
    }
}
