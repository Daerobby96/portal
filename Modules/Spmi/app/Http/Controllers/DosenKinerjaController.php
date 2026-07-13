<?php

namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use Modules\Spmi\Models\DosenKinerja;
use Modules\DataMaster\Models\Periode;
use App\Services\EdomImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DosenKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $selectedPeriodeId = $request->get('periode_id', $periodes->first()?->id);

        $kinerjas = DosenKinerja::where('periode_id', $selectedPeriodeId)
            ->orderBy('total_rerata', 'desc')
            ->get();

        return view('spmi::dosen-kinerja.index', compact('kinerjas', 'periodes', 'selectedPeriodeId'));
    }

    public function show(DosenKinerja $kinerja)
    {
        return view('spmi::dosen-kinerja.show', compact('kinerja'));
    }

    public function exportIndividualPdf(DosenKinerja $kinerja)
    {
        $kinerja->load('periode');
        
        \App\Models\Setting::clearCache();
        $setting = [
            'nama_institusi' => \App\Models\Setting::get('nama_institusi', 'NAMA PERGURUAN TINGGI'),
            'alamat_institusi' => \App\Models\Setting::get('alamat_institusi', 'Alamat Institusi'),
            'kota_institusi' => \App\Models\Setting::get('kota_institusi', 'Kota'),
            'logo_institusi' => \App\Models\Setting::get('logo_institusi', null),
        ];

        
        $ketuaSpmi = \App\Models\User::role('super_admin')->where('is_active', true)->first();

        $kepalaInstitusi = \App\Models\User::where('is_active', true)
            ->where(function ($q) {
                $q->where('jabatan', 'like', '%Kepala%')
                    ->orWhere('jabatan', 'like', '%Rektor%')
                    ->orWhere('jabatan', 'like', '%Direktur%');
            })->first();

        $data = compact('kinerja', 'setting', 'ketuaSpmi', 'kepalaInstitusi');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('spmi::dosen-kinerja.pdf-individual', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Rapor-EDOM-' . Str::slug($kinerja->dosen_name) . '.pdf';
        return $pdf->stream($filename);
    }

    public function importEdom(Request $request, EdomImportService $service)
    {
        $request->validate([
            'file' => 'required|max:5120', // 5MB
        ]);

        try {
            $html = file_get_contents($request->file('file')->getRealPath());
            $count = $service->importFromHtml($html);

            return redirect()->back()->with('success', "Berhasil mengimport data kinerja untuk $count dosen.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Gagal mengimport data: " . $e->getMessage());
        }
    }
}

