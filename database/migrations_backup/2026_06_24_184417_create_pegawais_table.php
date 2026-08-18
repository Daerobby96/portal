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
            $table->string('nip', 50)->nullable()->unique();
            $table->string('nama', 255);
            $table->string('email', 255)->nullable()->unique();
            $table->string('no_hp', 50)->nullable();
            $table->string('jabatan', 255)->nullable();
            $table->string('unit_kerja', 255)->nullable();
            $table->enum('jenis_pegawai', ['Dosen', 'Tenaga Kependidikan', 'Lainnya'])->default('Lainnya');
            $table->string('status_kepegawaian', 100)->nullable(); // PNS, PPPK, Honorer, dll
            $table->boolean('is_aktif')->default(true);
            // Relasi opsional ke user sistem (nullable)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
