<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\DataMaster\Models\ProgramStudi;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, \App\Traits\Loggable;

    protected $fillable = [
        'name', 'nip', 'email', 'unit_kerja',
        'jabatan', 'no_hp', 'foto', 'is_active', 'password', 'prodi_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'ketua_auditor_id');
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'pembuat_id');
    }

    // ─── Role Helpers (Spatie-backed) ──────────────────────────────
    public function isSuperAdmin(): bool  { return $this->hasRole('super_admin'); }
    public function isAuditor(): bool     { return $this->hasRole('auditor'); }
    public function isAuditee(): bool     { return $this->hasRole('auditee'); }
    public function isPimpinan(): bool    { return $this->hasRole('pimpinan'); }
    public function isStafDokumen(): bool { return $this->hasRole('staff'); }
    public function isKaprodi(): bool     { return $this->hasRole('kaprodi'); }

    public function canManageAudit(): bool
    {
        return $this->hasAnyRole(['super_admin', 'auditor']);
    }

    public function canManageDokumen(): bool
    {
        return $this->hasAnyRole(['super_admin', 'staff']);
    }

    // ─── Module Access Helpers ─────────────────────────────────────
    /**
     * Check if user has access to a given module.
     * Usage: auth()->user()->canAccessModule('data_akademik')
     */
    public function canAccessModule(string $module): bool
    {
        if ($this->isSuperAdmin()) return true;
        return $this->hasPermissionTo('access_' . $module);
    }

    // ─── Accessor ──────────────────────────────────────────────────
    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }

    /**
     * Get the first role for convenience.
     * This is a helper to access roles()->first() more easily.
     */
    public function getPrimaryRoleAttribute()
    {
        return $this->roles->first();
    }
}
