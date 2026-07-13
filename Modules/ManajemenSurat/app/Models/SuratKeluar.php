<?php

namespace Modules\ManajemenSurat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class SuratKeluar extends Model
{
    use SoftDeletes;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'jenis_surat_id',
        'unit_id',
        'nomor_surat',
        'nomor_agenda',
        'perihal',
        'isi_surat',
        'tanggal_surat',
        'tujuan',
        'alamat_tujuan',
        'penandatangan_nama',
        'penandatangan_jabatan',
        'penandatangan_nip',
        'jumlah_lampiran',
        'keterangan_lampiran',
        'file_path',
        'status',
        'catatan',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_at' => 'datetime',
        'jumlah_lampiran' => 'integer',
    ];

    /**
     * Get the jenis surat
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Get the unit pengelola
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitPengelolaSurat::class, 'unit_id');
    }

    /**
     * Get the creator user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the approver user
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan jenis surat
     */
    public function scopeJenis($query, $jenisId)
    {
        return $query->where('jenis_surat_id', $jenisId);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_surat', $tahun);
    }

    /**
     * Scope untuk surat yang butuh approval
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if surat is editable
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Check if surat can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }
}
