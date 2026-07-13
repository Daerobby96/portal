<?php

namespace Modules\Tridharma\Models;

use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publikasi extends Model
{
    protected $fillable = [
        'pegawai_id', 'judul', 'tahun', 'jenis',
        'nama_jurnal_penerbit', 'volume_nomor', 'url_tautan',
        'tingkat_sinta', 'prodi_id'
    ];

    protected $casts = [
        'tahun' => 'integer',
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
