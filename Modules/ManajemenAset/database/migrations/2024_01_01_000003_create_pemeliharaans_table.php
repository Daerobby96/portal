<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('aset')->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_pemeliharaan');
            $table->enum('jenis', ['preventif', 'korektif', 'kalibrasi', 'inspeksi'])->default('preventif');
            $table->text('deskripsi_kegiatan');
            $table->text('temuan')->nullable();
            $table->text('tindakan')->nullable();
            $table->enum('hasil', ['baik', 'perlu_perbaikan', 'perlu_penggantian'])->default('baik');
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->date('tanggal_berikutnya')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};
