<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kerjasamas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('nama_mitra', 150);
            $table->string('jenis_mitra', 50)->default('Industri'); // Industri, PTN/PTS, Pemerintah, NGO
            $table->string('tingkat', 30)->default('Nasional'); // Lokal, Nasional, Internasional
            $table->string('nomor_dokumen', 100)->nullable();
            $table->string('judul_kerjasama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('status', 30)->default('Aktif'); // Aktif, Berakhir, Diperpanjang
            $table->string('file_mou')->nullable();
            $table->text('evaluasi_terakhir')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluasi_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerjasama_id')->constrained('kerjasamas')->cascadeOnDelete();
            $table->date('tanggal_evaluasi');
            $table->integer('nilai')->default(80);
            $table->text('catatan')->nullable();
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_mitras');
        Schema::dropIfExists('kerjasamas');
    }
};
