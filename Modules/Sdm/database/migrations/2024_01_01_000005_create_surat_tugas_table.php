<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('perihal');
            $table->text('keperluan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('tempat_tujuan');
            $table->enum('jenis', ['dinas_luar', 'perjalanan_dinas', 'tugas_khusus', 'pelatihan', 'seminar'])->default('dinas_luar');
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->string('sumber_dana')->nullable();
            $table->text('catatan')->nullable();
            $table->string('file_surat')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'selesai'])->default('draft');
            $table->timestamps();

            $table->index('nomor_surat');
            $table->index('status');
            $table->index('tanggal_mulai');
        });

        // Pivot table untuk pegawai yang ditugaskan
        Schema::create('surat_tugas_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->enum('peran', ['ketua', 'anggota', 'penanggung_jawab'])->default('anggota');
            $table->timestamps();

            $table->unique(['surat_tugas_id', 'pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_pegawai');
        Schema::dropIfExists('surat_tugas');
    }
};
