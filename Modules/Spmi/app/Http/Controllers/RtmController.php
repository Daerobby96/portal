<?php

namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\RTM;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class RtmController extends Controller
{
    public function index()
    {
        $periodeId = session('active_periode_id');

        // Fallback ke periode yang sedang aktif jika session kosong
        if (!$periodeId) {
            $aktif = \Modules\DataMaster\Models\Periode::where('is_aktif', true)->first();
            $periodeId = $aktif ? $aktif->id : null;
        }

        $rtms = RTM::where('periode_id', $periodeId)->latest()->get();

        $stats = [
            'total_temuan' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->count(),
            'kts_mayor' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('kategori', 'KTS_Mayor')->count(),
            'kts_minor' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('kategori', 'KTS_Minor')->count(),
            'observasi' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('kategori', 'OB')->count(),
            'indikator_tercapai' => \Modules\Spmi\Models\Evaluasi::whereHas('monitoring', fn($q) => $q->where('periode_id', $periodeId))->where('hasil', 'tercapai')->count(),
            'indikator_total' => \Modules\Spmi\Models\Monitoring::where('periode_id', $periodeId)->count(),
        ];

        return view('spmi::rtm.index', compact('rtms', 'stats'));
    }

    public function create()
    {
        return view('spmi::rtm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'agenda' => 'nullable|string',
            'input_audit_internal' => 'nullable|string',
            'input_umpan_balik' => 'nullable|string',
            'input_kinerja_proses' => 'nullable|string',
            'input_status_tindakan' => 'nullable|string',
            'input_perubahan_sistem' => 'nullable|string',
            'input_rekomendasi' => 'nullable|string',
            'notulensi' => 'nullable|string',
            'output_keefektifan' => 'nullable|string',
            'output_perbaikan' => 'nullable|string',
            'output_sumber_daya' => 'nullable|string',
            'keputusan_manajemen' => 'nullable|string',
            'file_absensi' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $aktif = \Modules\DataMaster\Models\Periode::where('is_aktif', true)->first();
        $periodeId = session('active_periode_id') ?? ($aktif ? $aktif->id : null);

        $data = $request->except('file_absensi');
        $data['periode_id'] = $periodeId;

        if ($request->hasFile('file_absensi')) {
            $data['file_absensi'] = $request->file('file_absensi')->store('rtm/absensi', 'public');
        }

        RTM::create($data);

        return redirect()->route('rtm.index')->with('success', 'RTM berhasil dibuat.');
    }

    public function show(RTM $rTM)
    {
        $periodeId = $rTM->periode_id;
        $findingStats = [
            'open' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('status', 'open')->count(),
            'in_progress' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->where('status', 'in_progress')->count(),
            'closed' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->whereIn('status', ['closed', 'verified'])->count(),
        ];

        return view('spmi::rtm.create', compact('rTM', 'findingStats'));
    }

    public function edit(RTM $rTM)
    {
        return view('spmi::rtm.show', compact('rTM'));
    }

    public function update(Request $request, RTM $rTM)
    {
        $request->validate([
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'agenda' => 'nullable|string',
            'input_audit_internal' => 'nullable|string',
            'input_umpan_balik' => 'nullable|string',
            'input_kinerja_proses' => 'nullable|string',
            'input_status_tindakan' => 'nullable|string',
            'input_perubahan_sistem' => 'nullable|string',
            'input_rekomendasi' => 'nullable|string',
            'notulensi' => 'nullable|string',
            'output_keefektifan' => 'nullable|string',
            'output_perbaikan' => 'nullable|string',
            'output_sumber_daya' => 'nullable|string',
            'keputusan_manajemen' => 'nullable|string',
            'file_absensi' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'status' => 'required|in:draft,selesai',
        ]);

        $data = $request->except('file_absensi');

        if ($request->hasFile('file_absensi')) {
            if ($rTM->file_absensi)
                Storage::disk('public')->delete($rTM->file_absensi);
            $data['file_absensi'] = $request->file('file_absensi')->store('rtm/absensi', 'public');
        }

        $rTM->update($data);

        return redirect()->route('rtm.index')->with('success', 'RTM berhasil diperbarui.');
    }

    public function destroy(RTM $rTM)
    {
        if ($rTM->file_absensi)
            Storage::disk('public')->delete($rTM->file_absensi);
        $rTM->delete();
        return redirect()->route('rtm.index')->with('success', 'RTM berhasil dihapus.');
    }

    public function exportPdf(RTM $rtm)
    {
        $rtm->load('periode');

        Setting::clearCache();
        $setting = [
            'nama_institusi' => Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
            'alamat_institusi' => Setting::get('alamat_institusi', 'Alamat Institusi'),
            'kota_institusi' => Setting::get('kota_institusi', 'Kota'),
            'logo_institusi' => Setting::get('logo_institusi', null),
        ];


        $ketuaSpmi = User::role('super_admin')->where('is_active', true)->first();

        $kepalaInstitusi = User::where('is_active', true)
            ->where(function ($q) {
                $q->where('jabatan', 'like', '%Kepala%')
                    ->orWhere('jabatan', 'like', '%Rektor%')
                    ->orWhere('jabatan', 'like', '%Direktur%');
            })->first();

        $findingStats = [
            'open' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $rtm->periode_id))->where('status', 'open')->count(),
            'in_progress' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $rtm->periode_id))->where('status', 'in_progress')->count(),
            'closed' => \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $rtm->periode_id))->whereIn('status', ['closed', 'verified'])->count(),
        ];

        $data = [
            'rtm' => $rtm,
            'findingStats' => $findingStats,
            'setting' => $setting,
            'ketua_spmi' => $ketuaSpmi,
            'kepala_institusi' => $kepalaInstitusi,
        ];

        $pdf = Pdf::setOptions([
            'margin-top' => 75,
            'margin-left' => 75,
            'margin-right' => 75,
            'margin-bottom' => 75,
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ])->loadView('spmi::rtm.pdf', $data);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan-RTM-' . str_replace(' ', '-', $rtm->judul_rapat) . '.pdf';
        return $pdf->stream($filename);
    }

    public function compileAuditData(Request $request)
    {
        $periodeId = $request->periode_id ?? session('active_periode_id') ?? \Modules\DataMaster\Models\Periode::where('is_aktif', true)->value('id');

        $audits = \Modules\Spmi\Models\Audit::with(['ketuaAuditor', 'temuans'])
            ->where('periode_id', $periodeId)
            ->get();

        $totalAudit = $audits->count();
        $selesaiAudit = $audits->where('status', 'selesai')->count();

        $temuans = \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $periodeId))->get();
        $totalTemuan = $temuans->count();
        $ktsMayor = $temuans->where('kategori', 'KTS_Mayor')->count();
        $ktsMinor = $temuans->where('kategori', 'KTS_Minor')->count();
        $ob = $temuans->where('kategori', 'OB')->count();
        $rekomendasi = $temuans->where('kategori', 'Rekomendasi')->count();
        $openCount = $temuans->where('status', 'open')->count();
        $closedCount = $temuans->whereIn('status', ['closed', 'verified'])->count();

        $unitList = $audits->pluck('unit_yang_diaudit')->unique()->implode(', ');

        $compiledInputAudit = "Rekapitulasi Audit Mutu Internal (AMI) Periode Aktif:\n"
            . "- Total Audit Terjadwal: {$totalAudit} unit ({$selesaiAudit} selesai).\n"
            . "- Unit Kerja yang Diaudit: {$unitList}.\n"
            . "- Total Temuan: {$totalTemuan} (KTS Mayor: {$ktsMayor}, KTS Minor: {$ktsMinor}, Observasi: {$ob}, Rekomendasi: {$rekomendasi}).\n"
            . "- Status Penyelesaian Temuan: {$closedCount} Closed/Verified, {$openCount} Masih Open/In Progress.\n";

        if ($ktsMayor > 0) {
            $compiledInputAudit .= "\nCatatan Kritis: Terdapat {$ktsMayor} temuan KTS Mayor yang memerlukan perhatian khusus pimpinan dalam alokasi sumber daya dan perbaikan prosedur operasional.";
        }

        return response()->json([
            'success' => true,
            'data'    => $compiledInputAudit,
            'stats'   => [
                'total_audit'   => $totalAudit,
                'total_temuan'  => $totalTemuan,
                'kts_mayor'     => $ktsMayor,
                'kts_minor'     => $ktsMinor,
                'open_temuan'   => $openCount,
                'closed_temuan' => $closedCount,
            ],
        ]);
    }
}

