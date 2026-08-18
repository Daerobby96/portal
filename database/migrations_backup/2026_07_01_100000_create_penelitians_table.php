<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penelitians', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke dosen ketua (pegawai)
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
            
            // Detail penelitian
            $table->string('judul', 500);
            $table->unsignedSmallInteger('tahun');
            $table->string('sumber_dana', 255)->nullable();
            $table->decimal('jumlah_dana', 15, 2)->nullable();
            
            // Tingkat: Lokal, Nasional, Internasional
            $table->enum('tingkat', ['Lokal', 'Nasional', 'Internasional'])->default('Lokal');
            
            // Anggota (Text / JSON untuk kesederhanaan sementara)
            $table->text('anggota')->nullable();
            
            // Status penelitian
            $table->enum('status', ['Usulan', 'Berjalan', 'Selesai'])->default('Selesai');
            
            // Relasi ke prodi
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitians');
    }
};
