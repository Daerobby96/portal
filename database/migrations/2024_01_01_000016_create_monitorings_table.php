<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('indikator_id')->constrained('indikator_kinerjas')->cascadeOnDelete();
            $table->foreignId('pelapor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->decimal('nilai_capaian', 12, 2)->nullable();
            $table->text('capaian_deskripsi')->nullable();
            $table->date('tanggal_input');
            $table->text('keterangan')->nullable();
            $table->string('bukti_dukung')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
