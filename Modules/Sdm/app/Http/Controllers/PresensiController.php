<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Presensi;
use Modules\Sdm\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $pegawais = Pegawai::aktif()->orderBy('nama')->get();

        $stats = [
            'total_hari_ini' => Presensi::whereDate('tanggal', today())->count(),
            'hadir_hari_ini' => Presensi::whereDate('tanggal', today())->where('status', 'hadir')->count(),
            'izin_hari_ini' => Presensi::whereDate('tanggal', today())->whereIn('status', ['izin', 'sakit'])->count(),
            'alpa_hari_ini' => Presensi::whereDate('tanggal', today())->where('status', 'alpa')->count(),
        ];

        return view('sdm::presensi.index', compact('presensis', 'pegawais', 'stats'));
    }

    public function create()
    {
        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::presensi.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpa,cuti,dinas_luar',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        Presensi::create($request->all());

        return redirect()->route('presensi.index')
            ->with('success', 'Data presensi berhasil ditambahkan.');
    }

    public function edit(Presensi $presensi)
    {
        $pegawais = Pegawai::aktif()->orderBy('nama')->get();
        return view('sdm::presensi.edit', compact('presensi', 'pegawais'));
    }

    public function update(Request $request, Presensi $presensi)
    {
        $request->validate([
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpa,cuti,dinas_luar',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $presensi->update($request->all());

        return redirect()->route('presensi.index')
            ->with('success', 'Data presensi berhasil diperbarui.');
    }

    public function destroy(Presensi $presensi)
    {
        $presensi->delete();
        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $pegawais = Pegawai::aktif()->with(['user'])->get();

        $rekapData = [];
        foreach ($pegawais as $pegawai) {
            $presensis = Presensi::where('pegawai_id', $pegawai->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get();

            $rekapData[] = [
                'pegawai' => $pegawai,
                'hadir' => $presensis->where('status', 'hadir')->count(),
                'izin' => $presensis->where('status', 'izin')->count(),
                'sakit' => $presensis->where('status', 'sakit')->count(),
                'alpa' => $presensis->where('status', 'alpa')->count(),
                'cuti' => $presensis->where('status', 'cuti')->count(),
                'dinas_luar' => $presensis->where('status', 'dinas_luar')->count(),
                'total' => $presensis->count(),
            ];
        }

        return view('sdm::presensi.rekap', compact('rekapData', 'bulan', 'tahun'));
    }
}
