<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hkis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('judul_hki');
            $table->string('jenis_hki', 50)->default('Hak Cipta'); // Hak Cipta, Paten, Desain Industri, Merek
            $table->string('nomor_pencatatan', 100)->nullable();
            $table->integer('tahun_terbit');
            $table->string('status', 30)->default('Tersertifikasi');
            $table->string('file_sertifikat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hkis');
    }
};
