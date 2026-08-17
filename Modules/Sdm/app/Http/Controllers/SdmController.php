<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\{Presensi, Cuti, Lembur, PenilaianKinerja, SuratTugas, Pegawai};
use Illuminate\Http\Request;
use Inertia\Inertia;

class SdmController extends Controller
{
    public function index()
    {
        $stats = [
            // Presensi hari ini
            'presensi_hari_ini' => Presensi::whereDate('tanggal', today())->count(),
            'hadir_hari_ini'    => Presensi::whereDate('tanggal', today())->where('status', 'hadir')->count(),
            
            // Cuti
            'cuti_pending'      => Cuti::where('status', 'pending')->count(),
            'cuti_aktif'        => Cuti::where('status', 'approved')
                ->where('tanggal_mulai', '<=', today())
                ->where('tanggal_selesai', '>=', today())
                ->count(),
            
            // Lembur
            'lembur_pending'    => Lembur::where('status', 'pending')->count(),
            'lembur_bulan_ini'  => Lembur::where('status', 'approved')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->sum('jumlah_jam') ?: 0,
            
            // Penilaian
            'penilaian_tahun_ini' => PenilaianKinerja::where('tahun', now()->year)->count(),
            'avg_nilai_tahun_ini' => round((float) PenilaianKinerja::where('tahun', now()->year)->avg('nilai_total'), 1),
            
            // Surat Tugas
            'surat_tugas_pending' => SuratTugas::where('status', 'pending')->count(),
            'surat_tugas_aktif'   => SuratTugas::whereIn('status', ['approved'])
                ->where('tanggal_selesai', '>=', today())
                ->count(),
            
            // Pegawai
            'total_pegawai' => Pegawai::where('is_aktif', true)->count(),
            'dosen'         => Pegawai::where('is_aktif', true)->where('jenis_pegawai', 'Dosen')->count(),
            'tendik'        => Pegawai::where('is_aktif', true)->where('jenis_pegawai', 'Tenaga Kependidikan')->count(),
        ];

        // Recent activities
        $recentCutis      = Cuti::with('pegawai')->latest()->limit(5)->get();
        $recentLemburs    = Lembur::with('pegawai')->latest('tanggal')->limit(5)->get();
        $recentSuratTugas = SuratTugas::with('pegawais')->latest()->limit(5)->get();

        // Chart data: Presensi per hari (7 hari terakhir)
        $presensiChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $presensiChart[] = [
                'tanggal' => $date->format('d M'),
                'hadir'   => Presensi::whereDate('tanggal', $date)->where('status', 'hadir')->count(),
                'izin'    => Presensi::whereDate('tanggal', $date)->whereIn('status', ['izin', 'sakit'])->count(),
                'alpa'    => Presensi::whereDate('tanggal', $date)->where('status', 'alpa')->count(),
            ];
        }

        return Inertia::render('Sdm/Dashboard/Index', [
            'stats'            => $stats,
            'recentCutis'      => $recentCutis,
            'recentLemburs'    => $recentLemburs,
            'recentSuratTugas' => $recentSuratTugas,
            'presensiChart'    => $presensiChart,
        ]);
    }
}
