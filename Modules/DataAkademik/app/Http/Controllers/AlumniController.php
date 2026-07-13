<?php

namespace Modules\DataAkademik\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Mahasiswa;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil mahasiswa yang sudah lulus
        $query = Mahasiswa::lulus()->with(['prodi']);

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

        $alumnis = $query->paginate(20)->withQueryString();

        // ─── Statistik Alumni ─────────────────────────────────────────────
        $stats = [
            'total'      => Mahasiswa::lulus()->count(),
            'avg_ipk'    => Mahasiswa::lulus()->whereNotNull('ipk')->avg('ipk') ?? 0,
            'avg_studi'  => Mahasiswa::lulus()->whereNotNull('masa_studi_bulan')->avg('masa_studi_bulan') ?? 0,
            'tepat_waktu'=> Mahasiswa::lulus()->where('masa_studi_bulan', '<=', 48)->count(), // <= 4 tahun (contoh)
        ];

        // ─── Options untuk Filter ──────────────────────────────────
        $prodis = ProgramStudi::aktif()->orderBy('nama')->get();
        $angkatans = Mahasiswa::lulus()->whereNotNull('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        
        // Ambil tahun lulus dari tanggal_lulus
        $tahunLulus = Mahasiswa::lulus()->whereNotNull('tanggal_lulus')
                        ->selectRaw('EXTRACT(YEAR FROM tanggal_lulus) as thn')
                        ->distinct()
                        ->orderBy('thn', 'desc')
                        ->pluck('thn');

        return view('dataakademik::alumni.index', compact('alumnis', 'stats', 'prodis', 'angkatans', 'tahunLulus'));
    }
}
