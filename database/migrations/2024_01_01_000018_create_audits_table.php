<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('ketua_auditor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode_audit', 50)->unique();
            $table->string('nama_audit');
            $table->string('unit_yang_diaudit');
            $table->date('tanggal_audit');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['draft', 'aktif', 'selesai', 'dibatalkan'])->default('draft');
            $table->text('lingkup_audit')->nullable();
            $table->text('tujuan_audit')->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('opening_meeting')->nullable();
            $table->dateTime('closing_meeting')->nullable();
            $table->text('ai_summary')->nullable();

            // Surat Tugas Fields
            $table->string('nomor_surat_tugas', 100)->nullable();
            $table->date('tgl_surat_tugas')->nullable();
            $table->string('penandatangan_surat_tugas', 150)->nullable();
            $table->string('jabatan_penandatangan', 150)->nullable();

            // BAPA Digital Signature
            $table->dateTime('bapa_signed_at_auditor')->nullable();
            $table->dateTime('bapa_signed_at_auditee')->nullable();
            $table->foreignId('bapa_signed_by_auditee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('bapa_catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('audit_auditors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('peran')->default('anggota'); // ketua, anggota
            $table->timestamps();

            $table->unique(['audit_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_auditors');
        Schema::dropIfExists('audits');
    }
};
