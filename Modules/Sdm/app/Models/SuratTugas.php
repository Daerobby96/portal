<?php

namespace Modules\Sdm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Traits\Loggable;

class SuratTugas extends Model
{
    use Loggable;

    protected $table = 'surat_tugas';

    protected $fillable = [
        'nomor_surat', 'perihal', 'keperluan',
        'tanggal_mulai', 'tanggal_selesai', 'tempat_tujuan',
        'jenis', 'anggaran', 'sumber_dana', 'catatan',
        'file_surat', 'created_by', 'approved_by', 'approved_at', 'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'anggaran' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public static function jenisOptions(): array
    {
        return [
            'dinas_luar' => 'Dinas Luar',
            'perjalanan_dinas' => 'Perjalanan Dinas',
            'tugas_khusus' => 'Tugas Khusus',
            'pelatihan' => 'Pelatihan',
            'seminar' => 'Seminar/Konferensi',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'selesai' => 'Selesai',
        ];
    }

    // Relationships
    public function pegawais(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'surat_tugas_pegawai')
            ->withPivot('peran')
            ->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'selesai' => '<span class="badge bg-info">Selesai</span>',
            default => '<span class="badge bg-light text-dark">' . $this->status . '</span>',
        };
    }

    public function getJenisBadgeAttribute(): string
    {
        return match ($this->jenis) {
            'dinas_luar' => '<span class="badge bg-primary">Dinas Luar</span>',
            'perjalanan_dinas' => '<span class="badge bg-info">Perjalanan Dinas</span>',
            'tugas_khusus' => '<span class="badge bg-warning">Tugas Khusus</span>',
            'pelatihan' => '<span class="badge bg-success">Pelatihan</span>',
            'seminar' => '<span class="badge bg-danger">Seminar</span>',
            default => '<span class="badge bg-secondary">' . $this->jenis . '</span>',
        };
    }

    public function getDurasiAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}
