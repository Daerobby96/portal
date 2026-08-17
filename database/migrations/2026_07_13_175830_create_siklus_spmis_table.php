<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siklus_spmis', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // "Siklus Mutu 2024/2025"
            $table->year('tahun_siklus');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['persiapan', 'berjalan', 'evaluasi', 'ditutup'])->default('persiapan');
            $table->text('deskripsi')->nullable();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_aktif')->default(false);
            $table->json('snapshot_ppepp')->nullable(); // Store final PPEPP scores when cycle is closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siklus_spmis');
    }
};
