<?php

namespace Modules\Spmi\Models;

use Modules\DataMaster\Models\Periode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IkuDataInput extends Model
{
    protected $table = 'iku_data_input';
    
    protected $fillable = [
        'iku_resmi_id',
        'periode_id',
        'triwulan',
        'kategori',
        'nilai_input',
        'bobot',
        'metadata',
        'keterangan',
    ];
    
    protected $casts = [
        'nilai_input' => 'decimal:2',
        'bobot' => 'decimal:2',
        'metadata' => 'array',
    ];
    
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
    public function getNilaiTertimbangAttribute(): float
    {
        return $this->nilai_input * ($this->bobot ?? 1);
    }
}

