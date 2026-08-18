<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengabdians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('judul');
            $table->integer('tahun');
            $table->string('mitra', 150)->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->string('sumber_dana', 100)->nullable();
            $table->decimal('jumlah_dana', 15, 2)->default(0);
            $table->string('status_kemajuan', 50)->default('Selesai');
            $table->string('file_laporan')->nullable();
            $table->timestamps();
        });

        Schema::create('pengabdian_anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengabdian_id')->constrained('pengabdians')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->string('nama_anggota')->nullable();
            $table->string('peran', 50)->default('Anggota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengabdian_anggotas');
        Schema::dropIfExists('pengabdians');
    }
};
