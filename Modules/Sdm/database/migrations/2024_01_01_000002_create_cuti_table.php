<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->enum('jenis_cuti', ['tahunan', 'sakit', 'melahirkan', 'besar', 'alasan_penting'])->default('tahunan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->text('alasan');
            $table->string('file_pendukung')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamps();

            $table->index('pegawai_id');
            $table->index('status');
            $table->index('tanggal_mulai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
