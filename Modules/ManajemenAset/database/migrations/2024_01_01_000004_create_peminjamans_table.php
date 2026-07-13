<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->foreignId('peminjam_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approval_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('keperluan');
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_kembali_rencana');
            $table->dateTime('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'terlambat'])->default('pending');
            $table->text('catatan_peminjam')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->text('kondisi_saat_pinjam')->nullable();
            $table->text('kondisi_saat_kembali')->nullable();
            $table->decimal('denda', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
