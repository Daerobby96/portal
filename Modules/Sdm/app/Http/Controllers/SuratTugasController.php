<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\SuratTugas;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SuratTugasController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratTugas::with(['pegawais', 'createdBy', 'approvedBy'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $suratTugases = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => SuratTugas::count(),
            'pending'  => SuratTugas::where('status', 'pending')->count(),
            'approved' => SuratTugas::where('status', 'approved')->count(),
            'aktif'    => SuratTugas::whereIn('status', ['approved'])
                ->where('tanggal_selesai', '>=', today())
                ->count(),
        ];

        return Inertia::render('Sdm/SuratTugas/Index', [
            'suratTugases' => $suratTugases,
            'stats'        => $stats,
            'filters'      => [
                'status' => $request->status ?? '',
                'jenis'  => $request->jenis ?? '',
            ],
        ]);
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        $nomorSurat = $this->generateNomorSurat();
        
        return Inertia::render('Sdm/SuratTugas/Create', [
            'pegawais'   => $pegawais,
            'nomorSurat' => $nomorSurat,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'     => 'required|string|max:255|unique:surat_tugas,nomor_surat',
            'perihal'         => 'required|string|max:255',
            'keperluan'       => 'required|string|max:2000',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tempat_tujuan'   => 'required|string|max:255',
            'jenis'           => 'required|in:dinas_luar,perjalanan_dinas,tugas_khusus,pelatihan,seminar',
            'anggaran'        => 'nullable|numeric|min:0',
            'sumber_dana'     => 'nullable|string|max:255',
            'catatan'         => 'nullable|string|max:2000',
            'file_surat'      => 'nullable|file|mimes:pdf|max:5120',
            'pegawai_ids'     => 'required|array|min:1',
            'pegawai_ids.*'   => 'exists:pegawais,id',
            'peran'           => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['pegawai_ids', 'peran']);
            $data['created_by'] = auth()->id();
            $data['status'] = 'pending';

            if ($request->hasFile('file_surat')) {
                $data['file_surat'] = $request->file('file_surat')->store('sdm/surat-tugas', 'public');
            }

            $suratTugas = SuratTugas::create($data);

            $pegawaiData = [];
            foreach ($request->pegawai_ids as $index => $pegawaiId) {
                $pegawaiData[$pegawaiId] = [
                    'peran' => $request->peran[$index] ?? 'anggota',
                ];
            }
            $suratTugas->pegawais()->attach($pegawaiData);

            DB::commit();

            return redirect('/sdm/surat-tugas')->with('success', 'Surat tugas berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat surat tugas: ' . $e->getMessage());
        }
    }

    public function show(SuratTugas $suratTugas)
    {
        $suratTugas->load(['pegawais', 'createdBy', 'approvedBy']);
        return Inertia::render('Sdm/SuratTugas/Show', [
            'suratTugas' => $suratTugas,
        ]);
    }

    public function destroy(SuratTugas $suratTugas)
    {
        if ($suratTugas->file_surat) {
            Storage::disk('public')->delete($suratTugas->file_surat);
        }
        $suratTugas->delete();
        return redirect('/sdm/surat-tugas')->with('success', 'Surat tugas berhasil dihapus.');
    }

    public function approve(Request $request, SuratTugas $suratTugas)
    {
        $suratTugas->update([
            'status'           => 'approved',
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan_approval,
        ]);

        return back()->with('success', 'Surat tugas berhasil disetujui (Approved).');
    }

    public function reject(Request $request, SuratTugas $suratTugas)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $suratTugas->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Surat tugas telah ditolak.');
    }

    public function complete(Request $request, SuratTugas $suratTugas)
    {
        $request->validate([
            'laporan_hasil' => 'required|string|max:5000',
        ]);

        $suratTugas->update([
            'status'        => 'completed',
            'laporan_hasil' => $request->laporan_hasil,
        ]);

        return back()->with('success', 'Laporan pelaksanaan tugas kedinasan berhasil disimpan.');
    }

    private function generateNomorSurat()
    {
        $count = SuratTugas::whereYear('created_at', now()->year)->count() + 1;
        $nomor = str_pad($count, 3, '0', STR_PAD_LEFT);
        $bulan = now()->format('m');
        $tahun = now()->format('Y');

        return "ST/{$nomor}/POLKA-SDM/{$bulan}/{$tahun}";
    }
}
