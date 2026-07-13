<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ManajemenSurat\Models\SuratMasuk;
use Modules\ManajemenSurat\Models\JenisSurat;
use Modules\ManajemenSurat\Services\NomorSuratService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    protected $nomorSuratService;

    public function __construct(NomorSuratService $nomorSuratService)
    {
        $this->nomorSuratService = $nomorSuratService;
    }

    public function index(Request $request)
    {
        $query = SuratMasuk::with(['creator', 'jenisSurat', 'receiver', 'disposisi']);

        // Filter berdasarkan jenis surat
        if ($request->filled('jenis_surat_id')) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan sifat
        if ($request->filled('sifat')) {
            $query->where('sifat', $request->sifat);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_terima', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%");
            });
        }

        $suratMasuk = $query->latest('tanggal_terima')->paginate(15);
        $jenisSurat = JenisSurat::kategori('masuk')->active()->get();

        return view('manajemen-surat::surat-masuk.index', compact('suratMasuk', 'jenisSurat'));
    }

    public function create()
    {
        $jenisSurat = JenisSurat::kategori('masuk')->active()->get();
        
        return view('manajemen-surat::surat-masuk.create', compact('jenisSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'alamat_pengirim' => 'nullable|string',
            'perihal' => 'required|string|max:255',
            'jumlah_lampiran' => 'nullable|integer|min:0',
            'keterangan_lampiran' => 'nullable|string',
            'sifat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        ]);

        // Generate nomor agenda otomatis
        $tanggalTerima = Carbon::parse($validated['tanggal_terima']);
        $nomorAgenda = $this->nomorSuratService->generateNomorAgenda($tanggalTerima);

        $validated['nomor_agenda'] = $nomorAgenda;
        $validated['created_by'] = auth()->id();
        $validated['received_by'] = auth()->id();
        $validated['status'] = 'baru';

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_masuk', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $surat = SuratMasuk::create($validated);

        return redirect()
            ->route('surat-masuk.show', $surat)
            ->with('success', 'Surat masuk berhasil dicatat dengan nomor agenda: ' . $nomorAgenda);
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['jenisSurat', 'creator', 'receiver', 'disposisi.dari', 'disposisi.kepada']);
        
        return view('manajemen-surat::surat-masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $jenisSurat = JenisSurat::kategori('masuk')->active()->get();
        
        return view('manajemen-surat::surat-masuk.edit', compact('suratMasuk', 'jenisSurat'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'alamat_pengirim' => 'nullable|string',
            'perihal' => 'required|string|max:255',
            'jumlah_lampiran' => 'nullable|integer|min:0',
            'keterangan_lampiran' => 'nullable|string',
            'sifat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'status' => 'required|in:baru,proses,selesai,arsip',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_masuk', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $suratMasuk->update($validated);

        return redirect()
            ->route('surat-masuk.show', $suratMasuk)
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }

        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function download(SuratMasuk $suratMasuk)
    {
        if (!$suratMasuk->file_path || !Storage::disk('public')->exists($suratMasuk->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($suratMasuk->file_path);
    }
}
