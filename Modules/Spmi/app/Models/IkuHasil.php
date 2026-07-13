<?php

namespace Modules\Spmi\Models;

use Modules\DataMaster\Models\Periode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IkuHasil extends Model
{
    protected $table = 'iku_hasil';
    
    protected $fillable = [
        'iku_resmi_id',
        'periode_id',
        'triwulan',
        'target',
        'nilai_hasil',
        'persentase_capaian',
        'gap',
        'status_capaian',
        'catatan',
        'calculated_at',
    ];
    
    protected $casts = [
        'target' => 'decimal:2',
        'nilai_hasil' => 'decimal:2',
        'persentase_capaian' => 'decimal:2',
        'gap' => 'decimal:2',
        'calculated_at' => 'datetime',
    ];
    
    // ─── Constants ────────────────────────────────────────────────
    const STATUS_TERCAPAI = 'Tercapai';
    const STATUS_TIDAK_TERCAPAI = 'Tidak Tercapai';
    const STATUS_DALAM_PROGRESS = 'Dalam Progress';
    const STATUS_BELUM_DIHITUNG = 'Belum Dihitung';
    
    const TRIWULAN_1 = 'TW1';
    const TRIWULAN_2 = 'TW2';
    const TRIWULAN_3 = 'TW3';
    const TRIWULAN_4 = 'TW4';
    const TAHUNAN = 'TAHUNAN';
    
    public static function triwulanOptions(): array
    {
        return [
            self::TRIWULAN_1 => 'Triwulan 1 (Januari - Maret)',
            self::TRIWULAN_2 => 'Triwulan 2 (April - Juni)',
            self::TRIWULAN_3 => 'Triwulan 3 (Juli - September)',
            self::TRIWULAN_4 => 'Triwulan 4 (Oktober - Desember)',
            self::TAHUNAN => 'Tahunan (Akumulasi)',
        ];
    }
    
    // ─── Relationships ─────────────────────────────────────────────
    public function ikuResmi(): BelongsTo
    {
        return $this->belongsTo(IkuResmi::class);
    }
    
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
    
    // ─── Accessors ────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_capaian) {
            self::STATUS_TERCAPAI => '<span class="badge bg-success">Tercapai</span>',
            self::STATUS_TIDAK_TERCAPAI => '<span class="badge bg-danger">Tidak Tercapai</span>',
            self::STATUS_DALAM_PROGRESS => '<span class="badge bg-warning">Dalam Progress</span>',
            self::STATUS_BELUM_DIHITUNG => '<span class="badge bg-secondary">Belum Dihitung</span>',
            default => '<span class="badge bg-light text-dark">-</span>',
        };
    }
    
    public function getNilaiFormatAttribute(): string
    {
        $nilai = number_format($this->nilai_hasil, 2, ',', '.');
        $satuan = $this->ikuResmi->satuan ?? '';
        
        if ($satuan === '%') {
            return $nilai . '%';
        } elseif ($satuan) {
            return $nilai . ' ' . $satuan;
        }
        
        return $nilai;
    }
    
    public function getCapaianColorAttribute(): string
    {
        if ($this->persentase_capaian >= 100) return 'success';
        if ($this->persentase_capaian >= 80) return 'info';
        if ($this->persentase_capaian >= 60) return 'warning';
        return 'danger';
    }
    
    public function getGapStatusAttribute(): string
    {
        if ($this->gap > 0) return '<span class="text-success">+' . number_format($this->gap, 2) . ' (Melebihi Target)</span>';
        if ($this->gap < 0) return '<span class="text-danger">' . number_format($this->gap, 2) . ' (Di Bawah Target)</span>';
        return '<span class="text-muted">Tepat Target</span>';
    }
}

