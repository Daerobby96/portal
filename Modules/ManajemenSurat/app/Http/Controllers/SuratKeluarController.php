<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\ManajemenSurat\Models\SuratKeluar;
use Modules\ManajemenSurat\Models\JenisSurat;
use Modules\ManajemenSurat\Services\NomorSuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SuratKeluarController extends Controller
{
    protected $nomorSuratService;

    public function __construct(NomorSuratService $nomorSuratService)
    {
        $this->nomorSuratService = $nomorSuratService;
    }

    public function index(Request $request)
    {
        $query = SuratKeluar::with(['creator', 'jenisSurat', 'approver']);

        if ($request->filled('jenis_surat_id')) $query->where('jenis_surat_id', $request->jenis_surat_id);
        if ($request->filled('status'))         $query->where('status', $request->status);
        if ($request->filled('tahun'))          $query->whereYear('tanggal_surat', $request->tahun);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('nomor_surat', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%")
                ->orWhere('tujuan', 'like', "%{$search}%")
            );
        }

        $suratKeluar = $query->latest()->paginate(15)->through(fn ($s) => [
            'id'            => $s->id,
            'nomor_surat'   => $s->nomor_surat,
            'perihal'       => $s->perihal,
            'tujuan'        => $s->tujuan,
            'status'        => $s->status,
            'tanggal_surat' => $s->tanggal_surat?->format('Y-m-d'),
            'jenis_surat'   => $s->jenisSurat?->nama,
            'jenis_kode'    => $s->jenisSurat?->kode,
            'creator_name'  => $s->creator?->name,
            'approver_name' => $s->approver?->name,
            'approved_at'   => $s->approved_at?->format('d M Y'),
        ]);

        $jenisSurat = JenisSurat::where('kategori', 'keluar')->where('is_active', true)->get()
            ->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);

        return Inertia::render('Surat/SuratKeluar/Index', [
            'suratKeluar' => $suratKeluar,
            'jenisSurat'  => $jenisSurat,
            'filters'     => $request->only(['search', 'status', 'jenis_surat_id', 'tahun']),
            'canApprove'  => auth()->user()->hasRole(['super_admin', 'pimpinan']),
        ]);
    }

    public function create()
    {
        $jenisSurat    = JenisSurat::where('kategori', 'keluar')->where('is_active', true)->get()
            ->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);
        $unitPengelola = \Modules\ManajemenSurat\Models\UnitPengelolaSurat::where('is_active', true)
            ->orderBy('jenis_institusi')->orderBy('nama')->get()
            ->map(fn($u) => ['id' => $u->id, 'nama' => $u->nama, 'kode' => $u->kode, 'jenis' => $u->jenis_institusi]);

        return Inertia::render('Surat/SuratKeluar/Create', [
            'jenisSurat'    => $jenisSurat,
            'unitPengelola' => $unitPengelola,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id'       => 'required|exists:jenis_surat,id',
            'unit_id'              => 'nullable|exists:unit_pengelola_surat,id',
            'perihal'              => 'required|string|max:255',
            'isi_surat'            => 'nullable|string',
            'tanggal_surat'        => 'required|date',
            'tujuan'               => 'required|string|max:255',
            'alamat_tujuan'        => 'nullable|string',
            'penandatangan_nama'   => 'required|string|max:100',
            'penandatangan_jabatan'=> 'required|string|max:100',
            'penandatangan_nip'    => 'nullable|string|max:50',
            'jumlah_lampiran'      => 'nullable|integer|min:0',
            'keterangan_lampiran'  => 'nullable|string',
            'catatan'              => 'nullable|string',
            'status'               => 'required|in:draft,pending,published',
        ]);

        $tanggalSurat = Carbon::parse($validated['tanggal_surat']);
        $nomorSurat   = $this->nomorSuratService->generateNomorSurat(
            $validated['jenis_surat_id'],
            $validated['unit_id'] ?? null,
            $tanggalSurat
        );

        $validated['nomor_surat'] = $nomorSurat;
        $validated['created_by']  = auth()->id();

        if ($validated['status'] === 'published') {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $surat = SuratKeluar::create($validated);

        return redirect()->route('surat-keluar.show', $surat)
            ->with('success', 'Surat keluar berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'creator', 'approver']);

        return Inertia::render('Surat/SuratKeluar/Show', [
            'suratKeluar' => [
                'id'                   => $suratKeluar->id,
                'nomor_surat'          => $suratKeluar->nomor_surat,
                'perihal'              => $suratKeluar->perihal,
                'isi_surat'            => $suratKeluar->isi_surat,
                'tujuan'               => $suratKeluar->tujuan,
                'alamat_tujuan'        => $suratKeluar->alamat_tujuan,
                'penandatangan_nama'   => $suratKeluar->penandatangan_nama,
                'penandatangan_jabatan'=> $suratKeluar->penandatangan_jabatan,
                'penandatangan_nip'    => $suratKeluar->penandatangan_nip,
                'status'               => $suratKeluar->status,
                'tanggal_surat'        => $suratKeluar->tanggal_surat?->format('d M Y'),
                'jumlah_lampiran'      => $suratKeluar->jumlah_lampiran,
                'keterangan_lampiran'  => $suratKeluar->keterangan_lampiran,
                'catatan'              => $suratKeluar->catatan,
                'jenis_surat'          => $suratKeluar->jenisSurat?->nama,
                'jenis_kode'           => $suratKeluar->jenisSurat?->kode,
                'creator_name'         => $suratKeluar->creator?->name,
                'approver_name'        => $suratKeluar->approver?->name,
                'approved_at'          => $suratKeluar->approved_at?->format('d M Y H:i'),
                'created_at'           => $suratKeluar->created_at->format('d M Y H:i'),
                'pdf_url'              => route('surat-keluar.preview-pdf', $suratKeluar),
                'download_url'         => route('surat-keluar.pdf', $suratKeluar),
            ],
            'canApprove'  => auth()->user()->hasRole(['super_admin', 'pimpinan']),
            'canEdit'     => $suratKeluar->isEditable(),
        ]);
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        if (!$suratKeluar->isEditable()) {
            return redirect()->route('surat-keluar.show', $suratKeluar)
                ->with('error', 'Surat tidak dapat diedit karena sudah disetujui.');
        }

        $jenisSurat    = JenisSurat::where('kategori', 'keluar')->where('is_active', true)->get()
            ->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);
        $unitPengelola = \Modules\ManajemenSurat\Models\UnitPengelolaSurat::where('is_active', true)
            ->orderBy('nama')->get()
            ->map(fn($u) => ['id' => $u->id, 'nama' => $u->nama, 'kode' => $u->kode, 'jenis' => $u->jenis_institusi]);

        return Inertia::render('Surat/SuratKeluar/Edit', [
            'suratKeluar' => [
                'id'                   => $suratKeluar->id,
                'jenis_surat_id'       => $suratKeluar->jenis_surat_id,
                'unit_id'              => $suratKeluar->unit_id,
                'perihal'              => $suratKeluar->perihal,
                'isi_surat'            => $suratKeluar->isi_surat,
                'tanggal_surat'        => $suratKeluar->tanggal_surat?->format('Y-m-d'),
                'tujuan'               => $suratKeluar->tujuan,
                'alamat_tujuan'        => $suratKeluar->alamat_tujuan,
                'penandatangan_nama'   => $suratKeluar->penandatangan_nama,
                'penandatangan_jabatan'=> $suratKeluar->penandatangan_jabatan,
                'penandatangan_nip'    => $suratKeluar->penandatangan_nip,
                'jumlah_lampiran'      => $suratKeluar->jumlah_lampiran,
                'keterangan_lampiran'  => $suratKeluar->keterangan_lampiran,
                'catatan'              => $suratKeluar->catatan,
                'status'               => $suratKeluar->status,
            ],
            'jenisSurat'    => $jenisSurat,
            'unitPengelola' => $unitPengelola,
        ]);
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        if (!$suratKeluar->isEditable()) {
            return back()->with('error', 'Surat tidak dapat diedit karena sudah disetujui.');
        }

        $validated = $request->validate([
            'perihal'              => 'required|string|max:255',
            'isi_surat'            => 'nullable|string',
            'tanggal_surat'        => 'required|date',
            'tujuan'               => 'required|string|max:255',
            'alamat_tujuan'        => 'nullable|string',
            'penandatangan_nama'   => 'required|string|max:100',
            'penandatangan_jabatan'=> 'required|string|max:100',
            'penandatangan_nip'    => 'nullable|string|max:50',
            'jumlah_lampiran'      => 'nullable|integer|min:0',
            'keterangan_lampiran'  => 'nullable|string',
            'catatan'              => 'nullable|string',
            'status'               => 'required|in:draft,pending,published',
        ]);

        if ($validated['status'] === 'published' && $suratKeluar->status !== 'published') {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $suratKeluar->update($validated);

        return redirect()->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        $suratKeluar->delete();
        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function download(SuratKeluar $suratKeluar)
    {
        return $this->generatePdf($suratKeluar);
    }

    public function generatePdf(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'creator', 'approver']);

        $templateMap = [
            'SK-YYS' => 'sk_yayasan', 'SK-PT' => 'sk_pt', 'ST' => 'surat_tugas',
            'SU' => 'surat_undangan', 'SKET' => 'surat_keterangan', 'SE' => 'surat_edaran',
            'SP' => 'surat_pengantar', 'MOU' => 'mou', 'MOA' => 'moa', 'SREKOM' => 'surat_rekomendasi',
        ];

        $kodeJenis = $suratKeluar->jenisSurat->kode;
        $template  = $templateMap[$kodeJenis] ?? 'surat_generic';
        $viewPath  = "manajemen-surat::pdf.{$template}";

        if (!view()->exists($viewPath)) {
            $viewPath = "manajemen-surat::pdf.surat_generic";
        }

        $pdf      = Pdf::loadView($viewPath, ['surat' => $suratKeluar])->setPaper('a4', 'portrait');
        $filename = Str::slug($suratKeluar->perihal) . '-' . str_replace('/', '-', $suratKeluar->nomor_surat) . '.pdf';

        return $pdf->download($filename);
    }

    public function previewPdf(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'creator', 'approver']);

        $templateMap = [
            'SK-YYS' => 'sk_yayasan', 'SK-PT' => 'sk_pt', 'ST' => 'surat_tugas',
            'SU' => 'surat_undangan', 'SKET' => 'surat_keterangan', 'SE' => 'surat_edaran',
            'SP' => 'surat_pengantar', 'MOU' => 'mou', 'MOA' => 'moa', 'SREKOM' => 'surat_rekomendasi',
        ];

        $kodeJenis = $suratKeluar->jenisSurat->kode;
        $template  = $templateMap[$kodeJenis] ?? 'surat_generic';
        $viewPath  = "manajemen-surat::pdf.{$template}";

        if (!view()->exists($viewPath)) {
            $viewPath = "manajemen-surat::pdf.surat_generic";
        }

        return Pdf::loadView($viewPath, ['surat' => $suratKeluar])->setPaper('a4', 'portrait')->stream();
    }

    public function approve(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status === 'published') {
            return back()->with('error', 'Surat sudah disetujui sebelumnya.');
        }

        $suratKeluar->update([
            'status'      => 'published',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Surat berhasil disetujui dan dipublikasikan.');
    }

    public function reject(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status === 'published') {
            return back()->with('error', 'Surat yang sudah dipublikasikan tidak dapat ditolak.');
        }

        $suratKeluar->update([
            'status'      => 'draft',
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Surat ditolak dan dikembalikan ke status draft.');
    }
}
