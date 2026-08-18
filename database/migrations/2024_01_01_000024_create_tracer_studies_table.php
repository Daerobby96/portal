<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('nim', 30);
            $table->string('nama', 100);
            $table->string('prodi', 100)->nullable();
            $table->integer('tahun_lulus');
            $table->string('telepon', 25)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('status_kerja', 50)->nullable(); // Bekerja, Wirausaha, Melanjutkan Studi, Belum Bekerja
            $table->string('nama_instansi', 150)->nullable();
            $table->string('jabatan_pekerjaan', 100)->nullable();
            $table->integer('waktu_tunggu_bulan')->nullable();
            $table->decimal('gaji', 15, 2)->nullable();
            $table->string('relevansi_bidang', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
