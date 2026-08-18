<?php

namespace Modules\DataAkademik\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil mahasiswa yang sudah lulus
        $query = Mahasiswa::lulus()->with(['prodi'])->orderBy('tanggal_lulus', 'desc');

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
        if ($request->filled('tahun_lulus')) {
            $query->whereYear('tanggal_lulus', $request->tahun_lulus);
        }

        $alumnis = $query->paginate(15)->through(fn($a) => [
            'id'               => $a->id,
            'nim'              => $a->nim,
            'nama'             => $a->nama,
            'jenis_kelamin'    => $a->jenis_kelamin,
            'prodi_id'         => $a->prodi_id,
            'prodi_nama'       => $a->prodi?->nama,
            'angkatan'         => $a->angkatan,
            'ipk'              => $a->ipk,
            'tanggal_masuk'    => $a->tanggal_masuk?->format('Y-m-d'),
            'tanggal_lulus'    => $a->tanggal_lulus?->format('Y-m-d'),
            'masa_studi_bulan' => $a->masa_studi_bulan,
            'no_hp'            => $a->no_hp,
            'email'            => $a->email,
        ])->withQueryString();

        $totalLulus = Mahasiswa::lulus()->count();
        $tepatWaktu = Mahasiswa::lulus()->where('masa_studi_bulan', '<=', 48)->count();

        // ─── Statistik Alumni ─────────────────────────────────────────────
        $stats = [
            'total'               => $totalLulus,
            'avg_ipk'             => round(Mahasiswa::lulus()->whereNotNull('ipk')->avg('ipk') ?? 0, 2),
            'avg_studi'           => round(Mahasiswa::lulus()->whereNotNull('masa_studi_bulan')->avg('masa_studi_bulan') ?? 0, 1),
            'tepat_waktu'         => $tepatWaktu,
            'tepat_waktu_persen'  => $totalLulus > 0 ? round(($tepatWaktu / $totalLulus) * 100) : 0,
        ];

        // ─── Options untuk Filter ──────────────────────────────────
        $prodis = ProgramStudi::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama']);
        $angkatans = Mahasiswa::lulus()->whereNotNull('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        
        $tahunLulus = Mahasiswa::lulus()->whereNotNull('tanggal_lulus')
                        ->selectRaw('EXTRACT(YEAR FROM tanggal_lulus) as thn')
                        ->distinct()
                        ->orderBy('thn', 'desc')
                        ->pluck('thn');

        return Inertia::render('DataAkademik/Alumni/Index', [
            'alumnis'    => $alumnis,
            'stats'      => $stats,
            'prodis'     => $prodis,
            'angkatans'  => $angkatans,
            'tahunLulus' => $tahunLulus,
            'filters'    => $request->only(['search', 'prodi', 'angkatan', 'tahun_lulus']),
        ]);
    }
}

