<?php

namespace Modules\DataAkademik\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Mahasiswa;
use Modules\DataMaster\Models\ProgramStudi;
use Modules\DataMaster\Models\Periode;
use App\Imports\MahasiswaImport;
use App\Exports\TemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['prodi', 'periode'])
                    ->orderBy('angkatan', 'desc')
                    ->orderBy('nim', 'asc');

        // ─── Filter ────────────────────────────────────────────────
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'ilike', "%{$q}%")
                   ->orWhere('nim', 'ilike', "%{$q}%");
            });
        }
        if ($request->filled('prodi')) {
            $query->where('prodi_id', $request->prodi);
        }
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $mahasiswas = $query->paginate(20)->withQueryString();

        // ─── Statistik ─────────────────────────────────────────────
        $stats = [
            'total'              => Mahasiswa::count(),
            'aktif'              => Mahasiswa::aktif()->count(),
            'lulus'              => Mahasiswa::lulus()->count(),
            'mengundurkan_diri'  => Mahasiswa::where('status', 'mengundurkan_diri')->count(),
            'do'                 => Mahasiswa::where('status', 'DO')->count(),
            'avg_ipk'            => Mahasiswa::whereNotNull('ipk')->avg('ipk') ?? 0,
            'avg_studi'          => Mahasiswa::whereNotNull('masa_studi_bulan')->avg('masa_studi_bulan') ?? 0,
        ];

        // ─── Options untuk Filter ──────────────────────────────────
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get();
        $angkatans = Mahasiswa::whereNotNull('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        $statusOptions = Mahasiswa::statusOptions();

        return view('dataakademik::mahasiswa.index', compact('mahasiswas', 'stats', 'prodis', 'angkatans', 'statusOptions'));
    }

    public function create()
    {
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $statusOptions = Mahasiswa::statusOptions();
        $jalurOptions = Mahasiswa::JALUR_MASUK;
        
        return view('dataakademik::mahasiswa.create', compact('prodis', 'periodes', 'statusOptions', 'jalurOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'               => 'required|string|max:30|unique:mahasiswas',
            'nama'              => 'required|string|max:255',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'no_hp'             => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'prodi_id'          => 'nullable|exists:program_studis,id',
            'periode_id'        => 'nullable|exists:periodes,id',
            'angkatan'          => 'nullable|integer|min:2000|max:2100',
            'semester_berjalan' => 'nullable|integer|min:1|max:14',
            'jalur_masuk'       => 'nullable|string|max:50',
            'ipk'               => 'nullable|numeric|min:0|max:4',
            'status'            => 'required|string|in:' . implode(',', array_keys(Mahasiswa::statusOptions())),
            'tanggal_masuk'     => 'nullable|date',
            'tanggal_lulus'     => 'nullable|date|after_or_equal:tanggal_masuk',
            'masa_studi_bulan'  => 'nullable|integer|min:0',
            'keterangan'        => 'nullable|string',
        ]);

        // Hitung masa studi jika kosong tapi tgl masuk & lulus ada
        if (empty($validated['masa_studi_bulan']) && !empty($validated['tanggal_masuk']) && !empty($validated['tanggal_lulus'])) {
            $validated['masa_studi_bulan'] = Mahasiswa::hitungMasaStudi($validated['tanggal_masuk'], $validated['tanggal_lulus']);
        }
        
        // Hitung semester berjalan jika kosong tapi angkatan ada
        if (empty($validated['semester_berjalan']) && !empty($validated['angkatan'])) {
            $validated['semester_berjalan'] = Mahasiswa::hitungSemester($validated['angkatan']);
        }

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('dataakademik::mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $statusOptions = Mahasiswa::statusOptions();
        $jalurOptions = Mahasiswa::JALUR_MASUK;
        
        return view('dataakademik::mahasiswa.edit', compact('mahasiswa', 'prodis', 'periodes', 'statusOptions', 'jalurOptions'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim'               => 'required|string|max:30|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'              => 'required|string|max:255',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'no_hp'             => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'prodi_id'          => 'nullable|exists:program_studis,id',
            'periode_id'        => 'nullable|exists:periodes,id',
            'angkatan'          => 'nullable|integer|min:2000|max:2100',
            'semester_berjalan' => 'nullable|integer|min:1|max:14',
            'jalur_masuk'       => 'nullable|string|max:50',
            'ipk'               => 'nullable|numeric|min:0|max:4',
            'status'            => 'required|string|in:' . implode(',', array_keys(Mahasiswa::statusOptions())),
            'tanggal_masuk'     => 'nullable|date',
            'tanggal_lulus'     => 'nullable|date|after_or_equal:tanggal_masuk',
            'masa_studi_bulan'  => 'nullable|integer|min:0',
            'keterangan'        => 'nullable|string',
        ]);

        // Hitung masa studi jika kosong tapi tgl masuk & lulus ada
        if (empty($validated['masa_studi_bulan']) && !empty($validated['tanggal_masuk']) && !empty($validated['tanggal_lulus'])) {
            $validated['masa_studi_bulan'] = Mahasiswa::hitungMasaStudi($validated['tanggal_masuk'], $validated['tanggal_lulus']);
        }

        // Hitung semester berjalan jika kosong tapi angkatan ada
        if (empty($validated['semester_berjalan']) && !empty($validated['angkatan'])) {
            $validated['semester_berjalan'] = Mahasiswa::hitungSemester($validated['angkatan']);
        }

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));
            return back()->with('success', 'Data mahasiswa berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = [
            'nim', 'nama', 'jenis_kelamin', 'no_hp', 'email',
            'prodi', 'angkatan', 'semester_berjalan', 'jalur_masuk', 'ipk',
            'status', 'tanggal_masuk', 'tanggal_lulus', 'masa_studi_bulan', 'keterangan'
        ];
        return Excel::download(new TemplateExport($headings, 'Template Mahasiswa'), 'template-mahasiswa.xlsx');
    }

    /**
     * Endpoint untuk mengambil statistik IKU Mahasiswa via AJAX (misal untuk dashboard)
     */
    public function statistikIku()
    {
        // Contoh perhitungan sederhana IKU terkait Mahasiswa
        
        $totalLulusan = Mahasiswa::lulus()->count();
        $lulusTepatWaktu = Mahasiswa::lulus()->where('masa_studi_bulan', '<=', 48)->count(); // Contoh <= 4 tahun (48 bln) utk S1
        $persentaseTepatWaktu = $totalLulusan > 0 ? round(($lulusTepatWaktu / $totalLulusan) * 100, 2) : 0;

        $mhsPrestasi = 0; // Butuh data tambahan/tabel terpisah

        return response()->json([
            'lulus_tepat_waktu' => [
                'count' => $lulusTepatWaktu,
                'total' => $totalLulusan,
                'percentage' => $persentaseTepatWaktu,
                'label' => 'Lulusan Tepat Waktu (<= 4 Tahun)'
            ],
            // Tambahkan metrik lain jika diperlukan
        ]);
    }
}

