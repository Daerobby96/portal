<?php

namespace Modules\Sdm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Traits\Loggable;

class Lembur extends Model
{
    use Loggable;

    protected $table = 'lembur';

    protected $fillable = [
        'pegawai_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'jumlah_jam', 'keperluan', 'file_pendukung',
        'status', 'approved_by', 'approved_at', 'catatan_approval',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_jam' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

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
}
