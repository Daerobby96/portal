<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ManajemenSurat\Models\SuratKeluar;
use Modules\ManajemenSurat\Models\SuratMasuk;
use Modules\ManajemenSurat\Models\Disposisi;
use Modules\ManajemenSurat\Models\JenisSurat;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_surat_keluar' => SuratKeluar::count(),
            'total_surat_keluar_bulan_ini' => SuratKeluar::whereMonth('tanggal_surat', Carbon::now()->month)
                ->whereYear('tanggal_surat', Carbon::now()->year)
                ->count(),
            
            'total_surat_masuk' => SuratMasuk::count(),
            'surat_masuk_baru' => SuratMasuk::status('baru')->count(),
            'surat_masuk_bulan_ini' => SuratMasuk::whereMonth('tanggal_terima', Carbon::now()->month)
                ->whereYear('tanggal_terima', Carbon::now()->year)
                ->count(),
            
            'pending_approval' => SuratKeluar::status('pending')->count(),
            
            'my_disposisi_pending' => Disposisi::untukUser(auth()->id())->pending()->count(),
            'my_disposisi_overdue' => Disposisi::untukUser(auth()->id())->overdue()->count(),
            'my_disposisi_total' => Disposisi::untukUser(auth()->id())->count(),
        ];

        // Recent Activities
        $recentSuratMasuk = SuratMasuk::with(['jenisSurat', 'creator'])
            ->latest('tanggal_terima')
            ->take(5)
            ->get();

        $recentSuratKeluar = SuratKeluar::with(['jenisSurat', 'creator'])
            ->latest('tanggal_surat')
            ->take(5)
            ->get();

        // My Disposisi
        $myDisposisi = Disposisi::with(['suratMasuk.jenisSurat', 'dari'])
            ->untukUser(auth()->id())
            ->whereIn('status', ['pending', 'dibaca', 'proses'])
            ->latest()
            ->take(5)
            ->get();

        // Pending Approvals (for admin/pimpinan)
        $pendingApprovals = [];
        if (auth()->user()->hasRole(['super_admin', 'pimpinan'])) {
            $pendingApprovals = SuratKeluar::with(['jenisSurat', 'creator'])
                ->status('pending')
                ->latest()
                ->take(5)
                ->get();
        }

        // Chart Data - Surat per bulan (6 bulan terakhir)
        $chartData = $this->getChartData();

        return view('manajemen-surat::dashboard.index', compact(
            'stats',
            'recentSuratMasuk',
            'recentSuratKeluar',
            'myDisposisi',
            'pendingApprovals',
            'chartData'
        ));
    }

    protected function getChartData()
    {
        $months = [];
        $suratMasukData = [];
        $suratKeluarData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $suratMasukData[] = SuratMasuk::whereYear('tanggal_terima', $date->year)
                ->whereMonth('tanggal_terima', $date->month)
                ->count();

            $suratKeluarData[] = SuratKeluar::whereYear('tanggal_surat', $date->year)
                ->whereMonth('tanggal_surat', $date->month)
                ->count();
        }

        return [
            'labels' => $months,
            'surat_masuk' => $suratMasukData,
            'surat_keluar' => $suratKeluarData,
        ];
    }
}
