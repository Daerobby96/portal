<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('benchmarkings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->string('nama_mitra'); // e.g. Politeknik Negeri Bandung, UI, NUS
            $table->enum('tingkat', ['Lokal', 'Nasional', 'Internasional'])->default('Nasional');
            $table->string('bidang_standar'); // e.g. Standar Kurikulum OBE, Standar Riset & Paten, Standar SPMI
            $table->date('tanggal_kegiatan');
            $table->text('capaian_institusi')->nullable();
            $table->text('capaian_mitra')->nullable();
            $table->text('gap_analisis')->nullable();
            $table->text('best_practice_diadopsi')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->enum('status', ['Perencanaan', 'Terlaksana', 'Diimplementasikan'])->default('Terlaksana');
            $table->string('pic_nama')->nullable();
            $table->string('file_laporan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benchmarkings');
    }
};
