<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
        $query = SuratMasuk::with(['creator', 'jenisSurat', 'disposisi']);

        if ($request->filled('jenis_surat_id')) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sifat')) {
            $query->where('sifat', $request->sifat);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_terima', $request->tahun);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%");
            });
        }

        $suratMasuk = $query->latest('tanggal_terima')->paginate(15)->through(fn ($s) => [
            'id'             => $s->id,
            'nomor_agenda'   => $s->nomor_agenda,
            'nomor_surat'    => $s->nomor_surat,
            'pengirim'       => $s->pengirim,
            'perihal'        => $s->perihal,
            'sifat'          => $s->sifat,
            'prioritas'      => $s->prioritas,
            'status'         => $s->status,
            'tanggal_surat'  => $s->tanggal_surat?->format('Y-m-d'),
            'tanggal_terima' => $s->tanggal_terima?->format('Y-m-d'),
            'jenis_surat'    => $s->jenisSurat?->nama,
            'has_file'       => (bool) $s->file_path,
            'disposisi_count'=> $s->disposisi->count(),
            'creator_name'   => $s->creator?->name,
        ]);

        $jenisSurat = JenisSurat::where('kategori', 'masuk')->where('is_active', true)->get()
            ->map(fn ($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);

        return Inertia::render('Surat/SuratMasuk/Index', [
            'suratMasuk' => $suratMasuk,
            'jenisSurat' => $jenisSurat,
            'filters'    => $request->only(['search', 'status', 'sifat', 'jenis_surat_id', 'tahun']),
        ]);
    }

    public function create()
    {
        $jenisSurat = JenisSurat::where('kategori', 'masuk')->where('is_active', true)->get()
            ->map(fn ($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);

        return Inertia::render('Surat/SuratMasuk/Create', [
            'jenisSurat' => $jenisSurat,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id'       => 'required|exists:jenis_surat,id',
            'nomor_surat'          => 'required|string|max:100',
            'tanggal_surat'        => 'required|date',
            'tanggal_terima'       => 'required|date',
            'pengirim'             => 'required|string|max:255',
            'alamat_pengirim'      => 'nullable|string',
            'perihal'              => 'required|string|max:255',
            'jumlah_lampiran'      => 'nullable|integer|min:0',
            'keterangan_lampiran'  => 'nullable|string',
            'sifat'                => 'required|in:biasa,segera,sangat_segera,rahasia',
            'prioritas'            => 'required|in:rendah,sedang,tinggi',
            'catatan'              => 'nullable|string',
            'file'                 => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $tanggalTerima = Carbon::parse($validated['tanggal_terima']);
        $nomorAgenda   = $this->nomorSuratService->generateNomorAgenda($tanggalTerima);

        $validated['nomor_agenda'] = $nomorAgenda;
        $validated['created_by']   = auth()->id();
        $validated['received_by']  = auth()->id();
        $validated['status']       = 'baru';

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_masuk', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $surat = SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.show', $surat)
            ->with('success', 'Surat masuk berhasil dicatat dengan nomor agenda: ' . $nomorAgenda);
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['jenisSurat', 'creator', 'receiver', 'disposisi.dari', 'disposisi.kepada']);

        return Inertia::render('Surat/SuratMasuk/Show', [
            'suratMasuk' => [
                'id'                  => $suratMasuk->id,
                'nomor_agenda'        => $suratMasuk->nomor_agenda,
                'nomor_surat'         => $suratMasuk->nomor_surat,
                'pengirim'            => $suratMasuk->pengirim,
                'alamat_pengirim'     => $suratMasuk->alamat_pengirim,
                'perihal'             => $suratMasuk->perihal,
                'sifat'               => $suratMasuk->sifat,
                'prioritas'           => $suratMasuk->prioritas,
                'status'              => $suratMasuk->status,
                'tanggal_surat'       => $suratMasuk->tanggal_surat?->format('d M Y'),
                'tanggal_terima'      => $suratMasuk->tanggal_terima?->format('d M Y'),
                'jumlah_lampiran'     => $suratMasuk->jumlah_lampiran,
                'keterangan_lampiran' => $suratMasuk->keterangan_lampiran,
                'catatan'             => $suratMasuk->catatan,
                'file_url'            => $suratMasuk->file_path ? asset('storage/' . $suratMasuk->file_path) : null,
                'jenis_surat'         => $suratMasuk->jenisSurat?->nama,
                'creator_name'        => $suratMasuk->creator?->name,
                'disposisi'           => $suratMasuk->disposisi->map(fn ($d) => [
                    'id'                  => $d->id,
                    'dari_nama'           => $d->dari?->name,
                    'kepada_nama'         => $d->kepada?->name,
                    'isi_disposisi'       => $d->isi_disposisi,
                    'prioritas'           => $d->prioritas,
                    'status'              => $d->status,
                    'batas_waktu'         => $d->batas_waktu?->format('d M Y'),
                    'catatan_tindak_lanjut' => $d->catatan_tindak_lanjut,
                    'created_at'          => $d->created_at->format('d M Y H:i'),
                ]),
            ],
            'canDisposisi' => auth()->user()->hasRole(['super_admin', 'pimpinan', 'admin_prodi']),
        ]);
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $jenisSurat = JenisSurat::where('kategori', 'masuk')->where('is_active', true)->get()
            ->map(fn ($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);

        return Inertia::render('Surat/SuratMasuk/Edit', [
            'suratMasuk' => [
                'id'                  => $suratMasuk->id,
                'jenis_surat_id'      => $suratMasuk->jenis_surat_id,
                'nomor_surat'         => $suratMasuk->nomor_surat,
                'tanggal_surat'       => $suratMasuk->tanggal_surat?->format('Y-m-d'),
                'tanggal_terima'      => $suratMasuk->tanggal_terima?->format('Y-m-d'),
                'pengirim'            => $suratMasuk->pengirim,
                'alamat_pengirim'     => $suratMasuk->alamat_pengirim,
                'perihal'             => $suratMasuk->perihal,
                'jumlah_lampiran'     => $suratMasuk->jumlah_lampiran,
                'keterangan_lampiran' => $suratMasuk->keterangan_lampiran,
                'sifat'               => $suratMasuk->sifat,
                'prioritas'           => $suratMasuk->prioritas,
                'status'              => $suratMasuk->status,
                'catatan'             => $suratMasuk->catatan,
                'has_file'            => (bool) $suratMasuk->file_path,
            ],
            'jenisSurat' => $jenisSurat,
        ]);
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_surat'          => 'required|string|max:100',
            'tanggal_surat'        => 'required|date',
            'tanggal_terima'       => 'required|date',
            'pengirim'             => 'required|string|max:255',
            'alamat_pengirim'      => 'nullable|string',
            'perihal'              => 'required|string|max:255',
            'jumlah_lampiran'      => 'nullable|integer|min:0',
            'keterangan_lampiran'  => 'nullable|string',
            'sifat'                => 'required|in:biasa,segera,sangat_segera,rahasia',
            'prioritas'            => 'required|in:rendah,sedang,tinggi',
            'status'               => 'required|in:baru,proses,selesai,arsip',
            'catatan'              => 'nullable|string',
            'file'                 => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('file')) {
            if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_masuk', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $suratMasuk->update($validated);

        return redirect()->route('surat-masuk.show', $suratMasuk)
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_path && Storage::disk('public')->exists($suratMasuk->file_path)) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }
        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')
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
