<?php

namespace Modules\DataMaster\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Sdm\Models\Pegawai;

class Jabatan extends Model
{
    protected $table = 'jabatans';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'level_hirarki',
        'tunjangan_dasar',
        'deskripsi',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'tunjangan_dasar' => 'decimal:2',
        'level_hirarki' => 'integer',
    ];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'jabatan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
