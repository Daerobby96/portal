<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->foreignId('audit_checklist_id')->nullable()->constrained('audit_checklists')->nullOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode_temuan', 50)->unique();
            $table->enum('kategori', ['KTS_Mayor', 'KTS_Minor', 'OB', 'Observasi', 'Rekomendasi'])->default('KTS_Minor');
            $table->string('klausul_standar')->nullable();
            $table->text('uraian_temuan');
            $table->text('akar_penyebab')->nullable();
            $table->text('bukti_objektif')->nullable();
            $table->string('file_bukti')->nullable();
            $table->date('batas_tindak_lanjut')->nullable();
            $table->enum('status', ['open', 'in_progress', 'closed', 'verified', 'proses', 'selesai'])->default('open');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temuan_id')->constrained('temuans')->cascadeOnDelete();
            $table->foreignId('penanggung_jawab_id')->constrained('users')->cascadeOnDelete();
            $table->text('analisa_penyebab')->nullable();
            $table->text('rencana_tindakan')->nullable();
            $table->string('bukti_tindakan')->nullable();
            $table->date('target_selesai')->nullable();
            $table->date('tanggal_realisasi')->nullable();
            $table->enum('status', ['pending', 'proses', 'selesai', 'terverifikasi'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_verifikasi')->nullable();
            $table->enum('hasil_verifikasi', ['diterima', 'ditolak', 'revisi'])->nullable();
            $table->text('verifikasi_auditor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
        Schema::dropIfExists('temuans');
    }
};
