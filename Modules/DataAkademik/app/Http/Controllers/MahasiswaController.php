<?php

namespace Modules\DataAkademik\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Modules\DataMaster\Models\ProgramStudi;
use Modules\DataMaster\Models\Periode;
use App\Imports\MahasiswaImport;
use App\Exports\TemplateExport;
use Illuminate\Http\Request;
use Inertia\Inertia;
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

        $mahasiswas = $query->paginate(15)->through(fn($m) => [
            'id'                => $m->id,
            'nim'               => $m->nim,
            'nama'              => $m->nama,
            'jenis_kelamin'     => $m->jenis_kelamin,
            'no_hp'             => $m->no_hp,
            'email'             => $m->email,
            'angkatan'          => $m->angkatan,
            'semester_berjalan' => $m->semester_berjalan,
            'jalur_masuk'       => $m->jalur_masuk,
            'ipk'               => $m->ipk,
            'status'            => $m->status,
            'prodi_id'          => $m->prodi_id,
            'prodi_nama'        => $m->prodi?->nama,
            'periode_nama'      => $m->periode?->nama,
            'tanggal_masuk'     => $m->tanggal_masuk?->format('Y-m-d'),
            'tanggal_lulus'     => $m->tanggal_lulus?->format('Y-m-d'),
            'masa_studi_bulan'  => $m->masa_studi_bulan,
        ])->withQueryString();

        // ─── Statistik ─────────────────────────────────────────────
        $stats = [
            'total'              => Mahasiswa::count(),
            'aktif'              => Mahasiswa::aktif()->count(),
            'lulus'              => Mahasiswa::lulus()->count(),
            'cuti'               => Mahasiswa::where('status', Mahasiswa::STATUS_CUTI)->count(),
            'do'                 => Mahasiswa::where('status', Mahasiswa::STATUS_DO)->count(),
            'mengundurkan_diri'  => Mahasiswa::where('status', Mahasiswa::STATUS_MENGUNDURKAN_DIRI)->count(),
            'avg_ipk'            => round(Mahasiswa::whereNotNull('ipk')->avg('ipk') ?? 0, 2),
        ];

        // ─── Options untuk Filter ──────────────────────────────────
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $angkatans = Mahasiswa::whereNotNull('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        $statusOptions = Mahasiswa::statusOptions();

        return Inertia::render('DataAkademik/Mahasiswa/Index', [
            'mahasiswas'    => $mahasiswas,
            'stats'         => $stats,
            'prodis'        => $prodis,
            'angkatans'     => $angkatans,
            'statusOptions' => $statusOptions,
            'filters'       => $request->only(['search', 'prodi', 'angkatan', 'status', 'jenis_kelamin']),
        ]);
    }

    public function create()
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $periodes = Periode::orderBy('tahun', 'desc')->get(['id', 'nama', 'semester', 'tahun']);
        $statusOptions = Mahasiswa::statusOptions();
        $jalurOptions = Mahasiswa::JALUR_MASUK;
        
        return Inertia::render('DataAkademik/Mahasiswa/Create', [
            'prodis'        => $prodis,
            'periodes'      => $periodes,
            'statusOptions' => $statusOptions,
            'jalurOptions'  => $jalurOptions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'               => 'required|string|max:30|unique:mahasiswas,nim',
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
            'tempat_lahir'      => 'nullable|string|max:100',
            'tanggal_lahir'     => 'nullable|date',
            'nik'               => 'nullable|string|max:30',
            'alamat'            => 'nullable|string',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['prodi', 'periode']);
        
        return Inertia::render('DataAkademik/Mahasiswa/Show', [
            'mahasiswa' => [
                'id'                => $mahasiswa->id,
                'nim'               => $mahasiswa->nim,
                'nama'              => $mahasiswa->nama,
                'jenis_kelamin'     => $mahasiswa->jenis_kelamin,
                'no_hp'             => $mahasiswa->no_hp,
                'email'             => $mahasiswa->email,
                'angkatan'          => $mahasiswa->angkatan,
                'semester_berjalan' => $mahasiswa->semester_berjalan,
                'jalur_masuk'       => $mahasiswa->jalur_masuk,
                'ipk'               => $mahasiswa->ipk,
                'status'            => $mahasiswa->status,
                'prodi_id'          => $mahasiswa->prodi_id,
                'prodi_nama'        => $mahasiswa->prodi?->nama,
                'periode_nama'      => $mahasiswa->periode?->nama,
                'tanggal_masuk'     => $mahasiswa->tanggal_masuk?->format('Y-m-d'),
                'tanggal_lulus'     => $mahasiswa->tanggal_lulus?->format('Y-m-d'),
                'masa_studi_bulan'  => $mahasiswa->masa_studi_bulan,
                'keterangan'        => $mahasiswa->keterangan,
                'tempat_lahir'      => $mahasiswa->tempat_lahir,
                'tanggal_lahir'     => $mahasiswa->tanggal_lahir?->format('Y-m-d'),
                'nik'               => $mahasiswa->nik,
                'alamat'            => $mahasiswa->alamat,
                'nama_ayah'         => $mahasiswa->nama_ayah,
                'nama_ibu'          => $mahasiswa->nama_ibu,
                'pekerjaan_ayah'    => $mahasiswa->pekerjaan_ayah,
                'pekerjaan_ibu'     => $mahasiswa->pekerjaan_ibu,
            ]
        ]);
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $periodes = Periode::orderBy('tahun', 'desc')->get(['id', 'nama', 'semester', 'tahun']);
        $statusOptions = Mahasiswa::statusOptions();
        $jalurOptions = Mahasiswa::JALUR_MASUK;
        
        return Inertia::render('DataAkademik/Mahasiswa/Edit', [
            'mahasiswa'     => $mahasiswa,
            'prodis'        => $prodis,
            'periodes'      => $periodes,
            'statusOptions' => $statusOptions,
            'jalurOptions'  => $jalurOptions,
        ]);
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
            'tempat_lahir'      => 'nullable|string|max:100',
            'tanggal_lahir'     => 'nullable|date',
            'nik'               => 'nullable|string|max:30',
            'alamat'            => 'nullable|string',
        ]);

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
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
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

    public function statistikIku()
    {
        $totalLulusan = Mahasiswa::lulus()->count();
        $lulusTepatWaktu = Mahasiswa::lulus()->where('masa_studi_bulan', '<=', 48)->count();
        $persentaseTepatWaktu = $totalLulusan > 0 ? round(($lulusTepatWaktu / $totalLulusan) * 100, 2) : 0;

        return response()->json([
            'lulus_tepat_waktu' => [
                'count' => $lulusTepatWaktu,
                'total' => $totalLulusan,
                'percentage' => $persentaseTepatWaktu,
                'label' => 'Lulusan Tepat Waktu (<= 4 Tahun)'
            ],
        ]);
    }
}


