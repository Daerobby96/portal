<?php

namespace App\Services;

use App\Models\IkuDataInput;
use App\Models\IkuResmi;
use Modules\DataMaster\Models\Periode;
use App\Models\TracerStudy;
use App\Models\DosenKinerja;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class IkuIntegrationService
{
    /**
     * Sync data sources for a specific IKU, Period, and Triwulan
     */
    public function sync(int $ikuId, int $periodeId, string $triwulan): int
    {
        $iku = IkuResmi::findOrFail($ikuId);
        $periode = Periode::findOrFail($periodeId);
        
        switch ($iku->nomor_iku) {
            case 'IKU2':
                return $this->syncIku2($ikuId, $periode, $triwulan);
            case 'IKU4':
                return $this->syncIku4($ikuId, $periodeId, $triwulan);
            default:
                throw new \Exception("IKU {$iku->nomor_iku} tidak mendukung sinkronisasi otomatis.");
        }
    }

    /**
     * Sync IKU 2 from Tracer Study
     */
    private function syncIku2(int $ikuId, Periode $periode, string $triwulan): int
    {
        $year = $periode->tahun;
        $ump = Setting::get('ump_value', 3000000);
        $limitGaji = 1.2 * $ump;

        // Get tracer study records matching graduation year
        $alumniList = TracerStudy::where('tahun_lulus', $year)->get();

        // 15 Kategori IKU 2 dengan bobot bawaannya
        $categories = [
            'BEKERJA_6BLN_GAJI_HIGH' => ['nilai' => 0, 'bobot' => 1.0],
            'BEKERJA_1THN_GAJI_HIGH' => ['nilai' => 0, 'bobot' => 0.8],
            'BEKERJA_1THN_GAJI_LOW'  => ['nilai' => 0, 'bobot' => 0.6],
            'WIRAUSAHA_6BLN_HIGH'    => ['nilai' => 0, 'bobot' => 1.2],
            'WIRAUSAHA_1THN_HIGH'    => ['nilai' => 0, 'bobot' => 1.0],
            'WIRAUSAHA_6BLN_MID'     => ['nilai' => 0, 'bobot' => 0.8],
            'WIRAUSAHA_1THN_MID'     => ['nilai' => 0, 'bobot' => 0.6],
            'FREELANCE_6BLN_HIGH'    => ['nilai' => 0, 'bobot' => 0.5],
            'FREELANCE_1THN_HIGH'    => ['nilai' => 0, 'bobot' => 0.4],
            'FREELANCE_6BLN_LOW'     => ['nilai' => 0, 'bobot' => 0.3],
            'FREELANCE_1THN_LOW'     => ['nilai' => 0, 'bobot' => 0.2],
            'STUDI_LANJUT'           => ['nilai' => 0, 'bobot' => 0.6],
            'BEKERJA_SEBELUM_HIGH'   => ['nilai' => 0, 'bobot' => 1.0],
            'WIRAUSAHA_SEBELUM_HIGH' => ['nilai' => 0, 'bobot' => 1.0],
            'WIRAUSAHA_SEBELUM_LOW'  => ['nilai' => 0, 'bobot' => 0.6],
        ];

        foreach ($alumniList as $alumni) {
            $status = strtolower($alumni->status_kerja);
            $wt = $alumni->waktu_tunggu_bulan;
            $gaji = $alumni->gaji;

            // Klasifikasi Bekerja sebelum lulus / wirausaha sebelum lulus (wt < 0 atau null/belum diisi tapi status terisi)
            if ($wt < 0) {
                if (str_contains($status, 'kerja') || str_contains($status, 'karyawan')) {
                    if ($gaji > $limitGaji) {
                        $categories['BEKERJA_SEBELUM_HIGH']['nilai']++;
                    } else {
                        // fallback to standard if not high
                        $categories['BEKERJA_1THN_GAJI_LOW']['nilai']++;
                    }
                } elseif (str_contains($status, 'wira') || str_contains($status, 'usaha') || str_contains($status, 'freelance')) {
                    if ($gaji > $limitGaji) {
                        $categories['WIRAUSAHA_SEBELUM_HIGH']['nilai']++;
                    } else {
                        $categories['WIRAUSAHA_SEBELUM_LOW']['nilai']++;
                    }
                }
                continue;
            }

            // Normal classification
            if (str_contains($status, 'kerja') || str_contains($status, 'karyawan')) {
                if ($wt < 6) {
                    if ($gaji > $limitGaji) {
                        $categories['BEKERJA_6BLN_GAJI_HIGH']['nilai']++;
                    } else {
                        $categories['BEKERJA_1THN_GAJI_LOW']['nilai']++;
                    }
                } elseif ($wt <= 12) {
                    if ($gaji > $limitGaji) {
                        $categories['BEKERJA_1THN_GAJI_HIGH']['nilai']++;
                    } else {
                        $categories['BEKERJA_1THN_GAJI_LOW']['nilai']++;
                    }
                }
            } elseif (str_contains($status, 'wira') || str_contains($status, 'usaha') || str_contains($status, 'bisnis')) {
                if ($wt < 6) {
                    if ($gaji > $limitGaji) {
                        $categories['WIRAUSAHA_6BLN_HIGH']['nilai']++;
                    } else {
                        $categories['WIRAUSAHA_6BLN_MID']['nilai']++;
                    }
                } elseif ($wt <= 12) {
                    if ($gaji > $limitGaji) {
                        $categories['WIRAUSAHA_1THN_HIGH']['nilai']++;
                    } else {
                        $categories['WIRAUSAHA_1THN_MID']['nilai']++;
                    }
                }
            } elseif (str_contains($status, 'freelance') || str_contains($status, 'lepas')) {
                if ($wt < 6) {
                    if ($gaji > $limitGaji) {
                        $categories['FREELANCE_6BLN_HIGH']['nilai']++;
                    } else {
                        $categories['FREELANCE_6BLN_LOW']['nilai']++;
                    }
                } elseif ($wt <= 12) {
                    if ($gaji > $limitGaji) {
                        $categories['FREELANCE_1THN_HIGH']['nilai']++;
                    } else {
                        $categories['FREELANCE_1THN_LOW']['nilai']++;
                    }
                }
            } elseif (str_contains($status, 'studi') || str_contains($status, 'lanjut') || str_contains($status, 'kuliah')) {
                if ($wt <= 12) {
                    $categories['STUDI_LANJUT']['nilai']++;
                }
            }
        }

        DB::transaction(function () use ($ikuId, $periode, $triwulan, $categories) {
            // Delete old values
            IkuDataInput::where('iku_resmi_id', $ikuId)
                ->where('periode_id', $periode->id)
                ->where('triwulan', $triwulan)
                ->delete();

            // Insert new synchronized categories
            foreach ($categories as $kategori => $item) {
                IkuDataInput::create([
                    'iku_resmi_id' => $ikuId,
                    'periode_id' => $periode->id,
                    'triwulan' => $triwulan,
                    'kategori' => $kategori,
                    'nilai_input' => $item['nilai'],
                    'bobot' => $item['bobot'],
                    'keterangan' => 'Disinkronkan otomatis dari Tracer Study',
                ]);
            }
        });

        return count($categories);
    }

    /**
     * Sync IKU 4 from Dosen Kinerja
     */
    private function syncIku4(int $ikuId, int $periodeId, string $triwulan): int
    {
        $totalDosen = DosenKinerja::where('periode_id', $periodeId)->count();
        
        $dosenRekognisi = DosenKinerja::where('periode_id', $periodeId)
            ->where(function($query) {
                $query->where('total_rerata', '>=', 4.0)
                      ->orWhereIn('predikat', ['Sangat Baik', 'Baik']);
            })->count();

        DB::transaction(function () use ($ikuId, $periodeId, $triwulan, $totalDosen, $dosenRekognisi) {
            IkuDataInput::where('iku_resmi_id', $ikuId)
                ->where('periode_id', $periodeId)
                ->where('triwulan', $triwulan)
                ->delete();

            IkuDataInput::create([
                'iku_resmi_id' => $ikuId,
                'periode_id' => $periodeId,
                'triwulan' => $triwulan,
                'kategori' => 'TOTAL_DOSEN',
                'nilai_input' => $totalDosen,
                'keterangan' => 'Disinkronkan otomatis dari Kinerja Dosen',
            ]);

            IkuDataInput::create([
                'iku_resmi_id' => $ikuId,
                'periode_id' => $periodeId,
                'triwulan' => $triwulan,
                'kategori' => 'DOSEN_REKOGNISI',
                'nilai_input' => $dosenRekognisi,
                'keterangan' => 'Disinkronkan otomatis dari Kinerja Dosen (Skor >= 4.0)',
            ]);
        });

        return 2;
    }
}

