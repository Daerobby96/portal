<?php

namespace Modules\DataMaster\Models;

use App\Models\Audit;
use Modules\Spmi\Models\Monitoring;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    protected $fillable = [
        'nama', 'tahun', 'semester',
        'tanggal_mulai', 'tanggal_selesai',
        'is_aktif', 'keterangan', 'siklus_spmi_id',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif'        => 'boolean',
    ];

    public function siklus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Spmi\Models\SiklusSpmi::class, 'siklus_spmi_id');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }

    public static function aktif(): ?self
    {
        return self::where('is_aktif', true)->first();
    }

    public function getPpeppProgressAttribute(): array
    {
        $totalIndikator = \Modules\Spmi\Models\IndikatorKinerja::where('is_aktif', true)->count();
        $totalStandar = \Modules\Spmi\Models\Standar::count();
        
        // 1. Penetapan: Persentase standar yang telah memiliki minimal 1 dokumen yang disetujui (approved)
        $standarDenganDokumen = \Modules\Spmi\Models\Standar::whereHas('dokumens', function ($q) {
            $q->where('status', 'approved');
        })->count();
        $penetapan = $totalStandar > 0 ? min(100, round(($standarDenganDokumen / $totalStandar) * 100)) : 0;
        
        // 2. Pelaksanaan
        $totalMonitoring = \Modules\Spmi\Models\Monitoring::where('periode_id', $this->id)->count();
        $pelaksanaan = $totalIndikator > 0 ? min(100, round(($totalMonitoring / $totalIndikator) * 100)) : 0;
        
        // 3. Evaluasi
        $totalAudit = \Modules\Spmi\Models\Audit::where('periode_id', $this->id)->count();
        $completedAudit = \Modules\Spmi\Models\Audit::where('periode_id', $this->id)->where('status', 'selesai')->count();
        $evaluasi = $totalAudit > 0 ? min(100, round(($completedAudit / $totalAudit) * 100)) : 0;
        
        // 4. Pengendalian
        $totalTemuan = \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $this->id))->count();
        $resolvedTemuan = \Modules\Spmi\Models\Temuan::whereHas('audit', fn($q) => $q->where('periode_id', $this->id))->whereIn('status', ['closed', 'verified'])->count();
        
        if ($totalTemuan > 0) {
            $pengendalian = min(100, round(($resolvedTemuan / $totalTemuan) * 100));
        } else {
            // Jika tidak ada temuan, dan sudah dilakukan evaluasi, maka pengendalian 100% (sukses tanpa cacat)
            $pengendalian = $evaluasi >= 100 ? 100 : 0;
        }
        
        // 5. Peningkatan
        $rtmSelesai = \Modules\Spmi\Models\RTM::where('periode_id', $this->id)->where('status', 'selesai')->count();
        $peningkatan = $rtmSelesai > 0 ? 100 : 0;

        $overall = round(($penetapan + $pelaksanaan + $evaluasi + $pengendalian + $peningkatan) / 5);

        return [
            'penetapan' => $penetapan,
            'pelaksanaan' => $pelaksanaan,
            'evaluasi' => $evaluasi,
            'pengendalian' => $pengendalian,
            'peningkatan' => $peningkatan,
            'overall' => $overall,
            'is_loop_closed' => ($overall >= 100),
        ];
    }
}
