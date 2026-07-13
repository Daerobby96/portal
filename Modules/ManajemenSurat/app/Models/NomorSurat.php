<?php

namespace Modules\ManajemenSurat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NomorSurat extends Model
{
    protected $table = 'nomor_surat';

    protected $fillable = [
        'jenis_surat_id',
        'tahun',
        'bulan',
        'nomor_urut',
    ];

    protected $casts = [
        'nomor_urut' => 'integer',
    ];

    /**
     * Get the jenis surat
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
}
