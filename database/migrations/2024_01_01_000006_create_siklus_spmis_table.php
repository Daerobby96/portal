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
            $table->string('nama');
            $table->integer('tahun_siklus');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'evaluasi', 'pengendalian', 'peningkatan', 'selesai'])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->json('target_capaian')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siklus_spmis');
    }
};
