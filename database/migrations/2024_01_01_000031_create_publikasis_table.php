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
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('judul');
            $table->integer('tahun');
            $table->string('jenis', 50)->default('Jurnal Nasional'); // Jurnal Internasional Scopus, Sinta 1-6, Prosiding
            $table->string('nama_jurnal_penerbit')->nullable();
            $table->string('tingkat_sinta', 10)->nullable(); // SINTA 1 - 6
            $table->string('doi', 150)->nullable();
            $table->string('url_publikasi')->nullable();
            $table->timestamps();
        });

        Schema::create('publikasi_penulis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publikasi_id')->constrained('publikasis')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->string('nama_penulis')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_penulis');
        Schema::dropIfExists('publikasis');
    }
};
