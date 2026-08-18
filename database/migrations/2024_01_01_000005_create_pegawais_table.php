<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->string('nip', 50)->unique();
            $table->string('nidn', 50)->nullable();
            $table->string('nama', 100);
            $table->string('email')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('jenis_pegawai', 50)->default('Dosen'); // Dosen, Tendik
            $table->string('status_kepegawaian', 50)->default('Tetap Yayasan'); // PNS, PPPK, Tetap Yayasan, Kontrak
            $table->string('pendidikan_terakhir', 20)->nullable(); // S1, S2, S3
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
