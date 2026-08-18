<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->string('bidang', 50)->nullable(); // Pendidikan, Penelitian, PkM, Tata Kelola, dll
            $table->string('jenis', 50)->nullable(); // SN-Dikti, Standar Perguruan Tinggi
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standars');
    }
};
