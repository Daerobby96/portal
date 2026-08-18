<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benchmarkings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->string('nama_mitra', 150);
            $table->string('tingkat', 50)->default('Nasional'); // Nasional, Internasional
            $table->string('bidang_standar', 100);
            $table->date('tanggal_kegiatan');
            $table->text('aspek_dipelajari')->nullable();
            $table->text('best_practice')->nullable();
            $table->text('rencana_adopsi')->nullable();
            $table->string('pic_pelaksana', 100)->nullable();
            $table->string('file_laporan')->nullable();
            $table->enum('status_adopsi', ['direncanakan', 'dalam_proses', 'teradopsi'])->default('direncanakan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benchmarkings');
    }
};
