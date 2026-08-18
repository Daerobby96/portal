<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('dosen_nip', 50)->nullable();
            $table->string('dosen_name');
            $table->string('homebase')->nullable();
            $table->decimal('total_rerata', 5, 2)->default(0);
            $table->integer('total_responden')->default(0);
            $table->string('kategori_evaluasi', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_kinerjas');
    }
};
