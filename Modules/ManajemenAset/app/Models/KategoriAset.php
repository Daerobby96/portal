<?php

namespace Modules\ManajemenAset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAset extends Model
{
    protected $table = 'kategori_aset';
    
    protected $fillable = [
        'kode', 'nama', 'keterangan', 'icon', 'color', 'is_aktif'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    // Relationships
    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'kategori_id');
    }

    // Accessors
    public function getBadgeAttribute(): string
    {
        return '<span class="badge bg-' . $this->color . '"><i class="' . $this->icon . ' me-1"></i>' . $this->nama . '</span>';
    }
}
