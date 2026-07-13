<?php

namespace Modules\Spmi\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Spmi\Services\ModuleIntegrationService;
use Modules\DataMaster\Models\Periode;
use Illuminate\Http\Request;

class IntegrationDashboardController extends Controller
{
    protected $integrationService;

    public function __construct(ModuleIntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * Dashboard integrasi data dari semua modul
     */
    public function index(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $periode = null;
        
        if ($periodeId) {
            $periode = Periode::find($periodeId);
        } else {
            $periode = Periode::where('is_aktif', true)->first();
        }

        // Ambil semua data terintegrasi
        $integratedData = $this->integrationService->getAllIntegratedData($periode?->id);

        // Ambil list periode untuk filter
        $periodes = Periode::orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        return view('spmi::integration.dashboard', compact('integratedData', 'periode', 'periodes'));
    }

    /**
     * API endpoint untuk ambil data integrasi (untuk AJAX)
     */
    public function getData(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $module = $request->get('module'); // mahasiswa, penelitian, dll

        if ($module && method_exists($this->integrationService, 'get' . ucfirst($module) . 'Data')) {
            $method = 'get' . ucfirst($module) . 'Data';
            $data = $this->integrationService->$method($periodeId);
        } else {
            $data = $this->integrationService->getAllIntegratedData($periodeId);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Widget untuk dashboard utama
     */
    public function widget(Request $request)
    {
        $periode = Periode::where('is_aktif', true)->first();
        $data = $this->integrationService->getAllIntegratedData($periode?->id);

        return view('spmi::integration.widget', compact('data', 'periode'));
    }
}
