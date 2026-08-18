<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standar_id')->constrained('standars')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('tipe', 20)->default('IKU'); // IKU, IKT
            $table->decimal('bobot', 5, 2)->default(1.00);
            $table->string('unit_pengukuran', 50)->nullable(); // %, jumlah, skor, rasio
            $table->decimal('target_nilai', 10, 2)->nullable();
            $table->text('target_deskripsi')->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->string('frekuensi_pengukuran', 50)->default('Semester');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_kinerjas');
    }
};
