<?php

namespace Modules\ManajemenSurat\Services;

use Modules\ManajemenSurat\Models\JenisSurat;
use Modules\ManajemenSurat\Models\NomorSurat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NomorSuratService
{
    /**
     * Generate nomor surat otomatis dengan support unit pengelola
     * Format: XXX/KODE-JENIS/KODE-UNIT/MM/YYYY
     * 
     * @param int $jenisSuratId
     * @param int|null $unitId ID unit pengelola surat
     * @param Carbon|null $tanggal Tanggal surat (default: hari ini)
     * @return string
     */
    public function generateNomorSurat(int $jenisSuratId, ?int $unitId = null, ?Carbon $tanggal = null): string
    {
        $tanggal = $tanggal ?? Carbon::now();
        $tahun = $tanggal->format('Y');
        $bulan = (int) $tanggal->format('m');

        // Get jenis surat
        $jenisSurat = JenisSurat::findOrFail($jenisSuratId);

        // Get unit pengelola if provided
        $unit = null;
        if ($unitId) {
            $unit = \Modules\ManajemenSurat\Models\UnitPengelolaSurat::findOrFail($unitId);
        }

        // Get or create nomor urut (per jenis + unit + tahun + bulan)
        $nomorUrut = $this->getNextNomorUrut($jenisSuratId, $unitId, $tahun, $bulan);

        // Jika ada unit dengan custom format
        if ($unit && $unit->prefix_format) {
            return $unit->getFormatNomorSurat($jenisSurat->kode, $nomorUrut, $bulan, $tahun);
        }

        // Format default
        $components = [
            str_pad($nomorUrut, 3, '0', STR_PAD_LEFT), // Nomor urut (3 digit)
            $jenisSurat->kode,                          // Kode jenis surat
        ];

        // Add unit kode if provided
        if ($unit) {
            $components[] = $unit->kode;
        }

        $components[] = str_pad($bulan, 2, '0', STR_PAD_LEFT);  // Bulan
        $components[] = $tahun;  // Tahun

        return implode('/', $components);
    }

    /**
     * Get next nomor urut for jenis surat (dengan support unit)
     * 
     * @param int $jenisSuratId
     * @param int|null $unitId
     * @param string $tahun
     * @param int $bulan
     * @return int
     */
    protected function getNextNomorUrut(int $jenisSuratId, ?int $unitId, string $tahun, int $bulan): int
    {
        return DB::transaction(function () use ($jenisSuratId, $unitId, $tahun, $bulan) {
            $query = NomorSurat::lockForUpdate()
                ->where('jenis_surat_id', $jenisSuratId)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan);

            // Filter by unit if provided
            if ($unitId) {
                $query->where('unit_id', $unitId);
            } else {
                $query->whereNull('unit_id');
            }

            $nomorSurat = $query->first();

            if (!$nomorSurat) {
                // Create new record
                $nomorSurat = NomorSurat::create([
                    'jenis_surat_id' => $jenisSuratId,
                    'unit_id' => $unitId,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'nomor_urut' => 1,
                ]);
                
                return 1;
            }

            // Increment nomor urut
            $nomorSurat->increment('nomor_urut');
            
            return $nomorSurat->nomor_urut;
        });
    }

    /**
     * Generate nomor agenda untuk surat masuk
     * Format: XXX/SM/MM/YYYY
     * 
     * @param Carbon|null $tanggal
     * @return string
     */
    public function generateNomorAgenda(?Carbon $tanggal = null): string
    {
        $tanggal = $tanggal ?? Carbon::now();
        $tahun = $tanggal->format('Y');
        $bulan = $tanggal->format('m');

        // Get next nomor urut (menggunakan ID khusus untuk agenda surat masuk)
        $nomorUrut = $this->getNextAgendaNumber($tahun, $bulan);

        return sprintf(
            '%03d/SM/%s/%s',
            $nomorUrut,
            $bulan,
            $tahun
        );
    }

    /**
     * Get next agenda number
     * 
     * @param string $tahun
     * @param string $bulan
     * @return int
     */
    protected function getNextAgendaNumber(string $tahun, string $bulan): int
    {
        return DB::transaction(function () use ($tahun, $bulan) {
            // Find jenis surat for agenda (using special code)
            $agendaJenis = JenisSurat::firstOrCreate(
                ['kode' => 'AGENDA-SM'],
                [
                    'nama' => 'Agenda Surat Masuk',
                    'kategori' => 'masuk',
                    'keterangan' => 'Untuk penomoran agenda surat masuk',
                    'is_active' => false, // Hidden jenis
                ]
            );

            return $this->getNextNomorUrut($agendaJenis->id, $tahun, $bulan);
        });
    }

    /**
     * Parse nomor surat to get components
     * 
     * @param string $nomorSurat
     * @return array
     */
    public function parseNomorSurat(string $nomorSurat): array
    {
        $parts = explode('/', $nomorSurat);
        
        return [
            'nomor_urut' => (int) ($parts[0] ?? 0),
            'kode_jenis' => $parts[1] ?? '',
            'unit_kode' => count($parts) > 4 ? $parts[2] : null,
            'bulan' => $parts[count($parts) - 2] ?? '',
            'tahun' => $parts[count($parts) - 1] ?? '',
        ];
    }

    /**
     * Validate format nomor surat
     * 
     * @param string $nomorSurat
     * @return bool
     */
    public function isValidFormat(string $nomorSurat): bool
    {
        // Basic validation: must have at least 4 parts separated by /
        $parts = explode('/', $nomorSurat);
        
        if (count($parts) < 4) {
            return false;
        }

        // First part should be numeric
        if (!is_numeric($parts[0])) {
            return false;
        }

        // Last two parts should be numeric (month and year)
        $bulan = $parts[count($parts) - 2];
        $tahun = $parts[count($parts) - 1];

        return is_numeric($bulan) && is_numeric($tahun) && 
               strlen($bulan) === 2 && strlen($tahun) === 4;
    }
}
