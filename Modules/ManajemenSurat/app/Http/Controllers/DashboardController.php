<?php

namespace Modules\ManajemenSurat\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Modules\ManajemenSurat\Models\SuratKeluar;
use Modules\ManajemenSurat\Models\SuratMasuk;
use Modules\ManajemenSurat\Models\Disposisi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_surat_keluar'       => SuratKeluar::count(),
            'total_surat_keluar_bulan' => SuratKeluar::whereMonth('tanggal_surat', Carbon::now()->month)
                ->whereYear('tanggal_surat', Carbon::now()->year)->count(),
            'total_surat_masuk'        => SuratMasuk::count(),
            'surat_masuk_baru'         => SuratMasuk::where('status', 'baru')->count(),
            'surat_masuk_bulan'        => SuratMasuk::whereMonth('tanggal_terima', Carbon::now()->month)
                ->whereYear('tanggal_terima', Carbon::now()->year)->count(),
            'pending_approval'         => SuratKeluar::where('status', 'pending')->count(),
            'my_disposisi_pending'     => Disposisi::where('kepada_user_id', auth()->id())
                ->whereIn('status', ['pending', 'dibaca'])->count(),
            'my_disposisi_overdue'     => Disposisi::where('kepada_user_id', auth()->id())
                ->where('batas_waktu', '<', now())
                ->whereNotIn('status', ['selesai'])->count(),
            'my_disposisi_total'       => Disposisi::where('kepada_user_id', auth()->id())->count(),
        ];

        $recentSuratMasuk = SuratMasuk::with(['jenisSurat', 'creator'])
            ->latest('tanggal_terima')->take(5)->get()
            ->map(fn ($s) => [
                'id'             => $s->id,
                'nomor_agenda'   => $s->nomor_agenda,
                'nomor_surat'    => $s->nomor_surat,
                'pengirim'       => $s->pengirim,
                'perihal'        => $s->perihal,
                'sifat'          => $s->sifat,
                'status'         => $s->status,
                'tanggal_terima' => $s->tanggal_terima?->format('d M Y'),
                'jenis_surat'    => $s->jenisSurat?->nama,
            ]);

        $recentSuratKeluar = SuratKeluar::with(['jenisSurat', 'creator'])
            ->latest()->take(5)->get()
            ->map(fn ($s) => [
                'id'           => $s->id,
                'nomor_surat'  => $s->nomor_surat,
                'perihal'      => $s->perihal,
                'tujuan'       => $s->tujuan,
                'status'       => $s->status,
                'tanggal_surat'=> $s->tanggal_surat?->format('d M Y'),
                'jenis_surat'  => $s->jenisSurat?->nama,
            ]);

        $myDisposisi = Disposisi::with(['suratMasuk', 'dari'])
            ->where('kepada_user_id', auth()->id())
            ->whereIn('status', ['pending', 'dibaca', 'proses'])
            ->latest()->take(5)->get()
            ->map(fn ($d) => [
                'id'            => $d->id,
                'perihal'       => $d->suratMasuk?->perihal,
                'dari_nama'     => $d->dari?->name,
                'isi_disposisi' => $d->isi_disposisi,
                'prioritas'     => $d->prioritas,
                'status'        => $d->status,
                'batas_waktu'   => $d->batas_waktu?->format('d M Y'),
                'surat_masuk_id'=> $d->surat_masuk_id,
            ]);

        $pendingApprovals = [];
        if (auth()->user()->hasRole(['super_admin', 'pimpinan'])) {
            $pendingApprovals = SuratKeluar::with(['jenisSurat', 'creator'])
                ->where('status', 'pending')->latest()->take(5)->get()
                ->map(fn ($s) => [
                    'id'           => $s->id,
                    'nomor_surat'  => $s->nomor_surat,
                    'perihal'      => $s->perihal,
                    'creator_name' => $s->creator?->name,
                    'tanggal_surat'=> $s->tanggal_surat?->format('d M Y'),
                ]);
        }

        $chartData = $this->getChartData();

        return Inertia::render('Surat/Dashboard/Index', [
            'stats'           => $stats,
            'recentSuratMasuk'=> $recentSuratMasuk,
            'recentSuratKeluar'=> $recentSuratKeluar,
            'myDisposisi'     => $myDisposisi,
            'pendingApprovals'=> $pendingApprovals,
            'chartData'       => $chartData,
        ]);
    }

    protected function getChartData()
    {
        $months = [];
        $suratMasukData = [];
        $suratKeluarData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $suratMasukData[] = SuratMasuk::whereYear('tanggal_terima', $date->year)
                ->whereMonth('tanggal_terima', $date->month)->count();

            $suratKeluarData[] = SuratKeluar::whereYear('tanggal_surat', $date->year)
                ->whereMonth('tanggal_surat', $date->month)->count();
        }

        return [
            'labels'       => $months,
            'surat_masuk'  => $suratMasukData,
            'surat_keluar' => $suratKeluarData,
        ];
    }
}
