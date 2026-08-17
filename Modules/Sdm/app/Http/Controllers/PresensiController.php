<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Presensi;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['pegawai', 'approvedBy'])->latest('tanggal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $presensis = $query->paginate(20)->withQueryString();
        $pegawais  = Pegawai::where('is_aktif', true)->orderBy('nama')->get();

        $stats = [
            'total_hari_ini' => Presensi::whereDate('tanggal', today())->count(),
            'hadir_hari_ini' => Presensi::whereDate('tanggal', today())->where('status', 'hadir')->count(),
            'izin_hari_ini'  => Presensi::whereDate('tanggal', today())->whereIn('status', ['izin', 'sakit'])->count(),
            'alpa_hari_ini'  => Presensi::whereDate('tanggal', today())->where('status', 'alpa')->count(),
        ];

        return Inertia::render('Sdm/Presensi/Index', [
            'presensis' => $presensis,
            'pegawais'  => $pegawais,
            'stats'     => $stats,
            'filters'   => [
                'tanggal'    => $request->tanggal ?? '',
                'pegawai_id' => $request->pegawai_id ?? '',
                'status'     => $request->status ?? '',
            ],
        ]);
    }

    public function create()
    {
        $pegawais = Pegawai::where('is_aktif', true)->orderBy('nama')->get();
        return Inertia::render('Sdm/Presensi/Create', [
            'pegawais' => $pegawais,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal'    => 'required|date',
            'jam_masuk'  => 'nullable|string',
            'jam_keluar' => 'nullable|string',
            'status'     => 'required|in:hadir,izin,sakit,alpa,cuti,dinas_luar',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        Presensi::create($request->all());

        return redirect('/sdm/presensi')
            ->with('success', 'Data presensi berhasil ditambahkan.');
    }

    public function destroy(Presensi $presensi)
    {
        $presensi->delete();
        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $pegawais = Pegawai::where('is_aktif', true)->with('user')->orderBy('nama')->get();

        $rekapData = [];
        foreach ($pegawais as $pegawai) {
            $presensiPegawai = Presensi::where('pegawai_id', $pegawai->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $rekapData[] = [
                'pegawai'    => $pegawai,
                'hadir'      => $presensiPegawai->where('status', 'hadir')->count(),
                'izin'       => $presensiPegawai->where('status', 'izin')->count(),
                'sakit'      => $presensiPegawai->where('status', 'sakit')->count(),
                'alpa'       => $presensiPegawai->where('status', 'alpa')->count(),
                'cuti'       => $presensiPegawai->where('status', 'cuti')->count(),
                'dinas_luar' => $presensiPegawai->where('status', 'dinas_luar')->count(),
                'total'      => $presensiPegawai->count(),
            ];
        }

        return Inertia::render('Sdm/Presensi/Rekap', [
            'rekapData' => $rekapData,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
        ]);
    }
}
