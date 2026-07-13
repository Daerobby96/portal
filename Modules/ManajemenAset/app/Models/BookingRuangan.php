<?php

namespace Modules\ManajemenAset\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRuangan extends Model
{
    protected $table = 'booking_ruangan';
    
    protected $fillable = [
        'ruangan_id', 'pemohon_id', 'approval_by', 'keperluan', 'deskripsi',
        'tanggal', 'jam_mulai', 'jam_selesai', 'jumlah_peserta',
        'status', 'catatan_pemohon', 'catatan_approval'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-secondary">Pending</span>',
            'disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'selesai' => '<span class="badge bg-info">Selesai</span>',
            'dibatalkan' => '<span class="badge bg-warning">Dibatalkan</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getWaktuAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }
}
