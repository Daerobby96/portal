<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->cascadeOnDelete();
            $table->string('nama_kegiatan');
            $table->string('jenis_prestasi'); // Akademik, Non-Akademik
            $table->string('tingkat'); // Lokal, Nasional, Internasional
            $table->integer('tahun');
            $table->string('penyelenggara')->nullable();
            $table->string('peringkat')->nullable(); // Juara 1, Finalis, dll
            $table->string('sertifikat')->nullable(); // path file
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
