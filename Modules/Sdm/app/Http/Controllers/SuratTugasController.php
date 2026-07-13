<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\SuratTugas;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        $suratTugases = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => SuratTugas::count(),
            'pending' => SuratTugas::where('status', 'pending')->count(),
            'approved' => SuratTugas::where('status', 'approved')->count(),
            'aktif' => SuratTugas::whereIn('status', ['approved'])
                ->where('tanggal_selesai', '>=', today())
                ->count(),
        ];

        return view('sdm::surat-tugas.index', compact('suratTugases', 'stats'));
    }

    public function create()
    {
        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        $nomorSurat = $this->generateNomorSurat();
        
        return view('sdm::surat-tugas.create', compact('pegawais', 'nomorSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_tugas,nomor_surat',
            'perihal' => 'required|string|max:255',
            'keperluan' => 'required|string|max:2000',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tempat_tujuan' => 'required|string|max:255',
            'jenis' => 'required|in:dinas_luar,perjalanan_dinas,tugas_khusus,pelatihan,seminar',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:2000',
            'file_surat' => 'nullable|file|mimes:pdf|max:5120',
            'pegawai_ids' => 'required|array|min:1',
            'pegawai_ids.*' => 'exists:pegawais,id',
            'peran' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['pegawai_ids', 'peran']);
            $data['created_by'] = auth()->id();

            // Upload file if exists
            if ($request->hasFile('file_surat')) {
                $data['file_surat'] = $request->file('file_surat')
                    ->store('sdm/surat-tugas', 'public');
            }

            $suratTugas = SuratTugas::create($data);

            // Attach pegawai dengan peran
            $pegawaiData = [];
            foreach ($request->pegawai_ids as $index => $pegawaiId) {
                $pegawaiData[$pegawaiId] = [
                    'peran' => $request->peran[$index] ?? 'anggota',
                ];
            }
            $suratTugas->pegawais()->attach($pegawaiData);

            DB::commit();

            return redirect()->route('surat-tugas.index')
                ->with('success', 'Surat tugas berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat surat tugas: ' . $e->getMessage());
        }
    }

    public function show(SuratTugas $suratTugas)
    {
        $suratTugas->load(['pegawais', 'createdBy', 'approvedBy']);
        return view('sdm::surat-tugas.show', compact('suratTugas'));
    }

    public function edit(SuratTugas $suratTugas)
    {
        if (in_array($suratTugas->status, ['approved', 'selesai'])) {
            return back()->with('error', 'Surat tugas yang sudah disetujui tidak dapat diedit.');
        }

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::surat-tugas.edit', compact('suratTugas', 'pegawais'));
    }

    public function update(Request $request, SuratTugas $suratTugas)
    {
        if (in_array($suratTugas->status, ['approved', 'selesai'])) {
            return back()->with('error', 'Surat tugas yang sudah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'perihal' => 'required|string|max:255',
            'keperluan' => 'required|string|max:2000',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tempat_tujuan' => 'required|string|max:255',
            'anggaran' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:2000',
            'file_surat' => 'nullable|file|mimes:pdf|max:5120',
            'pegawai_ids' => 'required|array|min:1',
            'pegawai_ids.*' => 'exists:pegawais,id',
            'peran' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['pegawai_ids', 'peran']);

            // Upload new file if exists
            if ($request->hasFile('file_surat')) {
                if ($suratTugas->file_surat) {
                    Storage::disk('public')->delete($suratTugas->file_surat);
                }
                $data['file_surat'] = $request->file('file_surat')
                    ->store('sdm/surat-tugas', 'public');
            }

            $suratTugas->update($data);

            // Sync pegawai dengan peran
            $pegawaiData = [];
            foreach ($request->pegawai_ids as $index => $pegawaiId) {
                $pegawaiData[$pegawaiId] = [
                    'peran' => $request->peran[$index] ?? 'anggota',
                ];
            }
            $suratTugas->pegawais()->sync($pegawaiData);

            DB::commit();

            return redirect()->route('surat-tugas.index')
                ->with('success', 'Surat tugas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui surat tugas: ' . $e->getMessage());
        }
    }

    public function destroy(SuratTugas $suratTugas)
    {
        if ($suratTugas->file_surat) {
            Storage::disk('public')->delete($suratTugas->file_surat);
        }
        
        $suratTugas->delete();
        return back()->with('success', 'Surat tugas berhasil dihapus.');
    }

    public function approve(SuratTugas $suratTugas)
    {
        $suratTugas->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Surat tugas berhasil disetujui.');
    }

    public function reject(SuratTugas $suratTugas)
    {
        $suratTugas->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Surat tugas berhasil ditolak.');
    }

    public function complete(SuratTugas $suratTugas)
    {
        $suratTugas->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Surat tugas ditandai selesai.');
    }

    private function generateNomorSurat(): string
    {
        $tahun = now()->year;
        $bulan = now()->format('m');
        
        $lastNumber = SuratTugas::whereYear('created_at', $tahun)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;

        return sprintf('%03d/ST-SDM/%s/%d', $lastNumber, $bulan, $tahun);
    }
}
