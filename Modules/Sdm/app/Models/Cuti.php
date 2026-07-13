<?php

namespace Modules\Sdm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Traits\Loggable;

class Cuti extends Model
{
    use Loggable;

    protected $table = 'cuti';

    protected $fillable = [
        'pegawai_id', 'jenis_cuti', 'tanggal_mulai', 'tanggal_selesai',
        'jumlah_hari', 'alasan', 'file_pendukung',
        'status', 'approved_by', 'approved_at', 'catatan_approval',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime',
    ];

    public static function jenisOptions(): array
    {
        return [
            'tahunan' => 'Cuti Tahunan',
            'sakit' => 'Cuti Sakit',
            'melahirkan' => 'Cuti Melahirkan',
            'besar' => 'Cuti Besar',
            'alasan_penting' => 'Cuti Alasan Penting',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
    }

    // Relationships
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }

    public function getJenisBadgeAttribute(): string
    {
        return match ($this->jenis_cuti) {
            'tahunan' => '<span class="badge bg-primary">Tahunan</span>',
            'sakit' => '<span class="badge bg-info">Sakit</span>',
            'melahirkan' => '<span class="badge bg-success">Melahirkan</span>',
            'besar' => '<span class="badge bg-warning">Besar</span>',
            'alasan_penting' => '<span class="badge bg-danger">Alasan Penting</span>',
            default => '<span class="badge bg-secondary">' . $this->jenis_cuti . '</span>',
        };
    }
}
