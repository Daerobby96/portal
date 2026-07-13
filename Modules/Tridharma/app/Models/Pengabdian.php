<?php

namespace Modules\Tridharma\Models;

use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengabdian extends Model
{
    protected $fillable = [
        'pegawai_id', 'judul', 'tahun', 'mitra', 'lokasi',
        'sumber_dana', 'jumlah_dana', 'anggota', 'prodi_id'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah_dana' => 'decimal:2',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }
}
