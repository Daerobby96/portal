<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->year('tahun');
            $table->enum('periode', ['semester_1', 'semester_2', 'tahunan'])->default('tahunan');
            $table->decimal('nilai_disiplin', 5, 2)->default(0);
            $table->decimal('nilai_kinerja', 5, 2)->default(0);
            $table->decimal('nilai_loyalitas', 5, 2)->default(0);
            $table->decimal('nilai_kreativitas', 5, 2)->default(0);
            $table->decimal('nilai_kerjasama', 5, 2)->default(0);
            $table->decimal('nilai_total', 5, 2)->default(0);
            $table->enum('predikat', ['sangat_baik', 'baik', 'cukup', 'kurang'])->nullable();
            $table->text('catatan_atasan')->nullable();
            $table->text('catatan_pegawai')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->foreignId('penilai_id')->constrained('users');
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index('pegawai_id');
            $table->index(['tahun', 'periode']);
            $table->unique(['pegawai_id', 'tahun', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerja');
    }
};
