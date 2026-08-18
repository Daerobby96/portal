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
        Schema::create('peningkatan_standars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('standar_id')->constrained('standars')->cascadeOnDelete();
            $table->foreignId('indikator_kinerja_id')->nullable()->constrained('indikator_kinerjas')->nullOnDelete();
            $table->string('target_lama');
            $table->string('capaian_saat_ini');
            $table->string('target_baru');
            $table->text('dasar_pertimbangan')->nullable(); // RTM, Rekomendasi AMI, Regulasi SN-Dikti baru
            $table->text('strategi_pencapaian')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'diterapkan'])->default('draft');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_persetujuan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peningkatan_standars');
    }
};
