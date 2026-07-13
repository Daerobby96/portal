<?php

namespace Modules\Kerjasama\Models;

use Modules\DataMaster\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kerjasama extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_mitra', 'jenis_mitra', 'tingkat', 'judul_kerjasama',
        'jenis_dokumen', 'tanggal_mulai', 'tanggal_selesai', 'dokumen_mou', 'prodi_id',
        'status', 'keterangan'
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public const JENIS_MITRA = [
        'Perguruan Tinggi',
        'Instansi Pemerintah',
        'Perusahaan/DUDI',
        'Lembaga Swadaya',
        'Organisasi Internasional',
        'Lainnya'
    ];

    public const TINGKAT = [
        'Lokal',
        'Nasional',
        'Internasional'
    ];

    public const STATUS = [
        'Draft',
        'Aktif',
        'Selesai',
        'Kedaluwarsa'
    ];
    
    public const JENIS_DOKUMEN = [
        'MoU',
        'MoA',
        'IA',
        'Lainnya'
    ];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function evaluasiMitras()
    {
        return $this->hasMany(EvaluasiMitra::class, 'kerjasama_id');
    }

    public function isExpiring(): bool
    {
        if ($this->status !== 'Aktif' || !$this->tanggal_selesai) {
            return false;
        }
        
        return $this->tanggal_selesai->diffInDays(now()) <= 60 && $this->tanggal_selesai >= now();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Draft'       => '<span class="badge bg-secondary">Draft</span>',
            'Aktif'       => '<span class="badge bg-success">Aktif</span>',
            'Selesai'     => '<span class="badge bg-primary">Selesai</span>',
            'Kedaluwarsa' => '<span class="badge bg-danger">Kedaluwarsa</span>',
            default       => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }

    public function getTingkatBadgeAttribute(): string
    {
        return match ($this->tingkat) {
            'Internasional' => '<span class="badge bg-warning text-dark"><i class="bi bi-globe-americas me-1"></i>Internasional</span>',
            'Nasional'      => '<span class="badge bg-info"><i class="bi bi-flag-fill me-1"></i>Nasional</span>',
            'Lokal'         => '<span class="badge bg-secondary"><i class="bi bi-geo-alt-fill me-1"></i>Lokal</span>',
            default         => '<span class="badge bg-light text-dark">' . $this->tingkat . '</span>',
        };
    }
}
