<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();

            // ── Identitas ──────────────────────────────────────────
            $table->string('nim', 30)->unique();
            $table->string('nama', 255);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 255)->nullable();

            // ── Akademik ───────────────────────────────────────────
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete();
            $table->unsignedSmallInteger('angkatan')->nullable();           // Tahun masuk, e.g. 2022
            $table->unsignedTinyInteger('semester_berjalan')->nullable();   // Semester saat data diinput
            $table->string('jalur_masuk', 50)->nullable();                  // SNBP / SNBT / Mandiri / dll
            $table->decimal('ipk', 3, 2)->nullable();                       // 0.00 – 4.00

            // ── Status & Kelulusan ─────────────────────────────────
            $table->enum('status', [
                'aktif',
                'lulus',
                'cuti',
                'DO',
                'mengundurkan_diri',
            ])->default('aktif');
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_lulus')->nullable();
            $table->unsignedSmallInteger('masa_studi_bulan')->nullable();   // Dihitung otomatis saat lulus

            // ── Keterangan ─────────────────────────────────────────
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
