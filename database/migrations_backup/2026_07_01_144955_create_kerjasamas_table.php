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
        Schema::create('kerjasamas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mitra');
            $table->string('jenis_mitra'); // PT, Pemerintah, DUDI, dll
            $table->string('tingkat'); // Lokal, Nasional, Internasional
            $table->string('judul_kerjasama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('dokumen_mou')->nullable(); // path file
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('status')->default('Aktif'); // Aktif, Selesai, Kedaluwarsa
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
        Schema::dropIfExists('kerjasamas');
    }
};
