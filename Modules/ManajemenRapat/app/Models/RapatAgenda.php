<?php

namespace Modules\ManajemenRapat\Models;

use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Modules\Sdm\Models\Pegawai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapatAgenda extends Model
{
    protected $table = 'rapat_agendas';

    protected $fillable = [
        'rapat_id', 'urutan', 'judul', 'deskripsi',
        'estimasi_durasi', 'notulensi',
        'notulensi_updated_by', 'notulensi_updated_at',
    ];

    protected $casts = [
        'notulensi_updated_at' => 'datetime',
    ];

    public function rapat(): BelongsTo
    {
        return $this->belongsTo(Rapat::class);
    }

    public function notulensiUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'notulensi_updated_by');
    }
}


