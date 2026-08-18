<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->nullable()->constrained('mahasiswas')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('nama_kegiatan');
            $table->string('jenis_prestasi', 50)->default('Akademik'); // Akademik, Non-Akademik
            $table->string('tingkat', 50)->default('Nasional'); // Wilayah, Nasional, Internasional
            $table->string('capaian', 50)->default('Juara 1'); // Juara 1, 2, 3, Harapan
            $table->integer('tahun');
            $table->string('penyelenggara')->nullable();
            $table->string('file_sertifikat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
