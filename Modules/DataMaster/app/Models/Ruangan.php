<?php

namespace Modules\DataMaster\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = [
        'prodi_id',
        'kode_ruangan',
        'nama_ruangan',
        'jenis',
        'gedung',
        'lantai',
        'kapasitas',
        'luas',
        'kondisi',
        'status',
        'ber_ac',
        'ber_proyektor',
        'penanggung_jawab',
        'fasilitas',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'ber_ac' => 'boolean',
        'ber_proyektor' => 'boolean',
        'kapasitas' => 'integer',
        'luas' => 'decimal:2',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }
}
