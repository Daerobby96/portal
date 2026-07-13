<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->string('lokasi_keluar')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa', 'cuti', 'dinas_luar'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->decimal('latitude_masuk', 10, 7)->nullable();
            $table->decimal('longitude_masuk', 10, 7)->nullable();
            $table->decimal('latitude_keluar', 10, 7)->nullable();
            $table->decimal('longitude_keluar', 10, 7)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['pegawai_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
