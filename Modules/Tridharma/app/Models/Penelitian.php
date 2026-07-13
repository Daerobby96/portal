<?php

namespace Modules\Tridharma\Models;

use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penelitian extends Model
{
    protected $fillable = [
        'pegawai_id', 'judul', 'tahun', 'sumber_dana', 'jumlah_dana',
        'tingkat', 'anggota', 'status', 'prodi_id'
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

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Usulan'   => '<span class="badge bg-warning text-dark">Usulan</span>',
            'Berjalan' => '<span class="badge bg-primary">Berjalan</span>',
            'Selesai'  => '<span class="badge bg-success">Selesai</span>',
            default    => '<span class="badge bg-secondary">-</span>',
        };
    }
}
