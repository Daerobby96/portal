<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_ruangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
            $table->foreignId('pemohon_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approval_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('keperluan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('jumlah_peserta')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan_pemohon')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_ruangan');
    }
};
