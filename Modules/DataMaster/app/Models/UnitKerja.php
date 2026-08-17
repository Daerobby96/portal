<?php

namespace Modules\DataMaster\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Sdm\Models\Pegawai;

class UnitKerja extends Model
{
    protected $table = 'unit_kerjas';

    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'kepala_unit',
        'lokasi',
        'deskripsi',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'unit_kerja_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
