<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iku_resmi', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_iku')->unique();
            $table->string('nama');
            $table->string('sifat', 20)->default('positif'); // positif, negatif
            $table->text('formula')->nullable();
            $table->string('satuan', 30)->default('%');
            $table->decimal('target_default', 8, 2)->default(0);
            $table->text('definisi')->nullable();
            $table->timestamps();
        });

        Schema::create('iku_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iku_resmi_id')->constrained('iku_resmi')->cascadeOnDelete();
            $table->string('kode_kriteria', 20);
            $table->string('nama_kriteria');
            $table->decimal('bobot', 5, 2)->default(1.00);
            $table->timestamps();
        });

        Schema::create('iku_data_input', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iku_resmi_id')->constrained('iku_resmi')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kategori', 50)->nullable();
            $table->decimal('nilai_input', 12, 2)->default(0);
            $table->decimal('bobot', 5, 2)->default(1.00);
            $table->string('triwulan', 10)->nullable(); // Q1, Q2, Q3, Q4
            $table->timestamps();
        });

        Schema::create('iku_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iku_resmi_id')->constrained('iku_resmi')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->decimal('target', 8, 2)->default(0);
            $table->decimal('nilai_hasil', 12, 2)->default(0);
            $table->enum('status_capaian', ['tercapai', 'belum_tercapai'])->default('belum_tercapai');
            $table->string('triwulan', 10)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iku_hasil');
        Schema::dropIfExists('iku_data_input');
        Schema::dropIfExists('iku_kriteria');
        Schema::dropIfExists('iku_resmi');
    }
};
