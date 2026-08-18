<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peningkatan_standars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('standar_id')->constrained('standars')->cascadeOnDelete();
            $table->foreignId('indikator_kinerja_id')->nullable()->constrained('indikator_kinerjas')->nullOnDelete();
            $table->string('target_lama')->nullable();
            $table->string('capaian_saat_ini')->nullable();
            $table->string('target_baru');
            $table->text('dasar_pertimbangan')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'disetujui_rtm', 'diterapkan'])->default('draft');
            $table->date('tanggal_penetapan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peningkatan_standars');
    }
};
