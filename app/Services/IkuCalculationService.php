<?php

namespace App\Services;

use App\Models\IkuResmi;
use App\Models\IkuDataInput;
use App\Models\IkuHasil;
use Illuminate\Support\Facades\DB;

class IkuCalculationService
{
    /**
     * IKU 1: Angka Efisiensi Edukasi PT (AEE PT)
     * Formula: Rata-rata Tingkat Pencapaian AEE seluruh program aktif
     */
    public function calculateIku1($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 1)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
        
        $totalPencapaian = 0;
        $jumlahProgram = 0;
        
        foreach ($inputs as $input) {
            $totalMahasiswa = $input->metadata['total_mahasiswa'] ?? 0;
            
            if ($totalMahasiswa > 0 && $input->bobot > 0) {
                $aeeIdeal = $input->bobot; // AEE Ideal (dalam %, misal: 25, 33, 50, 100)
                $aeeRealisasi = ($input->nilai_input / $totalMahasiswa) * 100;
                $tingkatPencapaian = ($aeeRealisasi / $aeeIdeal) * 100;
                
                $totalPencapaian += min($tingkatPencapaian, 100); // Cap at 100%
                $jumlahProgram++;
            }
        }
        
        return $jumlahProgram > 0 ? round($totalPencapaian / $jumlahProgram, 2) : 0;
    }
    
    /**
     * IKU 2: % Lulusan D1-S1 bekerja/wirausaha/studi lanjut ≤1 tahun
     * Formula: (∑ nᵢ × kᵢ) / t × 100%
     */
    public function calculateIku2($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 2)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
        
        $totalTertimbang = $inputs->sum(function($input) {
            return $input->nilai_input * ($input->bobot ?? 1);
        });
        
        $totalResponden = $inputs->sum('nilai_input');
        
        return $totalResponden > 0 ? round(($totalTertimbang / $totalResponden) * 100, 2) : 0;
    }
    
    /**
     * IKU 3: % Mahasiswa D & S berkegiatan/berprestasi di luar prodi
     * Formula: (∑ nᵢ × kᵢ) / t × 100%
     */
    public function calculateIku3($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 3)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
        
        $totalTertimbang = $inputs->sum(function($input) {
            return $input->nilai_input * ($input->bobot ?? 1);
        });
        
        // Ambil total mahasiswa dari metadata input pertama atau field khusus
        $totalMahasiswa = $inputs->first()->metadata['total_mahasiswa'] ?? 
                         IkuDataInput::where('iku_resmi_id', 3)
                            ->where('periode_id', $periodeId)
                            ->where('triwulan', $triwulan)
                            ->where('kategori', 'TOTAL_MAHASISWA')
                            ->value('nilai_input') ?? 1;
        
        return $totalMahasiswa > 0 ? round(($totalTertimbang / $totalMahasiswa) * 100, 2) : 0;
    }
    
    /**
     * IKU 4: % Dosen rekognisi internasional
     * Formula: (Jumlah dosen rekognisi / Total dosen) × 100%
     */
    public function calculateIku4($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $dosenRekognisi = IkuDataInput::where('iku_resmi_id', 4)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'DOSEN_REKOGNISI')
            ->value('nilai_input') ?? 0;
        
        $totalDosen = IkuDataInput::where('iku_resmi_id', 4)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'TOTAL_DOSEN')
            ->value('nilai_input') ?? 0;
        
        return $totalDosen > 0 ? round(($dosenRekognisi / $totalDosen) * 100, 2) : 0;
    }
    
    /**
     * IKU 5: % Luaran hasil kerjasama PT–Industri/Lembaga
     * Formula: (Jumlah luaran / Total kerjasama) × 100%
     */
    public function calculateIku5($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $jumlahLuaran = IkuDataInput::where('iku_resmi_id', 5)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'JUMLAH_LUARAN')
            ->value('nilai_input') ?? 0;
        
        $totalKerjasama = IkuDataInput::where('iku_resmi_id', 5)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'TOTAL_KERJASAMA')
            ->value('nilai_input') ?? 0;
        
        return $totalKerjasama > 0 ? round(($jumlahLuaran / $totalKerjasama) * 100, 2) : 0;
    }
    
    /**
     * IKU 6: % Publikasi bereputasi internasional (Scopus/WoS)
     * Formula: (∑ nᵢ × kᵢ) / t × 100%
     */
    public function calculateIku6($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 6)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->whereNotIn('kategori', ['TOTAL_PUBLIKASI'])
            ->get();
        
        $totalTertimbang = $inputs->sum(function($input) {
            $bobot = $input->bobot ?? 1;
            $kolaborasiIntl = $input->metadata['kolaborasi_intl'] ?? 0;
            $bobotFinal = $bobot + ($kolaborasiIntl > 0 ? 0.25 : 0);
            
            return $input->nilai_input * $bobotFinal;
        });
        
        $totalPublikasi = IkuDataInput::where('iku_resmi_id', 6)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'TOTAL_PUBLIKASI')
            ->value('nilai_input') ?? 0;
        
        return $totalPublikasi > 0 ? round(($totalTertimbang / $totalPublikasi) * 100, 2) : 0;
    }
    
    /**
     * IKU 7: % Keterlibatan PT dalam SDGs
     * Formula: (∑ Program SDG wajib + pilihan) / Total program × 100%
     */
    public function calculateIku7($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 7)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
        
        $jumlahProgramSDG = $inputs->whereIn('kategori', ['SDG1', 'SDG4', 'SDG17', 'SDG_PILIHAN1', 'SDG_PILIHAN2'])
            ->sum('nilai_input');
        
        $totalProgram = IkuDataInput::where('iku_resmi_id', 7)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'TOTAL_PROGRAM')
            ->value('nilai_input') ?? 0;
        
        return $totalProgram > 0 ? round(($jumlahProgramSDG / $totalProgram) * 100, 2) : 0;
    }
    
    /**
     * IKU 8: % SDM PT terlibat penyusunan kebijakan
     * Formula: (Jumlah SDM terlibat / Total SDM) × 100%
     */
    public function calculateIku8($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $sdmTerlibat = IkuDataInput::where('iku_resmi_id', 8)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'SDM_TERLIBAT')
            ->value('nilai_input') ?? 0;
        
        $totalSdm = IkuDataInput::where('iku_resmi_id', 8)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'TOTAL_SDM')
            ->value('nilai_input') ?? 0;
        
        return $totalSdm > 0 ? round(($sdmTerlibat / $totalSdm) * 100, 2) : 0;
    }
    
    /**
     * IKU 9: % Pendapatan non-akademik (non-UKT)
     * Formula: (Total pendapatan non-mahasiswa / Total pendapatan PT) × 100%
     */
    public function calculateIku9($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 9)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
        
        $pendapatanNonAkademik = $inputs->whereIn('kategori', [
            'RISET_INOVASI', 
            'KERJASAMA_LAYANAN', 
            'USAHA_BISNIS', 
            'SUMBANGAN', 
            'DANA_ABADI'
        ])->sum('nilai_input');
        
        $totalPendapatan = IkuDataInput::where('iku_resmi_id', 9)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'TOTAL_PENDAPATAN')
            ->value('nilai_input') ?? 0;
        
        return $totalPendapatan > 0 ? round(($pendapatanNonAkademik / $totalPendapatan) * 100, 2) : 0;
    }
    
    /**
     * IKU 10: Zona Integritas (WBK/WBBM) – jumlah unit
     * Formula: Jumlah unit WBK + WBBM
     */
    public function calculateIku10($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $unitWbk = IkuDataInput::where('iku_resmi_id', 10)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'WBK')
            ->value('nilai_input') ?? 0;
        
        $unitWbbm = IkuDataInput::where('iku_resmi_id', 10)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'WBBM')
            ->value('nilai_input') ?? 0;
        
        return $unitWbk + $unitWbbm;
    }
    
    /**
     * IKU 11: Hasil Audit (Composite dari 11a-11d)
     * Return nilai rata-rata atau composite score
     */
    public function calculateIku11($periodeId, $triwulan = 'TAHUNAN'): array
    {
        $opiniAudit = IkuDataInput::where('iku_resmi_id', 11)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'OPINI_AUDIT')
            ->value('keterangan') ?? 'N/A';
        
        $nilaiSakip = IkuDataInput::where('iku_resmi_id', 11)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'NILAI_SAKIP')
            ->value('nilai_input') ?? 0;
        
        $pelanggaranAkademik = IkuDataInput::where('iku_resmi_id', 11)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->where('kategori', 'PELANGGARAN_AKADEMIK')
            ->value('nilai_input') ?? 0;
        
        $pencegahanInputs = IkuDataInput::where('iku_resmi_id', 11)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->whereIn('kategori', ['PENCEGAHAN_KEKERASAN', 'PENCEGAHAN_NARKOBA', 'PENCEGAHAN_KORUPSI'])
            ->get();
        
        $rataPencegahan = $pencegahanInputs->count() > 0 
            ? $pencegahanInputs->avg('nilai_input') 
            : 0;
        
        return [
            '11a_opini' => $opiniAudit,
            '11b_sakip' => $nilaiSakip,
            '11c_pelanggaran' => $pelanggaranAkademik,
            '11d_pencegahan' => round($rataPencegahan, 2),
        ];
    }
    
    /**
     * IKU 12: Ketersediaan Renstra Kesejahteraan Dosen
     * Formula: Jumlah komponen terpenuhi / Total komponen × 100%
     */
    public function calculateIku12($periodeId, $triwulan = 'TAHUNAN'): float
    {
        $inputs = IkuDataInput::where('iku_resmi_id', 12)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
        
        $komponenTerpenuhi = $inputs->where('nilai_input', 1)->count();
        $totalKomponen = $inputs->count();
        
        return $totalKomponen > 0 ? round(($komponenTerpenuhi / $totalKomponen) * 100, 2) : 0;
    }
    
    /**
     * Hitung semua IKU untuk periode tertentu
     */
    public function calculateAll($periodeId): array
    {
        $results = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $method = "calculateIku{$i}";
            
            if (method_exists($this, $method)) {
                try {
                    $hasil = $this->$method($periodeId);
                    $results["IKU{$i}"] = $hasil;
                    
                    // Simpan hasil ke database
                    $this->saveHasil($i, $periodeId, $hasil);
                } catch (\Exception $e) {
                    $results["IKU{$i}"] = 0;
                    \Log::error("Error calculating IKU{$i}: " . $e->getMessage());
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Simpan hasil perhitungan ke database
     */
    protected function saveHasil($ikuNumber, $periodeId, $nilaiHasil, $triwulan = 'TAHUNAN')
    {
        $ikuResmi = IkuResmi::where('nomor_iku', "IKU{$ikuNumber}")->first();
        
        if (!$ikuResmi) {
            return;
        }
        
        if (is_array($nilaiHasil)) {
            // Untuk IKU 11 yang composite
            $nilaiHasil = $nilaiHasil['11b_sakip'] ?? 0;
        }
        
        // Ambil target (dari hasil existing atau default dari master)
        $existingHasil = IkuHasil::where('iku_resmi_id', $ikuResmi->id)
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->first();
        
        $target = $existingHasil->target ?? $ikuResmi->target_default ?? 0;
        
        // Hitung persentase capaian dan gap
        $persentaseCapaian = 0;
        $gap = 0;
        
        if ($target > 0) {
            $persentaseCapaian = ($nilaiHasil / $target) * 100;
            $gap = $nilaiHasil - $target;
        }
        
        // Tentukan status capaian
        $status = $this->determineStatus($persentaseCapaian, $nilaiHasil);
        
        IkuHasil::updateOrCreate(
            [
                'iku_resmi_id' => $ikuResmi->id,
                'periode_id' => $periodeId,
                'triwulan' => $triwulan,
            ],
            [
                'target' => $target,
                'nilai_hasil' => $nilaiHasil,
                'persentase_capaian' => round($persentaseCapaian, 2),
                'gap' => round($gap, 2),
                'status_capaian' => $status,
                'calculated_at' => now(),
            ]
        );
    }
    
    /**
     * Tentukan status capaian berdasarkan persentase
     */
    protected function determineStatus($persentaseCapaian, $nilaiHasil)
    {
        if ($nilaiHasil == 0) {
            return IkuHasil::STATUS_BELUM_DIHITUNG;
        }
        
        if ($persentaseCapaian >= 100) {
            return IkuHasil::STATUS_TERCAPAI;
        } elseif ($persentaseCapaian >= 80) {
            return IkuHasil::STATUS_DALAM_PROGRESS;
        } else {
            return IkuHasil::STATUS_TIDAK_TERCAPAI;
        }
    }
    
    /**
     * Hitung ulang satu IKU tertentu
     */
    public function recalculate($ikuNumber, $periodeId, $triwulan = 'TAHUNAN')
    {
        $method = "calculateIku{$ikuNumber}";
        
        if (method_exists($this, $method)) {
            $hasil = $this->$method($periodeId, $triwulan);
            $this->saveHasil($ikuNumber, $periodeId, $hasil, $triwulan);
            
            return $hasil;
        }
        
        return null;
    }
}
