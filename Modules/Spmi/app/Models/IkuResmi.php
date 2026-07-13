<?php

namespace Modules\Spmi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IkuResmi extends Model
{
    protected $table = 'iku_resmi';
    
    protected $fillable = [
        'nomor_iku',
        'nama',
        'sifat',
        'formula',
        'satuan',
        'target_default',
        'deskripsi_target',
        'deskripsi',
        'referensi',
        'sheet_name',
        'is_aktif',
    ];
    
    protected $casts = [
        'is_aktif' => 'boolean',
    ];
    
    // ─── Constants ────────────────────────────────────────────────
    const SIFAT_WAJIB = 'WAJIB';
    const SIFAT_PILIHAN = 'PILIHAN';
    const SIFAT_WAJIB_PTN_BH = 'WAJIB PTN-BH';
    const SIFAT_PILIHAN_PTN = 'PILIHAN PTN';
    
    public static function sifatOptions(): array
    {
        return [
            self::SIFAT_WAJIB => 'WAJIB',
            self::SIFAT_PILIHAN => 'PILIHAN',
            self::SIFAT_WAJIB_PTN_BH => 'WAJIB PTN-BH',
            self::SIFAT_PILIHAN_PTN => 'PILIHAN PTN',
        ];
    }
    
    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
    
    public function scopeWajib($query)
    {
        return $query->where('sifat', self::SIFAT_WAJIB);
    }
    
    public function scopePilihan($query)
    {
        return $query->whereIn('sifat', [self::SIFAT_PILIHAN, self::SIFAT_PILIHAN_PTN]);
    }
    
    // ─── Accessors ────────────────────────────────────────────────
    public function getSifatBadgeAttribute(): string
    {
        return match ($this->sifat) {
            self::SIFAT_WAJIB => '<span class="badge bg-danger">WAJIB</span>',
            self::SIFAT_WAJIB_PTN_BH => '<span class="badge bg-danger">WAJIB PTN-BH</span>',
            self::SIFAT_PILIHAN => '<span class="badge bg-info">PILIHAN</span>',
            self::SIFAT_PILIHAN_PTN => '<span class="badge bg-info">PILIHAN PTN</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
    
    public function getNomorShortAttribute(): string
    {
        return str_replace('IKU', '', $this->nomor_iku);
    }
    
    // ─── Relationships ─────────────────────────────────────────────
    public function dataInputs(): HasMany
    {
        return $this->hasMany(IkuDataInput::class);
    }
    
    public function hasil(): HasMany
    {
        return $this->hasMany(IkuHasil::class);
    }
    
    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class);
    }
    
    // ─── Helper Methods ────────────────────────────────────────────
    public function getHasilByPeriode($periodeId)
    {
        return $this->hasil()->where('periode_id', $periodeId)->first();
    }
    
    public function getHasilByPeriodeTriwulan($periodeId, $triwulan = 'TAHUNAN')
    {
        return $this->hasil()
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->first();
    }
    
    public function getDataInputsByPeriode($periodeId)
    {
        return $this->dataInputs()->where('periode_id', $periodeId)->get();
    }
    
    public function getDataInputsByPeriodeTriwulan($periodeId, $triwulan = 'TAHUNAN')
    {
        return $this->dataInputs()
            ->where('periode_id', $periodeId)
            ->where('triwulan', $triwulan)
            ->get();
    }
}
