<?php

namespace Modules\ManajemenRapat\Models;

use App\Models\User;
use Modules\DataMaster\Models\Periode;
use Modules\Sdm\Models\Pegawai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapatLampiran extends Model
{
    protected $table = 'rapat_lampirans';

    protected $fillable = [
        'rapat_id', 'uploaded_by', 'nama_asli', 'path', 'mime_type', 'ukuran',
    ];

    public function rapat(): BelongsTo
    {
        return $this->belongsTo(Rapat::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUkuranFormatAttribute(): string
    {
        $bytes = $this->ukuran;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getIconAttribute(): string
    {
        return match (true) {
            str_contains($this->mime_type, 'pdf')        => 'bi-file-earmark-pdf text-danger',
            str_contains($this->mime_type, 'word')       => 'bi-file-earmark-word text-primary',
            str_contains($this->mime_type, 'spreadsheet')
                || str_contains($this->mime_type, 'excel') => 'bi-file-earmark-excel text-success',
            str_contains($this->mime_type, 'presentation')
                || str_contains($this->mime_type, 'powerpoint') => 'bi-file-earmark-ppt text-warning',
            str_contains($this->mime_type, 'image')      => 'bi-file-earmark-image text-info',
            default                                      => 'bi-file-earmark text-secondary',
        };
    }
}


