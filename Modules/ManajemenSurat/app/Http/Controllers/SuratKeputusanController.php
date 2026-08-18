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

class SuratKeputusanController extends Controller
{
    protected $nomorSuratService;

    public function __construct(NomorSuratService $nomorSuratService)
    {
        $this->nomorSuratService = $nomorSuratService;
    }

    public function index()
    {
        $sks = SuratKeluar::with(['creator', 'jenisSurat'])
            ->whereHas('jenisSurat', fn($q) => $q->whereIn('kode', ['SK-YYS', 'SK-PT']))
            ->latest()->paginate(10)->through(fn($s) => [
                'id'            => $s->id,
                'nomor_surat'   => $s->nomor_surat,
                'perihal'       => $s->perihal,
                'status'        => $s->status,
                'tanggal_surat' => $s->tanggal_surat?->format('d M Y'),
                'jenis_surat'   => $s->jenisSurat?->nama,
                'jenis_kode'    => $s->jenisSurat?->kode,
                'creator_name'  => $s->creator?->name,
                'penandatangan' => $s->penandatangan_nama,
                'has_file'      => (bool) $s->file_path,
                'download_url'  => $s->file_path ? route('surat-keputusan.download', $s) : null,
            ]);

        return Inertia::render('Surat/SuratKeputusan/Index', [
            'sks' => $sks,
        ]);
    }

    public function create()
    {
        $jenisSurat = JenisSurat::whereIn('kode', ['SK-YYS', 'SK-PT'])->where('is_active', true)->get()
            ->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama, 'kode' => $j->kode]);

        return Inertia::render('Surat/SuratKeputusan/Create', [
            'jenisSurat' => $jenisSurat,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_sk'               => 'required|in:yayasan,pt',
            'tentang'                => 'required|string',
            'isi_sk'                 => 'required|string',
            'tanggal_ditetapkan'     => 'required|date',
            'penandatangan_nama'     => 'required|string|max:255',
            'penandatangan_jabatan'  => 'required|string|max:255',
        ]);

        $kodeJenis   = $validated['jenis_sk'] === 'yayasan' ? 'SK-YYS' : 'SK-PT';
        $jenisSurat  = JenisSurat::where('kode', $kodeJenis)->firstOrFail();
        $tanggalSurat = Carbon::parse($validated['tanggal_ditetapkan']);
        $nomorSurat  = $this->nomorSuratService->generateNomorSurat($jenisSurat->id, null, $tanggalSurat);

        $sk = SuratKeluar::create([
            'jenis_surat_id'       => $jenisSurat->id,
            'nomor_surat'          => $nomorSurat,
            'perihal'              => $validated['tentang'],
            'isi_surat'            => $validated['isi_sk'],
            'tanggal_surat'        => $tanggalSurat,
            'tujuan'               => 'Umum',
            'penandatangan_nama'   => $validated['penandatangan_nama'],
            'penandatangan_jabatan'=> $validated['penandatangan_jabatan'],
            'status'               => 'published',
            'created_by'           => auth()->id(),
            'approved_by'          => auth()->id(),
            'approved_at'          => now(),
        ]);

        $viewPath = $validated['jenis_sk'] === 'yayasan'
            ? 'manajemen-surat::pdf.sk_yayasan'
            : 'manajemen-surat::pdf.sk_pt';

        $pdf      = Pdf::loadView($viewPath, ['sk' => $sk])->setPaper('A4', 'portrait');
        $fileName = 'SK_' . Str::slug($sk->nomor_surat) . '_' . time() . '.pdf';
        $filePath = 'surat_keputusan/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());
        $sk->update(['file_path' => $filePath]);

        return redirect()->route('surat-keputusan.index')
            ->with('success', 'Surat Keputusan berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'jenis_sk'               => 'required|in:yayasan,pt',
            'tentang'                => 'required|string|max:255',
            'isi_sk'                 => 'required|string',
            'tanggal_ditetapkan'     => 'required|date',
            'penandatangan_nama'     => 'required|string|max:100',
            'penandatangan_jabatan'  => 'required|string|max:100',
        ]);

        $kodeJenis  = $validated['jenis_sk'] === 'yayasan' ? 'SK-YYS' : 'SK-PT';
        $jenisSurat = JenisSurat::where('kode', $kodeJenis)->firstOrFail();

        $sk = new SuratKeluar([
            'nomor_surat'          => 'XXX/' . $kodeJenis . '/MM/YYYY',
            'perihal'              => $validated['tentang'],
            'isi_surat'            => $validated['isi_sk'],
            'tanggal_surat'        => Carbon::parse($validated['tanggal_ditetapkan']),
            'penandatangan_nama'   => $validated['penandatangan_nama'],
            'penandatangan_jabatan'=> $validated['penandatangan_jabatan'],
        ]);

        $sk->setRelation('jenisSurat', $jenisSurat);

        $viewPath = $validated['jenis_sk'] === 'yayasan'
            ? 'manajemen-surat::pdf.sk_yayasan'
            : 'manajemen-surat::pdf.sk_pt';

        return Pdf::loadView($viewPath, ['sk' => $sk])->setPaper('A4', 'portrait')->stream('preview.pdf');
    }

    public function download(SuratKeluar $surat_keputusan)
    {
        if (!$surat_keputusan->file_path || !Storage::disk('public')->exists($surat_keputusan->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($surat_keputusan->file_path);
    }

    public function destroy(SuratKeluar $surat_keputusan)
    {
        if ($surat_keputusan->file_path && Storage::disk('public')->exists($surat_keputusan->file_path)) {
            Storage::disk('public')->delete($surat_keputusan->file_path);
        }
        $surat_keputusan->delete();

        return redirect()->route('surat-keputusan.index')
            ->with('success', 'Surat Keputusan berhasil dihapus.');
    }
}
