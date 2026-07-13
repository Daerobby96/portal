<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke penulis utama / koresponden (pegawai)
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
            
            // Detail publikasi
            $table->string('judul', 500);
            $table->unsignedSmallInteger('tahun');
            
            // Jenis Publikasi
            $table->enum('jenis', [
                'Jurnal Nasional',
                'Jurnal Internasional',
                'Prosiding',
                'Buku',
                'HKI',
                'Lainnya'
            ])->default('Jurnal Nasional');
            
            $table->string('nama_jurnal_penerbit', 255)->nullable();
            $table->string('volume_nomor', 100)->nullable();
            $table->string('url_tautan', 500)->nullable();
            
            // Sinta / Scopus (optional)
            $table->string('tingkat_sinta', 20)->nullable();
            
            // Relasi ke prodi
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasis');
    }
};
