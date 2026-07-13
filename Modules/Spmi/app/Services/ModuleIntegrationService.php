<?php

namespace Modules\Spmi\Services;

use Illuminate\Support\Facades\DB;

/**
 * Service untuk integrasi data dari modul-modul lain ke SPMI
 * Menggunakan try-catch untuk handle jika modul tidak ada
 * 
 * LOKASI DATA:
 * - Mahasiswa & Prestasi: app/Models (data akademik utama)
 * - Pegawai: Modules/DataMaster/Models
 * - Penelitian, Pengabdian, Publikasi, HKI: Modules/Tridharma/Models
 * - Kerjasama: Modules/Kerjasama/Models
 * - TracerStudy: Modules/TracerStudy/Models
 * - Aset: Modules/ManajemenAset/Models
 */
class ModuleIntegrationService
{
    /**
     * Ambil data mahasiswa dari DataAkademik
     */
    public function getMahasiswaData($programStudiId = null, $periodeId = null)
    {
        try {
            $modelClass = 'App\Models\Mahasiswa';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'aktif' => 0,
                    'by_prodi' => [],
                    'available' => false
                ];
            }

            // Jangan filter berdasarkan periode untuk data mahasiswa total
            // Karena mahasiswa bisa dari berbagai angkatan
            
            // PENTING: Buat query baru untuk setiap count
            if ($programStudiId) {
                $total = $modelClass::where('prodi_id', $programStudiId)->count();
                $aktif = $modelClass::where('prodi_id', $programStudiId)->where('status', 'aktif')->count();
            } else {
                $total = $modelClass::count();
                $aktif = $modelClass::where('status', 'aktif')->count();
            }
            
            $byProdi = $modelClass::select('prodi_id', DB::raw('count(*) as total'))
                ->whereNotNull('prodi_id')
                ->groupBy('prodi_id')
                ->get()
                ->mapWithKeys(fn($item) => [$item->prodi_id => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'aktif' => $aktif,
                'by_prodi' => $byProdi,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting mahasiswa data: ' . $e->getMessage());
            return [
                'total' => 0,
                'aktif' => 0,
                'by_prodi' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data pegawai (dosen dan tendik) dari SDM
     */
    public function getPegawaiData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\Sdm\Models\Pegawai';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'aktif' => 0,
                    'dosen' => 0,
                    'tendik' => 0,
                    'by_jenis' => [],
                    'by_status' => [],
                    'available' => false
                ];
            }

            // Jangan filter berdasarkan periode untuk data pegawai
            // Karena pegawai adalah data master yang tidak terkait periode akademik
            
            // PENTING: Buat query baru untuk setiap count untuk menghindari kondisi bertumpuk
            $total = $modelClass::count();
            $aktif = $modelClass::where('is_aktif', true)->count();
            $dosen = $modelClass::where('jenis_pegawai', 'Dosen')->count();
            $tendik = $modelClass::where('jenis_pegawai', 'Tenaga Kependidikan')->count();
            
            $byJenis = $modelClass::select('jenis_pegawai', DB::raw('count(*) as total'))
                ->groupBy('jenis_pegawai')
                ->get()
                ->mapWithKeys(fn($item) => [$item->jenis_pegawai => $item->total])
                ->toArray();

            $byStatus = $modelClass::select('status_kepegawaian', DB::raw('count(*) as total'))
                ->whereNotNull('status_kepegawaian')
                ->groupBy('status_kepegawaian')
                ->get()
                ->mapWithKeys(fn($item) => [$item->status_kepegawaian => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'aktif' => $aktif,
                'dosen' => $dosen,
                'tendik' => $tendik,
                'by_jenis' => $byJenis,
                'by_status' => $byStatus,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting pegawai data: ' . $e->getMessage());
            return [
                'total' => 0,
                'aktif' => 0,
                'dosen' => 0,
                'tendik' => 0,
                'by_jenis' => [],
                'by_status' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data prestasi mahasiswa
     */
    public function getPrestasiData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\DataAkademik\Models\Prestasi';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'by_level' => [],
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->whereBetween('tanggal_prestasi', [
                        $periode->tanggal_mulai,
                        $periode->tanggal_selesai
                    ]);
                }
            }

            $total = $query->count();
            
            $byLevel = $query->select('tingkat', DB::raw('count(*) as total'))
                ->groupBy('tingkat')
                ->get()
                ->mapWithKeys(fn($item) => [$item->tingkat => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'by_level' => $byLevel,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting prestasi data: ' . $e->getMessage());
            return [
                'total' => 0,
                'by_level' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data penelitian dari Tridharma
     */
    public function getPenelitianData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\Tridharma\Models\Penelitian';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'by_status' => [],
                    'by_sumber_dana' => [],
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->where('tahun', $periode->tahun);
                }
            }

            $total = $query->count();
            
            $byStatus = $query->select('status', DB::raw('count(*) as total'))
                ->whereNotNull('status')
                ->groupBy('status')
                ->get()
                ->mapWithKeys(fn($item) => [$item->status => $item->total])
                ->toArray();

            $bySumberDana = $query->select('sumber_dana', DB::raw('count(*) as total'))
                ->whereNotNull('sumber_dana')
                ->groupBy('sumber_dana')
                ->get()
                ->mapWithKeys(fn($item) => [$item->sumber_dana => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'by_status' => $byStatus,
                'by_sumber_dana' => $bySumberDana,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting penelitian data: ' . $e->getMessage());
            return [
                'total' => 0,
                'by_status' => [],
                'by_sumber_dana' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data pengabdian dari Tridharma
     */
    public function getPengabdianData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\Tridharma\Models\Pengabdian';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'by_sumber_dana' => [],
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->where('tahun', $periode->tahun);
                }
            }

            $total = $query->count();
            
            $bySumberDana = $query->select('sumber_dana', DB::raw('count(*) as total'))
                ->whereNotNull('sumber_dana')
                ->groupBy('sumber_dana')
                ->get()
                ->mapWithKeys(fn($item) => [$item->sumber_dana => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'by_sumber_dana' => $bySumberDana,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting pengabdian data: ' . $e->getMessage());
            return [
                'total' => 0,
                'by_sumber_dana' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data publikasi dari Tridharma
     */
    public function getPublikasiData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\Tridharma\Models\Publikasi';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'by_jenis' => [],
                    'by_sinta' => [],
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->where('tahun', $periode->tahun);
                }
            }

            $total = $query->count();
            
            $byJenis = $query->select('jenis', DB::raw('count(*) as total'))
                ->whereNotNull('jenis')
                ->groupBy('jenis')
                ->get()
                ->mapWithKeys(fn($item) => [$item->jenis => $item->total])
                ->toArray();

            $bySinta = $query->select('tingkat_sinta', DB::raw('count(*) as total'))
                ->whereNotNull('tingkat_sinta')
                ->groupBy('tingkat_sinta')
                ->get()
                ->mapWithKeys(fn($item) => [$item->tingkat_sinta => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'by_jenis' => $byJenis,
                'by_sinta' => $bySinta,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting publikasi data: ' . $e->getMessage());
            return [
                'total' => 0,
                'by_jenis' => [],
                'by_sinta' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data HKI dari Tridharma
     */
    public function getHkiData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\Tridharma\Models\Hki';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'by_jenis' => [],
                    'by_status' => [],
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->where('tahun_terbit', $periode->tahun);
                }
            }

            $total = $query->count();
            
            $byJenis = $query->select('jenis_hki', DB::raw('count(*) as total'))
                ->whereNotNull('jenis_hki')
                ->groupBy('jenis_hki')
                ->get()
                ->mapWithKeys(fn($item) => [$item->jenis_hki => $item->total])
                ->toArray();

            $byStatus = $query->select('status', DB::raw('count(*) as total'))
                ->whereNotNull('status')
                ->groupBy('status')
                ->get()
                ->mapWithKeys(fn($item) => [$item->status => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'by_jenis' => $byJenis,
                'by_status' => $byStatus,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting HKI data: ' . $e->getMessage());
            return [
                'total' => 0,
                'by_jenis' => [],
                'by_status' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data kerjasama
     */
    public function getKerjasamaData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\Kerjasama\Models\Kerjasama';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'aktif' => 0,
                    'by_jenis' => [],
                    'by_tingkat' => [],
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->where(function($q) use ($periode) {
                        $q->whereBetween('tanggal_mulai', [
                            $periode->tanggal_mulai,
                            $periode->tanggal_selesai
                        ])->orWhereBetween('tanggal_selesai', [
                            $periode->tanggal_mulai,
                            $periode->tanggal_selesai
                        ]);
                    });
                }
            }

            $total = $query->count();
            $aktif = $query->where('status', 'Aktif')->count();
            
            $byJenis = $modelClass::select('jenis_mitra', DB::raw('count(*) as total'))
                ->whereNotNull('jenis_mitra')
                ->groupBy('jenis_mitra')
                ->get()
                ->mapWithKeys(fn($item) => [$item->jenis_mitra => $item->total])
                ->toArray();

            $byTingkat = $modelClass::select('tingkat', DB::raw('count(*) as total'))
                ->whereNotNull('tingkat')
                ->groupBy('tingkat')
                ->get()
                ->mapWithKeys(fn($item) => [$item->tingkat => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'aktif' => $aktif,
                'by_jenis' => $byJenis,
                'by_tingkat' => $byTingkat,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting kerjasama data: ' . $e->getMessage());
            return [
                'total' => 0,
                'aktif' => 0,
                'by_jenis' => [],
                'by_tingkat' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data tracer study alumni
     */
    public function getTracerStudyData($periodeId = null)
    {
        try {
            $modelClass = 'Modules\TracerStudy\Models\TracerStudy';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'bekerja' => 0,
                    'wirausaha' => 0,
                    'studi_lanjut' => 0,
                    'available' => false
                ];
            }

            // Cek apakah tabel ada
            if (!DB::getSchemaBuilder()->hasTable('tracer_studies')) {
                return [
                    'total' => 0,
                    'bekerja' => 0,
                    'wirausaha' => 0,
                    'studi_lanjut' => 0,
                    'available' => false
                ];
            }

            $query = $modelClass::query();
            
            if ($periodeId) {
                $periode = \Modules\DataMaster\Models\Periode::find($periodeId);
                if ($periode) {
                    $query->where('tahun_lulus', $periode->tahun);
                }
            }

            $total = $query->count();
            
            // Query terpisah untuk setiap kategori status dengan pattern matching
            $bekerja = $modelClass::where(function($q) {
                $q->where('status_kerja', 'like', '%Bekerja%')
                  ->orWhere('status_kerja', '1');
            })->count();
            
            $wirausaha = $modelClass::where(function($q) {
                $q->where('status_kerja', 'like', '%Wirausaha%')
                  ->orWhere('status_kerja', '2');
            })->count();
            
            $studiLanjut = $modelClass::where(function($q) {
                $q->where('status_kerja', 'like', '%Melanjutkan%')
                  ->orWhere('status_kerja', 'like', '%Lanjut%')
                  ->orWhere('status_kerja', '3');
            })->count();

            return [
                'total' => $total,
                'bekerja' => $bekerja,
                'wirausaha' => $wirausaha,
                'studi_lanjut' => $studiLanjut,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting tracer study data: ' . $e->getMessage());
            return [
                'total' => 0,
                'bekerja' => 0,
                'wirausaha' => 0,
                'studi_lanjut' => 0,
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil data aset dari ManajemenAset
     */
    public function getAsetData()
    {
        try {
            $modelClass = 'Modules\ManajemenAset\Models\Aset';
            if (!class_exists($modelClass)) {
                return [
                    'total' => 0,
                    'by_kondisi' => [],
                    'by_kategori' => [],
                    'available' => false
                ];
            }

            $total = $modelClass::count();
            
            $byKondisi = $modelClass::select('kondisi', DB::raw('count(*) as total'))
                ->groupBy('kondisi')
                ->get()
                ->mapWithKeys(fn($item) => [$item->kondisi => $item->total])
                ->toArray();

            $byKategori = $modelClass::select('kategori_id', DB::raw('count(*) as total'))
                ->groupBy('kategori_id')
                ->get()
                ->mapWithKeys(fn($item) => [$item->kategori_id => $item->total])
                ->toArray();

            return [
                'total' => $total,
                'by_kondisi' => $byKondisi,
                'by_kategori' => $byKategori,
                'available' => true
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting aset data: ' . $e->getMessage());
            return [
                'total' => 0,
                'by_kondisi' => [],
                'by_kategori' => [],
                'available' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Ambil semua data terintegrasi
     */
    public function getAllIntegratedData($periodeId = null)
    {
        return [
            'mahasiswa' => $this->getMahasiswaData(null, $periodeId),
            'pegawai' => $this->getPegawaiData($periodeId),
            'prestasi' => $this->getPrestasiData($periodeId),
            'penelitian' => $this->getPenelitianData($periodeId),
            'pengabdian' => $this->getPengabdianData($periodeId),
            'publikasi' => $this->getPublikasiData($periodeId),
            'hki' => $this->getHkiData($periodeId),
            'kerjasama' => $this->getKerjasamaData($periodeId),
            'tracer_study' => $this->getTracerStudyData($periodeId),
            'aset' => $this->getAsetData(),
        ];
    }
}
