<?php

namespace Modules\ManajemenAset\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    
    protected $fillable = [
        'aset_id', 'peminjam_id', 'approval_by', 'keperluan',
        'tanggal_pinjam', 'tanggal_kembali_rencana', 'tanggal_kembali_aktual',
        'status', 'catatan_peminjam', 'catatan_approval',
        'kondisi_saat_pinjam', 'kondisi_saat_kembali', 'denda'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
        'tanggal_kembali_aktual' => 'datetime',
        'denda' => 'decimal:2',
    ];

    // Relationships
    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
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
            'dipinjam' => '<span class="badge bg-primary">Dipinjam</span>',
            'dikembalikan' => '<span class="badge bg-info">Dikembalikan</span>',
            'terlambat' => '<span class="badge bg-warning">Terlambat</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getIsTerlambatAttribute(): bool
    {
        if ($this->status === 'dipinjam' && $this->tanggal_kembali_rencana) {
            return now()->isAfter($this->tanggal_kembali_rencana);
        }
        return false;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }
}
