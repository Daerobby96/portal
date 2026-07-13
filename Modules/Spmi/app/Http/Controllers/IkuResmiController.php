<?php

namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\IkuResmi;
use Modules\Spmi\Models\IkuDataInput;
use Modules\Spmi\Models\IkuHasil;
use Modules\DataMaster\Models\Periode;
use App\Services\IkuCalculationService;
use App\Services\IkuIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IkuResmiController extends Controller
{
    protected $calculationService;
    protected $integrationService;
    
    public function __construct(IkuCalculationService $calculationService, IkuIntegrationService $integrationService)
    {
        $this->calculationService = $calculationService;
        $this->integrationService = $integrationService;
    }
    
    /**
     * Dashboard IKU - Tampilan utama
     */
    public function index(Request $request)
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        
        $periodeId = $request->get('periode_id', $periodeAktif?->id);
        $triwulan = $request->get('triwulan', 'TAHUNAN'); // Default TAHUNAN
        
        $ikuList = IkuResmi::where('is_aktif', true)
            ->orderBy('nomor_iku')
            ->get()
            ->map(function ($iku) use ($periodeId, $triwulan) {
                $hasil = $iku->getHasilByPeriodeTriwulan($periodeId, $triwulan);
                $iku->nilai_hasil = $hasil->nilai_hasil ?? 0;
                $iku->status_capaian = $hasil->status_capaian ?? 'Belum Dihitung';
                return $iku;
            });
        
        // Summary
        $summary = [
            'total' => $ikuList->count(),
            'wajib' => $ikuList->whereIn('sifat', ['WAJIB', 'WAJIB PTN-BH'])->count(),
            'pilihan' => $ikuList->whereIn('sifat', ['PILIHAN', 'PILIHAN PTN'])->count(),
            'tercapai' => $ikuList->where('status_capaian', 'Tercapai')->count(),
            'progress' => $ikuList->where('status_capaian', 'Dalam Progress')->count(),
        ];
        
        $triwulanOptions = IkuHasil::triwulanOptions();
        
        return view('spmi::iku-resmi.index', compact('ikuList', 'periodes', 'periodeId', 'summary', 'triwulan', 'triwulanOptions'));
    }
    
    /**
     * Detail IKU - Tampilkan data input dan hasil
     */
    public function show($id, Request $request)
    {
        $iku = IkuResmi::findOrFail($id);
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        
        $periodeId = $request->get('periode_id', $periodeAktif?->id);
        $triwulan = $request->get('triwulan', 'TAHUNAN');
        
        $dataInputs = $iku->getDataInputsByPeriodeTriwulan($periodeId, $triwulan);
        $hasil = $iku->getHasilByPeriodeTriwulan($periodeId, $triwulan);
        
        $triwulanOptions = IkuHasil::triwulanOptions();
        
        return view('spmi::iku-resmi.show', compact('iku', 'dataInputs', 'hasil', 'periodes', 'periodeId', 'triwulan', 'triwulanOptions'));
    }
    
    /**
     * Form input data IKU
     */
    public function inputData($id, Request $request)
    {
        $iku = IkuResmi::findOrFail($id);
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        
        $periodeId = $request->get('periode_id', $periodeAktif?->id);
        $triwulan = $request->get('triwulan', 'TAHUNAN');
        
        // Load existing data
        $existingData = IkuDataInput::where('iku_resmi_id', $id)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('kategori');
        
        $ikuNumber = $iku->nomor_short;
        $viewName = "iku-resmi.input.iku{$ikuNumber}";
        
        // Fallback ke form generic jika view spesifik belum ada
        if (!view()->exists($viewName)) {
            $viewName = 'iku-resmi.input.generic';
        }
        
        $triwulanOptions = IkuHasil::triwulanOptions();
        
        return view($viewName, compact('iku', 'periodes', 'periodeId', 'existingData', 'triwulan', 'triwulanOptions'));
    }
    
    /**
     * Simpan data input IKU
     */
    public function storeData(Request $request, $id)
    {
        $iku = IkuResmi::findOrFail($id);
        
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'triwulan' => 'required|in:TW1,TW2,TW3,TW4,TAHUNAN',
            'data' => 'required|array',
        ]);
        
        DB::beginTransaction();
        try {
            // Hapus data lama untuk periode dan triwulan ini
            IkuDataInput::where('iku_resmi_id', $id)
                ->where('periode_id', $request->periode_id)
                ->where('triwulan', $request->triwulan)
                ->delete();
            
            // Simpan data baru
            foreach ($request->data as $kategori => $item) {
                IkuDataInput::create([
                    'iku_resmi_id' => $id,
                    'periode_id' => $request->periode_id,
                    'triwulan' => $request->triwulan,
                    'kategori' => $kategori,
                    'nilai_input' => $item['nilai'] ?? 0,
                    'bobot' => $item['bobot'] ?? null,
                    'metadata' => $item['metadata'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('iku-resmi.show', ['iku_resmi' => $id, 'periode_id' => $request->periode_id])
                ->with('success', 'Data IKU berhasil disimpan untuk ' . IkuHasil::triwulanOptions()[$request->triwulan] . '.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus data input IKU untuk periode dan triwulan tertentu
     */
    public function deleteData($id, Request $request)
    {
        $iku = IkuResmi::findOrFail($id);
        
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'triwulan' => 'nullable|in:TW1,TW2,TW3,TW4,TAHUNAN',
        ]);
        
        $periodeId = $request->periode_id;
        $triwulan = $request->triwulan ?? 'TAHUNAN';
        
        DB::beginTransaction();
        try {
            // Hapus data input
            $deletedInputs = IkuDataInput::where('iku_resmi_id', $id)
                ->where('periode_id', $periodeId)
                ->where('triwulan', $triwulan)
                ->delete();
            
            // Hapus hasil perhitungan
            $deletedHasil = IkuHasil::where('iku_resmi_id', $id)
                ->where('periode_id', $periodeId)
                ->where('triwulan', $triwulan)
                ->delete();
            
            DB::commit();
            
            $triwulanLabel = IkuHasil::triwulanOptions()[$triwulan] ?? $triwulan;
            
            return back()->with('success', 
                "Data IKU {$iku->nomor_iku} untuk {$triwulanLabel} berhasil dihapus. " .
                "({$deletedInputs} data input, {$deletedHasil} hasil)"
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    
    /**
     * Hitung IKU untuk periode tertentu
     */
    public function calculate($id, Request $request)
    {
        $iku = IkuResmi::findOrFail($id);
        $periodeId = $request->get('periode_id');
        $triwulan = $request->get('triwulan', 'TAHUNAN');
        
        if (!$periodeId) {
            return response()->json(['error' => 'Periode tidak ditemukan'], 400);
        }
        
        $ikuNumber = str_replace('IKU', '', $iku->nomor_iku);
        
        try {
            $hasil = $this->calculationService->recalculate($ikuNumber, $periodeId, $triwulan);
            
            return response()->json([
                'success' => true,
                'hasil' => $hasil,
                'message' => "IKU {$iku->nomor_iku} berhasil dihitung: {$hasil}%"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghitung IKU: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hitung semua IKU untuk periode tertentu
     */
    public function calculateAll(Request $request)
    {
        $periodeId = $request->get('periode_id');
        
        if (!$periodeId) {
            return back()->with('error', 'Periode tidak ditemukan');
        }
        
        try {
            $results = $this->calculationService->calculateAll($periodeId);
            
            return back()->with('success', 'Semua IKU berhasil dihitung untuk periode terpilih.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghitung IKU: ' . $e->getMessage());
        }
    }
    
    /**
     * Laporan IKU - Export ke Excel/PDF
     */
    public function report(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $periode = Periode::findOrFail($periodeId);
        
        $ikuList = IkuResmi::where('is_aktif', true)
            ->orderBy('id')
            ->get()
            ->map(function ($iku) use ($periodeId) {
                $hasil = $iku->getHasilByPeriode($periodeId);
                $iku->nilai_hasil = $hasil->nilai_hasil ?? 0;
                $iku->status_capaian = $hasil->status_capaian ?? 'Belum Dihitung';
                return $iku;
            });
        
        if ($request->get('format') === 'pdf') {
            // TODO: Implement PDF export
            return view('spmi::iku-resmi.report-pdf', compact('periode', 'ikuList'));
        }
        
        return view('spmi::iku-resmi.report', compact('periode', 'ikuList'));
    }
    
    /**
     * Form set target IKU
     */
    public function setTarget(Request $request)
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        
        $periodeId = $request->get('periode_id', $periodeAktif?->id);
        
        $ikuList = IkuResmi::where('is_aktif', true)
            ->orderBy('id')
            ->get()
            ->map(function ($iku) use ($periodeId) {
                $hasil = $iku->getHasilByPeriode($periodeId);
                $iku->target = $hasil->target ?? $iku->target_default ?? 0;
                $iku->nilai_hasil = $hasil->nilai_hasil ?? 0;
                $iku->persentase_capaian = $hasil->persentase_capaian ?? 0;
                return $iku;
            });
        
        return view('spmi::iku-resmi.set-target', compact('ikuList', 'periodes', 'periodeId'));
    }
    
    /**
     * Simpan target IKU
     */
    public function storeTarget(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'targets' => 'required|array',
            'targets.*' => 'nullable|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            foreach ($request->targets as $ikuId => $target) {
                if ($target !== null) {
                    IkuHasil::updateOrCreate(
                        [
                            'iku_resmi_id' => $ikuId,
                            'periode_id' => $request->periode_id,
                        ],
                        [
                            'target' => $target,
                        ]
                    );
                    
                    // Re-calculate status jika sudah ada nilai hasil
                    $hasil = IkuHasil::where('iku_resmi_id', $ikuId)
                        ->where('periode_id', $request->periode_id)
                        ->first();
                    
                    if ($hasil && $hasil->nilai_hasil > 0 && $target > 0) {
                        $persentaseCapaian = ($hasil->nilai_hasil / $target) * 100;
                        $gap = $hasil->nilai_hasil - $target;
                        
                        $status = $persentaseCapaian >= 100 ? 'Tercapai' : 
                                 ($persentaseCapaian >= 80 ? 'Dalam Progress' : 'Tidak Tercapai');
                        
                        $hasil->update([
                            'persentase_capaian' => round($persentaseCapaian, 2),
                            'gap' => round($gap, 2),
                            'status_capaian' => $status,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()
                ->route('iku-resmi.set-target', ['periode_id' => $request->periode_id])
                ->with('success', 'Target IKU berhasil disimpan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan target: ' . $e->getMessage());
        }
    }
    
    /**
     * Analisa kinerja IKU
     */
    public function analytics(Request $request)
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        
        $periodeId = $request->get('periode_id', $periodeAktif?->id);
        
        $ikuList = IkuResmi::where('is_aktif', true)
            ->orderBy('id')
            ->get()
            ->map(function ($iku) use ($periodeId) {
                $hasil = $iku->getHasilByPeriode($periodeId);
                $iku->target = $hasil->target ?? 0;
                $iku->nilai_hasil = $hasil->nilai_hasil ?? 0;
                $iku->persentase_capaian = $hasil->persentase_capaian ?? 0;
                $iku->gap = $hasil->gap ?? 0;
                $iku->status_capaian = $hasil->status_capaian ?? 'Belum Dihitung';
                return $iku;
            });
        
        // Summary statistics
        $summary = [
            'total' => $ikuList->count(),
            'tercapai' => $ikuList->where('status_capaian', 'Tercapai')->count(),
            'tidak_tercapai' => $ikuList->where('status_capaian', 'Tidak Tercapai')->count(),
            'progress' => $ikuList->where('status_capaian', 'Dalam Progress')->count(),
            'rata_capaian' => $ikuList->where('persentase_capaian', '>', 0)->avg('persentase_capaian'),
        ];
        
        return view('spmi::iku-resmi.analytics', compact('ikuList', 'periodes', 'periodeId', 'summary'));
    }
    
    /**
     * Dashboard monitoring triwulan
     */
    public function monitoringTriwulan(Request $request)
    {
        $periodeAktif = Periode::where('is_aktif', true)->first();
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        
        $periodeId = $request->get('periode_id', $periodeAktif?->id);
        $triwulan = $request->get('triwulan', 'TW1');
        
        $ikuList = IkuResmi::where('is_aktif', true)
            ->orderBy('id')
            ->get()
            ->map(function ($iku) use ($periodeId, $triwulan) {
                $hasil = $iku->getHasilByPeriodeTriwulan($periodeId, $triwulan);
                $iku->target = $hasil->target ?? 0;
                $iku->nilai_hasil = $hasil->nilai_hasil ?? 0;
                $iku->persentase_capaian = $hasil->persentase_capaian ?? 0;
                $iku->status_capaian = $hasil->status_capaian ?? 'Belum Dihitung';
                return $iku;
            });
        
        // Ambil data semua triwulan untuk comparison
        $comparisonData = [];
        foreach (['TW1', 'TW2', 'TW3', 'TW4'] as $tw) {
            $comparisonData[$tw] = IkuResmi::where('is_aktif', true)
                ->orderBy('id')
                ->get()
                ->map(function ($iku) use ($periodeId, $tw) {
                    $hasil = $iku->getHasilByPeriodeTriwulan($periodeId, $tw);
                    return [
                        'iku_id' => $iku->id,
                        'nilai' => $hasil->nilai_hasil ?? 0,
                        'status' => $hasil->status_capaian ?? 'Belum Dihitung',
                    ];
                })->keyBy('iku_id');
        }
        
        $triwulanOptions = IkuHasil::triwulanOptions();
        
        return view('spmi::iku-resmi.monitoring-triwulan', compact(
            'ikuList', 
            'periodes', 
            'periodeId', 
            'triwulan', 
            'triwulanOptions',
            'comparisonData'
        ));
    }
    
    /**
     * Laporan triwulan untuk Kemdiktisaintek
     */
    public function laporanTriwulan(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $triwulan = $request->get('triwulan', 'TW1');
        $periode = Periode::findOrFail($periodeId);
        
        $ikuList = IkuResmi::where('is_aktif', true)
            ->orderBy('id')
            ->get()
            ->map(function ($iku) use ($periodeId, $triwulan) {
                $hasil = $iku->getHasilByPeriodeTriwulan($periodeId, $triwulan);
                $iku->target = $hasil->target ?? 0;
                $iku->nilai_hasil = $hasil->nilai_hasil ?? 0;
                $iku->persentase_capaian = $hasil->persentase_capaian ?? 0;
                $iku->status_capaian = $hasil->status_capaian ?? 'Belum Dihitung';
                return $iku;
            });
        
        $triwulanOptions = IkuHasil::triwulanOptions();
        
        if ($request->get('format') === 'pdf') {
            // TODO: Implement PDF export
            return view('spmi::iku-resmi.laporan-triwulan-pdf', compact('periode', 'ikuList', 'triwulan', 'triwulanOptions'));
        }
        
        return view('spmi::iku-resmi.laporan-triwulan', compact('periode', 'ikuList', 'triwulan', 'triwulanOptions'));
    }

    /**
     * Sinkronisasikan data eksternal ke tabel data input IKU
     */
    public function syncDataSources(Request $request, $id)
    {
        $iku = IkuResmi::findOrFail($id);
        
        $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'triwulan' => 'required|in:TW1,TW2,TW3,TW4,TAHUNAN',
        ]);
        
        try {
            $count = $this->integrationService->sync($id, $request->periode_id, $request->triwulan);
            
            // Lakukan kalkulasi otomatis setelah sinkronisasi berhasil
            $ikuNumber = str_replace('IKU', '', $iku->nomor_iku);
            $this->calculationService->recalculate($ikuNumber, $request->periode_id, $request->triwulan);
            
            return redirect()
                ->route('iku-resmi.show', ['iku_resmi' => $id, 'periode_id' => $request->periode_id])
                ->with('success', "Berhasil menyinkronkan {$count} data indikator dari sistem primer dan memperbarui perhitungan!");
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyinkronkan data: ' . $e->getMessage());
        }
    }
}

