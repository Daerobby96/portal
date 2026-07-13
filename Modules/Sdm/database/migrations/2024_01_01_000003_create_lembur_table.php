<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('jumlah_jam', 5, 2);
            $table->text('keperluan');
            $table->string('file_pendukung')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamps();

            $table->index('pegawai_id');
            $table->index('status');
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lembur');
    }
};
