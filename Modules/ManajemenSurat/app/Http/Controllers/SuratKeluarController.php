<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        // Filter berdasarkan jenis surat
        if ($request->filled('jenis_surat_id')) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->tahun($request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        $suratKeluar = $query->latest()->paginate(15);
        $jenisSurat = JenisSurat::kategori('keluar')->active()->get();

        return view('manajemen-surat::surat-keluar.index', compact('suratKeluar', 'jenisSurat'));
    }

    public function create()
    {
        $jenisSurat = JenisSurat::kategori('keluar')->active()->get();
        $unitPengelola = \Modules\ManajemenSurat\Models\UnitPengelolaSurat::active()->orderBy('jenis_institusi')->orderBy('nama')->get();
        
        return view('manajemen-surat::surat-keluar.create', compact('jenisSurat', 'unitPengelola'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'unit_id' => 'nullable|exists:unit_pengelola_surat,id',
            'perihal' => 'required|string|max:255',
            'isi_surat' => 'nullable|string',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'alamat_tujuan' => 'nullable|string',
            'penandatangan_nama' => 'required|string|max:100',
            'penandatangan_jabatan' => 'required|string|max:100',
            'penandatangan_nip' => 'nullable|string|max:50',
            'jumlah_lampiran' => 'nullable|integer|min:0',
            'keterangan_lampiran' => 'nullable|string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:draft,pending,published',
        ]);

        // Generate nomor surat otomatis dengan unit
        $tanggalSurat = Carbon::parse($validated['tanggal_surat']);
        $nomorSurat = $this->nomorSuratService->generateNomorSurat(
            $validated['jenis_surat_id'],
            $validated['unit_id'] ?? null,
            $tanggalSurat
        );

        $validated['nomor_surat'] = $nomorSurat;
        $validated['created_by'] = auth()->id();

        // Auto-approve if status is published
        if ($validated['status'] === 'published') {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $surat = SuratKeluar::create($validated);

        return redirect()
            ->route('surat-keluar.show', $surat)
            ->with('success', 'Surat keluar berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'creator', 'approver']);
        
        return view('manajemen-surat::surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        if (!$suratKeluar->isEditable()) {
            return back()->with('error', 'Surat tidak dapat diedit karena sudah disetujui.');
        }

        $jenisSurat = JenisSurat::kategori('keluar')->active()->get();
        $unitPengelola = \Modules\ManajemenSurat\Models\UnitPengelolaSurat::active()->orderBy('jenis_institusi')->orderBy('nama')->get();
        
        return view('manajemen-surat::surat-keluar.edit', compact('suratKeluar', 'jenisSurat', 'unitPengelola'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        if (!$suratKeluar->isEditable()) {
            return back()->with('error', 'Surat tidak dapat diedit karena sudah disetujui.');
        }

        $validated = $request->validate([
            'perihal' => 'required|string|max:255',
            'isi_surat' => 'nullable|string',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'alamat_tujuan' => 'nullable|string',
            'penandatangan_nama' => 'required|string|max:100',
            'penandatangan_jabatan' => 'required|string|max:100',
            'penandatangan_nip' => 'nullable|string|max:50',
            'jumlah_lampiran' => 'nullable|integer|min:0',
            'keterangan_lampiran' => 'nullable|string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:draft,pending,published',
        ]);

        // Auto-approve if status is published
        if ($validated['status'] === 'published' && $suratKeluar->status !== 'published') {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $suratKeluar->update($validated);

        return redirect()
            ->route('surat-keluar.show', $suratKeluar)
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        // Tidak perlu hapus file karena tidak ada file yang disimpan
        // File PDF di-generate on-demand saja
        
        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function download(SuratKeluar $suratKeluar)
    {
        // Redirect ke generatePdf untuk generate on-the-fly
        return $this->generatePdf($suratKeluar);
    }

    public function generatePdf(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'creator', 'approver']);
        
        // Map jenis surat kode to PDF template
        $templateMap = [
            'SK-YYS' => 'sk_yayasan',
            'SK-PT' => 'sk_pt',
            'ST' => 'surat_tugas',
            'SU' => 'surat_undangan',
            'SKET' => 'surat_keterangan',
            'SE' => 'surat_edaran',
            'SP' => 'surat_pengantar',
            'MOU' => 'mou',
            'MOA' => 'moa',
            'SREKOM' => 'surat_rekomendasi',
        ];

        $kodeJenis = $suratKeluar->jenisSurat->kode;
        $template = $templateMap[$kodeJenis] ?? 'surat_generic'; // default to generic template

        // Check if template exists, fallback to generic
        $viewPath = "manajemen-surat::pdf.{$template}";
        if (!view()->exists($viewPath)) {
            $viewPath = "manajemen-surat::pdf.surat_generic";
        }

        // Generate PDF
        $pdf = Pdf::loadView($viewPath, [
            'surat' => $suratKeluar
        ]);

        // Set paper size dan orientation
        $pdf->setPaper('a4', 'portrait');

        // Download PDF
        $filename = Str::slug($suratKeluar->perihal) . '-' . str_replace('/', '-', $suratKeluar->nomor_surat) . '.pdf';
        
        return $pdf->download($filename);
    }

    public function previewPdf(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['jenisSurat', 'creator', 'approver']);
        
        // Map jenis surat kode to PDF template
        $templateMap = [
            'SK-YYS' => 'sk_yayasan',
            'SK-PT' => 'sk_pt',
            'ST' => 'surat_tugas',
            'SU' => 'surat_undangan',
            'SKET' => 'surat_keterangan',
            'SE' => 'surat_edaran',
            'SP' => 'surat_pengantar',
            'MOU' => 'mou',
            'MOA' => 'moa',
            'SREKOM' => 'surat_rekomendasi',
        ];

        $kodeJenis = $suratKeluar->jenisSurat->kode;
        $template = $templateMap[$kodeJenis] ?? 'surat_generic'; // default to generic template

        // Check if template exists, fallback to generic
        $viewPath = "manajemen-surat::pdf.{$template}";
        if (!view()->exists($viewPath)) {
            $viewPath = "manajemen-surat::pdf.surat_generic";
        }

        // Generate PDF
        $pdf = Pdf::loadView($viewPath, [
            'surat' => $suratKeluar
        ]);

        // Set paper size dan orientation
        $pdf->setPaper('a4', 'portrait');

        // Stream PDF (tampil di browser)
        return $pdf->stream();
    }

    public function approve(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status === 'published') {
            return back()->with('error', 'Surat sudah disetujui sebelumnya.');
        }

        $suratKeluar->update([
            'status' => 'published',
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
            'status' => 'draft',
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Surat ditolak dan dikembalikan ke status draft.');
    }
}
