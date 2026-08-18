<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuesioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('target_role', 50)->default('mahasiswa'); // mahasiswa, dosen, tendik, alumni, mitra, publik
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kuesioner_pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioners')->cascadeOnDelete();
            $table->string('kategori', 50)->nullable();
            $table->text('pertanyaan');
            $table->enum('tipe', ['skala', 'pilihan', 'esai', 'skala_4', 'skala_5'])->default('skala_5');
            $table->json('pilihan_jawaban')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('kuesioner_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioners')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_responden')->nullable();
            $table->string('identifier')->nullable(); // NIM, NIP, Email
            $table->string('institusi')->nullable();
            $table->string('prodi')->nullable();
            $table->string('tahun_masuk')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('filled_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('kuesioner_jawaban_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jawaban_id')->constrained('kuesioner_jawabans')->cascadeOnDelete();
            $table->foreignId('pertanyaan_id')->constrained('kuesioner_pertanyaans')->cascadeOnDelete();
            $table->integer('skor')->nullable();
            $table->text('jawaban_text')->nullable();
            $table->timestamps();
        });

        Schema::create('kuesioner_respondens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioners')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('sudah_mengisi_at')->nullable();
            $table->timestamps();

            $table->unique(['kuesioner_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuesioner_respondens');
        Schema::dropIfExists('kuesioner_jawaban_details');
        Schema::dropIfExists('kuesioner_jawabans');
        Schema::dropIfExists('kuesioner_pertanyaans');
        Schema::dropIfExists('kuesioners');
    }
};
