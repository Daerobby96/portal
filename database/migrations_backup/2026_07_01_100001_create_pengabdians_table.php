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
            
            // Relasi ke dosen ketua (pegawai)
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
            
            // Detail PkM
            $table->string('judul', 500);
            $table->unsignedSmallInteger('tahun');
            $table->string('mitra', 255)->nullable();
            $table->string('lokasi', 255)->nullable();
            $table->string('sumber_dana', 255)->nullable();
            $table->decimal('jumlah_dana', 15, 2)->nullable();
            
            // Anggota (Text / JSON)
            $table->text('anggota')->nullable();
            
            // Relasi ke prodi
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengabdians');
    }
};
