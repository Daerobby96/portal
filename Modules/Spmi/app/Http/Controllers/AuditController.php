<?php
namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\Audit;
use Modules\Spmi\Models\AuditChecklist;
use Modules\DataMaster\Models\Periode;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with(['periode', 'ketuaAuditor'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_audit', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_audit', 'like', '%' . $request->search . '%')
                  ->orWhere('unit_yang_diaudit', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        $audits   = $query->paginate(10)->withQueryString();
        $periodes = Periode::orderByDesc('tahun')->get();

        $stats = [
            'total'   => Audit::count(),
            'draft'   => Audit::where('status', 'draft')->count(),
            'aktif'   => Audit::where('status', 'aktif')->count(),
            'selesai' => Audit::where('status', 'selesai')->count(),
        ];

        return \Inertia\Inertia::render('Spmi/Audit/Index', [
            'audits' => $audits,
            'periodes' => $periodes,
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $periodes  = Periode::orderByDesc('tahun')->get();
        $auditors  = User::whereHas('roles', fn($q) => $q->whereIn('name', ['auditor', 'super_admin']))
                         ->where('is_active', true)->with('prodi')->orderBy('name')->get();
        $kodeAudit = Audit::generateKode();

        return \Inertia\Inertia::render('Spmi/Audit/Create', [
            'periodes'  => $periodes,
            'auditors'  => $auditors,
            'kodeAudit' => $kodeAudit,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id'                => 'required|exists:periodes,id',
            'nama_audit'                => 'required|string|max:255',
            'unit_yang_diaudit'         => 'required|string|max:255',
            'ketua_auditor_id'          => 'required|exists:users,id',
            'tanggal_audit'             => 'required|date',
            'opening_meeting'           => 'nullable|date',
            'closing_meeting'           => 'nullable|date',
            'tanggal_selesai'           => 'nullable|date|after_or_equal:tanggal_audit',
            'lingkup_audit'             => 'nullable|string',
            'tujuan_audit'              => 'nullable|string',
            'catatan'                   => 'nullable|string',
            'nomor_surat_tugas'         => 'nullable|string|max:100',
            'tgl_surat_tugas'           => 'nullable|date',
            'penandatangan_surat_tugas' => 'nullable|string|max:255',
            'jabatan_penandatangan'     => 'nullable|string|max:255',
            'anggota_auditor'           => 'nullable|array',
            'anggota_auditor.*'         => 'exists:users,id',
        ]);

        $audit = Audit::create([
            'periode_id'                => $request->periode_id,
            'kode_audit'                => Audit::generateKode(),
            'nama_audit'                => $request->nama_audit,
            'unit_yang_diaudit'         => $request->unit_yang_diaudit,
            'ketua_auditor_id'          => $request->ketua_auditor_id,
            'tanggal_audit'             => $request->tanggal_audit,
            'opening_meeting'           => $request->opening_meeting,
            'closing_meeting'           => $request->closing_meeting,
            'tanggal_selesai'           => $request->tanggal_selesai,
            'status'                    => 'draft',
            'lingkup_audit'             => $request->lingkup_audit,
            'tujuan_audit'              => $request->tujuan_audit,
            'catatan'                   => $request->catatan,
            'nomor_surat_tugas'         => $request->nomor_surat_tugas,
            'tgl_surat_tugas'           => $request->tgl_surat_tugas,
            'penandatangan_surat_tugas' => $request->penandatangan_surat_tugas,
            'jabatan_penandatangan'     => $request->jabatan_penandatangan,
        ]);

        // Simpan anggota auditor
        $auditorIds = collect($request->anggota_auditor ?? [])
            ->mapWithKeys(fn($id) => [$id => ['peran' => 'anggota']]);

        // Tambahkan ketua
        $auditorIds[$request->ketua_auditor_id] = ['peran' => 'ketua'];
        $audit->auditors()->sync($auditorIds);

        // Check Conflict of Interest warning
        $warnings = $this->checkAuditorConflicts($request->unit_yang_diaudit, $request->ketua_auditor_id, $request->anggota_auditor ?? []);
        $msg = 'Audit "' . $audit->nama_audit . '" berhasil dibuat.';
        if (!empty($warnings)) {
            $msg .= ' PERINGATAN: ' . implode(' ', $warnings);
        }

        return redirect()->route('audit.show', $audit)->with('success', $msg);
    }

    public function show(Audit $audit)
    {
        $audit->load(['periode', 'ketuaAuditor', 'auditors', 'temuans.tindakLanjuts', 'checklists.indikator.standar']);

        // Ambil indikator yang relevan dengan unit kerja ini
        $indikators = \Modules\Spmi\Models\IndikatorKinerja::where('unit_kerja', $audit->unit_yang_diaudit)
                                                ->where('is_aktif', true)
                                                ->with('standar')
                                                ->orderBy('kode')
                                                ->get();

        if ($indikators->isEmpty()) {
            $indikators = \Modules\Spmi\Models\IndikatorKinerja::where('is_aktif', true)
                                                    ->with('standar')
                                                    ->orderBy('kode')
                                                    ->get();
        }

        $statsTemuan = [
            'total'       => $audit->temuans->count(),
            'kts_mayor'   => $audit->temuans->where('kategori', 'KTS_Mayor')->count(),
            'kts_minor'   => $audit->temuans->where('kategori', 'KTS_Minor')->count(),
            'observasi'   => $audit->temuans->where('kategori', 'OB')->count(),
            'rekomendasi' => $audit->temuans->where('kategori', 'Rekomendasi')->count(),
            'open'        => $audit->temuans->where('status', 'open')->count(),
            'closed'      => $audit->temuans->where('status', 'closed')->count(),
        ];

        // Stats Checklist
        $statsChecklist = [
            'total' => $audit->checklists->count(),
            'sesuai' => $audit->checklists->where('status', 'sesuai')->count(),
            'tidak_sesuai' => $audit->checklists->where('status', 'tidak_sesuai')->count(),
            'belum' => $audit->checklists->where('status', 'belum_diisi')->count(),
        ];

        return \Inertia\Inertia::render('Spmi/Audit/Show', [
            'audit'          => $audit,
            'statsTemuan'    => $statsTemuan,
            'statsChecklist' => $statsChecklist,
            'indikators'     => $indikators,
        ]);
    }

    public function generateChecklist(Audit $audit)
    {
        // Ambil indikator yang relevan dengan unit kerja ini
        $indikators = \Modules\Spmi\Models\IndikatorKinerja::where('unit_kerja', $audit->unit_yang_diaudit)
                                                 ->where('is_aktif', true)
                                                 ->get();

        if ($indikators->isEmpty()) {
            $indikators = \Modules\Spmi\Models\IndikatorKinerja::where('is_aktif', true)->get();
        }

        $count = 0;
        foreach ($indikators as $ind) {
            $exists = \Modules\Spmi\Models\Checklist::where('audit_id', $audit->id)
                                               ->where('indikator_id', $ind->id)
                                               ->exists();
            if (!$exists) {
                // Cari data monitoring & evaluasi terakhir untuk indikator ini pada periode yang sama
                $monitoring = \Modules\Spmi\Models\Monitoring::with(['evaluasi', 'indikator'])
                                                   ->where('indikator_id', $ind->id)
                                                   ->where('periode_id', $audit->periode_id)
                                                   ->first();
                
                $statusDefault = 'belum_diisi';
                $catatan      = '';
                $bukti        = '';

                if ($monitoring) {
                    $bukti = $monitoring->bukti_dokumen;
                    if ($monitoring->evaluasi && $monitoring->evaluasi->hasil) {
                        $hasil = $monitoring->evaluasi->hasil;
                        $catatan = $monitoring->evaluasi->analisa;
                        
                        // Mapping hasil evaluasi ke status audit
                        if ($hasil === 'tercapai') {
                            $statusDefault = 'sesuai';
                        } elseif ($hasil === 'tidak_tercapai') {
                            $statusDefault = 'tidak_sesuai';
                        } elseif ($hasil === 'perlu_perhatian') {
                            $statusDefault = 'observasi';
                        }
                    } else {
                        // Jika belum ada evaluasi formal, gunakan is_tercapai dari monitoring
                        $statusDefault = $monitoring->is_tercapai ? 'sesuai' : 'tidak_sesuai';
                    }
                }

                $checklist = \Modules\Spmi\Models\Checklist::create([
                    'audit_id'       => $audit->id,
                    'indikator_id'   => $ind->id,
                    'pertanyaan'     => $ind->nama,
                    'status'         => $statusDefault,
                    'catatan'        => $catatan,
                    'bukti_objektif' => $bukti,
                ]);

                // OTOMATISASI TEMUAN: Jika statusnya Tidak Sesuai, buatkan Temuan formal langsung
                if ($statusDefault === 'tidak_sesuai') {
                    $uraianDefault = '[Auto-Generated] ' . ($catatan ?: 'Indikator tidak mencapai target pada periode monitoring.');
                    
                    // SMART AI GENERATOR: Menghasilkan uraian temuan kualitatif yang spesifik menggunakan LLM Llama-3.3-70b via Groq
                    try {
                        $aiService = app(\App\Services\AiService::class);
                        $standarNama = $ind->standar ? $ind->standar->pernyataan : 'Standar Terkait';
                        $realisasiNilai = $monitoring ? $monitoring->nilai_capaian : '0';
                        
                        $prompt = "Sebagai seorang Auditor Ahli Sistem Penjaminan Mutu Internal (SPMI) Perguruan Tinggi, rumuskan sebuah kalimat **Uraian Temuan** yang sangat spesifik, formal, kritis, dan profesional berdasarkan informasi ketidaksesuaian berikut:\n\n" .
                                  "- **Nama Indikator**: \"{$ind->nama}\"\n" .
                                  "- **Standar**: \"{$standarNama}\"\n" .
                                  "- **Target Nilai**: \"{$ind->target_nilai} {$ind->unit_pengukuran}\"\n" .
                                  "- **Realisasi Nilai**: \"{$realisasiNilai} {$ind->unit_pengukuran}\"\n" .
                                  "- **Catatan Evaluasi Lapangan**: \"" . ($catatan ?: 'Tidak ada catatan tambahan') . "\"\n" .
                                  "- **Unit Kerja**: \"{$audit->unit_yang_diaudit}\"\n\n" .
                                  "ATURAN FORMULASI (WAJIB):\n" .
                                  "1. Gunakan Bahasa Indonesia baku, formal, akademis, dan analitis.\n" .
                                  "2. Tuliskan dalam **SATU paragraf pendek** (maksimal 2 kalimat) yang langsung menggambarkan inti ketidaksesuaian beserta angkanya.\n" .
                                  "3. JANGAN menyertakan pembuka seperti \"Uraian Temuan:\" atau tanda petik di luar teks.\n" .
                                  "4. Sebutkan nama indikator dan unit kerjanya secara alami.\n" .
                                  "5. Jika tidak ada catatan evaluasi, formulasikan berdasarkan selisih antara target dan realisasi secara logis.\n" .
                                  "JANGAN berikan salam pembuka/penutup atau teks pengantar apa pun. Berikan langsung rumusan temuannya.";
                        
                        $aiResult = $aiService->generate($prompt);
                        if ($aiResult['status'] === 'success' && !empty($aiResult['data'])) {
                            $uraianDefault = '[Auto-Generated AI] ' . $aiResult['data'];
                        }
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Gagal generate AI temuan otomatis: ' . $e->getMessage());
                    }

                    \Modules\Spmi\Models\Temuan::create([
                        'audit_id'           => $audit->id,
                        'audit_checklist_id' => $checklist->id,
                        'auditor_id'         => $audit->ketua_auditor_id,
                        'kode_temuan'        => \Modules\Spmi\Models\Temuan::generateKode(),
                        'kategori'           => 'KTS_Minor', // Default kategori untuk auto-generate
                        'uraian_temuan'      => $uraianDefault,
                        'bukti_objektif'     => $bukti ?: 'Berdasarkan data monitoring sistem.',
                        'status'             => 'open',
                        'batas_tindak_lanjut' => now()->addDays(7), // Default 7 hari
                    ]);
                }

                $count++;
            }
        }

        return back()->with('success', $count . ' item checklist berhasil di-generate otomatis berdasarkan hasil monitoring.');
    }

    public function updateChecklistInline(Request $request)
    {
        $request->validate([
            'audit_id'     => 'required|exists:audits,id',
            'indikator_id' => 'required|exists:indikator_kinerjas,id',
            'field'        => 'required|in:status,catatan,bukti_objektif',
            'value'        => 'required',
        ]);

        $indikator = \Modules\Spmi\Models\IndikatorKinerja::find($request->indikator_id);
        
        $checklist = \Modules\Spmi\Models\Checklist::updateOrCreate(
            [
                'audit_id'     => $request->audit_id,
                'indikator_id' => $request->indikator_id,
            ],
            [
                'pertanyaan'    => $indikator->nama,
                $request->field => $request->value,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Checklist diperbarui.',
        ]);
    }

    public function updateChecklist(Request $request, Audit $audit, \Modules\Spmi\Models\Checklist $checklist)
    {
        if ($checklist->audit_id !== $audit->id) abort(403);

        $request->validate([
            'status' => 'required|in:sesuai,tidak_sesuai,observasi,tidak_terkait,belum_diisi',
            'catatan' => 'nullable|string',
            'bukti_objektif' => 'nullable|string',
        ]);

        $checklist->update($request->only(['status', 'catatan', 'bukti_objektif']));

        return back()->with('success', 'Checklist berhasil diupdate.');
    }

    public function edit(Audit $audit)
    {
        $periodes = Periode::orderByDesc('tahun')->get();
        $auditors = User::whereHas('roles', fn($q) => $q->whereIn('name', ['auditor', 'super_admin']))
                        ->where('is_active', true)->with('prodi')->orderBy('name')->get();
        $selectedAnggota = $audit->auditors()->wherePivot('peran', 'anggota')->pluck('users.id')->toArray();

        return \Inertia\Inertia::render('Spmi/Audit/Edit', [
            'audit'           => $audit,
            'periodes'        => $periodes,
            'auditors'        => $auditors,
            'selectedAnggota' => $selectedAnggota,
        ]);
    }

    public function update(Request $request, Audit $audit)
    {
        $request->validate([
            'periode_id'                => 'required|exists:periodes,id',
            'nama_audit'                => 'required|string|max:255',
            'unit_yang_diaudit'         => 'required|string|max:255',
            'ketua_auditor_id'          => 'required|exists:users,id',
            'tanggal_audit'             => 'required|date',
            'opening_meeting'           => 'nullable|date',
            'closing_meeting'           => 'nullable|date',
            'tanggal_selesai'           => 'nullable|date|after_or_equal:tanggal_audit',
            'status'                    => 'required|in:draft,aktif,selesai,ditutup',
            'lingkup_audit'             => 'nullable|string',
            'tujuan_audit'              => 'nullable|string',
            'catatan'                   => 'nullable|string',
            'nomor_surat_tugas'         => 'nullable|string|max:100',
            'tgl_surat_tugas'           => 'nullable|date',
            'penandatangan_surat_tugas' => 'nullable|string|max:255',
            'jabatan_penandatangan'     => 'nullable|string|max:255',
            'bapa_catatan'              => 'nullable|string',
            'anggota_auditor'           => 'nullable|array',
            'anggota_auditor.*'         => 'exists:users,id',
        ]);

        $audit->update($request->only([
            'periode_id', 'nama_audit', 'unit_yang_diaudit', 'ketua_auditor_id',
            'tanggal_audit', 'tanggal_selesai', 'opening_meeting', 'closing_meeting', 'status',
            'lingkup_audit', 'tujuan_audit', 'catatan',
            'nomor_surat_tugas', 'tgl_surat_tugas', 'penandatangan_surat_tugas', 'jabatan_penandatangan', 'bapa_catatan',
        ]));

        $auditorIds = collect($request->anggota_auditor ?? [])
            ->mapWithKeys(fn($id) => [$id => ['peran' => 'anggota']]);
        $auditorIds[$request->ketua_auditor_id] = ['peran' => 'ketua'];
        $audit->auditors()->sync($auditorIds);

        // Check Conflict of Interest warning
        $warnings = $this->checkAuditorConflicts($request->unit_yang_diaudit, $request->ketua_auditor_id, $request->anggota_auditor ?? []);
        $msg = 'Audit berhasil diperbarui.';
        if (!empty($warnings)) {
            $msg .= ' PERINGATAN: ' . implode(' ', $warnings);
        }

        return redirect()->route('audit.show', $audit)->with('success', $msg);
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return redirect()->route('audit.index')
            ->with('success', 'Audit berhasil dihapus.');
    }

    public function updateAiSummary(Request $request, Audit $audit)
    {
        $audit->update(['ai_summary' => $request->ai_summary]);
        return response()->json(['success' => true]);
    }

    public function suratTugasPdf(Audit $audit)
    {
        $audit->load(['periode', 'ketuaAuditor.prodi', 'auditors.prodi']);
        Setting::clearCache();
        $setting = [
            'nama_institusi'   => Setting::get('nama_institusi', 'LEMBAGA PENJAMINAN MUTU'),
            'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Kampus'),
            'kota_institusi'   => Setting::get('kota_institusi', 'Kota'),
            'logo_institusi'   => Setting::get('logo_institusi', null),
        ];

        $penandatanganDefault = User::role('super_admin')->where('is_active', true)->first();

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'sans-serif',
        ])->loadView('spmi::audit.pdf.surat_tugas', compact('audit', 'setting', 'penandatanganDefault'))
          ->setPaper('a4', 'portrait');

        return $pdf->stream('Surat_Tugas_Auditor_' . str_replace('/', '_', $audit->kode_audit) . '.pdf');
    }

    public function bapaPdf(Audit $audit)
    {
        $audit->load(['periode', 'ketuaAuditor', 'auditors', 'temuans.checklist.indikator.standar', 'bapaAuditee']);
        Setting::clearCache();
        $setting = [
            'nama_institusi'   => Setting::get('nama_institusi', 'LEMBAGA PENJAMINAN MUTU'),
            'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Kampus'),
            'kota_institusi'   => Setting::get('kota_institusi', 'Kota'),
            'logo_institusi'   => Setting::get('logo_institusi', null),
        ];

        $auditeeLeader = User::where('unit_kerja', $audit->unit_yang_diaudit)->where('is_active', true)->first();

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'sans-serif',
        ])->loadView('spmi::audit.pdf.bapa', compact('audit', 'setting', 'auditeeLeader'))
          ->setPaper('a4', 'portrait');

        return $pdf->stream('BAPA_' . str_replace('/', '_', $audit->kode_audit) . '.pdf');
    }

    public function signBapa(Request $request, Audit $audit)
    {
        $user = auth()->user();
        $isKetuaAuditor = ($user->id === $audit->ketua_auditor_id) || $user->isSuperAdmin();
        $isAuditeeLeader = $user->isAuditee() || ($user->unit_kerja === $audit->unit_yang_diaudit) || $user->isKaprodi() || $user->isSuperAdmin();

        $request->validate([
            'role_sign'    => 'required|in:auditor,auditee',
            'bapa_catatan' => 'nullable|string',
        ]);

        if ($request->filled('bapa_catatan')) {
            $audit->bapa_catatan = $request->bapa_catatan;
        }

        if ($request->role_sign === 'auditor') {
            if (!$isKetuaAuditor) abort(403, 'Hanya Ketua Auditor atau Super Admin yang dapat menyetujui BAPA sebagai auditor.');
            $audit->bapa_signed_at_auditor = now();
        } elseif ($request->role_sign === 'auditee') {
            if (!$isAuditeeLeader) abort(403, 'Hanya Pimpinan Auditee atau Super Admin yang dapat menyetujui BAPA.');
            $audit->bapa_signed_at_auditee = now();
            $audit->bapa_signed_by_auditee_id = $user->id;
        }

        $audit->save();

        return back()->with('success', 'Persetujuan Berita Acara Pelaksanaan Audit (BAPA) berhasil dicatat secara digital.');
    }

    public function updateDeskEvaluation(Request $request, Audit $audit, AuditChecklist $checklist)
    {
        if ($checklist->audit_id !== $audit->id) abort(403);

        $request->validate([
            'evaluasi_auditee' => 'nullable|string',
            'bukti_auditee'    => 'nullable|string',
            'catatan'          => 'nullable|string',
            'status'           => 'nullable|in:sesuai,tidak_sesuai,observasi,tidak_terkait,belum_diisi',
        ]);

        $updateData = [
            'evaluasi_auditee' => $request->evaluasi_auditee,
            'bukti_auditee'    => $request->bukti_auditee,
            'catatan'          => $request->catatan ?? $checklist->catatan,
        ];

        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        $checklist->update($updateData);

        return back()->with('success', 'Hasil Desk Evaluation / Evaluasi Diri berhasil diperbarui.');
    }

    private function checkAuditorConflicts(string $unitYangDiaudit, int $ketuaAuditorId, array $anggotaIds = []): array
    {
        $warnings = [];
        $allAuditorIds = array_unique(array_filter(array_merge([$ketuaAuditorId], $anggotaIds)));
        $auditors = User::with('prodi')->whereIn('id', $allAuditorIds)->get();

        foreach ($auditors as $user) {
            $userUnit = trim(strtolower($user->unit_kerja ?? ''));
            $userProdi = trim(strtolower($user->prodi?->nama ?? ''));
            $targetUnit = trim(strtolower($unitYangDiaudit));

            if ($targetUnit !== '') {
                if ($userUnit !== '' && (str_contains($targetUnit, $userUnit) || str_contains($userUnit, $targetUnit))) {
                    $warnings[] = "Auditor '{$user->name}' memiliki unit kerja ({$user->unit_kerja}) yang sama dengan auditee ({$unitYangDiaudit}).";
                } elseif ($userProdi !== '' && (str_contains($targetUnit, $userProdi) || str_contains($userProdi, $targetUnit))) {
                    $warnings[] = "Auditor '{$user->name}' terafiliasi dengan prodi ({$user->prodi->nama}) yang sama dengan auditee ({$unitYangDiaudit}).";
                }
            }
        }
        return $warnings;
    }
}
